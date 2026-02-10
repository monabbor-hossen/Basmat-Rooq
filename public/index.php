<?php require_once '../includes/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 text-white">
            <h1 class="display-3 fw-bold mb-4">Basmat <span style="color:var(--rooq-gold)">Rooq</span></h1>
            <p class="lead opacity-75"><?php echo $text['hero_desc'] ?? 'Secure MISA License tracking portal for the Kingdom of Saudi Arabia.'; ?></p>
            <div class="d-flex gap-3 mt-4">
                <a href="login.php" class="btn-rooq-primary px-4 py-3"><?php echo $text['login']; ?></a>
                <button class="btn-rooq-outline px-4 py-3"><?php echo $text['services']; ?></button>
            </div>
        </div>
        
        <div class="col-lg-6 mt-5 mt-lg-0">
            <div class="rooq-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span><?php echo $text['current_status'] ?? 'Active Portfolios'; ?></span>
                    <span class="badge bg-success">Live</span>
                </div>
                <h4 class="mb-4">Jahangir Contracting Ltd</h4>
                <div class="progress-container mb-2">
                    <div class="progress-bar-rooq" style="width: 65%;"></div>
                </div>
                <div class="d-flex justify-content-between small text-white-50">
                    <span>MISA Application</span>
                    <span>65% <?php echo $text['complete'] ?? 'Complete'; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row milestone-grid">
        <?php 
        $icons = [
            ['img' => 'qiwa.png', 'name' => 'QIWA'],
            ['img' => 'MUQEEM.png', 'name' => 'MUQEEM'],
            ['img' => 'GOSI-General-Organization-for-Social-Insurance.jpg', 'name' => 'GOSI']
        ];
        foreach($icons as $icon): ?>
        <div class="col-md-4 mb-4">
            <div class="rooq-card text-center p-4">
                <img src="<?php echo BASE_URL; ?>assets/img/icons/<?php echo $icon['img']; ?>" alt="<?php echo $icon['name']; ?>" style="height: 60px; filter: drop-shadow(0 0 10px rgba(255,255,255,0.3));">
                <h5 class="mt-3"><?php echo $icon['name']; ?></h5>
                <p class="small opacity-50"><?php echo $text['verified_partner'] ?? 'Authorized Integration'; ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>