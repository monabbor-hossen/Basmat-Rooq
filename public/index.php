<?php require_once '../includes/header.php'; ?>
<div class="login-wrapper d-flex min-vh-100">
    <div class="brand-side w-50 p-5 d-flex flex-column justify-content-center bg-dark bg-opacity-25">
        <h1 class="display-1 fw-bold"><?php echo $text['hero_title']; ?></h1>
        <p class="lead opacity-75 mb-5"><?php echo $text['hero_desc']; ?></p>
        
        <div class="card-box reveal mb-4">
            <h3><?php echo $text['about_title']; ?></h3>
            <p><?php echo $text['about_text']; ?></p>
        </div>
        
        <div class="card-box reveal">
            <h3><?php echo $text['contact_info']; ?></h3>
            <p>📍 <?php echo $text['location']; ?><br>📧 <?php echo $text['email']; ?></p>
        </div>
    </div>

    <div class="form-side w-50 p-5 d-flex flex-column justify-content-center">
        <div class="d-flex justify-content-between mb-5">
            <h2 class="fw-bold"><?php echo $text['status']; ?></h2>
            <a href="?lang=<?php echo $isRTL ? 'en' : 'ar'; ?>" class="btn btn-outline-light rounded-pill">
                <?php echo $isRTL ? 'English' : 'العربية'; ?>
            </a>
        </div>

        <div class="card-box reveal mb-4">
            <h5>Jahangir Contracting Ltd</h5>
            <p class="small opacity-50">MISA Application Status: In Progress</p>
            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar bg-warning" style="width: 65%;"></div>
            </div>
        </div>

        <div class="d-grid mt-4">
            <a href="login.php" class="btn-rooq-primary text-center text-decoration-none"><?php echo $text['login']; ?></a>
        </div>
    </div>
</div>

<script>
    const obs = new IntersectionObserver((es) => {
        es.forEach(e => { if (e.isIntersecting) e.target.classList.add('reveal'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach((el) => obs.observe(el));
</script>
<?php require_once '../includes/footer.php'; ?>