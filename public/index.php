<?php include '../includes/header.php'; ?>

<div class="hero-section">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3"><?php echo $text['hero_title']; ?></h1>
        <p class="lead mb-4"><?php echo $text['hero_desc']; ?></p>
    </div>
</div>

<section class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark"><?php echo $text['services_title']; ?></h2>
        <p class="text-muted"><?php echo $text['services_sub']; ?></p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="<?php echo BASE_URL; ?>/assets/img/icons/Ministry_of_Investment_Logo-Dark.svg" width="50">
                </div>
                <h5 class="card-title">MISA Licensing</h5>
                <p class="text-muted small">New Service License Processing and renewals.</p>
                <a href="<?php echo BASE_URL; ?>/services" class="btn-link"><?php echo $text['view']; ?> &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="<?php echo BASE_URL; ?>/assets/img/icons/GOSI-General-Organization-for-Social-Insurance.jpg" width="50">
                </div>
                <h5 class="card-title">GOSI & QIWA</h5>
                <p class="text-muted small">Social insurance and workforce quota management.</p>
                <a href="<?php echo BASE_URL; ?>/services" class="btn-link"><?php echo $text['view']; ?> &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="<?php echo BASE_URL; ?>/assets/img/icons/MUQEEM.png" width="50">
                </div>
                <h5 class="card-title">Muqeem & Visas</h5>
                <p class="text-muted small">Resident permits (Iqama) and visas.</p>
                <a href="<?php echo BASE_URL; ?>/services" class="btn-link"><?php echo $text['view']; ?> &rarr;</a>
            </div>
        </div>
        
         <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="<?php echo BASE_URL; ?>/assets/img/icons/Saudi_building_code_logo.svg" width="50">
                </div>
                <h5 class="card-title">Saudi Building Code</h5>
                <p class="text-muted small">Ensuring construction compliance with SBC.</p>
                <a href="<?php echo BASE_URL; ?>/services" class="btn-link"><?php echo $text['view']; ?> &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="<?php echo BASE_URL; ?>/assets/img/icons/Ministry_of_Commerce_Logo.svg" width="50">
                </div>
                <h5 class="card-title">Ministry of Commerce</h5>
                <p class="text-muted small">Commercial Registration (CR) and Trade Names.</p>
                <a href="<?php echo BASE_URL; ?>/services" class="btn-link"><?php echo $text['view']; ?> &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="<?php echo BASE_URL; ?>/assets/img/icons/iccksa.png" width="50">
                </div>
                <h5 class="card-title">Safety (ICC)</h5>
                <p class="text-muted small">International Code Council safety standards.</p>
                <a href="<?php echo BASE_URL; ?>/services" class="btn-link"><?php echo $text['view']; ?> &rarr;</a>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>