<?php include('../app/Config/Config.php'); ?>
<?php include('../includes/header.php'); ?>

<header class="py-5 bg-dark text-white text-center" style="background: linear-gradient(rgba(128,0,32,0.9), rgba(128,0,32,0.7)), url('assets/img/hero.jpg'); background-size: cover;">
    <div class="container py-5">
        <h1 class="display-4 fw-bold"><?php echo ($lang == 'ar' ? 'رقمنة تراخيص MISA' : 'Digitizing MISA Licensing'); ?></h1>
        <p class="lead"><?php echo ($lang == 'ar' ? 'تتبع مسار عملك في Unaizah بكل سهولة' : 'Seamlessly track your workflow in Unaizah'); ?></p>
    </div>
</header>

<section class="container my-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="rooq-card h-100 shadow-sm">
                <div class="rooq-card-header">
                    <img src="assets/img/icons/Ministry_of_Investment_Logo-Dark.svg" height="50" alt="MISA">
                </div>
                <h5>MISA Tracking</h5>
                <p class="small text-muted">Real-time status updates for Jahangir & Fonon portfolios.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="rooq-card h-100 shadow-sm">
                <div class="rooq-card-header">
                    <img src="assets/img/icons/qiwa.png" height="50" alt="Qiwa">
                </div>
                <h5>Government Milestones</h5>
                <p class="small text-muted">Automation of GOSI, MUQEEM, and QIWA registrations.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="rooq-card h-100 shadow-sm">
                <div class="rooq-card-header">
                    <img src="assets/img/icons/Saudi_building_code_logo.svg" height="50" alt="SBC">
                </div>
                <h5>Compliance (SBC)</h5>
                <p class="small text-muted">Ensuring all Saudi Building Code approvals are met.</p>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>