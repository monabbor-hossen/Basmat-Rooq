<?php
// portal/search_api.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

// 1. Security Check
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit(json_encode(['error' => 'Unauthorized']));
}

$term = $_GET['term'] ?? '';
if (strlen($term) < 2) exit(json_encode([])); // Only search if 2+ chars

$db = (new Database())->getConnection();

// 2. Search Query
$sql = "SELECT c.*, 
        w.hire_foreign_company, w.misa_application, w.sbc_application, 
        w.article_association, w.qiwa, w.muqeem, w.gosi, w.chamber_commerce,
        w.license_scope_status,
        COALESCE((SELECT SUM(amount) FROM payments WHERE client_id = c.client_id AND payment_status = 'Completed'), 0) as total_paid
        FROM clients c 
        LEFT JOIN workflow_tracking w ON c.client_id = w.client_id
        WHERE c.company_name LIKE :s 
           OR c.client_name LIKE :s 
           OR c.email LIKE :s 
           OR c.client_id = :sid 
        LIMIT 10";

$stmt = $db->prepare($sql);
$stmt->execute([':s' => "%$term%", ':sid' => intval($term)]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Process Data (Add Calculations)
$final_results = [];
foreach ($results as $row) {
    // Calculate Progress
    $steps = [$row['hire_foreign_company'], $row['misa_application'], $row['sbc_application'], 
              $row['article_association'], $row['qiwa'], $row['muqeem'], $row['gosi'], $row['chamber_commerce']];
    $approved = 0;
    foreach($steps as $s) if($s === 'Approved') $approved++;
    $row['progress_val'] = round(($approved / 8) * 100);
    $row['approved_count'] = $approved;

    // Calculate Due
    $row['due_val'] = $row['contract_value'] - $row['total_paid'];
    
    // Clean strings for JSON
    array_walk_recursive($row, function(&$item){
        if(is_string($item)) $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    });

    $final_results[] = $row;
}

header('Content-Type: application/json');
echo json_encode($final_results);