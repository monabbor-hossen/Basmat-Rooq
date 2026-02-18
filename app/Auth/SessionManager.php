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
     * Secure Login Function (Checks Admins AND Clients)
     */
    public function login($username, $password, $csrf_token) {
        // A. Validate CSRF Token
        Security::checkCSRF($csrf_token);

        // B. Sanitize input
        $clean_user = Security::clean($username);

        try {
            // --- CHECK 1: ADMIN/STAFF (users table) ---
            $query = "SELECT id, username, password, role FROM users WHERE username = :user LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user', $clean_user);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $this->createSession($user, 'internal');
                return true;
            }

            // --- CHECK 2: CLIENTS (client_accounts table) ---
            $query2 = "SELECT account_id, client_id, username, password_hash FROM client_accounts WHERE username = :user LIMIT 1";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bindParam(':user', $clean_user);
            $stmt2->execute();
            $client = $stmt2->fetch(PDO::FETCH_ASSOC);

            // Note: client_add.php used 'password_hash' column
            if ($client && password_verify($password, $client['password_hash'])) {
                $this->createSession($client, 'client');
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
    private function createSession($data, $type) {
        if ($type === 'internal') {
            // Admin / Staff
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['role'] = $data['role']; // 'admin', 'manager', etc.
            $_SESSION['user_type'] = 'internal';
        } else {
            // Client
            $_SESSION['user_id'] = $data['account_id']; // Use account ID as user_id
            $_SESSION['client_id'] = $data['client_id']; // Store specific client ID
            $_SESSION['role'] = 'client';
            $_SESSION['user_type'] = 'external';
        }

        $_SESSION['username'] = $data['username'];
        $_SESSION['last_regen'] = time();
        
        // Prevent Session Hijacking
        session_regenerate_id(true);
    }

    /**
     * Logout
     */
    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: " . BASE_URL . "public/login.php");
        exit();
    }

    /**
     * Check Login Status
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
?>