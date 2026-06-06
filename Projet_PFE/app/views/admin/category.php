<?php
/** @var array $categories */

$pageTitle = "Gestion des Catégories - MEGALOC";
$currentPage = "category";
$headerTitle = "Catégories";
$headerSubtitle = "Gérer les types d'équipements";

// Custom styles and scripts for this page
$pageCSS = "categories";
$pageJS = "categories";

// Initialisation de sécurité
if (!isset($categories)) {
    $categories = [];
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
        <h3>Liste des Catégories</h3>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            Ajouter une Catégorie
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Nom de la Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>#<?php echo $cat['id_category']; ?></td>
                    <td>
                        <img src="uploads/categories/<?php echo $cat['image']; ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>" class="category-img">
                    </td>
                    <td><strong><?php echo htmlspecialchars($cat['name']); ?></strong></td>
                    <td class="actions">
                        <button class="btn-action btn-edit" onclick="openEditModal(<?php echo $cat['id_category']; ?>, '<?php echo addslashes($cat['name']); ?>', '<?php echo $cat['image']; ?>')" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="index.php?url=delete_category&id=<?php echo $cat['id_category']; ?>" class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px; color: var(--text-gray);">
                        Aucune catégorie trouvée.
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
            <h2>Ajouter une Catégorie</h2>
            <span class="close-modal" onclick="closeAddModal()">&times;</span>
        </div>
        <form action="index.php?url=add_category" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom de la Catégorie</label>
                <input type="text" id="name" name="name" placeholder="Ex: Terrassement" required>
            </div>
            <div class="form-group">
                <label for="image">Image de la Catégorie</label>
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
            <h2>Modifier la Catégorie</h2>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <form action="index.php?url=update_category" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit_id" name="id_category">
            <input type="hidden" id="edit_current_image" name="current_image">
            <div class="form-group">
                <label for="edit_name">Nom de la Catégorie</label>
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
