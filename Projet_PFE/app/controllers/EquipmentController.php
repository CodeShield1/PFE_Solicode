<?php

require_once __DIR__ . "/../models/Equipment.php";
require_once __DIR__ . "/../models/City.php";
require_once __DIR__ . "/../models/Category.php";

class EquipmentController
{
    private $equipmentModel;
    private $cityModel;
    private $categoryModel;

    public function __construct()
    {
        $this->equipmentModel = new Equipment();
        $this->cityModel = new City();
        $this->categoryModel = new Category();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $equipments = $this->equipmentModel->getAll();
        $cities = $this->cityModel->getAll();
        $categories = $this->categoryModel->getAll();
        
        include __DIR__ . "/../views/admin/equipment.php";
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => htmlspecialchars($_POST['name']),
                'description' => htmlspecialchars($_POST['description']),
                'price_per_day' => $_POST['price_per_day'],
                'quantity_stock' => $_POST['quantity_stock'],
                'category_id' => $_POST['category_id'],
                'city_id' => $_POST['city_id'],
                'image' => ''
            ];

            $image = $_FILES['image'];
            $imageName = $this->uploadImage($image);

            if ($imageName) {
                $data['image'] = $imageName;
                try {
                    if ($this->equipmentModel->create($data)) {
                        $_SESSION['success'] = "Équipement ajouté avec succès.";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'ajout en base de données.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Erreur lors de l'ajout : " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Échec de l'upload de l'image.";
            }

            header('Location: index.php?url=equipment');
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_equipment'];
            $currentImage = $_POST['current_image'];
            
            $data = [
                'name' => htmlspecialchars($_POST['name']),
                'description' => htmlspecialchars($_POST['description']),
                'price_per_day' => $_POST['price_per_day'],
                'quantity_stock' => $_POST['quantity_stock'],
                'category_id' => $_POST['category_id'],
                'city_id' => $_POST['city_id'],
                'image' => $currentImage
            ];

            $newImage = $_FILES['image'];
            if ($newImage['size'] > 0) {
                $imageName = $this->uploadImage($newImage);
                if ($imageName) {
                    $data['image'] = $imageName;
                }
            }

            try {
                if ($this->equipmentModel->update($id, $data)) {
                    $_SESSION['success'] = "Équipement modifié avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la modification.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur lors de la modification : " . $e->getMessage();
            }

            header('Location: index.php?url=equipment');
            exit;
        }
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            try {
                if ($this->equipmentModel->delete($id)) {
                    $_SESSION['success'] = "Équipement supprimé avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Impossible de supprimer cet équipement car il est lié à des réservations.";
            }
            header('Location: index.php?url=equipment');
            exit;
        }
    }

    public function getEquipmentModel()
    {
        return $this->equipmentModel;
    }

    private function uploadImage($file)
    {
        $targetDir = __DIR__ . "/../public/uploads/equipments/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . "_" . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        if (in_array(strtolower($fileType), $allowTypes)) {
            if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
                return $fileName;
            }
        }
        return false;
    }
}
