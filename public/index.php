<?php require_once '../app/Config/Config.php'; ?>
<?php include '../includes/header.php'; ?>

<header class="hero-mt text-center">
    <div class="container">
        <h1 class="display-3 fw-bold"><?php echo $t['hero_h1']; ?></h1>
        <p class="lead w-75 mx-auto"><?php echo $t['hero_p']; ?></p>
        <a href="login.php" class="btn btn-rooq-primary mt-4"><?php echo $t['track']; ?></a>
    </div>
</header>

<section class="container my-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="rooq-card shadow-sm h-100">
                <div class="rooq-card-header">
                    <img src="assets/img/icons/Ministry_of_Investment_Logo-Dark.svg" height="60" alt="MISA">
                </div>
                <h4 class="mt-3">MISA Licensing</h4>
                <p class="text-muted small">Tracking for Jahangir & Fonon Contracting Ltd portfolios.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rooq-card shadow-sm h-100">
                <div class="rooq-card-header">
                    <img src="assets/img/icons/qiwa.png" height="60" alt="Qiwa">
                </div>
                <h4 class="mt-3">Workforce Status</h4>
                <p class="text-muted small">Real-time verification of QIWA and GOSI compliance.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rooq-card shadow-sm h-100">
                <div class="rooq-card-header">
                    <img src="assets/img/icons/MUQEEM.png" height="60" alt="Muqeem">
                </div>
                <h4 class="mt-3">Residence (Muqeem)</h4>
                <p class="text-muted small">Managing foreign company hire approvals and documentation.</p>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>