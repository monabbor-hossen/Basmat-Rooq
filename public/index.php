<?php require_once '../includes/header.php'; ?>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100 flex-column flex-lg-row">
        <div class="col-lg-6 p-5 d-flex flex-column justify-content-center" style="background: rgba(0,0,0,0.4);">
            <h1 class="display-2 fw-bold mb-4">ROOQ<span style="color:var(--rooq-gold)">FLOW</span></h1>
            <section class="mb-5">
                <h3 class="text-gold" style="color:var(--rooq-gold)"><?php echo $text['about_title']; ?></h3>
                <p class="lead opacity-75"><?php echo $text['about_text']; ?></p>
            </section>
            
            <section>
                <h3 class="text-gold" style="color:var(--rooq-gold)"><?php echo $text['services_title']; ?></h3>
                <p class="opacity-75"><?php echo $text['services_text']; ?></p>
            </section>
        </div>

        <div class="col-lg-6 p-5 overflow-auto">
            <div class="d-flex justify-content-between mb-5">
                <h2 class="fw-bold"><?php echo $text['tracking_title']; ?></h2>
                <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn btn-outline-light rounded-pill">
                    <?php echo ($lang == 'en' ? 'العربية' : 'English'); ?>
                </a>
            </div>

            <div class="rooq-card mb-4 reveal p-4">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">Jahangir Contracting Ltd</h4>
                    <span class="badge bg-success">In Progress</span>
                </div>
                <p class="small opacity-50">Milestone: Hire Foreign Co (Approved)</p>
                <div class="progress mt-3" style="height: 6px; background: rgba(255,255,255,0.1);">
                    <div class="progress-bar" style="width: 75%; background: var(--rooq-gold);"></div>
                </div>
            </div>

            <div class="rooq-card reveal p-4" style="border-top-color: var(--rooq-gold);">
                <h4 class="fw-bold mb-3"><?php echo $text['contact_title']; ?></h4>
                <p class="mb-1"><i class="bi bi-geo-alt me-2"></i> <?php echo $text['location']; ?></p>
                <p><i class="bi bi-envelope me-2"></i> <?php echo $text['email']; ?></p>
                <a href="login.php" class="btn btn-rooq-primary w-100 mt-3"><?php echo $text['login']; ?></a>
            </div>
        </div>
    </div>
</div>

<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('reveal');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
</script>

<?php require_once '../includes/footer.php'; ?>