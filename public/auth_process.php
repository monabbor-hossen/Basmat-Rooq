<?php
// public/auth_process.php
require_once __DIR__ . '/../app/Auth/SessionManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new SessionManager();
    
    // Retrieve form data
    $user  = $_POST['username'] ?? '';
    $pass  = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    // Attempt login using your rooqflow database
    if ($auth->login($user, $pass, $token)) {
        header("Location: ../portal/dashboard.php");
        exit();
    } else {
        session_start();
        $_SESSION['error'] = "Invalid username, password, or security token.";
        header("Location: login.php");
        exit();
    }
}