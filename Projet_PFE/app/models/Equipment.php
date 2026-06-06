<?php

require_once __DIR__ . "/../config/database.php";

class Equipment
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
        $query = "SELECT e.*, c.name as category_name, ci.name as city_name 
                  FROM equipment e
                  JOIN categories c ON e.category_id = c.id_category
                  JOIN cities ci ON e.city_id = ci.id_city
                  ORDER BY e.id_equipment DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM equipment WHERE id_equipment = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO equipment (name, description, price_per_day, quantity_stock, image, category_id, city_id) 
                  VALUES (:name, :description, :price_per_day, :quantity_stock, :image, :category_id, :city_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price_per_day' => $data['price_per_day'],
            ':quantity_stock' => $data['quantity_stock'],
            ':image' => $data['image'],
            ':category_id' => $data['category_id'],
            ':city_id' => $data['city_id']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE equipment 
                  SET name = :name, 
                      description = :description, 
                      price_per_day = :price_per_day, 
                      quantity_stock = :quantity_stock, 
                      image = :image, 
                      category_id = :category_id, 
                      city_id = :city_id 
                  WHERE id_equipment = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price_per_day' => $data['price_per_day'],
            ':quantity_stock' => $data['quantity_stock'],
            ':image' => $data['image'],
            ':category_id' => $data['category_id'],
            ':city_id' => $data['city_id'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM equipment WHERE id_equipment = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
