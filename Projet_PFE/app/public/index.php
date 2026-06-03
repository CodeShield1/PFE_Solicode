<?php

require_once "../controllers/AuthController.php";

$url = $_GET['url'] ?? 'home';

switch ($url) {

    case 'home':
        require_once "../views/client/home.php";
        break;

    case 'login':
        $controller = new AuthController();
        $controller->showLogin();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->showRegister();
        break;

    default:
        echo "Page Not Found";
}