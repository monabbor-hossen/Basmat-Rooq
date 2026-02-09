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
        // 1. Security: Check CSRF Token
        Security::checkCSRF($csrf_token);

        // 2. Security: Sanitize Input
        $username = Security::clean($username);

        // 3. Database Lookup (Prepared Statement)
        $query = "SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            // 4. Security: Verify Hash (Never compare plain text!)
            if (password_verify($password, $row['password'])) {
                
                // 5. Security: Prevent Session Fixation
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['last_activity'] = time();

                // Redirect based on role
                $redirect = ($row['role'] === 'admin') ? '/management/dashboard' : '/portal/dashboard';
                header("Location: " . BASE_URL . $redirect);
                exit;
            }
        }

        // Generic Error (Don't tell them if the username exists or not)
        $_SESSION['error'] = ($username == '') ? "Username is required." : "Invalid credentials.";
        header("Location: " . BASE_URL . "/login");
        exit;
    }
}
?>