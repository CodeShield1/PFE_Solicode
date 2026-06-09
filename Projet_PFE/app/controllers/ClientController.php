<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/City.php";
require_once __DIR__ . "/../models/Category.php";

class ClientController
{
    private $userModel;
    private $cityModel;
    private $categoryModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->cityModel = new City();
        $this->categoryModel = new Category();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function home()
    {
        $cities = $this->cityModel->getAll();
        $categories = $this->categoryModel->getAll();
        include __DIR__ . "/../views/client/home.php";
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
