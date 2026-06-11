<?php
$pageTitle  = 'My Cart - MEGALOC';
$activePage = 'equipments';
$showCart   = true;
$pageCSS    = ['equipments-page'];

include __DIR__ . '/../layout/client_header.php';
?>

<div class="ep-hero">
    <h1>My Cart</h1>
    <div class="ep-breadcrumb">
        <a href="index.php?url=home">Home</a>
        <span>/</span>
        <a href="index.php?url=equipments_list">Equipments</a>
        <span>/</span> Cart
    </div>
</div>

<div style="max-width:700px;margin:60px auto;text-align:center;padding:0 24px;">
    <i class="fas fa-shopping-cart" style="font-size:64px;color:var(--primary);margin-bottom:20px;display:block;"></i>
    <h2 style="font-size:24px;font-weight:800;color:var(--secondary);margin-bottom:10px;">Your cart is empty</h2>
    <p style="color:var(--muted-text);font-size:15px;margin-bottom:28px;">
        Browse our equipment and add items to your cart to start a reservation.
    </p>
    <a href="index.php?url=equipments_list"
       style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--primary);color:#fff;border-radius:10px;font-weight:700;font-size:15px;text-decoration:none;">
        <i class="fas fa-th-large"></i> Browse Equipments
    </a>
</div>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
