<?php
// Securely check if user is logged in
require_once __DIR__ . '/../../app/Config/Config.php';
require_once __DIR__ . '/../../app/Helpers/Security.php';
Security::requireLogin();

$lang = $_SESSION['lang'] ?? 'en';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';
$username = $_SESSION['username'] ?? 'User'; // Show logged-in user's name
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal | Basmat Rooq</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css">
    <?php if($dir == 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.rtl.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/rtl.css">
    <?php endif; ?>
</head>
<body class="portal-body">

<nav class="navbar navbar-expand-lg portal-header sticky-top">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-white">ROOQ<span class="text-gold">FLOW</span></span>
        
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <button class="btn dropdown-toggle text-white d-flex align-items-center border-0" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <div class="user-avatar me-2"><?php echo substr($username, 0, 1); ?></div>
                    <span class="d-none d-md-inline"><?php echo $username; ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end glass-dropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../public/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>