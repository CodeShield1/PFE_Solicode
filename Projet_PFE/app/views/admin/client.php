<?php
/** @var array $clients */

$pageTitle = "Liste des Clients - MEGALOC";
$currentPage = "clients";
$headerTitle = "Clients";
$headerSubtitle = "Gérer les comptes utilisateurs de la plateforme";

// Custom styles for this page
$pageCSS = "clients";

// Initialisation de sécurité
if (!isset($clients)) {
    $clients = [];
}

include __DIR__ . '/../layout/header.php';
?>

<div class="table-responsive">
    <div class="table-header">
        <h3>Clients Enregistrés</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date d'Inscription</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="client-avatar">
                                <?php echo strtoupper(substr($client['name'], 0, 1)); ?>
                            </div>
                            <div class="client-info">
                                <span class="name"><?php echo htmlspecialchars($client['name']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                    <td><span class="phone-number"><?php echo htmlspecialchars($client['phone']); ?></span></td>
                    <td>
                        <div class="date-badge">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('d M Y', strtotime($client['created_at'])); ?>
                        </div>
                    </td>
                    <td>
                        <span style="padding: 4px 10px; background: #e6fffa; color: #2c7a7b; border-radius: 20px; font-size: 11px; font-weight: 600;">
                            Actif
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-gray);">
                        Aucun client n'est encore inscrit.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
