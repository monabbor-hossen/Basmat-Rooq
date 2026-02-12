<?php
// portal/dashboard.php
require_once __DIR__ . '/../includes/header.php'; // Global styles/config
require_once __DIR__ . '/../includes/portal_header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<main class="portal-main">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h1 class="h2 fw-bold text-white mb-1">Welcome, <?php echo htmlspecialchars($username); ?></h1>
                <p class="text-white-50 small">Track your MISA investment milestones in real-time.</p>
            </div>
            <div class="badge bg-gold text-dark p-2 px-3 rounded-pill fw-bold">Live Status</div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4 col-md-6">
                <div class="card-box reveal h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <img src="../assets/img/icons/Ministry_of_Investment_Logo-Dark.svg" height="40" style="filter: invert(1);">
                        <span class="text-gold fw-bold">75%</span>
                    </div>
                    <h5 class="fw-bold">MISA License</h5>
                    <p class="small text-white-50">Article of Association (AoA) Approved</p>
                    <div class="progress mt-4" style="height: 6px; background: rgba(255,255,255,0.1);">
                        <div class="progress-bar bg-gold" style="width: 75%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card-box reveal h-100">
                    <div class="d-flex justify-content-between mb-3">
                        <img src="../assets/img/icons/qiwa.png" height="40">
                        <span class="text-gold fw-bold">30%</span>
                    </div>
                    <h5 class="fw-bold">Qiwa Integration</h5>
                    <p class="small text-white-50">Establishment Registration Pending</p>
                    <div class="progress mt-4" style="height: 6px; background: rgba(255,255,255,0.1);">
                        <div class="progress-bar bg-gold" style="width: 30%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Sidebar Toggle Logic
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('rooqSidebar').classList.toggle('active');
    });

    // Scroll Reveal for futuristic feel
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('reveal');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.card-box').forEach((el) => observer.observe(el));
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>