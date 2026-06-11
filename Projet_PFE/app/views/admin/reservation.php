<?php
/** @var array $reservations */

$pageTitle = "Réservations - MEGALOC";
$currentPage = "reservations";
$headerTitle = "Réservations";
$headerSubtitle = "Gérer les demandes de réservation des clients";

$pageCSS = "reservations";
$pageJS = "reservations";

if (!isset($reservations)) {
    $reservations = [];
}

include __DIR__ . '/../layout/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="res-alert res-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="res-alert res-alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- Reservation List -->
<div class="res-list">
    <?php if (empty($reservations)): ?>
        <div class="res-empty">
            <i class="fas fa-calendar-xmark"></i>
            <p>Aucune réservation trouvée.</p>
        </div>
    <?php else: ?>
        <?php foreach ($reservations as $res): ?>
            <?php
            $statusLabels = ['Pending' => 'En Attente', 'Approved' => 'Approuvée', 'Rejected' => 'Rejetée'];
            $statusClass  = strtolower($res['status']);
            $days = (strtotime($res['end_date']) - strtotime($res['start_date'])) / 86400;
            ?>
            <div class="res-card res-card-<?= $statusClass ?>" id="res-card-<?= $res['id_reservation'] ?>">
                <!-- Summary Row -->
                <div class="res-summary" onclick="toggleReservation(<?= $res['id_reservation'] ?>)">
                    <div class="res-summary-left">
                        <div class="res-summary-id">
                            <span class="res-ref">RES-<?= str_pad($res['id_reservation'], 3, '0', STR_PAD_LEFT) ?></span>
                            <span class="res-summary-date"><?= date('d M Y', strtotime($res['created_at'])) ?></span>
                        </div>
                        <div class="res-summary-period">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?= date('d M Y', strtotime($res['start_date'])) ?> – <?= date('d M Y', strtotime($res['end_date'])) ?> <strong>(<?= $days ?> jour<?= $days > 1 ? 's' : '' ?>)</strong></span>
                        </div>
                        <div class="res-summary-equip">
                            <i class="fas fa-tools"></i>
                            <span><?= $res['items_count'] ?> article<?= $res['items_count'] > 1 ? 's' : '' ?></span>
                        </div>
                        <div class="res-summary-total">
                            <span class="res-total-label">Total</span>
                            <span class="res-total-value"><?= number_format($res['total_price'], 0) ?> DH</span>
                        </div>
                    </div>
                    <div class="res-summary-right">
                        <span class="res-status res-status-<?= $statusClass ?>">
                            <span class="res-status-dot"></span>
                            <?= $statusLabels[$res['status']] ?? $res['status'] ?>
                        </span>
                        <button class="btn-view-details">Voir Détails</button>
                        <span class="res-chevron">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                </div>

                <!-- Expanded Details -->
                <div class="res-details" id="res-details-<?= $res['id_reservation'] ?>">
                    <div class="res-details-inner">
                        <!-- Details Header -->
                        <div class="res-details-header">
                            <h4>Détails de la Réservation</h4>
                            <div class="res-details-meta">
                                <div class="res-meta-item">
                                    <span class="meta-label">Réservation</span>
                                    <span class="meta-value">RES-<?= str_pad($res['id_reservation'], 3, '0', STR_PAD_LEFT) ?></span>
                                </div>
                                <div class="res-meta-item">
                                    <span class="meta-label">Début</span>
                                    <span class="meta-value"><?= date('d M Y', strtotime($res['start_date'])) ?></span>
                                </div>
                                <div class="res-meta-item">
                                    <span class="meta-label">Fin</span>
                                    <span class="meta-value"><?= date('d M Y', strtotime($res['end_date'])) ?></span>
                                </div>
                                <div class="res-meta-item">
                                    <span class="meta-label">Statut</span>
                                    <span class="res-status res-status-<?= $statusClass ?>" style="font-size:11px;padding:3px 10px">
                                        <span class="res-status-dot"></span>
                                        <?= $statusLabels[$res['status']] ?? $res['status'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Client Info -->
                        <div class="res-details-client">
                            <div class="res-client-avatar-lg"><?= strtoupper(substr($res['client_name'], 0, 1)) ?></div>
                            <div class="res-client-fields">
                                <span class="rcf-name"><?= htmlspecialchars($res['client_name']) ?></span>
                                <span class="rcf-contact"><i class="fas fa-envelope"></i> <?= htmlspecialchars($res['client_email']) ?></span>
                                <span class="rcf-contact"><i class="fas fa-phone"></i> <?= htmlspecialchars($res['client_phone'] ?? '—') ?></span>
                            </div>
                        </div>

                        <!-- Equipment Table -->
                        <div class="res-eq-table-wrap">
                            <table class="res-eq-table">
                                <thead>
                                    <tr>
                                        <th>Équipement</th>
                                        <th>Quantité</th>
                                        <th>Prix / Jour</th>
                                        <th>Durée</th>
                                        <th>Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($res['equipments'] as $eq): ?>
                                        <tr>
                                            <td>
                                                <div class="res-eq-cell">
                                                    <div class="res-eq-thumb">
                                                        <?php
                                                        $eqImgPath = __DIR__ . '/../../public/uploads/equipments/' . $eq['image'];
                                                        if (!empty($eq['image']) && file_exists($eqImgPath)):
                                                        ?>
                                                            <img src="uploads/equipments/<?= htmlspecialchars($eq['image']) ?>" alt="">
                                                        <?php else: ?>
                                                            <div class="eq-thumb-placeholder"><i class="fas fa-tractor"></i></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="res-eq-name-city">
                                                        <span class="ren-name"><?= htmlspecialchars($eq['equipment_name']) ?></span>
                                                        <span class="ren-city"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($eq['city_name']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $eq['quantity'] ?></td>
                                            <td><?= number_format($eq['price_per_day'], 0) ?> DH</td>
                                            <td><?= $days ?> Jour<?= $days > 1 ? 's' : '' ?></td>
                                            <td><strong><?= number_format($eq['price_per_day'] * $eq['quantity'] * $days, 0) ?> DH</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:right"><span class="res-total-footer">Prix Total</span></td>
                                        <td><span class="res-total-footer-value"><?= number_format($res['total_price'], 0) ?> DH</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Actions -->
                        <?php if ($res['status'] === 'Pending'): ?>
                            <div class="res-detail-actions">
                                <a href="index.php?url=approve_reservation&id=<?= $res['id_reservation'] ?>" 
                                   class="res-btn res-btn-approve"
                                   onclick="return confirm('Approuver cette réservation ? Le stock sera déduit.')">
                                    <i class="fas fa-check"></i> Approuver
                                </a>
                                <a href="index.php?url=reject_reservation&id=<?= $res['id_reservation'] ?>" 
                                   class="res-btn res-btn-reject"
                                   onclick="return confirm('Rejeter cette réservation ?')">
                                    <i class="fas fa-times"></i> Rejeter
                                </a>
                            </div>
                        <?php elseif ($res['status'] === 'Approved'): ?>
                            <div class="res-detail-actions">
                                <span class="res-action-note res-note-approved"><i class="fas fa-check-circle"></i> Stock déjà déduit</span>
                                <a href="index.php?url=reject_reservation&id=<?= $res['id_reservation'] ?>" 
                                   class="res-btn res-btn-reject"
                                   onclick="return confirm('Rejeter ? Le stock sera restauré.')">
                                    <i class="fas fa-times"></i> Rejeter
                                </a>
                            </div>
                        <?php elseif ($res['status'] === 'Rejected'): ?>
                            <div class="res-detail-actions res-actions-rejected">
                                <span class="res-action-note res-note-rejected"><i class="fas fa-ban"></i> Réservation rejetée</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>