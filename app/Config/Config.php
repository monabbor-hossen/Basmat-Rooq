<?php
// app/Config/Config.php

// Define the protocol (http or https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Define the domain
$domain = $_SERVER['HTTP_HOST'];

// Define the project folder (if applicable)
// If your project is in the root, leave as ''; if in a folder, use '/basmat-rooq'
$projectFolder = '/rooqflow'; 

// Combine to create the BASE_URL constant
define('BASE_URL', $protocol . $domain . $projectFolder . '/');

// Project Brand Colors (as seen in theme.css)
define('COLOR_BURGUNDY', '#800020');
define('COLOR_GOLD', '#D4AF37');
?>