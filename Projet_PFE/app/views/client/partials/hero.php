<?php /* HERO SECTION */ ?>
<section class="hero-section">
    <div class="hero-overlay"></div>

    <div class="container hero-container">
        <div class="hero-content">
            <span class="hero-badge">
                <i class="fas fa-bolt"></i> #1 Equipment Rental in Morocco
            </span>
            <h1>
                High Quality Equipment <br>
                <span>For Every Project</span>
            </h1>
            <p>
                Rent professional construction machinery and tools, anywhere, anytime.
                Fast booking, fair prices, total reliability.
            </p>
            <a href="index.php?url=equipments_list" class="btn-search" style="display:inline-flex;align-items:center;gap:8px;margin-top:24px;text-decoration:none;">
                <i class="fas fa-th-large"></i> View All Equipments
            </a>
        </div>

        <!-- SEARCH FORM WRAPPER -->
        <div class="hero-search-wrapper">
            <form action="index.php" method="GET" class="search-form">
                <input type="hidden" name="url" value="equipments_list">
                
                <div class="search-input-group">
                    <label><i class="fas fa-map-marker-alt"></i> Location</label>
                    <div class="custom-select-wrapper">
                        <select name="city" class="custom-select-trigger" style="appearance: none; -webkit-appearance: none; width: 100%; border: 1.5px solid #eef2f7; border-radius: 11px; padding: 12px 16px; font-weight: 600; cursor: pointer; background: #f8fafc;">
                            <option value="">Select City</option>
                            <?php foreach ($cities as $c): ?>
                                <option value="<?php echo $c['id_city']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="search-input-group">
                    <label><i class="fas fa-calendar-alt"></i> Start Date</label>
                    <input type="text" name="start_date" id="heroStart" placeholder="Pick a date" readonly>
                </div>

                <div class="search-input-group">
                    <label><i class="fas fa-calendar-check"></i> End Date</label>
                    <input type="text" name="end_date" id="heroEnd" placeholder="Pick a date" readonly>
                </div>

                <div class="search-button-group">
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> Search Machines
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const fpStart = flatpickr("#heroStart", {
        altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", minDate: tomorrow,
        onChange: function(selectedDates, dateStr) { fpEnd.set('minDate', dateStr); }
    });
    const fpEnd = flatpickr("#heroEnd", {
        altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", minDate: tomorrow
    });
});
</script>
