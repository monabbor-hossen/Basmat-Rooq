<?php
// portal/toggle_status_api.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
$type = $data['type'] ?? ''; // 'user' or 'client'
$status = intval($data['status'] ?? 0);

if (!$id || !in_array($type, ['user', 'client'])) {
    exit(json_encode(['success' => false, 'error' => 'Invalid data']));
}

$db = (new Database())->getConnection();

try {
    if ($type === 'user') {
        $stmt = $db->prepare("UPDATE users SET status = :st WHERE id = :id");
    } else {
        $stmt = $db->prepare("UPDATE client_accounts SET status = :st WHERE account_id = :id");
    }
    
    $stmt->execute([':st' => $status, ':id' => $id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}