<?php
// On vérifie si l'utilisateur est admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?url=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - MEGALOC</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Bienvenue Admin, <?php echo $_SESSION['user_name']; ?></h1>
        <p>Ici vous pouvez gérer les équipements, les catégories et les villes.</p>
        <a href="index.php?url=logout" class="btn-main" style="max-width: 200px;">Déconnexion</a>
    </div>
</body>
</html>