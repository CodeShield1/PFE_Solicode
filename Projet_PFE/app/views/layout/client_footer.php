<?php
/**
 * Reusable Client Footer
 * --------------------------------------------
 * Expected variables (optional):
 *   $pageJS array  Extra JS files (without .js) to load at the end
 */
$pageJS = isset($pageJS) && is_array($pageJS) ? $pageJS : [];
?>

<footer class="main-footer">
    <div class="container footer-container">

        <div class="footer-col footer-brand">
            <a href="index.php?url=home" class="footer-logo">
                <span class="mega">MEGA</span><span class="loc">LOC</span>
            </a>
            <p class="footer-tagline">
                Your trusted partner for construction equipment rental.
                Quality machinery, fair prices, anywhere in Morocco.
            </p>
            <div class="footer-socials">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Company</h4>
            <ul class="footer-links">
                <li><a href="index.php?url=home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="index.php?url=equipments_list">Equipments</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Support</h4>
            <ul class="footer-links">
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Terms of Service</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-title">Contact</h4>
            <ul class="footer-contact">
                <li><i class="fas fa-map-marker-alt"></i> Casablanca, Morocco</li>
                <li><i class="fas fa-phone-alt"></i> +212 5XX XX XX XX</li>
                <li><i class="fas fa-envelope"></i> contact@megaloc.ma</li>
                <li><i class="fas fa-clock"></i> Mon - Sat : 8:00 - 18:00</li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?php echo date('Y'); ?> <strong>MEGALOC</strong>. All rights reserved.</p>
            <p class="made-with">Built with <i class="fas fa-heart"></i> in Morocco</p>
        </div>
    </div>
</footer>

<script src="js/client_header.js"></script>
<?php foreach ($pageJS as $js): ?>
    <script src="js/<?php echo htmlspecialchars($js); ?>.js"></script>
<?php endforeach; ?>

</body>
</html>
