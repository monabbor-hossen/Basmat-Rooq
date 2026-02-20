<?php
// portal/update_status_api.php

require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';

// Ensure user is logged in and has appropriate permissions (e.g., Admin)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

    if ($id <= 0 || !in_array($type, ['user', 'client'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
        exit;
    }

    $db = (new Database())->getConnection();

    try {
        if ($type === 'user') {
            $query = "UPDATE users SET is_active = :status WHERE id = :id";
        } else {
            // Assuming the ID passed is the account_id or client_id in client_accounts
            $query = "UPDATE client_accounts SET is_active = :status WHERE account_id = :id"; 
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        error_log("Status Update Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>