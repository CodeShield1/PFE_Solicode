<?php
/** @var array $cities */

$pageTitle = "Gestion des Villes - MEGALOC";
$currentPage = "cities";
$headerTitle = "Villes";
$headerSubtitle = "Gérer les emplacements de vos équipements";

// Custom styles and scripts for this page
$pageCSS = "cities";
$pageJS = "cities";

// Initialisation de sécurité
if (!isset($cities)) {
    $cities = [];
}

include __DIR__ . '/../layout/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div class="table-container">
    <div class="table-header">
        <h3>Liste des Villes</h3>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            Ajouter une Ville
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom de la Ville</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city): ?>
                <tr>
                    <td>#<?php echo $city['id_city']; ?></td>
                    <td>
                        <img src="uploads/cities/<?php echo $city['image']; ?>" alt="<?php echo htmlspecialchars($city['name']); ?>" class="city-img">
                    </td>
                    <td><strong><?php echo htmlspecialchars($city['name']); ?></strong></td>
                    <td class="actions">
                        <button class="btn-action btn-edit" onclick="openEditModal(<?php echo $city['id_city']; ?>, '<?php echo addslashes($city['name']); ?>', '<?php echo $city['image']; ?>')" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="index.php?url=delete_city&id=<?php echo $city['id_city']; ?>" class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ville ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($cities)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: var(--text-gray);">
                        Aucune ville trouvée.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ADD MODAL -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ajouter une Ville</h2>
            <span class="close-modal" onclick="closeAddModal()">&times;</span>
        </div>
        <form action="index.php?url=add_city" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom de la Ville</label>
                <input type="text" id="name" name="name" placeholder="Ex: Casablanca" required>
            </div>
            <div class="form-group">
                <label for="image">Image de la Ville</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Modifier la Ville</h2>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <form action="index.php?url=update_city" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit_id" name="id_city">
            <input type="hidden" id="edit_current_image" name="current_image">
            
            <div class="form-group">
                <label for="edit_name">Nom de la Ville</label>
                <input type="text" id="edit_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_image">Changer l'Image (optionnel)</label>
                <input type="file" id="edit_image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn-submit">Mettre à jour</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
