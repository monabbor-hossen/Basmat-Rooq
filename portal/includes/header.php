<?php
// portal/includes/header.php
require_once __DIR__ . '/../../app/Config/Config.php';
require_once __DIR__ . '/../../app/Helpers/Security.php';
require_once __DIR__ . '/../../app/Helpers/Translator.php';

Security::requireLogin();

$lang = $_SESSION['lang'] ?? 'en';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';
$username = $_SESSION['username'] ?? 'User';

$translator = new Translator();
$text = $translator->getTranslation($lang);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .portal-header {
            background: rgba(128, 0, 32, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            padding: 10px 20px;
        }
        .profile-trigger {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.5);
            padding: 5px 15px;
            border-radius: 50px;
            cursor: pointer;
            transition: 0.3s;
        }
        .profile-trigger:hover { background: rgba(212, 175, 55, 0.2); }
        .avatar-circle {
            width: 35px;
            height: 35px;
            background: #D4AF37;
            color: #800020;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .glass-dropdown {
            background: rgba(45, 45, 45, 0.98);
            backdrop-filter: blur(15px);
            border: 1px solid #D4AF37;
            border-radius: 15px;
        }
        .glass-dropdown .dropdown-item { color: white; padding: 10px 20px; }
        .glass-dropdown .dropdown-item:hover { background: rgba(212, 175, 55, 0.2); color: #D4AF37; }
    </style>
</head>
<body>

<header class="portal-header sticky-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="header-left d-flex align-items-center">
            <img src="<?php echo BASE_URL; ?>assets/img/logo.png" height="40" class="me-2">
            <h4 class="text-white mb-0 fw-bold d-none d-md-block">BASMAT <span style="color: #D4AF37;">ROOQ</span></h4>
        </div>

        <div class="header-right d-flex align-items-center">
            <div class="dropdown">
                <div class="profile-trigger d-flex align-items-center" data-bs-toggle="dropdown">
                    <div class="text-end me-2 d-none d-sm-block">
                        <div class="text-white small fw-bold" style="line-height: 1;"><?php echo $username; ?></div>
                        <small style="color: #D4AF37; font-size: 0.7rem;">Active Portfolio</small>
                    </div>
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($username, 0, 1)); ?>
                    </div>
                    <i class="bi bi-chevron-down text-white ms-2 small"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end glass-dropdown mt-2 shadow-lg">
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> Profile Settings</a></li>
                    <li><a class="dropdown-item" href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>"><i class="bi bi-translate me-2"></i> <?php echo ($lang == 'en' ? 'العربية' : 'English'); ?></a></li>
                    <li><hr class="dropdown-divider border-secondary"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>public/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>