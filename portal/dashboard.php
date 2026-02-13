<?php 
// Secure the page
require_once 'includes/header.php'; //
?>

<div class="d-flex" style="min-height: 100vh; padding-top: 70px;">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="flex-grow-1 p-4" style="background: radial-gradient(circle at center, #2a000e, #1a1a1a);">
        <div class="container-fluid">
            <h2 class="text-white mb-4">Dashboard Overview</h2>
            
            <div class="rooq-card p-4 mb-4">
                <h4 class="text-gold">Welcome back, <?php echo $username; ?></h4>
                <p class="text-white-50">Track your investment milestones and download official documents.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="rooq-card p-4 text-center">
                        <i class="bi bi-briefcase text-gold fs-1 mb-3"></i>
                        <h5 class="text-white">Active Services</h5>
                        <h2 class="fw-bold text-white">3</h2>
                    </div>
                </div>
                </div>
        </div>
    </main>
</div>

<?php 

require_once 'includes/footer.php'; //
?>