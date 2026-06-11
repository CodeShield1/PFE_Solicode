<?php
$pageTitle  = htmlspecialchars($equipment['name']) . ' - MEGALOC';
$activePage = 'equipments';
$showCart   = true;
$pageCSS    = ['equipments-page'];

include __DIR__ . '/../layout/client_header.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Hero -->
<div class="ep-hero">
    <h1><?= htmlspecialchars($equipment['name']) ?></h1>
    <div class="ep-breadcrumb">
        <a href="index.php?url=home">Home</a><span>/</span>
        <a href="index.php?url=equipments_list">Equipments</a><span>/</span>
        <?= htmlspecialchars($equipment['name']) ?>
    </div>
</div>

<!-- Detail Layout -->
<div style="max-width:1100px;margin:40px auto;padding:0 24px;display:flex;gap:36px;flex-wrap:wrap;align-items:flex-start;">

    <!-- Image -->
    <div style="flex:1;min-width:300px;border-radius:16px;overflow:hidden;background:#f0f0f0;max-height:420px;">
        <?php if (!empty($equipment['image'])): ?>
            <img src="uploads/equipments/<?= htmlspecialchars($equipment['image']) ?>"
                 alt="<?= htmlspecialchars($equipment['name']) ?>"
                 style="width:100%;height:420px;object-fit:cover;">
        <?php else: ?>
            <div style="height:420px;display:flex;align-items:center;justify-content:center;color:#ccc;font-size:72px;">
                <i class="fas fa-tractor"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info + Add to Cart -->
    <div style="flex:1;min-width:280px;">
        <span style="background:var(--primary);color:#fff;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;">
            <?= htmlspecialchars($equipment['category_name']) ?>
        </span>

        <h1 style="font-size:26px;font-weight:800;color:var(--secondary);margin:14px 0 8px;">
            <?= htmlspecialchars($equipment['name']) ?>
        </h1>

        <p style="display:flex;align-items:center;gap:6px;color:#888;font-size:14px;margin-bottom:14px;">
            <i class="fas fa-map-marker-alt" style="color:var(--primary);"></i>
            <?= htmlspecialchars($equipment['city_name']) ?>
        </p>

        <?php if (!empty($equipment['description'])): ?>
            <p style="color:#666;font-size:14px;line-height:1.7;margin-bottom:20px;">
                <?= nl2br(htmlspecialchars($equipment['description'])) ?>
            </p>
        <?php endif; ?>

        <div style="font-size:28px;font-weight:900;color:var(--primary);margin-bottom:6px;">
            <?= number_format($equipment['price_per_day'], 0, '.', ',') ?> DH
            <span style="font-size:14px;font-weight:400;color:#aaa;">/ Day</span>
        </div>

        <p style="font-size:13px;margin-bottom:24px;color:<?= $equipment['quantity_stock'] > 0 ? '#22c55e' : '#ef4444' ?>;font-weight:600;">
            <i class="fas fa-<?= $equipment['quantity_stock'] > 0 ? 'check-circle' : 'times-circle' ?>"></i>
            <?= $equipment['quantity_stock'] > 0 ? 'Available (' . $equipment['quantity_stock'] . ' units)' : 'Out of stock' ?>
        </p>

        <?php if ($equipment['quantity_stock'] > 0): ?>
        <!-- Date picker + Add to Cart -->
        <div style="background:#f8f9fa;border-radius:12px;padding:20px;margin-bottom:20px;">
            <p style="font-size:13px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;">
                <i class="fas fa-calendar-alt" style="color:var(--primary);"></i> Select Rental Dates
            </p>
            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
                <div style="flex:1;min-width:120px;">
                    <label style="font-size:11px;font-weight:700;color:#999;text-transform:uppercase;">Start Date</label>
                    <input type="text" id="detailStart" placeholder="Start date" readonly
                           style="width:100%;padding:10px 12px;border:1.5px solid #e0e0e0;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;margin-top:4px;">
                </div>
                <div style="flex:1;min-width:120px;">
                    <label style="font-size:11px;font-weight:700;color:#999;text-transform:uppercase;">End Date</label>
                    <input type="text" id="detailEnd" placeholder="End date" readonly
                           style="width:100%;padding:10px 12px;border:1.5px solid #e0e0e0;border-radius:8px;font-size:14px;background:#fff;cursor:pointer;margin-top:4px;">
                </div>
            </div>
            <div id="detailDaysInfo" style="font-size:13px;color:#888;margin-bottom:14px;"></div>
            <button onclick="detailAddToCart()" class="cart-checkout-btn" style="font-size:15px;padding:13px;">
                <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
            <div id="detailError" style="color:#e53e3e;font-size:12px;font-weight:600;margin-top:8px;display:none;"></div>
        </div>
        <?php endif; ?>

        <a href="index.php?url=equipments_list" style="font-size:13px;color:#888;text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Back to Equipments
        </a>
    </div>
</div>

<!-- Cart Drawer (same as equipments page) -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCartDrawer()"></div>
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-drawer-header">
        <h3><i class="fas fa-shopping-cart" style="margin-right:8px;"></i> My Cart</h3>
        <button class="cart-drawer-close" onclick="closeCartDrawer()"><i class="fas fa-times"></i></button>
    </div>
    <div class="cart-drawer-body" id="cartDrawerBody">
        <div class="cart-empty-state" id="cartEmptyState">
            <i class="fas fa-shopping-cart"></i>
            <p>Your cart is empty</p>
        </div>
        <div id="cartItemsList"></div>
    </div>
    <div class="cart-drawer-footer" id="cartDrawerFooter" style="display:none;">
        <div class="cart-total">
            <span>Total</span>
            <span id="cartTotal">0 DH</span>
        </div>
        <a href="index.php?url=checkout" class="cart-checkout-btn">
            <i class="fas fa-check-circle"></i> Proceed to Checkout
        </a>
    </div>
</div>

<script>
const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);

const fpS = flatpickr("#detailStart", {
    minDate: tomorrow, dateFormat: "Y-m-d",
    onChange: function(sel, dateStr) {
        const next = new Date(dateStr); next.setDate(next.getDate() + 1);
        fpE.set('minDate', next);
        if (fpE.selectedDates[0] && fpE.selectedDates[0] <= sel[0]) fpE.clear();
        updateDaysInfo();
    }
});
const fpE = flatpickr("#detailEnd", {
    minDate: tomorrow, dateFormat: "Y-m-d",
    onChange: updateDaysInfo
});

function updateDaysInfo() {
    const s = document.getElementById('detailStart').value;
    const e = document.getElementById('detailEnd').value;
    const info = document.getElementById('detailDaysInfo');
    if (s && e) {
        const days = Math.ceil((new Date(e) - new Date(s)) / 86400000);
        const total = days * <?= $equipment['price_per_day'] ?>;
        info.innerHTML = `<strong>${days} day${days>1?'s':''}</strong> × <?= number_format($equipment['price_per_day'],0,'.',',') ?> DH = <strong style="color:var(--primary)">${total.toLocaleString()} DH</strong>`;
    } else { info.innerHTML = ''; }
}

function detailAddToCart() {
    const s = document.getElementById('detailStart').value;
    const e = document.getElementById('detailEnd').value;
    const err = document.getElementById('detailError');
    if (!s || !e) {
        err.textContent = 'Please select start and end dates.';
        err.style.display = 'block'; return;
    }
    err.style.display = 'none';
    addToCart(
        <?= $equipment['id_equipment'] ?>,
        '<?= addslashes(htmlspecialchars($equipment['name'])) ?>',
        <?= $equipment['price_per_day'] ?>,
        '<?= addslashes(htmlspecialchars($equipment['city_name'])) ?>',
        '<?= htmlspecialchars($equipment['image'] ?? '') ?>',
        s, e
    );
}

// ---- Cart logic (shared) ----
function getCart() { return JSON.parse(localStorage.getItem('megaloc_cart') || '[]'); }
function saveCart(c) { localStorage.setItem('megaloc_cart', JSON.stringify(c)); }

function addToCart(id, name, price, city, image, startDate, endDate) {
    const cart = getCart();
    const exists = cart.find(i => i.id === id);
    if (exists) { openCartDrawer(); return; }
    const days = Math.ceil((new Date(endDate) - new Date(startDate)) / 86400000);
    cart.push({ id, name, price, city, image, startDate, endDate, days, total: price * days });
    saveCart(cart); updateCartBadge(); renderCart(); openCartDrawer();
}

function removeFromCart(id) {
    saveCart(getCart().filter(i => i.id !== id));
    updateCartBadge(); renderCart();
}

function updateCartBadge() {
    const b = document.getElementById('cartBadge');
    if (b) b.textContent = getCart().length;
}

function renderCart() {
    const cart = getCart();
    const list = document.getElementById('cartItemsList');
    const empty = document.getElementById('cartEmptyState');
    const footer = document.getElementById('cartDrawerFooter');
    if (!cart.length) {
        list.innerHTML = ''; empty.style.display = 'flex'; footer.style.display = 'none'; return;
    }
    empty.style.display = 'none'; footer.style.display = 'block';
    let totalDH = 0;
    list.innerHTML = cart.map(item => {
        totalDH += item.total;
        const img = item.image
            ? `<img src="uploads/equipments/${item.image}" class="cart-item-img" alt="">`
            : `<div class="cart-item-img placeholder"><i class="fas fa-tractor"></i></div>`;
        return `<div class="cart-item">${img}
            <div class="cart-item-info">
                <p class="cart-item-name">${item.name}</p>
                <p class="cart-item-city"><i class="fas fa-map-marker-alt"></i> ${item.city}</p>
                <p class="cart-item-dates">${item.startDate} → ${item.endDate} (${item.days} day${item.days>1?'s':''})</p>
                <p class="cart-item-price">${item.total.toLocaleString()} DH</p>
            </div>
            <button class="cart-item-remove" onclick="removeFromCart(${item.id})"><i class="fas fa-trash"></i></button>
        </div>`;
    }).join('');
    document.getElementById('cartTotal').textContent = totalDH.toLocaleString() + ' DH';
}

function openCartDrawer() {
    document.getElementById('cartDrawer').classList.add('open');
    document.getElementById('cartOverlay').classList.add('open');
    renderCart();
}
function closeCartDrawer() {
    document.getElementById('cartDrawer').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('open');
}

updateCartBadge();
</script>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
