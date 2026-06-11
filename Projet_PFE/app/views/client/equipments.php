<?php
$pageTitle  = 'Equipments - MEGALOC';
$activePage = 'equipments';
$showCart   = true;
$pageCSS    = ['equipments-page'];

include __DIR__ . '/../layout/client_header.php';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- HERO BANNER -->
<div class="ep-hero">
    <h1>Find the Right Equipment</h1>
    <div class="ep-breadcrumb">
        <a href="index.php?url=home">Home</a>
        <span>/</span> Equipment
    </div>
</div>

<!-- FILTER BAR -->
<form method="GET" action="index.php" class="ep-filter-bar">
    <input type="hidden" name="url" value="equipments_list">

    <div class="ep-filter-group">
        <label><i class="fas fa-map-marker-alt"></i> Location</label>
        <select name="city">
            <option value="">All Locations</option>
            <?php foreach ($cities as $c): ?>
                <option value="<?= $c['id_city'] ?>" <?= ($city_id == $c['id_city']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="ep-filter-divider"></div>

    <div class="ep-filter-dates">
        <div class="ep-filter-group">
            <label><i class="fas fa-calendar-alt"></i> Dates</label>
            <input type="text" name="start_date" id="fpStart"
                   value="<?= htmlspecialchars($start_date) ?>"
                   placeholder="Start date" readonly>
        </div>
        <span class="ep-date-sep">—</span>
        <div class="ep-filter-group">
            <label>&nbsp;</label>
            <input type="text" name="end_date" id="fpEnd"
                   value="<?= htmlspecialchars($end_date) ?>"
                   placeholder="End date" readonly>
        </div>
    </div>

    <button type="submit" class="ep-btn-search">
        <i class="fas fa-search"></i> Search
    </button>

    <?php if (!empty($date_error)): ?>
        <div class="ep-date-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($date_error) ?></div>
    <?php endif; ?>
</form>

<!-- LAYOUT: SIDEBAR + GRID -->
<div class="ep-layout">

    <!-- SIDEBAR -->
    <aside class="ep-sidebar">

        <!-- Categories -->
        <div class="ep-sidebar-box">
            <h3 class="ep-sidebar-title">Categories</h3>
            <a href="index.php?url=equipments_list&<?= http_build_query(array_filter(['city' => $city_id, 'start_date' => $start_date, 'end_date' => $end_date, 'price_min' => $price_min, 'price_max' => $price_max])) ?>"
               class="ep-cat-item <?= empty($category_id) ? 'active' : '' ?>">
                <span>All Categories</span>
                <span class="ep-cat-count"><?= $total ?></span>
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?url=equipments_list&<?= http_build_query(array_filter(['city' => $city_id, 'category' => $cat['id_category'], 'start_date' => $start_date, 'end_date' => $end_date, 'price_min' => $price_min, 'price_max' => $price_max])) ?>"
                   class="ep-cat-item <?= ($category_id == $cat['id_category']) ? 'active' : '' ?>">
                    <span><?= htmlspecialchars($cat['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Price Range -->
        <div class="ep-sidebar-box">
            <h3 class="ep-sidebar-title">Price / Day (DH)</h3>
            <form method="GET" action="index.php" id="priceForm">
                <input type="hidden" name="url"        value="equipments_list">
                <input type="hidden" name="city"       value="<?= (int)$city_id ?>">
                <input type="hidden" name="category"   value="<?= (int)$category_id ?>">
                <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                <input type="hidden" name="end_date"   value="<?= htmlspecialchars($end_date) ?>">
                <input type="hidden" name="price_min"  id="priceMinInput" value="<?= $price_min ?? 500 ?>">
                <input type="hidden" name="price_max"  id="priceMaxInput" value="<?= $price_max ?? 10000 ?>">

                <input type="range" class="ep-range" id="priceRange"
                       min="500" max="10000" step="100"
                       value="<?= $price_max ?? 10000 ?>"
                       oninput="updatePrice(this.value)">
                <div class="ep-price-labels">
                    <span>500 DH</span>
                    <span id="priceMaxLabel"><?= number_format($price_max ?? 10000, 0, '', ',') ?> DH</span>
                </div>
                <button type="submit" class="ep-btn-search" style="width:100%;margin-top:14px;justify-content:center;">
                    Apply
                </button>
            </form>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="ep-main">

        <div class="ep-topbar">
            <p class="ep-count">
                Showing <strong><?= (($page - 1) * 12) + 1 ?>–<?= min($page * 12, $total) ?></strong>
                of <strong><?= $total ?></strong> equipment
            </p>
        </div>

        <div class="ep-grid">
            <?php if (empty($equipments)): ?>
                <div class="ep-empty">
                    <i class="fas fa-box-open"></i>
                    <p>No equipment found</p>
                    <small>Try adjusting your filters.</small>
                </div>
            <?php else: ?>
                <?php foreach ($equipments as $eq): ?>
                    <div class="ep-card">
                        <div class="ep-card-img">
                            <?php if (!empty($eq['image'])): ?>
                                <img src="uploads/equipments/<?= htmlspecialchars($eq['image']) ?>"
                                     alt="<?= htmlspecialchars($eq['name']) ?>">
                            <?php else: ?>
                                <div class="ep-placeholder"><i class="fas fa-tractor"></i></div>
                            <?php endif; ?>
                            <span class="ep-card-cat"><?= htmlspecialchars($eq['category_name']) ?></span>
                        </div>
                        <div class="ep-card-body">
                            <h3 class="ep-card-name"><?= htmlspecialchars($eq['name']) ?></h3>
                            <p class="ep-card-city">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($eq['city_name']) ?>
                            </p>
                            <div class="ep-card-price">
                                <?= number_format($eq['price_per_day'], 0, '.', ',') ?> DH
                                <small>/ Day</small>
                            </div>
                        </div>
                        <a href="index.php?url=equipment_detail&id=<?= $eq['id_equipment'] ?>" class="ep-btn-details">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
            <?php
            $baseParams = array_filter([
                'url'        => 'equipments_list',
                'city'       => $city_id,
                'category'   => $category_id,
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'price_min'  => $price_min,
                'price_max'  => $price_max,
            ]);
            ?>
            <nav class="ep-pagination">
                <a href="?<?= http_build_query(array_merge($baseParams, ['page' => max(1, $page - 1)]) ) ?>"
                   class="ep-page-btn <?= $page <= 1 ? 'disabled' : '' ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>

                <?php
                $start_p = max(1, $page - 2);
                $end_p   = min($totalPages, $page + 2);
                if ($start_p > 1): ?>
                    <a href="?<?= http_build_query(array_merge($baseParams, ['page' => 1])) ?>" class="ep-page-btn">1</a>
                    <?php if ($start_p > 2): ?><span class="ep-page-btn" style="border:none;color:#aaa;">…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start_p; $i <= $end_p; $i++): ?>
                    <a href="?<?= http_build_query(array_merge($baseParams, ['page' => $i])) ?>"
                       class="ep-page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($end_p < $totalPages): ?>
                    <?php if ($end_p < $totalPages - 1): ?><span class="ep-page-btn" style="border:none;color:#aaa;">…</span><?php endif; ?>
                    <a href="?<?= http_build_query(array_merge($baseParams, ['page' => $totalPages])) ?>" class="ep-page-btn"><?= $totalPages ?></a>
                <?php endif; ?>

                <a href="?<?= http_build_query(array_merge($baseParams, ['page' => min($totalPages, $page + 1)])) ?>"
                   class="ep-page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </nav>
        <?php endif; ?>

    </main>
</div>

<!-- ===== CART DRAWER ===== -->
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
            <small style="color:#bbb;font-size:13px;">Add equipment to get started</small>
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
// ---- Flatpickr ----
const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
const fpStart = flatpickr("#fpStart", {
    minDate: tomorrow, dateFormat: "Y-m-d",
    onChange: function(sel, dateStr) {
        const next = new Date(dateStr); next.setDate(next.getDate() + 1);
        fpEnd.set('minDate', next);
        if (fpEnd.selectedDates[0] && fpEnd.selectedDates[0] <= sel[0]) fpEnd.clear();
    }
});
const fpEnd = flatpickr("#fpEnd", { minDate: tomorrow, dateFormat: "Y-m-d" });

// ---- Price range ----
function updatePrice(val) {
    document.getElementById('priceMaxLabel').textContent = parseInt(val).toLocaleString() + ' DH';
    document.getElementById('priceMaxInput').value = val;
}

// ---- Cart (localStorage) ----
function getCart() { return JSON.parse(localStorage.getItem('megaloc_cart') || '[]'); }
function saveCart(c) { localStorage.setItem('megaloc_cart', JSON.stringify(c)); }

function addToCart(id, name, price, city, image, startDate, endDate) {
    if (!startDate || !endDate) {
        alert('Please select start and end dates before adding to cart.');
        return;
    }
    const cart = getCart();
    const exists = cart.find(i => i.id === id);
    if (exists) { openCartDrawer(); return; }

    const days = Math.ceil((new Date(endDate) - new Date(startDate)) / 86400000);
    cart.push({ id, name, price, city, image, startDate, endDate, days, total: price * days });
    saveCart(cart);
    updateCartBadge();
    renderCart();
    openCartDrawer();
}

function removeFromCart(id) {
    saveCart(getCart().filter(i => i.id !== id));
    updateCartBadge();
    renderCart();
}

function updateCartBadge() {
    const badge = document.getElementById('cartBadge');
    if (badge) badge.textContent = getCart().length;
}

function renderCart() {
    const cart = getCart();
    const list  = document.getElementById('cartItemsList');
    const empty = document.getElementById('cartEmptyState');
    const footer = document.getElementById('cartDrawerFooter');

    if (!cart.length) {
        list.innerHTML = '';
        empty.style.display = 'flex';
        footer.style.display = 'none';
        return;
    }
    empty.style.display = 'none';
    footer.style.display = 'block';

    let totalDH = 0;
    list.innerHTML = cart.map(item => {
        totalDH += item.total;
        const img = item.image
            ? `<img src="uploads/equipments/${item.image}" class="cart-item-img" alt="">`
            : `<div class="cart-item-img placeholder"><i class="fas fa-tractor"></i></div>`;
        return `
        <div class="cart-item">
            ${img}
            <div class="cart-item-info">
                <p class="cart-item-name">${item.name}</p>
                <p class="cart-item-city"><i class="fas fa-map-marker-alt"></i> ${item.city}</p>
                <p class="cart-item-dates">${item.startDate} → ${item.endDate} (${item.days} day${item.days > 1 ? 's' : ''})</p>
                <p class="cart-item-price">${item.total.toLocaleString()} DH</p>
            </div>
            <button class="cart-item-remove" onclick="removeFromCart(${item.id})">
                <i class="fas fa-trash"></i>
            </button>
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

// Init
updateCartBadge();
</script>

<?php include __DIR__ . '/../layout/client_footer.php'; ?>
