<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Security {
    // Generate a unique token for the form
    public static function generateCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Verify the token on submission
    public static function checkCSRF($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            // Log this event as a potential attack
            error_log("Potential CSRF Attack detected from IP: " . $_SERVER['REMOTE_ADDR']);
            die("Security Alert: Invalid Request Token.");
        }
        return true;
    }

    // Sanitize Output to prevent XSS (Cross-Site Scripting)
    public static function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}
?>