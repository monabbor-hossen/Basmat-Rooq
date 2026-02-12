<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Security {
    public static function generateCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkCSRF($token) {
    // Ensure $token is a string and session token is set to avoid TypeErrors
    if (!is_string($token) || !isset($_SESSION['csrf_token'])) {
        die("Security Alert: Missing or invalid request token.");
    }
    
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        die("Security Alert: Invalid Request Token.");
    }
    return true;
}

    public static function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    public static function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // If the user_id session is not set, they are not logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "public/login.php");
            exit();
        }
    }

}
?>