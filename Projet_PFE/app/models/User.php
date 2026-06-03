<?php

require_once "../config/Database.php";

class User extends Database
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connect();
    }
}
