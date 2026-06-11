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
}
