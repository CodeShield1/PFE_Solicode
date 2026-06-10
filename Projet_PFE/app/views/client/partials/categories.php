<?php /* CATEGORIES SECTION :: horizontal scroll with arrows */ ?>
<section class="categories-section" id="categories">
    <div class="container">

        <div class="section-head">
            <div>
                <span class="section-eyebrow">Browse</span>
                <h2 class="section-title">Equipment <span>Categories</span></h2>
                <p class="section-sub">Find the right machine for the right job.</p>
            </div>
            <div class="scroll-controls">
                <button class="scroll-btn" data-target="categoriesTrack" data-dir="-1" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="scroll-btn" data-target="categoriesTrack" data-dir="1" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="h-scroll" id="categoriesTrack">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <a href="index.php?url=equipments_list&category=<?php echo $cat['id_category']; ?>" class="cat-card">
                        <div class="cat-card-img">
                            <?php if (!empty($cat['image'])): ?>
                                <img src="uploads/categories/<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                            <?php else: ?>
                                <i class="fas fa-layer-group"></i>
                            <?php endif; ?>
                        </div>
                        <div class="cat-card-body">
                            <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                            <span class="cat-card-link">Explore <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <?php
                $placeholders = ['Excavators','Bulldozers','Cranes','Loaders','Concrete Mixers','Generators','Scaffolding','Compressors'];
                foreach ($placeholders as $i => $name): ?>
                    <a href="#" class="cat-card">
                        <div class="cat-card-img placeholder">
                            <i class="fas fa-<?php echo ['truck-monster','tractor','helicopter','dolly','blender','plug','layer-group','wind'][$i] ?? 'layer-group'; ?>"></i>
                        </div>
                        <div class="cat-card-body">
                            <h3><?php echo $name; ?></h3>
                            <span class="cat-card-link">Explore <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
