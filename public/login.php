<?php 
require_once __DIR__ . '/../app/Config/Config.php'; 
// If already logged in, redirect to dashboard (placeholder logic)
if (isset($_SESSION['user_id'])) {
    header("Location: /dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Basmat Rooq</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/theme.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: var(--rooq-burgundy); border-bottom: 3px solid var(--rooq-gold);">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="/index">BASMAT ROOQ</a>
        <div class="ms-auto">
            <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="text-white text-decoration-none small">
                <?php echo ($lang == 'en' ? 'عربي' : 'English'); ?>
            </a>
        </div>
    </div>
</nav>

<div class="login-container">
    <div class="login-card">
        
        <div class="login-visual">
            <img src="/assets/img/logo.png" alt="Basmat Rooq" style="max-width: 150px; margin: 0 auto 20px; filter: brightness(0) invert(1);">
            <h3 class="fw-bold mb-3"><?php echo ($lang == 'ar' ? 'بوابة العملاء' : 'Client Portal'); ?></h3>
            <p class="small opacity-75">
                <?php echo ($lang == 'ar' 
                ? 'الوصول الآمن إلى خدمات جهانجير وفنون للمقاولات' 
                : 'Secure access for Jahangir & Fonon Contracting services.'); ?>
            </p>
        </div>

        <div class="login-form-section">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark"><?php echo ($lang == 'ar' ? 'تسجيل الدخول' : 'Sign In'); ?></h2>
                <p class="text-muted small"><?php echo ($lang == 'ar' ? 'أدخل بيانات الاعتماد الخاصة بك للمتابعة' : 'Enter your credentials to continue'); ?></p>
            </div>

            <form action="/auth/login" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username"><?php echo ($lang == 'ar' ? 'اسم المستخدم' : 'Username / CR Number'); ?></label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><?php echo ($lang == 'ar' ? 'كلمة المرور' : 'Password'); ?></label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            <?php echo ($lang == 'ar' ? 'تذكرني' : 'Remember me'); ?>
                        </label>
                    </div>
                    <a href="#" class="small text-decoration-none" style="color: var(--rooq-gold);">
                        <?php echo ($lang == 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?'); ?>
                    </a>
                </div>

                <button type="submit" class="btn btn-rooq-primary w-100 py-2 fw-bold">
                    <?php echo ($lang == 'ar' ? 'دخول آمن' : 'Secure Login'); ?>
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <small class="text-muted">
                    &copy; <?php echo date('Y'); ?> Basmat Rooq Co. Ltd.
                </small>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>