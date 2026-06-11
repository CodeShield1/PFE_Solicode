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

$pageCSS = "admin";
$pageJS = "dashboard";

include __DIR__ . '/../layout/header.php';

// Default values if not set
$dashPending   = $dashPending ?? 0;
$dashApproved  = $dashApproved ?? 0;
$dashRejected  = $dashRejected ?? 0;
$dashRevenue   = $dashRevenue ?? 0;
$dashEquipment = $dashEquipment ?? 0;
$dashClients   = $dashClients ?? 0;
$dashCategories = $dashCategories ?? 0;
$dashCities    = $dashCities ?? 0;
$dashRecentRes = $dashRecentRes ?? [];
?>

<!-- DASHBOARD STATS -->
<div class="dash-stats">
    <!-- Row 1: Reservation stats -->
    <div class="dash-stat-card dash-stat-pending">
        <div class="dash-stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashPending ?></span>
            <span class="dash-stat-label">En Attente</span>
        </div>
        <a href="index.php?url=reservations" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-stat-card dash-stat-approved">
        <div class="dash-stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashApproved ?></span>
            <span class="dash-stat-label">Approuvées</span>
        </div>
        <a href="index.php?url=reservations" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-stat-card dash-stat-rejected">
        <div class="dash-stat-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashRejected ?></span>
            <span class="dash-stat-label">Rejetées</span>
        </div>
        <a href="index.php?url=reservations" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-stat-card dash-stat-revenue">
        <div class="dash-stat-icon">
            <i class="fas fa-coins"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= number_format($dashRevenue, 0) ?> DH</span>
            <span class="dash-stat-label">Revenus</span>
        </div>
    </div>
</div>

<!-- Row 2: Platform stats -->
<div class="dash-stats">
    <div class="dash-stat-card dash-stat-equip">
        <div class="dash-stat-icon">
            <i class="fas fa-tools"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashEquipment ?></span>
            <span class="dash-stat-label">Équipements</span>
        </div>
        <a href="index.php?url=equipment" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-stat-card dash-stat-clients">
        <div class="dash-stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashClients ?></span>
            <span class="dash-stat-label">Clients</span>
        </div>
        <a href="index.php?url=clients" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-stat-card dash-stat-cats">
        <div class="dash-stat-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashCategories ?></span>
            <span class="dash-stat-label">Catégories</span>
        </div>
        <a href="index.php?url=categories" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-stat-card dash-stat-cities">
        <div class="dash-stat-icon">
            <i class="fas fa-city"></i>
        </div>
        <div class="dash-stat-content">
            <span class="dash-stat-number"><?= $dashCities ?></span>
            <span class="dash-stat-label">Villes</span>
        </div>
        <a href="index.php?url=cities" class="dash-stat-link"><i class="fas fa-arrow-right"></i></a>
    </div>
</div>

<!-- Recent Reservations -->
<div class="dash-recent">
    <div class="dash-recent-header">
        <h3><i class="fas fa-clock"></i> Réservations Récentes</h3>
        <a href="index.php?url=reservations" class="dash-see-all">Voir tout <i class="fas fa-arrow-right"></i></a>
    </div>
    <?php if (empty($dashRecentRes)): ?>
        <div class="dash-recent-empty">
            <i class="fas fa-calendar-xmark"></i>
            <p>Aucune réservation pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="dash-recent-list">
            <?php foreach ($dashRecentRes as $r): ?>
                <div class="dash-recent-item">
                    <div class="dash-recent-left">
                        <div class="dash-recent-avatar">
                            <?= strtoupper(substr($r['client_name'], 0, 1)) ?>
                        </div>
                        <div class="dash-recent-info">
                            <span class="dash-recent-name"><?= htmlspecialchars($r['client_name']) ?></span>
                            <span class="dash-recent-id">Réservation #<?= $r['id_reservation'] ?></span>
                        </div>
                    </div>
                    <div class="dash-recent-right">
                        <span class="dash-recent-price"><?= number_format($r['total_price'], 0) ?> DH</span>
                        <span class="dash-recent-badge badge-<?= strtolower($r['status']) ?>">
                            <?php
                            $labels = ['Pending' => 'En Attente', 'Approved' => 'Approuvée', 'Rejected' => 'Rejetée'];
                            echo $labels[$r['status']] ?? $r['status'];
                            ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Dashboard Stats */
.dash-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.dash-stat-card {
    background: white;
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}

.dash-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}

.dash-stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.dash-stat-pending .dash-stat-icon { background: #fff8e1; color: #f59e0b; }
.dash-stat-approved .dash-stat-icon { background: #ecfdf5; color: #10b981; }
.dash-stat-rejected .dash-stat-icon { background: #fef2f2; color: #ef4444; }
.dash-stat-revenue .dash-stat-icon { background: #eff6ff; color: #3b82f6; }
.dash-stat-equip .dash-stat-icon { background: #fef3e2; color: var(--active-orange); }
.dash-stat-clients .dash-stat-icon { background: #f0e6ff; color: #8b5cf6; }
.dash-stat-cats .dash-stat-icon { background: #e6fff0; color: #10b981; }
.dash-stat-cities .dash-stat-icon { background: #e6f4ff; color: #0ea5e9; }

.dash-stat-content {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
}

.dash-stat-number {
    font-size: 22px;
    font-weight: 800;
    color: #1a202c;
    line-height: 1.2;
}

.dash-stat-label {
    font-size: 12px;
    color: #a0aec0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.dash-stat-link {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #a0aec0;
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
}

.dash-stat-link:hover {
    background: var(--active-orange);
    color: white;
}

/* Recent Reservations */
.dash-recent {
    background: white;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    overflow: hidden;
}

.dash-recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 22px;
    border-bottom: 1px solid #edf2f7;
}

.dash-recent-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dash-recent-header h3 i { color: var(--active-orange); }

.dash-see-all {
    font-size: 13px;
    font-weight: 600;
    color: var(--active-orange);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: gap 0.2s;
}

.dash-see-all:hover { gap: 10px; }

.dash-recent-empty {
    text-align: center;
    padding: 40px;
    color: #a0aec0;
}

.dash-recent-empty i { font-size: 36px; color: #e2e8f0; margin-bottom: 12px; }

.dash-recent-list { padding: 8px 0; }

.dash-recent-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 22px;
    transition: background 0.15s;
}

.dash-recent-item:hover { background: #f8fafc; }

.dash-recent-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.dash-recent-avatar {
    width: 40px;
    height: 40px;
    background: #ebf8ff;
    color: #3182ce;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 15px;
}

.dash-recent-info { display: flex; flex-direction: column; gap: 2px; }

.dash-recent-name { font-size: 14px; font-weight: 700; color: #1a202c; }

.dash-recent-id { font-size: 12px; color: #a0aec0; }

.dash-recent-right {
    display: flex;
    align-items: center;
    gap: 14px;
}

.dash-recent-price {
    font-size: 15px;
    font-weight: 700;
    color: #1a202c;
}

.dash-recent-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.badge-pending { background: #fff8e1; color: #d97706; }
.badge-approved { background: #ecfdf5; color: #059669; }
.badge-rejected { background: #fef2f2; color: #dc2626; }

/* Responsive */
@media (max-width: 1200px) {
    .dash-stats { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 576px) {
    .dash-stats { grid-template-columns: 1fr; }
    .dash-recent-right { flex-direction: column; align-items: flex-end; gap: 6px; }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>