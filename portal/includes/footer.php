
<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script> 
<script src="<?php echo BASE_URL; ?>assets/js/all.min.js"></script> 
<script>
document.addEventListener("DOMContentLoaded", function() {
    var toggleBtn = document.getElementById("sidebarToggle");
    var sidebar = document.getElementById("portalSidebar");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Stop page from jumping
            sidebar.classList.toggle("show"); // Add/Remove 'show' CSS class
        });
        
        // Optional: Close sidebar if clicking outside on mobile
        document.addEventListener("click", function(e) {
            if (window.innerWidth < 992 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove("show");
            }
        });
    } else {
        console.error("Sidebar or Toggle Button ID not found!");
    }
});
</script>

</body>

</html>