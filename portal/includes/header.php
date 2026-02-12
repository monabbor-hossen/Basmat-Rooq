<?php
// portal/includes/header.php
require_once __DIR__ . '/../../app/Config/Config.php'; //
require_once __DIR__ . '/../../app/Helpers/Security.php'; //

// Securely check if user is logged in
Security::requireLogin();

$lang = $_SESSION['lang'] ?? 'en';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Basmat Rooq</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <?php if($dir == 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.rtl.min.css">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="portal-body">

<header class="portal-header sticky-top">
    <div class="container-fluid px-4">
        <div class="header-container d-flex justify-content-between align-items-center">
            <div class="header-brand">
                <a href="dashboard.php" class="text-decoration-none d-flex align-items-center">
                    <img src="<?php echo BASE_URL; ?>assets/img/logo.png" alt="Logo" height="35" class="me-2">
                    <span class="brand-text fw-bold text-white d-none d-sm-inline">ROOQ<span class="text-gold">FLOW</span></span>
                </a>
            </div>

            <div class="header-actions d-flex align-items-center">
                <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 me-3 d-none d-md-block">
                    <?php echo ($lang == 'en' ? 'العربية' : 'English'); ?>
                </a>

                <div class="dropdown">
                    <div class="profile-trigger d-flex align-items-center py-1 px-2 rounded-pill" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-info text-end me-2 d-none d-md-block">
                            <div class="user-name fw-bold text-white mb-0" style="font-size: 0.9rem; line-height: 1;"><?php echo $username; ?></div>
                            <small class="user-role text-gold" style="font-size: 0.75rem;">Verified Account</small>
                        </div>
                        <div class="profile-avatar">
                            <span class="avatar-letter"><?php echo strtoupper(substr($username, 0, 1)); ?></span>
                        </div>
                        <i class="bi bi-chevron-down text-white ms-2 small"></i>
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end glass-dropdown mt-2 shadow-lg">
                        <li class="px-3 py-2 d-md-none border-bottom border-light border-opacity-10 mb-2">
                            <span class="fw-bold text-white"><?php echo $username; ?></span>
                        </li>
                        <li><a class="dropdown-item" href="profile-settings.php"><i class="bi bi-person-gear me-2"></i> Profile Settings</a></li>
                        <li><a class="dropdown-item" href="activity-log.php"><i class="bi bi-clock-history me-2"></i> Activity Log</a></li>
                        <li><hr class="dropdown-divider bg-light opacity-10"></li>
                        <li><a class="dropdown-item text-danger" href="../public/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>