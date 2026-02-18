<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// app/Helpers/Security.php

class Security {

    /**
     * Generate a CSRF token and store it in the session.
     * @return string
     */
    public static function generateCSRF() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate the CSRF token (Returns boolean).
     * Use this inside if-statements.
     * * @param string $token
     * @return bool
     */
    public static function validateCSRF($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }
        return true;
    }

    /**
     * Check CSRF and DIE if invalid.
     * Use this at the top of POST processing scripts.
     * * @param string $token
     */
    public static function checkCSRF($token) {
        if (!self::validateCSRF($token)) {
            header("HTTP/1.1 403 Forbidden");
            die("Security Error: Invalid CSRF Token. Please refresh the page.");
        }
    }

    /**
     * Sanitize input strings to prevent XSS.
     * * @param string $data
     * @return string
     */
    public static function clean($data) {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Require a user to be logged in.
     */
    public static function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../public/login.php");
            exit();
        }
    }
}