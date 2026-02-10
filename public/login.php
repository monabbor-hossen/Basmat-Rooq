
<?php 
require_once __DIR__ . '/../app/Helpers/Security.php'; // Load Security Helper
require_once __DIR__ . '/../app/Config/Config.php'; 
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Basmat Rooq</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; font-family: <?php echo ($lang == 'ar' ? "'Cairo', sans-serif" : "'Segoe UI', sans-serif"); ?>; }
    </style>
</head>
<body>

<div class="login-wrapper">
    
    <div class="login-brand-side">
        <div class="text-center position-relative" style="z-index: 2;">
            <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Basmat Rooq" class="brand-logo-img mb-4" style="max-width: 180px; filter: brightness(0) invert(1);">
            <h2 class="fw-bold mb-2">BASMAT ROOQ</h2>
            <p class="opacity-75 mb-4">Contracting Co. Ltd.</p>
            <div style="width: 50px; height: 3px; background: var(--rooq-gold); margin: 0 auto;"></div>
            <p class="mt-4 small opacity-75 d-none d-md-block">
                <?php echo ($lang == 'ar' ? 'بوابة العملاء الآمنة لخدمات الاستثمار' : 'Secure Client Portal for Investment Services'); ?>
            </p>
        </div>
    </div>

    <div class="login-form-side">
        
        <div class="position-absolute top-0 end-0 p-4">
            <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn btn-sm btn-outline-secondary">
                <?php echo ($lang == 'en' ? 'عربي' : 'English'); ?>
            </a>
        </div>

        <div class="login-form-container">
            <div class="mb-5">
                <h2 class="fw-bold text-dark"><?php echo ($lang == 'ar' ? 'تسجيل الدخول' : 'Welcome Back'); ?></h2>
                <p class="text-muted"><?php echo ($lang == 'ar' ? 'يرجى إدخال بيانات الاعتماد الخاصة بك' : 'Please enter your credentials to access your dashboard.'); ?></p>
            </div>
<form action="<?php echo BASE_URL; ?>/auth/login" method="POST">
    
    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger small p-2 text-center border-0 bg-danger bg-opacity-10 text-danger">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Clear error after showing
            ?>
        </div>
    <?php endif; ?>

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        <label for="username"><?php echo ($lang == 'ar' ? 'اسم المستخدم' : 'Username'); ?></label>
    </div>

    <div class="form-floating mb-3">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <label for="password"><?php echo ($lang == 'ar' ? 'كلمة المرور' : 'Password'); ?></label>
    </div>

    <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold shadow-sm">
        <?php echo ($lang == 'ar' ? 'دخول' : 'Sign In'); ?>
    </button>
</form>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>