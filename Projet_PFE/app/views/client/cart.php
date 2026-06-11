<?php
$pageTitle  = 'My Cart - MEGALOC';
$activePage = 'equipments';
$showCart   = true;
include __DIR__ . '/../layout/client_header.php';

$cart = $_SESSION['cart'] ?? [];
$totalDH = 0;
?>

<div class="container" style="margin-top: 120px; min-height: 60vh; padding-bottom: 50px;">
    <h2 style="margin-bottom: 30px; font-weight: 700; color: #333;">Shopping Cart</h2>

    <?php if (empty($cart)): ?>
        <div style="text-align: center; padding: 50px; background: #f9f9f9; border-radius: 12px;">
            <i class="fas fa-shopping-basket" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
            <p style="color: #777;">Your cart is empty.</p>
            <a href="index.php?url=equipments_list" class="btn-primary" style="display: inline-block; margin-top: 15px; background: #e67e22; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none;">Browse Equipment</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            <!-- Cart Items -->
            <div class="cart-items-list">
                <?php foreach ($cart as $id => $item): 
                    $days = (strtotime($item['end_date']) - strtotime($item['start_date'])) / 86400;
                    $itemTotal = $item['price'] * $item['quantity'] * $days;
                    $totalDH += $itemTotal;
                ?>
                    <div class="cart-item-card" style="display: flex; gap: 20px; background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 15px; margin-bottom: 15px; align-items: center;">
                        <img src="uploads/equipments/<?php echo $item['image']; ?>" alt="" style="width: 100px; height: 80px; object-fit: cover; border-radius: 8px;">
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 5px; color: #2c3e50;"><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p style="margin: 0; font-size: 13px; color: #777;"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($item['city_name']); ?></p>
                            <p style="margin: 5px 0 0; font-size: 13px; color: #777;"><i class="fas fa-calendar-alt"></i> <?php echo $item['start_date']; ?> to <?php echo $item['end_date']; ?> (<?php echo $days; ?> days)</p>
                        </div>
                        <div style="text-align: center; min-width: 100px;">
                            <div style="font-size: 14px; color: #888;">Qty: <?php echo $item['quantity']; ?></div>
                            <div style="font-weight: 700; color: #e67e22;"><?php echo number_format($itemTotal, 2); ?> DH</div>
                        </div>
                        <a href="index.php?url=remove_from_cart&id=<?php echo $id; ?>" style="color: #e74c3c; font-size: 18px; padding: 10px;"><i class="fas fa-trash"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="cart-summary" style="background: #fdfdfd; border: 1px solid #eee; border-radius: 12px; padding: 25px; align-self: start;">
                <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 20px; color: #2c3e50;">Summary</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; color: #666;">
                    <span>Subtotal</span>
                    <span><?php echo number_format($totalDH, 2); ?> DH</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-top: 15px; border-top: 1px solid #eee; font-weight: 700; font-size: 18px; color: #333;">
                    <span>Total</span>
                    <span style="color: #e67e22;"><?php echo number_format($totalDH, 2); ?> DH</span>
                </div>
                <form action="index.php?url=reserve_all" method="POST">
                    <button type="submit" style="width: 100%; padding: 15px; background: #e67e22; color: #fff; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 16px;">Confirm Reservation</button>
                </form>
                <a href="index.php?url=equipments_list" style="display: block; text-align: center; margin-top: 15px; color: #777; font-size: 14px; text-decoration: none;">Continue Shopping</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
