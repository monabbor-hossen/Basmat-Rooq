<?php require_once __DIR__ . '/../app/Config/Config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $text['title']; ?></title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/theme.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: <?php echo ($lang == 'ar' ? "'Cairo', sans-serif" : "'Segoe UI', sans-serif"); ?>; }
        .hero-section {
            background: linear-gradient(rgba(128, 0, 32, 0.9), rgba(45, 45, 45, 0.8));
            color: #fff;
            padding: 120px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: var(--rooq-burgundy); border-bottom: 3px solid var(--rooq-gold);">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="<?php echo BASE_URL; ?>/">
            <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Basmat Rooq" width="150" style="filter: brightness(0) invert(1);">
        </a>
        
        <div class="d-flex align-items-center ms-auto">
            <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn btn-sm btn-outline-light me-3">
                <?php echo ($lang == 'en' ? 'عربي' : 'English'); ?>
            </a>
            <a href="<?php echo BASE_URL; ?>/public/login" class="btn-rooq-outline btn-sm text-white border-white">
                <?php echo $text['login']; ?>
            </a>
        </div>
    </div>
</nav>