<?php

require_once "../controllers/AuthController.php";

// On démarre la session ici aussi pour le check des rôles
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = $_GET['url'] ?? 'home';
$authController = new AuthController();

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

    default:
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
