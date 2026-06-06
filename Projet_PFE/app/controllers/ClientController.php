<?php

require_once __DIR__ . "/../models/User.php";

class ClientController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        // Seul l'admin peut voir la liste des clients
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?url=login');
            exit;
        }

        $clients = $this->userModel->getClients();
        include __DIR__ . "/../views/admin/client.php";
    }
}
