<?php

require_once "../models/User.php";

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showLogin()
    {
        require_once "../views/auth/login.php";
    }

    public function showRegister()
    {
        require_once "../views/auth/register.php";
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: index.php?url=admin_dashboard');
                } else {
                    header('Location: index.php?url=equipments_list');
                }
                exit;
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect.";
                header('Location: index.php?url=login');
                exit;
            }
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'password' => $_POST['password']
            ];

            if ($this->userModel->create($data)) {
                $_SESSION['success'] = "Inscription réussie ! Connectez-vous.";
                header('Location: index.php?url=login');
                exit;
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
                header('Location: index.php?url=register');
                exit;
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?url=login');
        exit;
    }
}
