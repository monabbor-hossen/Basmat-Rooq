<?php
// app/Auth/SessionManager.php

// 1. Initialize dependencies
require_once dirname(__DIR__) . '/Config/Config.php';
require_once dirname(__DIR__) . '/Config/Database.php';
require_once dirname(__DIR__) . '/Helpers/Security.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SessionManager {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Secure Login Function
     */
    public function login($username, $password, $csrf_token) {
        // A. Validate CSRF Token for security
        Security::checkCSRF($csrf_token);

        // B. Sanitize inputs to prevent XSS and SQL Injection
        $clean_user = Security::clean($username);

        try {
            // C. Securely query the 'rooqflow' database
            $query = "SELECT id, username, password, role FROM users WHERE username = :user LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user', $clean_user);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // D. Verify password (assuming use of password_hash in DB)
            if ($user && password_verify($password, $user['password'])) {
                $this->createSession($user);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Auth Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Establish Secure Session Data
     */
    private function createSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_regen'] = time();
        
        // Prevent Session Hijacking by regenerating ID
        session_regenerate_id(true);
    }

    /**
     * Logout and destroy session
     */
    public function logout() {
        $_SESSION = array();
        session_destroy();
        header("Location: " . BASE_URL . "public/login.php");
        exit();
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>