<?php
// public/auth_process.php
require_once __DIR__ . '/../app/Auth/SessionManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new SessionManager();
    
    $user  = $_POST['username'] ?? '';
    $pass  = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    try {
        // Attempt Login
        if ($auth->login($user, $pass, $token)) {
            // Success Redirect
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
                header("Location: ../management/dashboard.php");
            } else {
                header("Location: ../portal/dashboard.php");
            }
            exit();
        }
    } catch (Exception $e) {
        // Security Error (Locked out, or Wrong Password)
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['error'] = $e->getMessage(); // Show the specific security message
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}