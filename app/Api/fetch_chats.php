<?php
// app/Api/fetch_chats.php<?php
// app/Api/fetch_chats.php
require_once __DIR__ . '/../Config/Config.php'; // ADD THIS LINE!
require_once __DIR__ . '/../Config/Database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$client_id = $_GET['client_id'] ?? 0;
if (!$client_id || !isset($_SESSION['user_id'])) exit;

$viewer_type = ($_SESSION['role'] === 'client') ? 'client' : 'internal';

try {
    $db = (new Database())->getConnection();
    
    // Fetch chats & mark as read
    if ($viewer_type === 'internal') {
        $db->prepare("UPDATE chat_messages SET is_read = 1 WHERE client_id = ? AND sender_type = 'client'")->execute([$client_id]);
    } else {
        $db->prepare("UPDATE chat_messages SET is_read = 1 WHERE client_id = ? AND sender_type IN ('admin', 'staff')")->execute([$client_id]);
    }

    $stmt = $db->prepare("
        SELECT c.*, u.full_name as internal_name, cl.company_name as client_name 
        FROM chat_messages c
        LEFT JOIN users u ON c.sender_id = u.id AND c.sender_type IN ('admin', 'staff')
        LEFT JOIN clients cl ON c.sender_id = cl.client_id AND c.sender_type = 'client'
        WHERE c.client_id = ? ORDER BY c.created_at ASC
    ");
    $stmt->execute([$client_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '';
    foreach ($messages as $msg) {
        $is_me = ($viewer_type === 'client' && $msg['sender_type'] === 'client') || ($viewer_type === 'internal' && in_array($msg['sender_type'], ['admin', 'staff']));
        
        $align = $is_me ? 'align-self-end text-end' : 'align-self-start';
        $bg_color = $is_me ? 'background: #800020; color: #fff;' : 'background: rgba(255,255,255,0.05); color: #fff; border-left: 3px solid #D4AF37;';
        $border_radius = $is_me ? 'border-radius: 15px 15px 2px 15px;' : 'border-radius: 15px 15px 15px 2px;';
        $time = date('M d, h:i A', strtotime($msg['created_at']));
        
        $sender_name = '';
        if (!$is_me) {
            $name = ($msg['sender_type'] === 'client') ? $msg['client_name'] : ($msg['internal_name'] ?? 'Basmat Rooq Team');
            $sender_name = "<div class='small text-gold fw-bold mb-1'>{$name}</div>";
        }

        $html .= "
            <div class='mb-3 w-75 {$align}'>
                {$sender_name}
                <div class='p-3 shadow-sm' style='{$bg_color} {$border_radius} display: inline-block; text-align: left;'>
                    " . nl2br(htmlspecialchars($msg['message'])) . "
                </div>
                <div class='small text-white-50 mt-1' style='font-size: 0.7rem;'>{$time}</div>
            </div>";
    }
    echo $html;
} catch (PDOException $e) { }