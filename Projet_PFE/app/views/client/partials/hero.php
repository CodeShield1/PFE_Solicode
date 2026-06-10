<?php /* HERO SECTION :: search city + start date + end date + search btn */ ?>
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
        </div>
    </div>

    <div class="hero-search-wrapper">
        <form action="index.php" method="GET" class="search-form">
            <input type="hidden" name="url" value="equipments_list">

            <div class="search-input-group">
                <label><i class="fas fa-map-marker-alt"></i> Where?</label>
                <div class="custom-select-wrapper" id="locationDropdown">
                    <div class="custom-select-trigger" id="locationTrigger">
                        <span id="selectedCityText">Select Location</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="custom-options-menu" id="locationMenu">
                        <div class="option-item" data-value="">All Cities</div>
                        <?php foreach (($cities ?? []) as $city): ?>
                            <div class="option-item" data-value="<?php echo $city['id_city']; ?>">
                                <i class="fas fa-city"></i>
                                <?php echo htmlspecialchars($city['name']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="city" id="cityInput" value="">
                </div>
            </div>

            <div class="search-input-group">
                <label><i class="fas fa-calendar-day"></i> Start Date</label>
                <input type="text" id="startDatePicker" name="start_date" placeholder="Pick start date" readonly>
            </div>

            <div class="search-input-group">
                <label><i class="fas fa-calendar-check"></i> End Date</label>
                <input type="text" id="endDatePicker" name="end_date" placeholder="Pick end date" readonly>
            </div>

            <div class="search-button-group">
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</section>
