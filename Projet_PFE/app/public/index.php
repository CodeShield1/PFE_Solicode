<?php

require_once "../controllers/AuthController.php";
require_once "../controllers/CityController.php";
require_once "../controllers/CategoryController.php";
require_once "../controllers/EquipmentController.php";
require_once "../controllers/ClientController.php";


// On démarre la session ici aussi pour le check des rôles
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = $_GET['url'] ?? 'home';
$authController = new AuthController();
$cityController = new CityController();
$categoryController = new CategoryController();
$equipmentController = new EquipmentController();
$clientController = new ClientController();

switch ($url) {

    case 'home':
        $categories = $categoryController->getAllCategories();
        $cities = $cityController->getAllCities();
        require_once "../views/client/home.php";
        break;

    case 'equipments_list':
        // 1. Get from URL (highest priority)
        $city_id     = !empty($_GET['city'])       ? (int)$_GET['city']       : null;
        $category_id = !empty($_GET['category'])   ? (int)$_GET['category']   : null;
        $price_min   = !empty($_GET['price_min'])  ? (float)$_GET['price_min']: null;
        $price_max   = !empty($_GET['price_max'])  ? (float)$_GET['price_max']: null;
        $page        = !empty($_GET['page'])       ? max(1,(int)$_GET['page']) : 1;
        $get_start   = $_GET['start_date'] ?? null;
        $get_end     = $_GET['end_date'] ?? null;

        // 2. Fallback to Session (if URL is empty)
        if (!$city_id && isset($_SESSION['search_city'])) {
            $city_id = (int)$_SESSION['search_city'];
        }
        if (!$get_start && isset($_SESSION['search_start_date'])) {
            $get_start = $_SESSION['search_start_date'];
        }
        if (!$get_end && isset($_SESSION['search_end_date'])) {
            $get_end = $_SESSION['search_end_date'];
        }

        // Date validation securisée
        $start_date = '';
        $end_date   = '';
        $date_error = '';
        $today    = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        if (!empty($get_start) && !empty($get_end)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $get_start) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $get_end)) {
                $date_error = 'Invalid date format.';
            } elseif ($get_start < $tomorrow) {
                $date_error = 'Start date must be at least tomorrow.';
            } elseif ($get_end <= $get_start) {
                $date_error = 'End date must be after start date.';
            } else {
                $start_date = $get_start;
                $end_date   = $get_end;
            }
        }

        $filters = array_filter([
            'city_id'     => $city_id,
            'category_id' => $category_id,
            'price_min'   => $price_min,
            'price_max'   => $price_max,
            'start_date'  => $start_date,
            'end_date'    => $end_date,
        ]);

        // Save back to session to keep it sticky
        if ($city_id) $_SESSION['search_city'] = $city_id;
        if ($start_date) $_SESSION['search_start_date'] = $start_date;
        if ($end_date) $_SESSION['search_end_date'] = $end_date;

        $result     = $equipmentController->getEquipmentModel()->search($filters, $page);
        $equipments = $result['items'];
        $total      = $result['total'];
        $totalPages = $result['pages'];

        $cities     = $cityController->getAllCities();
        $categories = $categoryController->getAllCategories();
        require_once "../views/client/equipments.php";
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'admin_dashboard':
        // Sécurité : Seul l'admin peut entrer ici
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            require_once __DIR__ . "/../config/database.php";
            $db = new Database();
            $conn = $db->connect();

            // Current month / previous month boundaries
            $thisMonthStart = date('Y-m-01');
            $lastMonthStart = date('Y-m-01', strtotime('first day of last month'));
            $lastMonthEnd   = date('Y-m-t', strtotime('last month'));

            // --- STAT CARDS ---

            // Total equipment
            $dashEquipment = (int)$conn->query("SELECT COUNT(*) FROM equipment")->fetchColumn();
            $dashEquipThisMonth = (int)$conn->query("SELECT COUNT(*) FROM equipment WHERE created_at >= '$thisMonthStart'")->fetchColumn();

            // Available equipment (stock > 0)
            $dashAvailEquip = (int)$conn->query("SELECT COUNT(*) FROM equipment WHERE quantity_stock > 0")->fetchColumn();
            $dashAvailThisMonth = (int)$conn->query("SELECT COUNT(*) FROM equipment WHERE quantity_stock > 0 AND created_at >= '$thisMonthStart'")->fetchColumn();

            // Total reservations
            $dashTotalRes = (int)$conn->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
            $dashResThisMonth = (int)$conn->query("SELECT COUNT(*) FROM reservations WHERE created_at >= '$thisMonthStart'")->fetchColumn();

            // Monthly revenue (approved reservations this month)
            $stmt = $conn->query("SELECT COALESCE(SUM(total_price),0) FROM reservations WHERE status='Approved' AND created_at >= '$thisMonthStart'");
            $dashMonthRevenue = (float)$stmt->fetchColumn();
            $stmt = $conn->query("SELECT COALESCE(SUM(total_price),0) FROM reservations WHERE status='Approved' AND created_at >= '$lastMonthStart' AND created_at <= '$lastMonthEnd'");
            $dashLastMonthRevenue = (float)$stmt->fetchColumn();
            $dashRevenueDelta = $dashMonthRevenue - $dashLastMonthRevenue;

            // Total clients
            $dashClients = (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn();
            $dashClientsThisMonth = (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='client' AND created_at >= '$thisMonthStart'")->fetchColumn();

            // Reservation status breakdown (for donut chart)
            $dashPending = 0; $dashApproved = 0; $dashRejected = 0;
            $stmt = $conn->query("SELECT status, COUNT(*) as cnt FROM reservations GROUP BY status");
            foreach ($stmt as $rs) {
                if ($rs['status'] === 'Pending')  $dashPending  = (int)$rs['cnt'];
                if ($rs['status'] === 'Approved') $dashApproved = (int)$rs['cnt'];
                if ($rs['status'] === 'Rejected') $dashRejected = (int)$rs['cnt'];
            }

            // Most used equipment (for bar chart) - top 5 by reservation count
            $stmt = $conn->query("
                SELECT e.name, COUNT(re.reservation_id) as usage_count
                FROM equipment e
                JOIN reservation_equipment re ON e.id_equipment = re.equipment_id
                GROUP BY e.id_equipment
                ORDER BY usage_count DESC
                LIMIT 5
            ");
            $dashMostUsed = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Recent reservations with items count
            $stmt = $conn->query("
                SELECT r.id_reservation, r.status, r.total_price, r.start_date, r.end_date, r.created_at,
                       u.name as client_name, u.email as client_email,
                       (SELECT COUNT(*) FROM reservation_equipment WHERE reservation_id = r.id_reservation) as items_count
                FROM reservations r
                JOIN users u ON r.user_id = u.id_user
                ORDER BY r.created_at DESC
                LIMIT 5
            ");
            $dashRecentRes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Notification count (pending reservations)
            $dashNotifCount = $dashPending;

            require_once "../views/admin/dashboard.php";
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'cities':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $cityController->index();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'add_city':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $cityController->store();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'update_city':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $cityController->update();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'delete_city':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $cityController->delete();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'categories':
    case 'category':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $categoryController->index();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'add_category':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $categoryController->store();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'update_category':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $categoryController->update();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'delete_category':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $categoryController->delete();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'equipment':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $equipmentController->index();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'add_equipment':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $equipmentController->store();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'update_equipment':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $equipmentController->update();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'delete_equipment':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $equipmentController->delete();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'equipment_detail':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // Save search context to session
        if (isset($_GET['city'])) $_SESSION['search_city'] = $_GET['city'];
        if (isset($_GET['start_date'])) $_SESSION['search_start_date'] = $_GET['start_date'];
        if (isset($_GET['end_date'])) $_SESSION['search_end_date'] = $_GET['end_date'];

        $equipment = $equipmentController->getEquipmentModel()->getById($id);
        if (!$equipment) { echo "<h1>Equipment not found</h1>"; break; }
        
        // Check availability if dates are in session
        $available_stock = $equipment['quantity_stock'];
        if (!empty($_SESSION['search_start_date']) && !empty($_SESSION['search_end_date'])) {
            $available_stock = $equipmentController->getEquipmentModel()->getAvailableStock($id, $_SESSION['search_start_date'], $_SESSION['search_end_date']);
        }

        $categories = $categoryController->getAllCategories();
        require_once "../views/client/equipment_detail.php";
        break;

    case 'add_to_cart':
        require_once "../controllers/CartController.php";
        (new CartController())->add();
        break;

    case 'remove_from_cart':
        require_once "../controllers/CartController.php";
        (new CartController())->remove();
        break;

    case 'clear_cart':
        require_once "../controllers/CartController.php";
        (new CartController())->clear();
        break;

    case 'reserve_now':
        require_once "../controllers/ReservationController.php";
        (new ReservationController())->reserveNow();
        break;

    case 'reserve_all':
        require_once "../controllers/ReservationController.php";
        (new ReservationController())->reserveAll();
        break;

    case 'my_reservations':
        require_once "../controllers/ReservationController.php";
        (new ReservationController())->myReservations();
        break;

    case 'cart':
        $categories = $categoryController->getAllCategories();
        require_once "../views/client/cart.php";
        break;

    case 'clients':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $clientController->index();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'reservations':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            require_once "../controllers/ReservationController.php";
            (new ReservationController())->adminIndex();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'approve_reservation':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            require_once "../controllers/ReservationController.php";
            (new ReservationController())->approve();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'reject_reservation':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            require_once "../controllers/ReservationController.php";
            (new ReservationController())->reject();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    default:
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
