<?php

require_once "../controllers/AuthController.php";
require_once "../controllers/CityController.php";
require_once "../controllers/CategoryController.php";
require_once "../controllers/EquipmentController.php";


// On démarre la session ici aussi pour le check des rôles
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = $_GET['url'] ?? 'home';
$authController = new AuthController();
$cityController = new CityController();
$categoryController = new CategoryController();
$equipmentController = new EquipmentController();

switch ($url) {

    case 'home':
        require_once "../views/client/home.php";
        break;

    case 'equipments_list':
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client') {
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

    default:
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
