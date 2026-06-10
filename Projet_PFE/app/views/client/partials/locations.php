<?php
/* LOCATIONS SECTION :: city cards from DB only.
   Images come from  public/uploads/cities/{file}  (added via admin).
   When a city has no image yet, we render a clean CSS placeholder. */
$citiesDir = __DIR__ . '/../../../public/uploads/cities/';
?>
<section class="locations-section" id="locations">
    <div class="container">

        <div class="section-head">
            <div>
                <span class="section-eyebrow">Coverage</span>
                <h2 class="section-title">Available <span>Locations</span></h2>
                <p class="section-sub">Pick up equipment in any of our partner cities across Morocco.</p>
            </div>
            <div class="scroll-controls">
                <button class="scroll-btn" data-target="locationsTrack" data-dir="-1" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="scroll-btn" data-target="locationsTrack" data-dir="1" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <?php if (!empty($cities)): ?>
            <div class="h-scroll locations-track" id="locationsTrack">
                <?php foreach ($cities as $city):
                    $cityId   = $city['id_city'] ?? '';
                    $cityName = $city['name']    ?? '';
                    $hasImage = !empty($city['image']) && is_file($citiesDir . $city['image']);
                    $imgPath  = $hasImage ? 'uploads/cities/' . $city['image'] : null;
                ?>
                    <a href="index.php?url=equipments_list&city=<?php echo $cityId; ?>" class="city-card">
                        <div class="city-card-img <?php echo $hasImage ? '' : 'no-image'; ?>">
                            <?php if ($hasImage): ?>
                                <img src="<?php echo htmlspecialchars($imgPath); ?>"
                                     alt="<?php echo htmlspecialchars($cityName); ?>" loading="lazy">
                            <?php else: ?>
                                <i class="fas fa-city city-card-placeholder-icon"></i>
                            <?php endif; ?>
                        </div>
                        <div class="city-card-overlay">
                            <div class="city-card-info">
                                <h3><?php echo htmlspecialchars($cityName); ?></h3>
                                <span><i class="fas fa-map-marker-alt"></i> Morocco</span>
                            </div>
                            <span class="city-card-arrow"><i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="locations-empty">
                <i class="fas fa-map-location-dot"></i>
                <p>No locations available yet. Please check back soon.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
