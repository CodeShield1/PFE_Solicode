<?php

require_once __DIR__ . "/../config/database.php";

class City
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
        $query = "SELECT * FROM cities ORDER BY id_city DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM cities WHERE id_city = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO cities (name, image) VALUES (:name, :image)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':image' => $data['image']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE cities SET name = :name, image = :image WHERE id_city = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':image' => $data['image'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM cities WHERE id_city = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
