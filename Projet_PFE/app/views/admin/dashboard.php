<?php
// On vérifie si l'utilisateur est admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?url=login');
    exit;
}

$pageTitle = "Dashboard - MEGALOC";
$currentPage = "dashboard";
$headerTitle = "Dashboard";
$headerSubtitle = '<span style="color:var(--active-orange)">Accueil</span> / Dashboard';

$pageCSS = "admin";
$pageJS = "dashboard";

include __DIR__ . '/../layout/header.php';

// Defaults
$dashEquipment       = $dashEquipment ?? 0;
$dashEquipThisMonth  = $dashEquipThisMonth ?? 0;
$dashAvailEquip      = $dashAvailEquip ?? 0;
$dashAvailThisMonth  = $dashAvailThisMonth ?? 0;
$dashTotalRes        = $dashTotalRes ?? 0;
$dashResThisMonth    = $dashResThisMonth ?? 0;
$dashMonthRevenue    = $dashMonthRevenue ?? 0;
$dashRevenueDelta    = $dashRevenueDelta ?? 0;
$dashClients         = $dashClients ?? 0;
$dashClientsThisMonth = $dashClientsThisMonth ?? 0;
$dashPending         = $dashPending ?? 0;
$dashApproved        = $dashApproved ?? 0;
$dashRejected        = $dashRejected ?? 0;
$dashMostUsed        = $dashMostUsed ?? [];
$dashRecentRes       = $dashRecentRes ?? [];
?>

<!-- ========== STAT CARDS ========== -->
<div class="dash-cards">
    <!-- Total Equipment -->
    <div class="dash-card">
        <div class="dash-card-icon dash-icon-equip">
            <i class="fas fa-tractor"></i>
        </div>
        <div class="dash-card-body">
            <span class="dash-card-label">Total Équipements</span>
            <span class="dash-card-value"><?= $dashEquipment ?></span>
        </div>
    </div>
    <!-- Available Equipment -->
    <div class="dash-card">
        <div class="dash-card-icon dash-icon-avail">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="dash-card-body">
            <span class="dash-card-label">Équipements Disponibles</span>
            <span class="dash-card-value"><?= $dashAvailEquip ?></span>
        </div>
    </div>
    <!-- Total Reservations -->
    <div class="dash-card">
        <div class="dash-card-icon dash-icon-res">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="dash-card-body">
            <span class="dash-card-label">Total Réservations</span>
            <span class="dash-card-value"><?= $dashTotalRes ?></span>
        </div>
    </div>
    <!-- Monthly Revenue -->
    <div class="dash-card">
        <div class="dash-card-icon dash-icon-revenue">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="dash-card-body">
            <span class="dash-card-label">Revenu Mensuel</span>
            <span class="dash-card-value"><?= number_format($dashMonthRevenue, 0) ?> DH</span>
            <span class="dash-card-change <?= $dashRevenueDelta >= 0 ? 'change-up' : 'change-down' ?>">
                <i class="fas fa-arrow-<?= $dashRevenueDelta >= 0 ? 'up' : 'down' ?>"></i>
                <?= number_format(abs($dashRevenueDelta), 0) ?> DH ce mois
            </span>
        </div>
    </div>
    <!-- Total Clients -->
    <div class="dash-card">
        <div class="dash-card-icon dash-icon-clients">
            <i class="fas fa-users"></i>
        </div>
        <div class="dash-card-body">
            <span class="dash-card-label">Total Clients</span>
            <span class="dash-card-value"><?= $dashClients ?></span>
        </div>
    </div>
</div>

<!-- ========== CHARTS ROW ========== -->
<div class="dash-charts">
    <!-- Bar Chart: Most Used Equipment -->
    <div class="dash-chart-card">
        <div class="dash-chart-header">
            <h3>Équipements les Plus Utilisés</h3>
            <span class="dash-chart-filter">Ce Mois</span>
        </div>
        <div class="dash-chart-body">
            <canvas id="barChart"></canvas>
        </div>
    </div>
    <!-- Donut Chart: Reservation Status -->
    <div class="dash-chart-card">
        <div class="dash-chart-header">
            <h3>Statut des Réservations</h3>
        </div>
        <div class="dash-chart-body dash-donut-wrap">
            <div class="dash-donut-container">
                <canvas id="donutChart"></canvas>
                <div class="dash-donut-center">
                    <span class="donut-total"><?= $dashTotalRes ?></span>
                    <span class="donut-label">Total</span>
                </div>
            </div>
            <div class="dash-donut-legend">
                <div class="legend-item">
                    <span class="legend-dot dot-pending"></span>
                    <span class="legend-text">En Attente</span>
                    <span class="legend-val"><?= $dashPending ?> <span class="legend-pct">(<?= $dashTotalRes > 0 ? round($dashPending/$dashTotalRes*100) : 0 ?>%)</span></span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-rejected"></span>
                    <span class="legend-text">Rejetées</span>
                    <span class="legend-val"><?= $dashRejected ?> <span class="legend-pct">(<?= $dashTotalRes > 0 ? round($dashRejected/$dashTotalRes*100) : 0 ?>%)</span></span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-approved"></span>
                    <span class="legend-text">Approuvées</span>
                    <span class="legend-val"><?= $dashApproved ?> <span class="legend-pct">(<?= $dashTotalRes > 0 ? round($dashApproved/$dashTotalRes*100) : 0 ?>%)</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== LATEST RESERVATIONS TABLE ========== -->
<div class="dash-table-card">
    <div class="dash-table-header">
        <h3><i class="fas fa-clock"></i> Dernières Réservations</h3>
        <a href="index.php?url=reservations" class="dash-view-all">Voir tout <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="dash-table-wrap">
        <table class="dash-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Articles</th>
                    <th>Date</th>
                    <th>Durée</th>
                    <th>Prix Total</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dashRecentRes as $r): ?>
                    <?php
                    $days = (strtotime($r['end_date']) - strtotime($r['start_date'])) / 86400;
                    $statusLabels = ['Pending' => 'En Attente', 'Approved' => 'Approuvée', 'Rejected' => 'Rejetée'];
                    $statusClass  = strtolower($r['status']);
                    ?>
                    <tr>
                        <td><strong>RES-<?= str_pad($r['id_reservation'], 3, '0', STR_PAD_LEFT) ?></strong></td>
                        <td>
                            <div class="dash-client-cell">
                                <div class="dash-client-avatar"><?= strtoupper(substr($r['client_name'], 0, 1)) ?></div>
                                <span><?= htmlspecialchars($r['client_name']) ?></span>
                            </div>
                        </td>
                        <td><?= $r['items_count'] ?> Équipement<?= $r['items_count'] > 1 ? 's' : '' ?></td>
                        <td>
                            <span class="dash-date-cell">
                                <i class="far fa-calendar-alt"></i>
                                <?= date('d M Y', strtotime($r['created_at'])) ?>
                            </span>
                        </td>
                        <td><?= $days ?> Jour<?= $days > 1 ? 's' : '' ?></td>
                        <td><span class="dash-price"><?= number_format($r['total_price'], 0) ?> DH</span></td>
                        <td>
                            <span class="dash-status dash-status-<?= $statusClass ?>">
                                <span class="dash-status-dot"></span>
                                <?= $statusLabels[$r['status']] ?? $r['status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($dashRecentRes)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:30px;color:var(--text-gray);">
                            Aucune réservation pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// ===== BAR CHART: Most Used Equipment =====
(function() {
    var barCtx = document.getElementById('barChart');
    if (!barCtx) return;

    var labels = <?= json_encode(array_column($dashMostUsed, 'name')) ?>;
    var data   = <?= json_encode(array_map('intval', array_column($dashMostUsed, 'usage_count'))) ?>;

    // If no data, show placeholders
    if (labels.length === 0) {
        labels = ['Aucune donnée'];
        data = [0];
    }

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Réservations',
                data: data,
                backgroundColor: '#ff6b00',
                borderRadius: 6,
                maxBarThickness: 44
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#a0aec0', font: { size: 12 } },
                    grid: { color: '#edf2f7' }
                },
                x: {
                    ticks: { color: '#718096', font: { size: 11 }, maxRotation: 30 },
                    grid: { display: false }
                }
            }
        }
    });
})();

// ===== DONUT CHART: Reservation Status =====
(function() {
    var donutCtx = document.getElementById('donutChart');
    if (!donutCtx) return;

    var pending  = <?= $dashPending ?>;
    var approved = <?= $dashApproved ?>;
    var rejected = <?= $dashRejected ?>;

    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: ['En Attente', 'Rejetées', 'Approuvées'],
            datasets: [{
                data: [pending, rejected, approved],
                backgroundColor: ['#f59e0b', '#ef4444', '#10b981'],
                borderWidth: 0,
                cutout: '72%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.label + ': ' + ctx.raw;
                        }
                    }
                }
            }
        }
    });
})();
</script>

<style>
/* ========== DASHBOARD STYLES ========== */

/* --- Stat Cards --- */
.dash-cards {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 18px;
}

.dash-card {
    background: white;
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    transition: transform 0.2s, box-shadow 0.2s;
}

.dash-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.07);
}

.dash-card-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.dash-icon-equip    { background: #fff3e0; color: #ff6b00; }
.dash-icon-avail    { background: #ecfdf5; color: #10b981; }
.dash-icon-res      { background: #eff6ff; color: #3b82f6; }
.dash-icon-revenue  { background: #fef3e2; color: #ff6b00; }
.dash-icon-clients  { background: #f0e6ff; color: #8b5cf6; }

.dash-card-body {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}

.dash-card-label {
    font-size: 11px;
    color: #a0aec0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.dash-card-value {
    font-size: 20px;
    font-weight: 800;
    color: #1a202c;
    line-height: 1.2;
}

.dash-card-change {
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.change-up { color: #10b981; }
.change-down { color: #ef4444; }

/* --- Charts Row --- */
.dash-charts {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 14px;
    margin-bottom: 18px;
}

.dash-chart-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    overflow: hidden;
}

.dash-chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #edf2f7;
}

.dash-chart-header h3 {
    font-size: 14px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

.dash-chart-filter {
    font-size: 11px;
    font-weight: 600;
    color: var(--active-orange);
    background: #fff3e0;
    padding: 3px 10px;
    border-radius: 20px;
}

.dash-chart-body {
    padding: 14px;
    height: 220px;
}

/* Donut Chart */
.dash-donut-wrap {
    display: flex;
    align-items: center;
    gap: 18px;
    height: auto;
    padding: 16px;
}

.dash-donut-container {
    position: relative;
    width: 160px;
    height: 160px;
    flex-shrink: 0;
}

.dash-donut-container canvas {
    width: 100% !important;
    height: 100% !important;
}

.dash-donut-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.donut-total {
    display: block;
    font-size: 22px;
    font-weight: 800;
    color: #1a202c;
    line-height: 1;
}

.donut-label {
    display: block;
    font-size: 12px;
    color: #a0aec0;
    font-weight: 600;
    text-transform: uppercase;
}

.dash-donut-legend {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.dot-pending  { background: #f59e0b; }
.dot-rejected { background: #ef4444; }
.dot-approved { background: #10b981; }

.legend-text {
    font-size: 13px;
    font-weight: 600;
    color: #4a5568;
    min-width: 70px;
}

.legend-val {
    font-size: 13px;
    font-weight: 700;
    color: #1a202c;
}

.legend-pct {
    font-weight: 500;
    color: #a0aec0;
}

/* --- Latest Reservations Table --- */
.dash-table-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.04);
    overflow: hidden;
}

.dash-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #edf2f7;
}

.dash-table-header h3 {
    font-size: 14px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dash-table-header h3 i { color: var(--active-orange); }

.dash-view-all {
    font-size: 12px;
    font-weight: 600;
    color: var(--active-orange);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: gap 0.2s;
}

.dash-view-all:hover { gap: 10px; }

.dash-table-wrap { overflow-x: auto; }

.dash-table {
    width: 100%;
    border-collapse: collapse;
}

.dash-table th {
    text-align: left;
    padding: 10px 14px;
    font-size: 11px;
    text-transform: uppercase;
    color: #718096;
    font-weight: 700;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #edf2f7;
    background: #f8fafc;
}

.dash-table td {
    padding: 10px 14px;
    font-size: 13px;
    color: #4a5568;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.dash-table tbody tr:last-child td { border-bottom: none; }

.dash-table tbody tr:hover td { background: #f8fafc; }

/* Client cell */
.dash-client-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dash-client-avatar {
    width: 28px;
    height: 28px;
    background: #ebf8ff;
    color: #3182ce;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 11px;
    flex-shrink: 0;
}

/* Date cell */
.dash-date-cell {
    display: flex;
    align-items: center;
    gap: 6px;
}

.dash-date-cell i { color: #a0aec0; font-size: 12px; }

/* Price */
.dash-price {
    font-weight: 700;
    color: var(--active-orange);
}

/* Status badge */
.dash-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.dash-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
}

.dash-status-pending  { background: #fff8e1; color: #d97706; }
.dash-status-pending .dash-status-dot  { background: #f59e0b; }
.dash-status-approved { background: #ecfdf5; color: #059669; }
.dash-status-approved .dash-status-dot { background: #10b981; }
.dash-status-rejected { background: #fef2f2; color: #dc2626; }
.dash-status-rejected .dash-status-dot { background: #ef4444; }

/* --- Responsive --- */
@media (max-width: 1400px) {
    .dash-cards { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 1100px) {
    .dash-cards { grid-template-columns: repeat(2, 1fr); }
    .dash-charts { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .dash-cards { grid-template-columns: 1fr; }
    .dash-donut-wrap { flex-direction: column; }
    .dash-donut-container { width: 140px; height: 140px; margin: 0 auto; }
}
</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>