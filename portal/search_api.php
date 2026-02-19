<?php
// portal/search_api.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) exit(json_encode([]));

$term = $_GET['term'] ?? '';
if (strlen($term) < 2) exit(json_encode([]));

$db = (new Database())->getConnection();

// Search Clients
$sql = "SELECT c.*, 
        w.hire_foreign_company, w.misa_application, w.sbc_application, 
        w.article_association, w.qiwa, w.muqeem, w.gosi, w.chamber_commerce,
        COALESCE((SELECT SUM(amount) FROM payments WHERE client_id = c.client_id AND payment_status = 'Completed'), 0) as total_paid
        FROM clients c 
        LEFT JOIN workflow_tracking w ON c.client_id = w.client_id
        WHERE c.company_name LIKE :s 
           OR c.client_name LIKE :s 
           OR c.email LIKE :s 
           OR c.client_id = :sid 
        LIMIT 5";

$stmt = $db->prepare($sql);
$stmt->execute([':s' => "%$term%", ':sid' => intval($term)]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format for JSON
foreach ($results as &$row) {
    // Calculate Due
    $row['due_val'] = $row['contract_value'] - $row['total_paid'];
    
    // Clean strings
    array_walk_recursive($row, function(&$item){
        if(is_string($item)) $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    });
}

header('Content-Type: application/json');
echo json_encode($results);