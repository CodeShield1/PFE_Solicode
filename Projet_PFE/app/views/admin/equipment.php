<?php
/** @var array $equipments */
/** @var array $categories */
/** @var array $cities */

$pageTitle = "Gestion des Équipements - MEGALOC";
$currentPage = "equipment";
$headerTitle = "Équipements";
$headerSubtitle = "Gérer votre catalogue de matériel de construction";

// Custom styles and scripts for this page
$pageCSS = "equipments";
$pageJS = "equipments";

// Initialisation de sécurité
if (!isset($equipments)) { $equipments = []; }
if (!isset($categories)) { $categories = []; }
if (!isset($cities)) { $cities = []; }

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

<div class="table-responsive">
    <div class="table-header">
        <h3>Catalogue Matériel</h3>
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i>
            Ajouter un Matériel
        </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Nom / Modèle</th>
                <th>Catégorie</th>
                <th>Ville</th>
                <th>Prix / Jour</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipments as $equip): ?>
                <tr>
                    <td>
                        <?php
                        $adminImgPath = __DIR__ . '/../../public/uploads/equipments/' . $equip['image'];
                        if (!empty($equip['image']) && file_exists($adminImgPath)):
                        ?>
                            <img src="uploads/equipments/<?php echo $equip['image']; ?>" alt="Equip" class="equip-img">
                        <?php else: ?>
                            <div style="width:60px;height:45px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#ccc;"><i class="fas fa-tractor"></i></div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo htmlspecialchars($equip['name']); ?></strong></td>
                    <td>
                        <span class="category-badge cat-<?php echo ($equip['category_id'] % 5) + 1; ?>">
                            <?php echo htmlspecialchars($equip['category_name']); ?>
                        </span>
                    </td>
                    <td><span class="city-name"><i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($equip['city_name']); ?></span></td>
                    <td><span class="price-tag"><?php echo number_format($equip['price_per_day'], 2); ?> DH</span></td>
                    <td>
                        <span class="badge-stock <?php echo ($equip['quantity_stock'] > 0) ? 'stock-ok' : 'stock-low'; ?>">
                            <?php echo $equip['quantity_stock']; ?> 
                        </span>
                    </td>
                    <td class="actions">
                        <?php 
                            // Convertir l'objet en JSON pour le passer au modal
                            $jsonEquip = htmlspecialchars(json_encode($equip), ENT_QUOTES, 'UTF-8');
                        ?>
                        <button class="btn-action btn-edit" onclick='openEditModal(<?php echo $jsonEquip; ?>)' title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="index.php?url=delete_equipment&id=<?php echo $equip['id_equipment']; ?>" class="btn-action btn-delete" title="Supprimer" onclick="return confirm('Supprimer cet équipement ?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($equipments)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-gray);">
                        Aucun équipement disponible pour le moment.
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
            <h2>Ajouter un Équipement</h2>
            <span class="close-modal" onclick="closeAddModal()">&times;</span>
        </div>
        <form action="index.php?url=add_equipment" method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nom de l'équipement</label>
                    <input type="text" name="name" required placeholder="Ex: Mini Pelle 2.5T">
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" placeholder="Détails techniques..."></textarea>
                </div>
                <div class="form-group">
                    <label>Prix par Jour (DH)</label>
                    <input type="number" step="0.01" name="price_per_day" required>
                </div>
                <div class="form-group">
                    <label>Quantité en Stock</label>
                    <input type="number" name="quantity_stock" required value="1">
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category_id" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id_category']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ville</label>
                    <select name="city_id" required>
                        <option value="">Sélectionner...</option>
                        <?php foreach($cities as $city): ?>
                            <option value="<?php echo $city['id_city']; ?>"><?php echo $city['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Image de l'équipement</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
            </div>
            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Modifier l'Équipement</h2>
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
        </div>
        <form action="index.php?url=update_equipment" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit_id" name="id_equipment">
            <input type="hidden" id="edit_current_image" name="current_image">
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nom de l'équipement</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea id="edit_description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label>Prix par Jour (DH)</label>
                    <input type="number" step="0.01" id="edit_price" name="price_per_day" required>
                </div>
                <div class="form-group">
                    <label>Quantité en Stock</label>
                    <input type="number" id="edit_stock" name="quantity_stock" required>
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select id="edit_category" name="category_id" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id_category']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ville</label>
                    <select id="edit_city" name="city_id" required>
                        <?php foreach($cities as $city): ?>
                            <option value="<?php echo $city['id_city']; ?>"><?php echo $city['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Changer l'Image (optionnel)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
            </div>
            <button type="submit" class="btn-submit">Mettre à jour</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
