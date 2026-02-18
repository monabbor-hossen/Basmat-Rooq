<?php
// public/auth_process.php
require_once __DIR__ . '/../app/Auth/SessionManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new SessionManager();
    
    // Retrieve form data
    $user  = $_POST['username'] ?? '';
    $pass  = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    // Attempt login
    if ($auth->login($user, $pass, $token)) {
        // Login Successful - Check Role for Redirect
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
            // Redirect Client
            header("Location: ../management/dashboard.php");
        } else {
            // Redirect Admin/Staff
            header("Location: ../portal/dashboard.php");
        }
        exit();
    } else {
        // Login Failed
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['error'] = "Invalid username, password, or security token.";
        header("Location: login.php");
        exit();
    }
} else {
    // If accessed directly without POST
    header("Location: login.php");
    exit();
}