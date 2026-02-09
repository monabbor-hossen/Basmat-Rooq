<?php
// 1. FIX: Only start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. FIX: Automatic Base URL Detection (Fixes broken links)
// This calculates your folder path automatically (e.g., /basmat-rooq)
$rootScript = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = str_replace('/public', '', $rootScript); 
$baseUrl = rtrim($baseUrl, '/'); 
define('BASE_URL', $baseUrl);

// 3. Language Logic
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = ($_GET['lang'] == 'ar') ? 'ar' : 'en';
}
$lang = $_SESSION['lang'] ?? 'en';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

// 4. Translations
$t = [
    'en' => [
        'title' => 'Basmat Rooq | Government Services',
        'home' => 'Home',
        'login' => 'Portal Login',
        'hero_title' => 'Digitizing MISA Licensing',
        'hero_desc' => 'Seamless government workflows for Jahangir & Fonon Contracting.',
        'services_title' => 'Our Services',
        'services_sub' => 'Comprehensive government solutions for your business',
        'view' => 'View Details'
    ],
    'ar' => [
        'title' => 'بصمة روق | الخدمات الحكومية',
        'home' => 'الرئيسية',
        'login' => 'دخول البوابة',
        'hero_title' => 'رقمنة تراخيص الاستثمار',
        'hero_desc' => 'سير عمل حكومي سلس لشركات جهانجير وفنون للمقاولات.',
        'services_title' => 'خدماتنا',
        'services_sub' => 'حلول حكومية شاملة لأعمالك',
        'view' => 'عرض التفاصيل'
    ]
];
$text = $t[$lang];
?>