<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEGALOC - Equipment Rental</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

    <!-- HEADER -->
    <header class="main-header">
        <div class="container header-container">
            <!-- Left: Logo -->
            <div class="header-left">
                <a href="index.php?url=home" class="logo">
                    <span class="mega">MEGA</span><span class="loc">LOC</span>
                </a>
                
                <!-- Category List Menu -->
                <div class="nav-item category-dropdown">
                    <div class="dropdown-trigger" id="categoryTrigger">
                        <span>All Categories</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <!-- Mega Menu Content -->
                    <div class="mega-menu" id="categoryMenu">
                        <div class="mega-menu-grid">
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <a href="index.php?url=equipments_list&category=<?php echo $category['id_category']; ?>" class="mega-menu-item">
                                        <div class="item-icon">
                                            <img src="uploads/categories/<?php echo htmlspecialchars($category['image']); ?>" alt="">
                                        </div>
                                        <span class="item-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-cats">No categories found</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Contact & Profile -->
            <div class="header-right">
                <div class="company-phone">
                    <i class="fas fa-phone-alt"></i>
                    <span>+212 5XX XX XX XX</span>
                </div>

                <div class="profile-nav">
                    <div class="profile-trigger" id="profileTrigger">
                        <i class="fas fa-user-circle"></i>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <div class="profile-dropdown-menu" id="profileMenu">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="user-info">
                                <span class="welcome">Welcome,</span>
                                <span class="username"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </div>
                            <hr>
                            <a href="index.php?url=my_reservations" class="dropdown-link">
                                <i class="fas fa-calendar-alt"></i> My Reservations
                            </a>
                            <a href="index.php?url=logout" class="dropdown-link logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="index.php?url=login" class="dropdown-link">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="index.php?url=register" class="dropdown-link">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container hero-container">
            <!-- Hero Content (Ketab f xeml) -->
            <div class="hero-content">
                <h1>High Quality Equipment <br> <span>For Every Project</span></h1>
                <p>We provide the best construction machinery and tools for your success. Rent easily, work safely.</p>
            </div>

            <!-- Search Bar Wrapper (Dakhela f Hero) -->
            <div class="hero-search-wrapper">
                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="url" value="equipments_list">
                    
                    <div class="search-input-group custom-select-group">
                        <label><i class="fas fa-map-marker-alt"></i> Where?</label>
                        <div class="custom-select-wrapper" id="locationDropdown">
                            <div class="custom-select-trigger" id="locationTrigger">
                                <span id="selectedCityText">Select Location</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="custom-options-menu" id="locationMenu">
                                <div class="option-item" data-value="">Select Location</div>
                                <?php foreach ($cities as $city): ?>
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
                        <label><i class="fas fa-calendar-alt"></i> When?</label>
                        <input type="text" id="rangeDatePicker" name="dates" placeholder="Select Rental Period" readonly>
                    </div>

                    <div class="search-button-group">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Flatpickr (Modern Date Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Scripts for Dropdowns & Picker -->
    <script>
        // Initialization of Date Range Picker
        flatpickr("#rangeDatePicker", {
            mode: "range",
            minDate: "today",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
        });
        // Category Dropdown
        const catTrigger = document.getElementById('categoryTrigger');
        const catMenu = document.getElementById('categoryMenu');
        
        catTrigger.addEventListener('click', (e) => {
            catMenu.classList.toggle('show');
            e.stopPropagation();
        });

        // Profile Dropdown
        const profTrigger = document.getElementById('profileTrigger');
        const profMenu = document.getElementById('profileMenu');
        
        profTrigger.addEventListener('click', (e) => {
            profMenu.classList.toggle('show');
            e.stopPropagation();
        });

        // Close on outside click
        window.addEventListener('click', () => {
            catMenu.classList.remove('show');
            profMenu.classList.remove('show');
            locationMenu.classList.remove('show');
        });

        // Custom Location Dropdown logic
        const locationTrigger = document.getElementById('locationTrigger');
        const locationMenu = document.getElementById('locationMenu');
        const cityInput = document.getElementById('cityInput');
        const selectedCityText = document.getElementById('selectedCityText');
        const optionItems = document.querySelectorAll('.option-item');

        locationTrigger.addEventListener('click', (e) => {
            locationMenu.classList.toggle('show');
            e.stopPropagation();
        });

        optionItems.forEach(item => {
            item.addEventListener('click', () => {
                const val = item.getAttribute('data-value');
                const text = item.textContent.trim();
                
                cityInput.value = val;
                selectedCityText.textContent = text;
                
                // Active class
                optionItems.forEach(i => i.classList.remove('selected'));
                item.classList.add('selected');
                
                locationMenu.classList.remove('show');
            });
        });
    </script>
</body>
</html>
