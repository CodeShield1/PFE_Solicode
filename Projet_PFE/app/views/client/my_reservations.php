<?php
$pageTitle  = 'My Reservations - MEGALOC';
$activePage = 'reservations';
include __DIR__ . '/../layout/client_header.php';
?>

<div class="container" style="margin-top: 120px; min-height: 60vh; padding-bottom: 50px;">
    <h2 style="margin-bottom: 30px; font-weight: 700; color: #333;">My Reservations</h2>

    <?php if (empty($reservations)): ?>
        <div style="text-align: center; padding: 50px; background: #f9f9f9; border-radius: 12px;">
            <i class="fas fa-calendar-times" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
            <p style="color: #777;">You have no reservations yet.</p>
            <a href="index.php?url=equipments_list" class="btn-primary" style="display: inline-block; margin-top: 15px; background: #e67e22; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none;">Browse Equipment</a>
        </div>
    <?php else: ?>
        <div class="reservations-list">
            <?php foreach ($reservations as $res): ?>
                <div class="reservation-card" style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; border-bottom: 1px solid #f5f5f5; padding-bottom: 10px;">
                        <div>
                            <span style="font-size: 14px; color: #888;">Order #<?php echo $res['id_reservation']; ?></span>
                            <h4 style="margin: 5px 0; color: #2c3e50;">Reserved on <?php echo date('d M Y', strtotime($res['created_at'])); ?></h4>
                        </div>
                        <span class="status-badge status-<?php echo strtolower($res['status']); ?>" style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: <?php echo $res['status'] === 'Approved' ? '#2ecc71' : ($res['status'] === 'Pending' ? '#f1c40f' : '#e74c3c'); ?>; color: #fff;">
                            <?php echo $res['status']; ?>
                        </span>
                    </div>

                    <div class="reservation-details" style="display: flex; flex-wrap: wrap; gap: 20px;">
                        <div style="flex: 1; min-width: 250px;">
                            <p style="margin: 0 0 10px; font-size: 15px;"><i class="fas fa-calendar-alt" style="color: #e67e22; width: 20px;"></i> <strong>Period:</strong> <?php echo date('d/m/Y', strtotime($res['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($res['end_date'])); ?></p>
                            <p style="margin: 0; font-size: 15px;"><i class="fas fa-money-bill-wave" style="color: #e67e22; width: 20px;"></i> <strong>Total:</strong> <?php echo number_format($res['total_price'], 2); ?> DH</p>
                        </div>
                        <div style="flex: 2; min-width: 300px;">
                            <h5 style="margin: 0 0 10px; font-size: 14px; text-transform: uppercase; color: #999;">Equipment:</h5>
                            <?php foreach ($res['equipments'] as $eq): ?>
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                    <img src="uploads/equipments/<?php echo $eq['image']; ?>" alt="" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                    <span><?php echo htmlspecialchars($eq['equipment_name']); ?> (x<?php echo $eq['quantity']; ?>)</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
