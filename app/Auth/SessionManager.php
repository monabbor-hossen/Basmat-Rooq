<?php
// app/Auth/SessionManager.php

// 1. Initialize dependencies
require_once dirname(__DIR__) . '/Config/Config.php';
require_once dirname(__DIR__) . '/Config/Database.php';
require_once dirname(__DIR__) . '/Helpers/Security.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// app/Auth/SessionManager.php

class SessionManager {
    /**
     * Start the session securely with best-practice settings.
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Security Settings
            ini_set('session.use_only_cookies', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_httponly', 1);
            
            // Allow secure cookies if running on HTTPS
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            if ($secure) {
                ini_set('session.cookie_secure', 1);
            }

            session_set_cookie_params([
                'lifetime' => 0, // Session cookie (until browser closes)
                'path' => '/',
                'domain' => '', 
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();
        }
    }

    /**
     * Regenerate session ID to prevent Session Fixation attacks.
     * Call this immediately after a successful login.
     */
    public static function regenerate() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /**
     * Destroy the session (Logout).
     */
    public static function destroy() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
    
    /**
     * Check if a specific role is logged in.
     */
    public static function requireRole($role) {
        self::start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            header("Location: ../public/login.php");
            exit();
        }
    }
}
?>