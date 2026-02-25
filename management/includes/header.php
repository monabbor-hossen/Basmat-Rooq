<?php
// management/includes/header.php
require_once __DIR__ . '/../../app/Config/Config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// --- STRICT CLIENT SECURITY CHECK ---
// If they are not logged in, OR if they are not a 'client', kick them out!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal - RooqFlow</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/theme.css"> 
</head>
<body class="bg-dark text-white">

<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-light border-opacity-10 mb-4" style="background: rgba(0,0,0,0.5);">
    <div class="container-fluid px-4">
        <a class="navbar-brand text-gold fw-bold" href="dashboard.php">
            <i class="bi bi-layers me-2"></i>Client Portal
        </a>
        <div class="d-flex align-items-center">
            <span class="text-white-50 small me-3 d-none d-md-block">Account: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../public/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>

<main class="container">