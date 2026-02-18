<?php
// public/auth_process.php

require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Auth/SessionManager.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

// 1. Start Secure Session
SessionManager::start();

// 2. Validate Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Check CSRF Token
if (!isset($_POST['csrf_token']) || !Security::validateCSRF($_POST['csrf_token'])) {
    die("Security Error: Invalid or missing CSRF token.");
}

$input_user = Security::clean($_POST['email']); // Accepts Email (Admin) or Username (Client)
$password   = $_POST['password'];

$db = (new Database())->getConnection();

try {
    // --- CHECK 1: IS IT AN ADMIN/STAFF? (Table: users) ---
    // Staff usually login with Email
    $stmt = $db->prepare("SELECT user_id, username, password_hash, role FROM users WHERE email = :input LIMIT 1");
    $stmt->execute([':input' => $input_user]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        // SUCCESS: Admin Login
        SessionManager::regenerate(); // [Security] Prevent session fixation
        
        $_SESSION['user_id']  = $admin['user_id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role']     = $admin['role']; // 'admin' or 'manager'
        $_SESSION['type']     = 'internal';

        header("Location: ../portal/dashboard.php");
        exit();
    }

    // --- CHECK 2: IS IT A CLIENT? (Table: client_accounts) ---
    // Clients login with Username
    $stmt = $db->prepare("SELECT account_id, client_id, username, password_hash FROM client_accounts WHERE username = :input LIMIT 1");
    $stmt->execute([':input' => $input_user]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client && password_verify($password, $client['password_hash'])) {
        // SUCCESS: Client Login
        SessionManager::regenerate(); // [Security] Prevent session fixation

        $_SESSION['user_id']   = $client['account_id'];
        $_SESSION['client_id'] = $client['client_id']; // Link to profile
        $_SESSION['username']  = $client['username'];
        $_SESSION['role']      = 'client';
        $_SESSION['type']      = 'external';

        header("Location: ../management/dashboard.php");
        exit();
    }

    // --- FAILED ---
    $_SESSION['error'] = "Invalid email/username or password.";
    header("Location: login.php");
    exit();

} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    $_SESSION['error'] = "System error. Please try again later.";
    header("Location: login.php");
    exit();
}