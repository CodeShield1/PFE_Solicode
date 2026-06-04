<?php
// On vérifie si l'utilisateur est admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?url=login');
    exit;
}

$pageTitle = "Dashboard - MEGALOC";
$currentPage = "dashboard";
$headerTitle = "Dashboard";
$headerSubtitle = "Bienvenue sur votre espace d'administration";

include __DIR__ . '/../layout/header.php';
?>

<!-- DASHBOARD CONTENT -->
<div class="stats-grid">
    <!-- On pourra ajouter des cartes de statistiques ici plus tard -->
    <p>Statistiques et graphiques à venir...</p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
