<?php
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
    
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/img/favicon-32x32.png" type="image/x-icon" />
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/img/favicon-32x32.png" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <?php if($dir == 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.rtl.min.css">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="portal-body">

<header class="portal-header sticky-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link text-white d-lg-none p-0" id="sidebarToggle">
                <i class="fa-solid fa-bars fa-xl"></i>
            </button>
            <a href="dashboard.php" class="text-decoration-none d-flex align-items-center">
                <img src="<?php echo BASE_URL; ?>assets/img/logo.png" height="50" alt="Logo">
            </a>
        </div>

        <div class="search-container d-none d-md-block mx-auto">
            <div class="input-group glass-search">
                <span class="input-group-text bg-transparent border-0 text-white-50"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-transparent border-0 text-white" placeholder="Search licenses, clients..." aria-label="Search">
            </div>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="position-relative d-none d-sm-block" style="cursor: pointer;">
                <i class="bi bi-bell text-white fs-5 opacity-75 hover-gold"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-dark rounded-circle"></span>
            </div>

            <div class="dropdown">
                <div class="profile-trigger-refined d-flex align-items-center gap-3" data-bs-toggle="dropdown" aria-expanded="false">
                    
                    <div class="text-end d-none d-lg-block">
                        <div class="user-name-text"><?php echo $username; ?></div>
                        <div class="user-role-text text-uppercase">Admin</div>
                    </div>
                    
                    <div class="avatar-circle-refined">
                        <?php echo strtoupper(substr($username, 0, 2)); // Show first 2 letters like image (e.g., AB) ?>
                    </div>

                    <i class="bi bi-chevron-down dropdown-chevron"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end glass-dropdown mt-3 shadow-lg border-0" style="background: rgba(20, 20, 20, 0.95);">
                    <li class="d-lg-none px-3 py-2 text-white fw-bold border-bottom border-secondary border-opacity-25 mb-2">
                        <?php echo $username; ?> <br>
                        <small class="text-gold">Admin</small>
                    </li>
                    <li><a class="dropdown-item text-white-50 hover-white" href="profile.php"><i class="bi bi-person-gear me-2 text-gold"></i> Settings</a></li>
                    <li><a class="dropdown-item text-white-50 hover-white" href="#"><i class="bi bi-activity me-2 text-gold"></i> Activity</a></li>
                    <li><hr class="dropdown-divider bg-light opacity-10"></li>
                    <li><a class="dropdown-item text-danger fw-bold" href="<?php echo BASE_URL; ?>public/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="d-flex portal-wrapper">
    <?php require_once 'sidebar.php'; ?>
    
    <main class="w-100 p-4">