<?php

require_once __DIR__ . "/../models/Equipment.php";
require_once __DIR__ . "/../config/database.php";

class ReservationController
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function reserveNow()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=equipments_list');
            exit;
        }

        $id = (int)$_POST['id_equipment'];
        $qty = (int)$_POST['quantity'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $equipmentModel = new Equipment();
        $equipment = $equipmentModel->getById($id);
        $available = $equipmentModel->getAvailableStock($id, $start_date, $end_date);

        if ($qty > $available) {
            $_SESSION['error'] = "Not enough stock available for these dates.";
            header("Location: index.php?url=equipment_detail&id=$id");
            exit;
        }

        $days = (strtotime($end_date) - strtotime($start_date)) / 86400;
        $total_price = $equipment['price_per_day'] * $qty * $days;

        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO reservations (user_id, start_date, end_date, total_price, status) 
                      VALUES (:user_id, :start_date, :end_date, :total_price, 'Pending')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':total_price' => $total_price
            ]);

            $reservation_id = $this->conn->lastInsertId();

            $query = "INSERT INTO reservation_equipment (reservation_id, equipment_id, quantity) 
                      VALUES (:res_id, :eq_id, :qty)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':res_id' => $reservation_id,
                ':eq_id' => $id,
                ':qty' => $qty
            ]);

            $this->conn->commit();
            $_SESSION['success'] = "Reservation created successfully!";
            header('Location: index.php?url=my_reservations');
            exit;

        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['error'] = "Error creating reservation: " . $e->getMessage();
            header("Location: index.php?url=equipment_detail&id=$id");
            exit;
        }
    }

    public function reserveAll()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        if (empty($_SESSION['cart'])) {
            header('Location: index.php?url=equipments_list');
            exit;
        }

        $cart = $_SESSION['cart'];
        $firstItem = reset($cart);
        $start_date = $firstItem['start_date'];
        $end_date = $firstItem['end_date'];
        
        $total_price = 0;
        $days = (strtotime($end_date) - strtotime($start_date)) / 86400;

        try {
            $this->conn->beginTransaction();

            // Calculate total and check stock for all items
            $items_to_reserve = [];
            foreach ($cart as $id => $item) {
                $equipmentModel = new Equipment();
                $available = $equipmentModel->getAvailableStock($id, $start_date, $end_date);
                if ($item['quantity'] > $available) {
                    throw new Exception("Not enough stock for " . $item['name']);
                }
                $total_price += $item['price'] * $item['quantity'] * $days;
                $items_to_reserve[] = $item;
            }

            $query = "INSERT INTO reservations (user_id, start_date, end_date, total_price, status) 
                      VALUES (:user_id, :start_date, :end_date, :total_price, 'Pending')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':total_price' => $total_price
            ]);

            $reservation_id = $this->conn->lastInsertId();

            $query = "INSERT INTO reservation_equipment (reservation_id, equipment_id, quantity) 
                      VALUES (:res_id, :eq_id, :qty)";
            $stmt = $this->conn->prepare($query);

            foreach ($items_to_reserve as $item) {
                $stmt->execute([
                    ':res_id' => $reservation_id,
                    ':eq_id' => $item['id'],
                    ':qty' => $item['quantity']
                ]);
            }

            $this->conn->commit();
            $_SESSION['cart'] = []; // Clear cart
            $_SESSION['success'] = "Reservation created successfully!";
            header('Location: index.php?url=my_reservations');
            exit;

        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['error'] = "Error creating reservation: " . $e->getMessage();
            header("Location: index.php?url=cart");
            exit;
        }
    }

    public function myReservations()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?url=login');
            exit;
        }

        $query = "SELECT r.*, COUNT(re.equipment_id) as items_count 
                  FROM reservations r
                  LEFT JOIN reservation_equipment re ON r.id_reservation = re.reservation_id
                  WHERE r.user_id = :user_id
                  GROUP BY r.id_reservation
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch equipment for each reservation
        foreach ($reservations as &$res) {
            $query = "SELECT re.quantity, e.name as equipment_name, e.image
                      FROM reservation_equipment re
                      JOIN equipment e ON re.equipment_id = e.id_equipment
                      WHERE re.reservation_id = :res_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':res_id' => $res['id_reservation']]);
            $res['equipments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fetch categories for the header mega menu
        require_once __DIR__ . "/../models/Category.php";
        $categories = (new Category())->getAll();

        require_once __DIR__ . "/../views/client/my_reservations.php";
    }

    // ==========================================
    // ADMIN: List all reservations
    // ==========================================
    public function adminIndex()
    {
        $query = "SELECT r.*, u.name as client_name, u.email as client_email, u.phone as client_phone,
                  COUNT(re.equipment_id) as items_count
                  FROM reservations r
                  JOIN users u ON r.user_id = u.id_user
                  LEFT JOIN reservation_equipment re ON r.id_reservation = re.reservation_id
                  GROUP BY r.id_reservation
                  ORDER BY 
                      CASE r.status 
                          WHEN 'Pending' THEN 1 
                          WHEN 'Approved' THEN 2 
                          WHEN 'Rejected' THEN 3 
                      END,
                      r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch equipment for each reservation
        foreach ($reservations as &$res) {
            $query = "SELECT re.quantity, e.name as equipment_name, e.price_per_day, e.image, e.quantity_stock,
                      ci.name as city_name
                      FROM reservation_equipment re
                      JOIN equipment e ON re.equipment_id = e.id_equipment
                      JOIN cities ci ON e.city_id = ci.id_city
                      WHERE re.reservation_id = :res_id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':res_id' => $res['id_reservation']]);
            $res['equipments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        include __DIR__ . "/../views/admin/reservation.php";
    }

    // ==========================================
    // ADMIN: Approve a reservation
    // ==========================================
    public function approve()
    {
        if (!isset($_GET['id'])) {
            header('Location: index.php?url=reservations');
            exit;
        }

        $id = (int)$_GET['id'];

        try {
            $this->conn->beginTransaction();

            // Get reservation current status
            $stmt = $this->conn->prepare("SELECT status FROM reservations WHERE id_reservation = :id");
            $stmt->execute([':id' => $id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                $_SESSION['error'] = "Réservation introuvable.";
                header('Location: index.php?url=reservations');
                exit;
            }

            if ($reservation['status'] !== 'Pending') {
                $_SESSION['error'] = "Seules les réservations en attente peuvent être approuvées.";
                header('Location: index.php?url=reservations');
                exit;
            }

            // Get equipment items for this reservation
            $stmt = $this->conn->prepare("
                SELECT re.equipment_id, re.quantity, e.quantity_stock, e.name
                FROM reservation_equipment re
                JOIN equipment e ON re.equipment_id = e.id_equipment
                WHERE re.reservation_id = :id
            ");
            $stmt->execute([':id' => $id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check stock availability and decrease quantity
            foreach ($items as $item) {
                if ($item['quantity'] > $item['quantity_stock']) {
                    throw new Exception("Stock insuffisant pour \"" . $item['name'] . "\" (Stock: " . $item['quantity_stock'] . ", Demandé: " . $item['quantity'] . ")");
                }
                // Decrease equipment stock
                $stmt = $this->conn->prepare("
                    UPDATE equipment SET quantity_stock = quantity_stock - :qty
                    WHERE id_equipment = :eq_id
                ");
                $stmt->execute([
                    ':qty' => $item['quantity'],
                    ':eq_id' => $item['equipment_id']
                ]);
            }

            // Update reservation status to Approved
            $stmt = $this->conn->prepare("UPDATE reservations SET status = 'Approved' WHERE id_reservation = :id");
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            $_SESSION['success'] = "Réservation #$id approuvée avec succès. Le stock a été mis à jour.";

        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
        }

        header('Location: index.php?url=reservations');
        exit;
    }

    // ==========================================
    // ADMIN: Reject a reservation
    // ==========================================
    public function reject()
    {
        if (!isset($_GET['id'])) {
            header('Location: index.php?url=reservations');
            exit;
        }

        $id = (int)$_GET['id'];

        try {
            $this->conn->beginTransaction();

            // Get reservation current status
            $stmt = $this->conn->prepare("SELECT status FROM reservations WHERE id_reservation = :id");
            $stmt->execute([':id' => $id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$reservation) {
                $_SESSION['error'] = "Réservation introuvable.";
                header('Location: index.php?url=reservations');
                exit;
            }

            // If it was previously Approved, restore the stock
            if ($reservation['status'] === 'Approved') {
                $stmt = $this->conn->prepare("
                    SELECT equipment_id, quantity
                    FROM reservation_equipment
                    WHERE reservation_id = :id
                ");
                $stmt->execute([':id' => $id]);
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($items as $item) {
                    $stmt = $this->conn->prepare("
                        UPDATE equipment SET quantity_stock = quantity_stock + :qty
                        WHERE id_equipment = :eq_id
                    ");
                    $stmt->execute([
                        ':qty' => $item['quantity'],
                        ':eq_id' => $item['equipment_id']
                    ]);
                }
            }

            // Update reservation status to Rejected
            $stmt = $this->conn->prepare("UPDATE reservations SET status = 'Rejected' WHERE id_reservation = :id");
            $stmt->execute([':id' => $id]);

            $this->conn->commit();
            $_SESSION['success'] = "Réservation #$id rejetée.";

        } catch (Exception $e) {
            $this->conn->rollBack();
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
        }

        header('Location: index.php?url=reservations');
        exit;
    }
}
