<?php
session_start();
$root = dirname(__DIR__);

// Load Core Dependencies
require_once $root . '/app/Config/Config.php';
require_once $root . '/app/Helpers/Translator.php';

// Language Switching Logic
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = $_SESSION['lang'] ?? 'en';
$isRTL = ($lang === 'ar');

// Initialize Translation Helper
$translator = new Translator();
$text = $translator->getTranslation($lang);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $isRTL ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basmat Rooq | <?php echo $text['hero_title']; ?></title>

    <?php if ($isRTL): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/rtl.css">
    <?php else: ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mobile.css">
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: var(--rooq-burgundy); border-bottom: 3px solid var(--rooq-gold);">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="<?php echo BASE_URL; ?>">
            <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Basmat Rooq" width="150" style="filter: brightness(0) invert(1);">
        </a>
        
        <div class="d-flex align-items-center ms-auto">
            <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn-rooq-outline">
                <?php echo ($lang == 'en' ? 'العربية' : 'English'); ?>
            </a>
            <a href="<?php echo BASE_URL; ?>public/login.php" class="btn-rooq-outline btn-sm text-white border-white">
                <?php echo $text['login']; ?>
            </a>
        </div>
    </div>
</nav>