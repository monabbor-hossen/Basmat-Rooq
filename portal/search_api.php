<?php
// portal/search_api.php

// 1. SILENCE HTML ERRORS (Crucial Fix)
error_reporting(E_ALL);        // Report all errors internally
ini_set('display_errors', 0);  // Do NOT send HTML errors to the browser
ini_set('log_errors', 1);      // Log errors to file instead

// 2. Set Header to JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // 3. Load Dependencies with Checks
    $configPath = __DIR__ . '/../app/Config/Config.php';
    $dbPath     = __DIR__ . '/../app/Config/Database.php';

    if (!file_exists($configPath)) throw new Exception("Configuration file missing.");
    if (!file_exists($dbPath))     throw new Exception("Database file missing.");

    require_once $configPath;
    require_once $dbPath;

    // 4. Check Session
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]); // Return empty array if not logged in
        exit;
    }

    // 5. Get Search Term
    $term = $_GET['term'] ?? '';
    if (strlen($term) < 2) {
        echo json_encode([]);
        exit;
    }

    // 6. Database Query
    $db = (new Database())->getConnection();
    
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

    // 7. Format Data
    foreach ($results as &$row) {
        // Calculate Due
        $contract = floatval($row['contract_value'] ?? 0);
        $paid     = floatval($row['total_paid'] ?? 0);
        $row['due_val'] = $contract - $paid;
        
        // Clean strings (prevent broken JSON characters)
        array_walk_recursive($row, function(&$item){
            if(is_string($item)) {
                // Remove newlines/tabs that break JSON
                $item = preg_replace('/[\x00-\x1F\x7F]/u', '', $item);
                $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
            }
        });
    }

    // 8. Return Valid JSON
    echo json_encode($results);

} catch (Throwable $e) {
    // 9. Catch ALL Errors (Fatal or Logic) and return JSON
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}