<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $dir; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Segoe+UI:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/theme.css"> <style>
        body { font-family: 'Segoe UI', 'Cairo', sans-serif; }
        .rooq-navbar { background-color: <?php echo ROOQ_BURGUNDY; ?>; border-bottom: 3px solid <?php echo ROOQ_GOLD; ?>; }
        .hero-mt { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/img/hero_bg.jpg'); background-size: cover; color: white; padding: 100px 0; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg rooq-navbar sticky-top navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index"><img src="../assets/img/logo.png" alt="" width="250"></a>
        <div class="d-flex align-items-center ms-auto">
            <a href="?lang=<?php echo ($lang == 'en' ? 'ar' : 'en'); ?>" class="nav-link text-white me-3 fw-bold">
                <?php echo ($lang == 'en' ? 'عربي' : 'English'); ?>
            </a>
            <a href="login.php" class="btn btn-rooq-outline btn-sm px-4"><?php echo $t['login']; ?></a>
        </div>
    </div>
</nav>