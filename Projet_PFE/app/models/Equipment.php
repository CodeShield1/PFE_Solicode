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
        $query = "SELECT e.*, c.name as category_name, ci.name as city_name
                  FROM equipment e
                  JOIN categories c ON e.category_id = c.id_category
                  JOIN cities ci ON e.city_id = ci.id_city
                  WHERE e.id_equipment = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Add a placeholder brand since it's not in the DB but requested in UI
        if ($res && !isset($res['brand'])) {
            $res['brand'] = 'Generic'; // Fallback
        }
        return $res;
    }

    public function getAvailableStock($id, $startDate, $endDate)
    {
        // Get total stock
        $query = "SELECT quantity_stock FROM equipment WHERE id_equipment = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $totalStock = (int)$stmt->fetchColumn();

        // Get reserved quantity for these dates (Approved or Pending)
        $query = "SELECT SUM(re.quantity) 
                  FROM reservation_equipment re
                  JOIN reservations r ON re.reservation_id = r.id_reservation
                  WHERE re.equipment_id = :id
                    AND r.status IN ('Pending', 'Approved')
                    AND r.start_date <= :end_date
                    AND r.end_date >= :start_date";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':id' => $id,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        $reserved = (int)$stmt->fetchColumn();

        return max(0, $totalStock - $reserved);
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

    public function search($filters = [], $page = 1, $per_page = 12)
    {
        $params = [];
        $where  = "WHERE e.quantity_stock > 0";

        if (!empty($filters['city_id'])) {
            $where .= " AND e.city_id = :city_id";
            $params[':city_id'] = (int)$filters['city_id'];
        }
        if (!empty($filters['category_id'])) {
            $where .= " AND e.category_id = :category_id";
            $params[':category_id'] = (int)$filters['category_id'];
        }
        if (!empty($filters['price_min'])) {
            $where .= " AND e.price_per_day >= :price_min";
            $params[':price_min'] = (float)$filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $where .= " AND e.price_per_day <= :price_max";
            $params[':price_max'] = (float)$filters['price_max'];
        }

        // Exclure les équipements dont le stock est épuisé sur ces dates
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $start = $filters['start_date'];
            $end   = $filters['end_date'];
            $where .= " AND e.id_equipment NOT IN (
                SELECT re.equipment_id
                FROM reservation_equipment re
                JOIN reservations r ON re.reservation_id = r.id_reservation
                JOIN equipment e2 ON re.equipment_id = e2.id_equipment
                WHERE r.status IN ('Pending','Approved')
                  AND r.start_date <= :end_date
                  AND r.end_date   >= :start_date
                GROUP BY re.equipment_id
                HAVING SUM(re.quantity) >= MIN(e2.quantity_stock)
            )";
            $params[':start_date'] = $start;
            $params[':end_date']   = $end;
        }

        // Count total
        $countStmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM equipment e $where"
        );
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Paginate
        $offset = ($page - 1) * $per_page;
        $query  = "SELECT e.*, c.name as category_name, ci.name as city_name
                   FROM equipment e
                   JOIN categories c ON e.category_id = c.id_category
                   JOIN cities ci ON e.city_id = ci.id_city
                   $where
                   ORDER BY e.id_equipment DESC
                   LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,   PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'pages' => (int)ceil($total / $per_page),
            'page'  => $page,
        ];
    }
}
