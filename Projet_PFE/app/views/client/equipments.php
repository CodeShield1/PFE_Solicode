<?php
// On vérifie si l'utilisateur est un client connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'client') {
    header('Location: index.php?url=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nos Équipements - MEGALOC</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app-container">
        <nav>
            <div class="logo">
                <span class="mega">MEGA</span><span class="loc">LOC</span>
            </div>
            <div class="user-info">
                <span>Bienvenue, <?php echo $_SESSION['user_name']; ?> (Client)</span>
                <a href="index.php?url=logout" class="btn-main" style="padding: 10px 20px; font-size: 14px; max-width: 150px;">Déconnexion</a>
            </div>
        </nav>

        <section class="equipments-section">
            <h1>Équipements Disponibles</h1>
            <p>Choisissez le matériel dont vous avez besoin pour vos travaux.</p>
            
            <!-- Liste des équipements (à venir avec le Model) -->
            <div class="equipments-grid">
                <!-- Placeholder -->
                <p>Chargement des équipements...</p>
            </div>
        </section>
    </div>
</body>
</html>