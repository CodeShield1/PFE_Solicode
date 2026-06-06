<?php

require_once __DIR__ . "/../config/database.php";

class Category
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function getAll()
    {
        $query = "SELECT * FROM categories ORDER BY id_category DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM categories WHERE id_category = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO categories (name, image) VALUES (:name, :image)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':image' => $data['image']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE categories SET name = :name, image = :image WHERE id_category = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM categories WHERE id_category = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
