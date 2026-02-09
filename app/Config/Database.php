<?php
class Database {
    private $host = "localhost";
    private $db_name = "basmat_rooq_db"; // Update with your DB name
    private $username = "root";          // Update with your DB user
    private $password = "";              // Update with your DB pass
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Security: Exception Error Mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Security: Disable Emulation (Forces Real Prepared Statements)
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // Security: Never show raw SQL errors to users
            error_log("Connection error: " . $exception->getMessage());
            die("System Error. Please contact the administrator.");
        }
        return $this->conn;
    }
}
?>