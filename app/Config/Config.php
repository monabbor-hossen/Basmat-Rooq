<?php
// Determine protocol and domain for BASE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];
// Adjust path if your project is in a subfolder like /rooqflow/
define('BASE_URL', $protocol . $domain . '/rooqflow/');

// Brand Identity
define('COLOR_BURGUNDY', '#800020');
define('COLOR_GOLD', '#D4AF37');
?>