<?php
/**
 * MEGALOC :: EQUIPMENT DETAILS PAGE
 * -------------------------------------------
 * Displays comprehensive info about a specific machine.
 */
$pageTitle  = $equipment['name'] . ' - MEGALOC';
$activePage = 'equipments';
$showCart   = true;
$pageCSS    = ['equipments']; // Uses C:\xampp\htdocs\PFE_Solicode\Projet_PFE\app\public\css\equipments.css

include __DIR__ . '/../layout/client_header.php';

// Retrieve search context from session
$session_city  = $_SESSION['search_city'] ?? null;
$start_date    = $_SESSION['search_start_date'] ?? '';
$end_date      = $_SESSION['search_end_date'] ?? '';

// Check if current equipment matches the session city (if city is set in session)
$city_match = (!$session_city || $session_city == $equipment['city_id']);
$is_available = ($available_stock > 0);
?>

<div class="ed-container container">
    
    <!-- Breadcrumb Navigation -->
    <div class="ed-breadcrumb">
        <a href="index.php?url=home">Home</a> / 
        <a href="index.php?url=equipments_list">Browse Equipments</a> / 
        <span><?php echo htmlspecialchars($equipment['name']); ?></span>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> 
            <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> 
            <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['warning'])): ?>
        <div class="alert-warning-card">
            <div class="warning-header">
                <i class="fas fa-exclamation-triangle"></i>
                <p><?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?></p>
            </div>
            <div class="warning-actions">
                <a href="index.php?url=clear_cart" class="btn-warning-action clear">Clear Cart & Continue</a>
                <button onclick="this.closest('.alert-warning-card').remove()" class="btn-warning-action cancel">Dismiss</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="ed-grid">
        
        <!-- LEFT COLUMN: Image Gallery -->
        <div class="ed-gallery">
            <div class="ed-main-img">
                <?php if (!empty($equipment['image'])): ?>
                    <img src="uploads/equipments/<?php echo htmlspecialchars($equipment['image']); ?>" 
                         alt="<?php echo htmlspecialchars($equipment['name']); ?>" id="mainImage">
                <?php else: ?>
                    <div class="ed-placeholder"><i class="fas fa-tractor"></i></div>
                <?php endif; ?>
            </div>
            
            <div class="ed-thumbnails">
                <div class="thumb active" onclick="updateMainImage('uploads/equipments/<?php echo $equipment['image']; ?>', this)">
                    <img src="uploads/equipments/<?php echo htmlspecialchars($equipment['image']); ?>" alt="Thumbnail 1">
                </div>
                <!-- Mock secondary images -->
                <div class="thumb" onclick="updateMainImage('uploads/equipments/<?php echo $equipment['image']; ?>', this)">
                    <img src="uploads/equipments/<?php echo htmlspecialchars($equipment['image']); ?>" alt="Thumbnail 2" style="filter: brightness(0.9) contrast(1.1);">
                </div>
                <div class="thumb" onclick="updateMainImage('uploads/equipments/<?php echo $equipment['image']; ?>', this)">
                    <img src="uploads/equipments/<?php echo htmlspecialchars($equipment['image']); ?>" alt="Thumbnail 3" style="filter: saturate(1.5);">
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Details & Action Card -->
        <div class="ed-details">
            <div class="ed-header">
                <span class="ed-badge-cat"><?php echo htmlspecialchars($equipment['category_name']); ?></span>
                <h1 class="ed-title"><?php echo htmlspecialchars($equipment['name']); ?></h1>
                
                <div class="ed-meta">
                    <span><i class="fas fa-industry"></i> Brand: <strong><?php echo htmlspecialchars($equipment['brand'] ?? 'Generic'); ?></strong></span>
                    <span><i class="fas fa-map-marker-alt"></i> City: <strong><?php echo htmlspecialchars($equipment['city_name']); ?></strong></span>
                    <span><i class="fas fa-info-circle"></i> Status: 
                        <strong style="color: <?php echo $is_available ? '#10b981' : '#ef4444'; ?>">
                            <?php echo $is_available ? 'Available' : 'Unavailable'; ?>
                        </strong>
                    </span>
                </div>
            </div>

            <div class="ed-price-box">
                <div class="price-val">
                    <?php echo number_format($equipment['price_per_day'], 0); ?> DH 
                    <small>/ Day</small>
                </div>
                <div class="stock-status <?php echo $is_available ? 'in-stock' : 'out-of-stock'; ?>">
                    <i class="fas <?php echo $is_available ? 'fa-box-open' : 'fa-times-circle'; ?>"></i>
                    <?php echo $is_available ? 'Stock: ' . $available_stock : 'Out of Stock'; ?>
                </div>
            </div>

            <div class="ed-description">
                <h3>Product Description</h3>
                <p><?php echo nl2br(htmlspecialchars($equipment['description'])); ?></p>
            </div>

            <!-- BOOKING FORM -->
            <form action="index.php?url=add_to_cart" method="POST" id="bookingForm" class="ed-actions">
                <input type="hidden" name="id_equipment" value="<?php echo $equipment['id_equipment']; ?>">
                <input type="hidden" name="city_id" value="<?php echo $equipment['city_id']; ?>">
                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">

                <div class="ed-qty-selector">
                    <label>Requested Quantity:</label>
                    <div class="qty-controls">
                        <button type="button" onclick="changeQty(-1)" class="qty-btn" <?php echo !$is_available ? 'disabled' : ''; ?>>
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" name="quantity" id="quantityInput" 
                               value="1" min="1" max="<?php echo $available_stock; ?>" 
                               readonly <?php echo !$is_available ? 'disabled' : ''; ?>>
                        <button type="button" onclick="changeQty(1)" class="qty-btn" <?php echo !$is_available ? 'disabled' : ''; ?>>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <?php if ($start_date && $end_date): ?>
                    <div class="ed-period-info">
                        <i class="fas fa-calendar-check"></i>
                        <span>Period: <strong><?php echo date('d M', strtotime($start_date)); ?></strong> — <strong><?php echo date('d M Y', strtotime($end_date)); ?></strong></span>
                    </div>
                    <?php if (!$city_match): ?>
                        <div class="ed-period-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Warning: This equipment is in <strong><?php echo htmlspecialchars($equipment['city_name']); ?></strong> but you searched in <strong><?php echo htmlspecialchars($session_city_name ?? 'another city'); ?></strong>.</span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="ed-period-warning">
                        <i class="fas fa-calendar-times"></i>
                        <span>Dates not set. Please select dates in <a href="index.php?url=equipments_list">Equipment List</a>.</span>
                    </div>
                <?php endif; ?>

                <div class="ed-buttons">
                    <button type="submit" name="action" value="add_to_cart" class="btn-cart" 
                            <?php echo (!$is_available || !$start_date || !$city_match) ? 'disabled' : ''; ?>>
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <button type="submit" name="action" value="reserve_now" class="btn-reserve" 
                            formaction="index.php?url=reserve_now"
                            <?php echo (!$is_available || !$start_date || !$city_match) ? 'disabled' : ''; ?>>
                        <i class="fas fa-bolt"></i> Reserve Now
                    </button>
                </div>
            </form>

            <div class="ed-features">
                <div class="feature"><i class="fas fa-shield-halved"></i> <span>Quality<br>Assured</span></div>
                <div class="feature"><i class="fas fa-truck-fast"></i> <span>Express<br>Delivery</span></div>
                <div class="feature"><i class="fas fa-clock-rotate-left"></i> <span>24/7<br>Support</span></div>
                <div class="feature"><i class="fas fa-file-contract"></i> <span>Secure<br>Rental</span></div>
            </div>
        </div>
    </div>
</div>

<style>
/* Local helper styles for alerts/gallery not in external CSS */
.alert-warning-card { background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 20px; margin-bottom: 30px; }
.warning-header { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
.warning-header i { font-size: 24px; color: #f59e0b; }
.warning-header p { margin: 0; font-weight: 600; color: #92400e; }
.warning-actions { display: flex; gap: 10px; justify-content: flex-end; }
.btn-warning-action { padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; border: none; }
.btn-warning-action.clear { background: #ef4444; color: #fff; }
.btn-warning-action.cancel { background: #f3f4f6; color: #374151; }
</style>

<script>
function changeQty(delta) {
    const input = document.getElementById('quantityInput');
    if (!input || input.disabled) return;
    const max = parseInt(input.getAttribute('max')) || 1;
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
function updateMainImage(src, thumbEl) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    thumbEl.classList.add('active');
}
</script>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
