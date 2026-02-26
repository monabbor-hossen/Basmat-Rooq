<?php
// management/chat.php
require_once '../portal/includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$db = (new Database())->getConnection();
$account_id = $_SESSION['account_id'] ?? $_SESSION['user_id']; 

// Fetch ONLY this client's active projects
$stmt = $db->prepare("SELECT client_id, company_name FROM clients WHERE account_id = ? AND is_active = 1 ORDER BY company_name ASC");
$stmt->execute([$account_id]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$active_client = $_GET['client_id'] ?? ($clients[0]['client_id'] ?? 0);

// Security Check: Make sure the selected client_id actually belongs to this account
$owns_project = false;
foreach($clients as $c) { if($c['client_id'] == $active_client) $owns_project = true; }
if (!$owns_project && count($clients) > 0) $active_client = $clients[0]['client_id'];

?>