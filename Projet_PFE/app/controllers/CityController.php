<?php

require_once __DIR__ . "/../models/City.php";

class CityController
{
    private $cityModel;

    public function __construct()
    {
        $this->cityModel = new City();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $cities = $this->cityModel->getAll();
        include __DIR__ . "/../views/admin/city.php";
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
                $this->cityModel->create($data);
                header('Location: index.php?url=cities');
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header('Location: index.php?url=cities');
            }
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_city'];
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
                $this->cityModel->update($id, $data);
                header('Location: index.php?url=cities');
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header('Location: index.php?url=cities');
            }
            exit;
        }
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->cityModel->delete($id);
            header('Location: index.php?url=cities');
            exit;
        }
    }

    private function uploadImage($file)
    {
        $targetDir = __DIR__ . "/../public/uploads/cities/";
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
