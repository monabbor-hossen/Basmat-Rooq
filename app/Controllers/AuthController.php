<?php
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Helpers/Security.php';

class AuthController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function login($username, $password, $csrf_token) {
        // 1. Check Security Token
        Security::checkCSRF($csrf_token);

        // 2. Sanitize Input
        $username = Security::clean($username);

        // 3. Check User in Database
        $query = "SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            // 4. Verify Password
            if (password_verify($password, $row['password'])) {
                // Prevent Session Hijacking
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];
                
                // Success: Send to Dashboard
                header("Location: " . BASE_URL . "/portal/dashboard");
                exit;
            }
        }

        // Failure: Send back to Login with Error
        $_SESSION['error'] = "Invalid Username or Password";
        header("Location: " . BASE_URL . "/login");
        exit;
    }
}
?>