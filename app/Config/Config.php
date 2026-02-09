<?php
session_start();

// --- 1. SMART BASE URL (Fixes Broken Links) ---
// Detects your folder name automatically (e.g., /basmat-rooq/)
$rootScript = dirname($_SERVER['SCRIPT_NAME']);
// If running from public/, go up one level to get the real root
$baseUrl = str_replace('/public', '', $rootScript); 
// Remove trailing slash if it exists to be safe
$baseUrl = rtrim($baseUrl, '/'); 
define('BASE_URL', $baseUrl);


// --- 2. Language Logic ---
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = ($_GET['lang'] == 'ar') ? 'ar' : 'en';
}
$lang = $_SESSION['lang'] ?? 'en';
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';

// --- 3. Translations ---
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