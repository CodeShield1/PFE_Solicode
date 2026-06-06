<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'MEGALOC'; ?></title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Global Styles -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Admin Base Layout Styles -->
    <link rel="stylesheet" href="css/admin.css">
    <!-- Page Specific Styles -->
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="css/<?php echo $pageCSS; ?>.css">
    <?php endif; ?>
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
            <a href="index.php?url=admin_dashboard" class="menu-item <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="index.php?url=equipment" class="menu-item <?php echo ($currentPage == 'equipment') ? 'active' : ''; ?>">
                <i class="fas fa-tools"></i>
                Equipments
            </a>
            <a href="index.php?url=categories" class="menu-item <?php echo ($currentPage == 'category') ? 'active' : ''; ?>">
                <i class="fas fa-layer-group"></i>
                Catégories
            </a>
            <a href="index.php?url=reservations" class="menu-item <?php echo ($currentPage == 'reservations') ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i>
                Reservations
            </a>

            <div class="menu-label">Gestion</div>
            <a href="index.php?url=cities" class="menu-item <?php echo ($currentPage == 'cities') ? 'active' : ''; ?>">
                <i class="fas fa-city"></i>
                Villes
            </a>
            <a href="index.php?url=clients" class="menu-item <?php echo ($currentPage == 'clients') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                Clients
            </a>
            
            <div class="menu-label">Autres</div>
            <a href="index.php?url=settings" class="menu-item <?php echo ($currentPage == 'settings') ? 'active' : ''; ?>">
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

    <!-- MAIN CONTENT WRAPPER -->
    <main class="main-content">
        <!-- HEADER -->
        <header>
            <div class="header-left">
                <div class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div>
                    <h2><?php echo $headerTitle ?? 'Dashboard'; ?></h2>
                    <p><?php echo $headerSubtitle ?? "Bienvenue sur votre espace d'administration"; ?></p>
                </div>
            </div>

            <div class="header-right">
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <span class="name"><?php echo $_SESSION['user_name'] ?? 'Admin'; ?></span>
                        <span class="role">Administrateur</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
