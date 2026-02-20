<?php
// portal/toggle_status_api.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) session_start();

// Security Check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'] ?? '';
$id = $data['id'] ?? 0;
$status = $data['status'] ? 1 : 0;

if (!$type || !$id) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

try {
    $db = (new Database())->getConnection();
    
    if ($type === 'user') {
        // Prevent disabling yourself
        if ($id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot deactivate your own account.']);
            exit;
        }
        $sql = "UPDATE users SET is_active = :status WHERE id = :id";
    } elseif ($type === 'client') {
        $sql = "UPDATE client_accounts SET is_active = :status WHERE account_id = :id";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid type']);
        exit;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute([':status' => $status, ':id' => $id]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}