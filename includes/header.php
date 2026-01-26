<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basmat Rooq | RooqFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/theme.css">
    <?php if ($dir == 'rtl'): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
        <style>body { font-family: 'Cairo', sans-serif; }</style>
    <?php endif; ?>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top rooq-navbar">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="index.php">BASMAT ROOQ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link text-white" href="#"><?php echo ($lang == 'ar' ? 'الرئيسية' : 'Home'); ?></a></li>
                <li class="nav-item mx-2">
                    <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="btn btn-sm btn-outline-light">
                        <?php echo ($lang == 'en' ? 'عربي' : 'English'); ?>
                    </a>
                </li>
                <li class="nav-item"><a class="btn-rooq-outline btn-sm ms-lg-3" href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>