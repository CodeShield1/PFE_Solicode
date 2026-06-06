/**
 * Equipments Management JavaScript
 * Handles Modals for Adding and Editing Equipments
 */

function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function openEditModal(equip) {
    document.getElementById('edit_id').value = equip.id_equipment;
    document.getElementById('edit_name').value = equip.name;
    document.getElementById('edit_description').value = equip.description;
    document.getElementById('edit_price').value = equip.price_per_day;
    document.getElementById('edit_stock').value = equip.quantity_stock;
    document.getElementById('edit_category').value = equip.category_id;
    document.getElementById('edit_city').value = equip.city_id;
    document.getElementById('edit_current_image').value = equip.image;
    
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target == addModal) {
        closeAddModal();
    }
    if (event.target == editModal) {
        closeEditModal();
    }
}
