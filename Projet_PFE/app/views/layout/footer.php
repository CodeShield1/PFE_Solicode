        </div> <!-- End of content-wrapper -->
    </main>

    <!-- Global Scripts -->
    <script src="js/dashboard.js"></script>
    
    <!-- Page Specific Scripts -->
    <?php if (isset($pageJS)): ?>
        <script src="js/<?php echo $pageJS; ?>.js"></script>
    <?php endif; ?>
</body>
</html>