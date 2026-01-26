<?php
session_start();

// Language Switcher Logic
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = ($_GET['lang'] == 'ar') ? 'ar' : 'en';
}
$lang = $_SESSION['lang'] ?? 'en';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

// Brand Constants from your theme.css
define('ROOQ_BURGUNDY', '#800020');
define('ROOQ_GOLD', '#D4AF37');

// Translation Array
$words = [
    'en' => [
        'title' => 'Basmat Rooq | MISA Tracking',
        'home' => 'Home',
        'services' => 'Services',
        'login' => 'Portal Login',
        'hero_h1' => 'MISA License Digital Tracking',
        'hero_p' => 'Simplifying government workflows for international investors in Saudi Arabia.',
        'track' => 'Track Progress'
    ],
    'ar' => [
        'title' => 'بصمة روق | تتبع تراخيص ميزة',
        'home' => 'الرئيسية',
        'services' => 'الخدمات',
        'login' => 'دخول البوابة',
        'hero_h1' => 'التتبع الرقمي لتراخيص MISA',
        'hero_p' => 'تبسيط إجراءات العمل الحكومي للمستثمرين الدوليين في المملكة العربية السعودية.',
        'track' => 'تتبع التقدم'
    ]
];
$t = $words[$lang];
?>