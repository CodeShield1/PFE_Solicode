<?php
/* BRANDS SECTION :: auto-scrolling logo marquee.
   Drop logo files into  public/uploads/brands/{slug}.png  (or .jpg/.svg/.webp).
   The slug column below is what we look for on disk. If the file is missing
   we render a clean monogram placeholder so the layout never breaks. */
$brands = [
    ['name' => 'Caterpillar', 'slug' => 'caterpillar'],
    ['name' => 'JCB',         'slug' => 'jcb'],
    ['name' => 'Komatsu',     'slug' => 'komatsu'],
    ['name' => 'Bobcat',      'slug' => 'bobcat'],
    ['name' => 'Volvo CE',    'slug' => 'volvo'],
    ['name' => 'Hitachi',     'slug' => 'hitachi'],
    ['name' => 'Liebherr',    'slug' => 'liebherr'],
    ['name' => 'John Deere',  'slug' => 'john-deere'],
    ['name' => 'Doosan',      'slug' => 'doosan'],
    ['name' => 'Hyundai',     'slug' => 'hyundai'],
];

$brandsDir   = __DIR__ . '/../../../public/uploads/brands/';
$brandsUrl   = 'uploads/brands/';
$extensions  = ['png', 'webp', 'svg', 'jpg', 'jpeg'];

/** Returns the first existing logo path or null. */
$resolveLogo = function (string $slug) use ($brandsDir, $brandsUrl, $extensions): ?string {
    foreach ($extensions as $ext) {
        if (is_file($brandsDir . $slug . '.' . $ext)) {
            return $brandsUrl . $slug . '.' . $ext;
        }
    }
    return null;
};

/** First letters of a brand name, used as monogram fallback. */
$initials = function (string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    $out = '';
    foreach ($parts as $p) { if ($p !== '') { $out .= strtoupper($p[0]); } }
    return substr($out, 0, 2);
};
?>
<section class="brands-section">
    <div class="container">

        <div class="section-head centered tight">
            <span class="section-eyebrow">Trusted Partners</span>
            <h2 class="section-title">Top <span>Brands</span> We Carry</h2>
            <p class="section-sub">Only certified machinery from leading global manufacturers.</p>
        </div>

        <div class="brands-marquee">
            <div class="brands-track">
                <?php foreach (array_merge($brands, $brands) as $b):
                    $logo = $resolveLogo($b['slug']); ?>
                    <div class="brand-logo" title="<?php echo htmlspecialchars($b['name']); ?>">
                        <?php if ($logo): ?>
                            <img src="<?php echo htmlspecialchars($logo); ?>"
                                 alt="<?php echo htmlspecialchars($b['name']); ?>" loading="lazy">
                        <?php else: ?>
                            <span class="brand-monogram"><?php echo $initials($b['name']); ?></span>
                            <span class="brand-name"><?php echo htmlspecialchars($b['name']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <p class="brands-note">
            <i class="fas fa-shield-halved"></i>
            All equipment is certified, insured and regularly inspected.
        </p>
    </div>
</section>
