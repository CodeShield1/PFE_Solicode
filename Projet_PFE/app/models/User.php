<?php

require_once "../config/Database.php";

class User extends Database
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (name, email, phone, password, role) VALUES (:name, :email, :phone, :password, :role)";
        $stmt = $this->conn->prepare($sql);

        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = $data['role'] ?? 'client';

        return $stmt->execute([
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':phone'    => $data['phone'],
            ':password' => $hashed_password,
            ':role'     => $role
        ]);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
