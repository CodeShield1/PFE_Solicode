<?php

require_once __DIR__ . "/../models/Category.php";

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $categories = $this->categoryModel->getAll();
        include __DIR__ . "/../views/admin/category.php";
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $image = $_FILES['image'];

            $imageName = $this->uploadImage($image);

            if ($imageName) {
                $data = [
                    'name' => $name,
                    'image' => $imageName
                ];
                try {
                    if ($this->categoryModel->create($data)) {
                        $_SESSION['success'] = "Catégorie ajoutée avec succès.";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'ajout.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Erreur lors de l'ajout : " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Échec de l'upload de l'image.";
            }
            header('Location: index.php?url=categories');
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_category'];
            $name = htmlspecialchars($_POST['name']);
            $currentImage = $_POST['current_image'];
            $newImage = $_FILES['image'];

            if ($newImage['size'] > 0) {
                $imageName = $this->uploadImage($newImage);
            } else {
                $imageName = $currentImage;
            }

            if ($imageName) {
                $data = [
                    'name' => $name,
                    'image' => $imageName
                ];
                try {
                    if ($this->categoryModel->update($id, $data)) {
                        $_SESSION['success'] = "Catégorie modifiée avec succès.";
                    } else {
                        $_SESSION['error'] = "Erreur lors de la modification.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Erreur lors de la modification : " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Échec de l'upload de l'image.";
            }
            header('Location: index.php?url=categories');
            exit;
        }
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            try {
                if ($this->categoryModel->delete($id)) {
                    $_SESSION['success'] = "Catégorie supprimée avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Impossible de supprimer cette catégorie car elle est liée à des équipements.";
            }
            header('Location: index.php?url=categories');
            exit;
        }
    }

    public function getAllCategories()
    {
        return $this->categoryModel->getAll();
    }

    private function uploadImage($file)
    {
        $targetDir = __DIR__ . "/../public/uploads/categories/";
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
