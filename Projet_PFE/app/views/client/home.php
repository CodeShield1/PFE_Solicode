<?php
/**
 * MEGALOC :: HOME PAGE
 * Composes the page from reusable header, partial sections and footer.
 *
 * Expected from controller / router:
 *   $categories  array  (from CategoryController::getAllCategories)
 *   $cities      array  (from CityController::getAllCities)
 */

$pageTitle  = 'MEGALOC - Equipment Rental in Morocco';
$activePage = 'home';
$showCart   = false;
$pageCSS    = ['home/home'];
$pageJS     = ['home'];

include __DIR__ . '/../layout/client_header.php';
?>

<!-- Flatpickr (modern date picker) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<main class="home-page">

    <?php include __DIR__ . '/partials/hero.php';          ?>
    <?php include __DIR__ . '/partials/categories.php';    ?>
    <?php include __DIR__ . '/partials/brands.php';        ?>
    <?php include __DIR__ . '/partials/locations.php';     ?>
    <?php include __DIR__ . '/partials/about.php';         ?>
    <?php include __DIR__ . '/partials/how_it_works.php';  ?>
    <?php include __DIR__ . '/partials/cta.php';           ?>

</main>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
