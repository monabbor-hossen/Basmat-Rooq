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
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];
                
                // SUCCESS: Redirect to the specific dashboard file
                header("Location: " . BASE_URL . "/portal/dashboard.php");
                exit;
            }
        }

        // FAILURE: Set error message and go back
        $_SESSION['error'] = "Username or Password not valid"; // Exact message you wanted
        header("Location: " . BASE_URL . "/login");
        exit;
    }
}
?>