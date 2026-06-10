<?php
/* HOW IT WORKS SECTION :: 5 steps timeline */
$steps = [
    ['icon' => 'fa-magnifying-glass',  'title' => 'Search Equipment', 'desc' => 'Browse our catalogue and pick the machine you need.'],
    ['icon' => 'fa-calendar-days',     'title' => 'Choose Dates',     'desc' => 'Select your rental period — start and end dates.'],
    ['icon' => 'fa-file-signature',    'title' => 'Submit Reservation','desc' => 'Send your booking request in one click.'],
    ['icon' => 'fa-user-check',        'title' => 'Admin Approval',   'desc' => 'Our team reviews and confirms your reservation.'],
    ['icon' => 'fa-helmet-safety',     'title' => 'Start Work',       'desc' => 'Pick up the equipment and get the job done.'],
];
?>
<section class="how-section" id="how-it-works">
    <div class="container">

        <div class="section-head centered">
            <span class="section-eyebrow">Simple Process</span>
            <h2 class="section-title">How It <span>Works</span></h2>
            <p class="section-sub">From browsing to building — five easy steps.</p>
        </div>

        <div class="how-steps">
            <?php foreach ($steps as $i => $step): ?>
                <div class="how-step" style="--delay: <?php echo $i * 0.1; ?>s">
                    <div class="how-step-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                    <div class="how-step-icon">
                        <i class="fas <?php echo $step['icon']; ?>"></i>
                    </div>
                    <h3><?php echo $step['title']; ?></h3>
                    <p><?php echo $step['desc']; ?></p>
                    <?php if ($i < count($steps) - 1): ?>
                        <span class="how-step-connector"><i class="fas fa-arrow-right"></i></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
