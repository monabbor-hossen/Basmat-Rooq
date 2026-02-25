<?php
// portal/log_activity_api.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Security check - Must be logged in
if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['action']) && !empty($data['action'])) {
    $action = Security::clean($data['action']);
    Security::logActivity($action);
}
?>