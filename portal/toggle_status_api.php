<?php
// portal/toggle_status_api.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Security.php'; // <-- THIS WAS MISSING!

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
        // Disables the entire Master Account
        $sql = "UPDATE client_accounts SET is_active = :status WHERE account_id = :id";
        
    } elseif ($type === 'license') {
        // Disables ONLY the specific license/application
        $sql = "UPDATE clients SET is_active = :status WHERE client_id = :id";
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid type']);
        exit;
    }
    
    // Execute the database update
    $stmt = $db->prepare($sql);
    $stmt->execute([':status' => $status, ':id' => $id]);

    // NEW: Log the status change securely
    $action_text = $status ? "Activated" : "Deactivated";
    Security::logActivity($action_text . " login access for " . $type . " ID: #" . $id);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'System error']);
}