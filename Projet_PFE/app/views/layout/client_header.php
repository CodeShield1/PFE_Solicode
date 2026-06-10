<?php
/**
 * Reusable Client Header
 * --------------------------------------------
 * Expected variables (optional, with defaults):
 *   $pageTitle   string  Browser tab title
 *   $pageCSS     array   Extra CSS files (without .css) to load
 *   $activePage  string  Slug of current page (home|equipments|reservations|categories)
 *   $showCart    bool    Show cart icon (true on Equipment page)
 *   $categories  array   Category list for the mega menu
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/* Safe defaults — these guards also keep IDE static-analysis happy
   and prevent foreach errors if a caller passes the wrong type. */
$pageTitle  = isset($pageTitle)  && is_string($pageTitle) ? $pageTitle  : 'MEGALOC - Equipment Rental';
$activePage = isset($activePage) && is_string($activePage) ? $activePage : '';
$showCart   = isset($showCart)   ? (bool) $showCart : false;
$categories = isset($categories) && is_array($categories) ? $categories : [];
$pageCSS    = isset($pageCSS)    && is_array($pageCSS)    ? $pageCSS    : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="css/components/header.css">
    <link rel="stylesheet" href="css/components/footer.css">

    <?php foreach ($pageCSS as $css): ?>
        <link rel="stylesheet" href="css/<?php echo htmlspecialchars($css); ?>.css">
    <?php endforeach; ?>
</head>
<body>

<header class="main-header">
    <div class="container header-container">

        <div class="header-left">
            <a href="index.php?url=home" class="logo">
                <span class="mega">MEGA</span><span class="loc">LOC</span>
            </a>

            <nav class="header-nav">
                <a href="index.php?url=home"
                   class="nav-link <?php echo $activePage === 'home' ? 'active' : ''; ?>">Home</a>
                <a href="index.php?url=equipments_list"
                   class="nav-link <?php echo $activePage === 'equipments' ? 'active' : ''; ?>">Equipments</a>

                <div class="nav-item category-dropdown">
                    <div class="dropdown-trigger" id="categoryTrigger">
                        <span>Categories</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="mega-menu" id="categoryMenu">
                        <div class="mega-menu-grid">
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <a href="index.php?url=equipments_list&category=<?php echo $category['id_category']; ?>" class="mega-menu-item">
                                        <div class="item-icon">
                                            <?php if (!empty($category['image'])): ?>
                                                <img src="uploads/categories/<?php echo htmlspecialchars($category['image']); ?>" alt="">
                                            <?php else: ?>
                                                <i class="fas fa-layer-group"></i>
                                            <?php endif; ?>
                                        </div>
                                        <span class="item-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-cats">No categories found</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'client'): ?>
                    <a href="index.php?url=my_reservations"
                       class="nav-link <?php echo $activePage === 'reservations' ? 'active' : ''; ?>">My Reservations</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="header-right">
            <div class="company-phone">
                <i class="fas fa-phone-alt"></i>
                <span>+212 5XX XX XX XX</span>
            </div>

            <?php if ($showCart): ?>
                <a href="index.php?url=cart" class="cart-btn" aria-label="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge">0</span>
                </a>
            <?php endif; ?>

            <div class="profile-nav">
                <div class="profile-trigger" id="profileTrigger">
                    <i class="fas fa-user-circle"></i>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="profile-dropdown-menu" id="profileMenu">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-info">
                            <span class="welcome">Welcome,</span>
                            <span class="username"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                        </div>
                        <hr>
                        <?php if (($_SESSION['user_role'] ?? '') === 'client'): ?>
                            <a href="index.php?url=my_reservations" class="dropdown-link">
                                <i class="fas fa-calendar-alt"></i> My Reservations
                            </a>
                        <?php endif; ?>
                        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                            <a href="index.php?url=admin_dashboard" class="dropdown-link">
                                <i class="fas fa-gauge-high"></i> Dashboard
                            </a>
                        <?php endif; ?>
                        <a href="index.php?url=logout" class="dropdown-link logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    <?php else: ?>
                        <a href="index.php?url=login" class="dropdown-link">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="index.php?url=register" class="dropdown-link">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>
