<?php require_once '../includes/header.php'; ?>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100 flex-column flex-lg-row">
        <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center text-center p-5 order-lg-1" 
             style="background: linear-gradient(135deg, var(--rooq-burgundy) 0%, #000 100%);">
            <h1 class="display-1 fw-bold"><?php echo $text['hero_title']; ?></h1>
            <p class="lead opacity-75"><?php echo $text['hero_desc']; ?></p>
            <div class="mt-4 floating-anim">
                <img src="<?php echo BASE_URL; ?>assets/img/icons/qiwa.png" style="height: 100px; filter: drop-shadow(0 0 15px var(--rooq-gold));">
            </div>
        </div>

        <div class="col-lg-6 p-5 order-lg-2">
            <div class="mx-auto" style="max-width: 500px;">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="fw-bold m-0"><?php echo $text['current_status']; ?></h2>
                    <a href="?lang=<?php echo $isRTL ? 'en' : 'ar'; ?>" class="btn btn-outline-light rounded-pill px-4">
                        <?php echo $isRTL ? 'English' : 'العربية'; ?>
                    </a>
                </div>

                <div class="card-box mb-4 reveal p-4">
                    <div class="d-flex justify-content-between">
                        <h4 class="fw-bold">Jahangir Contracting Ltd</h4>
                        <img src="<?php echo BASE_URL; ?>assets/img/icons/GOSI-General-Organization-for-Social-Insurance.jpg" style="height: 30px;">
                    </div>
                    <p class="small text-white-50"><?php echo $text['milestones']; ?>: Hire Foreign Co (Approved)</p>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar bg-gold" style="width: 75%;"></div>
                    </div>
                </div>

                <div class="d-grid mt-5">
                    <a href="login.php" class="btn-rooq-primary text-center py-3"><?php echo $text['login']; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('reveal');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
</script>

<?php require_once '../includes/footer.php'; ?>