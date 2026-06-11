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
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
            $city_id     = !empty($_GET['city'])       ? (int)$_GET['city']       : null;
            $category_id = !empty($_GET['category'])   ? (int)$_GET['category']   : null;
            $price_min   = !empty($_GET['price_min'])  ? (float)$_GET['price_min']: null;
            $price_max   = !empty($_GET['price_max'])  ? (float)$_GET['price_max']: null;
            $page        = !empty($_GET['page'])       ? max(1,(int)$_GET['page']) : 1;

            // Date validation securisée
            $start_date = '';
            $end_date   = '';
            $date_error = '';
            $today    = date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime('+1 day'));

            if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                $s = $_GET['start_date'];
                $e = $_GET['end_date'];
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $s) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $e)) {
                    $date_error = 'Invalid date format.';
                } elseif ($s < $tomorrow) {
                    $date_error = 'Start date must be at least tomorrow.';
                } elseif ($e <= $s) {
                    $date_error = 'End date must be after start date.';
                } else {
                    $start_date = $s;
                    $end_date   = $e;
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

            $result     = $equipmentController->getEquipmentModel()->search($filters, $page);
            $equipments = $result['items'];
            $total      = $result['total'];
            $totalPages = $result['pages'];

            $cities     = $cityController->getAllCities();
            $categories = $categoryController->getAllCategories();
            require_once "../views/client/equipments.php";
        } else {
            header('Location: index.php?url=login');
            exit;
        }
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
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $equipment = $equipmentController->getEquipmentModel()->getById($id);
            if (!$equipment) { echo "<h1>Equipment not found</h1>"; break; }
            $categories = $categoryController->getAllCategories();
            require_once "../views/client/equipment_detail.php";
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'cart':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
            $categories = $categoryController->getAllCategories();
            require_once "../views/client/cart.php";
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    case 'clients':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $clientController->index();
        } else {
            header('Location: index.php?url=login');
            exit;
        }
        break;

    default:
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
