
<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script> 
<script src="<?php echo BASE_URL; ?>assets/js/all.min.js"></script> 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('portalSidebar');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('show');
            });
        }

        // Optional: Close sidebar when clicking outside of it on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992) { // Only on mobile
                const isClickInside = sidebar.contains(event.target) || toggleBtn.contains(event.target);
                if (!isClickInside && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    });
</script>


</body>

</html>