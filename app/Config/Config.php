<?php
// Brand Identity
define('COLOR_BURGUNDY', '#800020');
define('COLOR_GOLD', '#D4AF37');

// Language Handling
session_start();
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default to English
}

$lang = $_SESSION['lang'];
$dir = ($lang == 'ar') ? 'rtl' : 'ltr';
?>