<?php
// includes/portal_header.php
require_once __DIR__ . '/../app/Helpers/Security.php';
Security::requireLogin(); // Ensure only logged-in users enter

$username = $_SESSION['username'] ?? 'User';
?>
<nav class="rooq-navbar fixed-top d-flex align-items-center justify-content-between px-4">
    <div class="d-flex align-items-center">
        <button class="btn text-white d-lg-none me-3" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand fw-bold text-white" href="#">
            ROOQ<span style="color:var(--rooq-gold)">FLOW</span>
        </a>
    </div>

    <div class="d-flex align-items-center gap-3">
        <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn btn-sm btn-outline-light rounded-pill">
            <?php echo ($lang == 'en' ? 'العربية' : 'English'); ?>
        </a>

        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 text-white dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                <div class="avatar-sm rounded-circle bg-gold text-dark d-flex align-items-center justify-content-center fw-bold">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
                <span class="d-none d-md-inline"><?php echo htmlspecialchars($username); ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end glass-dropdown mt-2 shadow-lg">
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-cog me-2"></i> Profile Setting</a></li>
                <li><hr class="dropdown-divider border-secondary"></li>
                <li><a class="dropdown-item text-danger" href="../public/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>