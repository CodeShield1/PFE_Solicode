<?php

require_once __DIR__ . "/../models/Equipment.php";

class CartController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=equipments_list');
            exit;
        }

        $id = (int)$_POST['id_equipment'];
        $qty = (int)$_POST['quantity'];
        $city_id = $_POST['city_id'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Validation
        if ($qty <= 0) {
            $_SESSION['error'] = "Quantity must be at least 1.";
            header("Location: index.php?url=equipment_detail&id=$id");
            exit;
        }

        $equipmentModel = new Equipment();
        $available = $equipmentModel->getAvailableStock($id, $start_date, $end_date);

        if ($qty > $available) {
            $_SESSION['error'] = "Not enough stock available for these dates (Available: $available).";
            header("Location: index.php?url=equipment_detail&id=$id");
            exit;
        }

        // Check if cart has items from another city or dates
        if (!empty($_SESSION['cart'])) {
            $firstItem = reset($_SESSION['cart']);
            if ($firstItem['city_id'] != $city_id || $firstItem['start_date'] != $start_date || $firstItem['end_date'] != $end_date) {
                $_SESSION['pending_item'] = [
                    'id' => $id,
                    'qty' => $qty,
                    'city_id' => $city_id,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                ];
                $_SESSION['warning'] = "Your cart already contains items for a different city or period. Would you like to clear it and start a new reservation?";
                header("Location: index.php?url=equipment_detail&id=$id");
                exit;
            }
        }

        // Add to cart
        $equipment = $equipmentModel->getById($id);
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'name' => $equipment['name'],
            'image' => $equipment['image'],
            'price' => $equipment['price_per_day'],
            'quantity' => $qty,
            'city_id' => $city_id,
            'city_name' => $equipment['city_name'],
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $_SESSION['success'] = "Added to cart successfully!";
        header("Location: index.php?url=equipment_detail&id=$id");
        exit;
    }

    public function remove()
    {
        $id = (int)$_GET['id'];
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function clear()
    {
        $_SESSION['cart'] = [];
        if (isset($_SESSION['pending_item'])) {
            $pending = $_SESSION['pending_item'];
            unset($_SESSION['pending_item']);
            // Re-add the pending item
            $_POST = [
                'id_equipment' => $pending['id'],
                'quantity' => $pending['qty'],
                'city_id' => $pending['city_id'],
                'start_date' => $pending['start_date'],
                'end_date' => $pending['end_date']
            ];
            $this->add();
            return;
        }
        header('Location: index.php?url=equipments_list');
        exit;
    }
}
