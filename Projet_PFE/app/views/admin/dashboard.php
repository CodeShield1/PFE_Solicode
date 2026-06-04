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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MEGALOC</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Global Styles -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Admin Specific Styles -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-helmet-safety"></i>
            <div class="brand">MEGA<span>LOC</span></div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Principal</div>
            <a href="index.php?url=admin/dashboard" class="menu-item active">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="index.php?url=equipment" class="menu-item">
                <i class="fas fa-tools"></i>
                Equipments
            </a>
            <a href="index.php?url=category" class="menu-item">
                <i class="fas fa-layer-group"></i>
                Catégories
            </a>
            <a href="index.php?url=reservations" class="menu-item">
                <i class="fas fa-calendar-check"></i>
                Reservations
            </a>

            <div class="menu-label">Gestion</div>
            <a href="index.php?url=cities" class="menu-item">
                <i class="fas fa-city"></i>
                Villes
            </a>
            <a href="index.php?url=clients" class="menu-item">
                <i class="fas fa-users"></i>
                Clients
            </a>
            
            <div class="menu-label">Autres</div>
            <a href="index.php?url=settings" class="menu-item">
                <i class="fas fa-cog"></i>
                Paramètres
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="index.php?url=logout" class="menu-item logout">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- HEADER -->
        <header>
            <div class="header-left">
                <div class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div>
                    <h2>Dashboard</h2>
                    <p>Bienvenue sur votre espace d'administration</p>
                </div>
            </div>

            <div class="header-right">
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <span class="name"><?php echo $_SESSION['user_name']; ?></span>
                        <span class="role">Administrateur</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD CONTENT (Currently Empty) -->
        <div class="content-wrapper">
            <!-- Les statistiques et graphiques iront ici -->
        </div>
    </main>

    <script src="js/dashboard.js"></script>
</body>
</html>