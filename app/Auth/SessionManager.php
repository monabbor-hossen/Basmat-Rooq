<?php
// app/Auth/SessionManager.php

require_once dirname(__DIR__) . '/Config/Config.php';
require_once dirname(__DIR__) . '/Config/Database.php';
require_once dirname(__DIR__) . '/Helpers/Security.php';
require_once dirname(__DIR__) . '/Helpers/RateLimiter.php'; // Load RateLimiter

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SessionManager {
    private $db;
    private $limiter;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->limiter = new RateLimiter(); // Initialize Limiter
    }

    /**
     * Secure Login with Rate Limiting
     * Returns TRUE on success, or throws Exception with error message on failure.
     */
    // --- 1. ADD THIS NEW METHOD ---
    private function logActivity($username, $ip, $activity) {
        try {
            // Insert the activity record into the database
            $sql = "INSERT INTO login_attempts (ip_address, username, activity) VALUES (:ip, :user, :activity)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':ip' => $ip, 
                ':user' => $username, 
                ':activity' => $activity
            ]);
        } catch (PDOException $e) {
            // Silently fail so a logging error doesn't stop the user from logging in
            error_log("Activity Log Error: " . $e->getMessage());
        }
    }

    // --- 2. UPDATE YOUR LOGIN METHOD ---
    public function login($username, $password, $csrf_token) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $clean_user = Security::clean($username);

        // Check Rate Limit
        if ($this->limiter->isLocked($ip)) {
            $this->logActivity($clean_user, $ip, "Blocked: IP Locked due to too many failed attempts");
            throw new Exception("Security Alert: Too many failed attempts. Your IP is locked for 15 minutes.");
        }

        Security::checkCSRF($csrf_token);

        try {
            // --- CHECK ADMIN / STAFF ---
            $query = "SELECT id, username, password, role, is_active, full_name FROM users WHERE username = :user LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user', $clean_user);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // INTERNAL (ADMIN/STAFF) LOGIN CHECK
            if ($user && password_verify($password, $user['password'])) {
                if (isset($user['is_active']) && $user['is_active'] == 0) {
                    throw new Exception("Security Alert: Account deactivated.");
                }
                $this->createSession($user, 'internal');
                $this->limiter->reset($ip);
                
                // NEW: Record the Login
                \Security::logActivity("User Logged In");
                return true;
            }

            // --- CHECK CLIENT ---
            $query2 = "SELECT account_id, client_id, username, password_hash, is_active FROM client_accounts WHERE username = :user LIMIT 1";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bindParam(':user', $clean_user);
            $stmt2->execute();
            $client = $stmt2->fetch(PDO::FETCH_ASSOC);

            if ($client && password_verify($password, $client['password_hash'])) {
                $this->createSession($client, 'client');
                $this->limiter->reset($ip);
                
                // NEW: Record the Login
                \Security::logActivity("Client Logged In");
                return true;
            }

            // --- LOGIN FAILED ---
            $error_msg = $this->limiter->increment($ip);
            
            // LOG FAILURE
            $this->logActivity($clean_user, $ip, "Failed: Invalid Credentials");
            throw new Exception($error_msg);

        } catch (PDOException $e) {
            error_log("Auth Error: " . $e->getMessage());
            throw new Exception("System error occurred.");
        }
    }
    
    private function createSession($data, $type) {
        if ($type === 'internal') {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['user_type'] = 'internal';
            // NEW: Save full name (fallback to username if empty)
            $_SESSION['full_name'] = !empty($data['full_name']) ? $data['full_name'] : $data['username'];
        } else {
            $_SESSION['user_id'] = $data['account_id'];
            $_SESSION['client_id'] = $data['client_id'];
            $_SESSION['role'] = 'client';
            $_SESSION['user_type'] = 'external';
            // Clients might not have full_name in accounts table, so default to username
            $_SESSION['full_name'] = $data['username'];
        }
        $_SESSION['username'] = $data['username'];
        $_SESSION['last_regen'] = time();
        session_regenerate_id(true);
    }
    
    // ... logout and other methods remain the same



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