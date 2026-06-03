<?php

require_once "../models/User.php";

class AuthController
{
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

    }

    public function register()
    {

    }

    public function logout()
    {

    }
}