<?php
// public/404.php
require_once __DIR__ . '/../app/Config/Config.php';

// Start session if not already started to check login status
if (session_status() === PHP_SESSION_NONE) session_start();

// Dynamically determine where the "Go Back" button should take the user
$home_link = 'login.php';
$btn_text = 'Go to Login';

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
        $home_link = '../management/dashboard.php';
        $btn_text = 'Return to Dashboard';
    } else {
        $home_link = '../portal/dashboard.php';
        $btn_text = 'Return to Dashboard';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found | Basmat Rooq</title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 error-page-body">

    <div id="global-loader" class="global-loader">
        <div class="rooq-spinner"></div>
    </div>

    <div class="error-bg-glow"></div>

    <div class="container text-center position-relative" style="z-index: 10;">
        <div class="error-container glass-panel mx-auto">
            <div class="error-code mb-2">404</div>
            <h2 class="text-white fw-bold mb-3">Page Not Found</h2>
            <p class="text-white-50 mb-5 px-md-4">
                The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>
            <a href="<?php echo $home_link; ?>" class="btn btn-rooq-primary px-5 rounded-pill shadow-lg">
                <i class="bi bi-house-door me-2"></i> <?php echo $btn_text; ?>
            </a>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>