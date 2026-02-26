<?php
// app/Api/send_chat.php
require_once __DIR__ . '/../Config/Config.php';
require_once __DIR__ . '/../Config/Database.php';

// Manually load PHPMailer (since we know this works!)
require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) session_start();

$data = json_decode(file_get_contents("php://input"), true);
$client_id = $data['client_id'] ?? 0;
$message_text = trim($data['message'] ?? '');

if (!$client_id || empty($message_text) || !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data.']);
    exit;
}

$sender_type = ($_SESSION['role'] === 'client') ? 'client' : (($_SESSION['role'] == '1') ? 'staff' : 'admin');
$sender_id = $_SESSION['user_id'];

try {
    $db = (new Database())->getConnection();

    // 1. Save to DB
    $stmt = $db->prepare("INSERT INTO chat_messages (client_id, sender_type, sender_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$client_id, $sender_type, $sender_id, $message_text]);

    // 2. Get Client Data for Emails
    $stmtClient = $db->prepare("SELECT company_name, email FROM clients WHERE client_id = ?");
    $stmtClient->execute([$client_id]);
    $client_info = $stmtClient->fetch(PDO::FETCH_ASSOC);

    // 3. Send Email Notification
    try {
        $mail = new PHPMailer(true);
        // NOTE: We intentionally DO NOT include SMTPDebug here, because it breaks the JSON response!
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        $mail->Username   = 'pagolrea@gmail.com'; // Your Gmail
        $mail->Password   = 'qdvgevktmxduryca'; // Put your 16-digit App Password here!
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // XAMPP Bypass
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('pagolrea@gmail.com', 'Basmat Rooq Portal');

        if ($sender_type === 'client') {
            $mail->addAddress('pagolrea@gmail.com'); // Admin receives it
            $mail->Subject = 'New Chat Message from ' . $client_info['company_name'];
            $mail->Body    = "Hello Team,\n\nNew message regarding {$client_info['company_name']}:\n\n\"" . substr($message_text, 0, 100) . "...\"\n\nLog in to reply.";
        } else {
            if (!empty($client_info['email'])) {
                $mail->addAddress($client_info['email']); // Client receives it
                $mail->Subject = 'Basmat Rooq: New Message Received';
                $mail->Body    = "Hello {$client_info['company_name']},\n\nOur team has replied to your message. Please log in to your Basmat Rooq portal to view it.\n\nThank you.";
            }
        }
        if (count($mail->getAllRecipientAddresses()) > 0) $mail->send();
    } catch (Exception $e) { 
        // We let this fail silently so the chat still works even if the email doesn't send
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB Error.']);
}