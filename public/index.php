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
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--rooq-burgundy);">Our Services</h2>
        <p class="text-muted">Comprehensive government solutions for your business</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="../assets/img/icons/Ministry_of_Investment_Logo-Dark.svg" width="50" alt="MISA Icon">
                </div>
                <h5 class="card-title">MISA Licensing</h5>
                <p class="card-text">Complete management of foreign investment licenses. We handle the full application process for Jahangir & Fonon Contracting.</p>
                <a href="services.php?type=misa" class="btn-link">View Details &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="../assets/img/icons/qiwa.png" width="50" alt="Qiwa Icon">
                </div>
                <h5 class="card-title">GOSI & QIWA</h5>
                <p class="card-text">Seamless integration with GOSI and QIWA platforms to ensure your workforce compliance and contract management.</p>
                <a href="services.php?type=gosi" class="btn-link">View Details &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="../assets/img/icons/MUQEEM.png" width="50" alt="Muqeem Icon">
                </div>
                <h5 class="card-title">Visa & Muqeem</h5>
                <p class="card-text">Issuance and renewal of resident permits (Iqama). Managing exit/re-entry visas through the Muqeem portal.</p>
                <a href="services.php?type=muqeem" class="btn-link">View Details &rarr;</a>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="../assets/img/icons/Saudi_building_code_logo.svg" width="50" alt="SBC Icon">
                </div>
                <h5 class="card-title">Building Code (SBC)</h5>
                <p class="card-text">Ensuring all construction projects meet the strict standards of the Saudi Building Code for final approval.</p>
                <a href="services.php?type=sbc" class="btn-link">View Details &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="../assets/img/icons/Ministry_of_Commerce_Logo.svg" width="50" alt="COC Icon">
                </div>
                <h5 class="card-title">Ministry of Commerce</h5>
                <p class="card-text">Commercial Registration (CR) issuance, renewals, and trade name reservations (e.g., ALSAMA SKR).</p>
                <a href="services.php?type=coc" class="btn-link">View Details &rarr;</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <div class="card-icon">
                    <img src="../assets/img/icons/iccksa.png" width="50" alt="ICC Icon">
                </div>
                <h5 class="card-title">Safety Compliance</h5>
                <p class="card-text">Adhering to International Code Council (ICC) standards for safety and fire protection in your facilities.</p>
                <a href="services.php?type=icc" class="btn-link">View Details &rarr;</a>
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>