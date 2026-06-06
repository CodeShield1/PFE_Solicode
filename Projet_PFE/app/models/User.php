<?php

require_once __DIR__ . "/../config/database.php";

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

    public function getClients()
    {
        $sql = "SELECT id_user, name, email, phone, role, created_at 
                FROM users 
                WHERE role = 'client' 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
