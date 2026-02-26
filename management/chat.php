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
<div class="container-fluid py-4 h-100">
    <h3 class="text-white fw-bold mb-4"><i class="bi bi-chat-dots text-gold me-2"></i>Support Messages</h3>
    
    <div class="row g-0 rounded overflow-hidden" style="height: 70vh; border: 1px solid rgba(255,255,255,0.1);">
        <div class="col-md-4 bg-dark bg-opacity-75 border-end border-light border-opacity-10 overflow-auto h-100">
            <div class="p-3 border-bottom border-light border-opacity-10 text-gold fw-bold">Active Projects</div>
            <div class="list-group list-group-flush bg-transparent">
                <?php foreach ($clients as $c): 
                    $is_active = ($c['client_id'] == $active_client) ? 'bg-rooq-primary text-white' : 'text-white-50 hover-white';
                ?>
                    <a href="chat.php?client_id=<?php echo $c['client_id']; ?>" class="list-group-item bg-transparent <?php echo $is_active; ?> py-3 border-bottom border-light border-opacity-10">
                        <i class="bi bi-building me-2"></i><?php echo htmlspecialchars($c['company_name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-8 d-flex flex-column h-100" style="background: rgba(0,0,0,0.3);">
            <?php if ($active_client): ?>
                <div class="p-3 border-bottom border-light border-opacity-10 d-flex justify-content-between" style="background: rgba(128,0,32,0.3);">
                    <h5 class="text-white fw-bold mb-0">Conversation History</h5>
                    <a href="project-details.php?id=<?php echo $active_client; ?>" class="btn btn-sm btn-outline-light rounded-pill">View Project</a>
                </div>
                
                <div id="chatBox" class="flex-grow-1 p-4 overflow-auto d-flex flex-column">
                    <div class="text-center text-white-50"><div class="spinner-border spinner-border-sm me-2"></div> Loading chats...</div>
                </div>

                <div class="p-3 border-top border-light border-opacity-10 bg-dark">
                    <div class="input-group">
                        <textarea id="chatInput" class="form-control glass-input text-white" placeholder="Type your reply here..." rows="2" style="resize:none;"></textarea>
                        <button onclick="sendMessage()" class="btn btn-rooq-primary px-4 fw-bold"><i class="bi bi-send-fill fs-5"></i></button>
                    </div>
                </div>
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center h-100 text-white-50">Select a project to start chatting.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
const currentClientId = <?php echo $active_client; ?>;
let lastChatHTML = ""; // This prevents the screen from flashing every 3 seconds!

function loadChats() {
    if (!currentClientId) return;
    
    fetch(`../app/Api/fetch_chats.php?client_id=${currentClientId}`)
    .then(r => r.text())
    .then(html => {
        // ONLY update the screen if someone actually sent a new message
        if (html !== lastChatHTML) {
            const box = document.getElementById('chatBox');
            
            // Figure out if the user is currently reading old messages up top
            const isScrolledToBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 100;
            
            // Draw the new messages
            box.innerHTML = html || "<div class='text-center text-white-50 mt-5'>No messages yet. Start the conversation!</div>";
            
            // If they were already at the bottom, automatically pull them down to see the new message!
            if (isScrolledToBottom) {
                box.scrollTop = box.scrollHeight;
            }
            
            // Save this HTML so we don't redraw it until a new message arrives
            lastChatHTML = html;
        }
    });
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg || !currentClientId) return;
    
    input.value = ''; // Clear text box instantly

    // OPTIMISTIC UI: Draw it instantly so the user doesn't wait for the server
    const box = document.getElementById('chatBox');
    if (box.innerHTML.includes("No messages yet")) box.innerHTML = '';
    
    const tempBubble = `
        <div class='mb-3 w-75 align-self-end text-end temp-msg'>
            <div class='small text-white-50 fw-bold mb-1 fst-italic'>Sending...</div>
            <div class='p-3 shadow-sm' style='background: #800020; color: #fff; border-radius: 15px 15px 2px 15px; display: inline-block; text-align: left; opacity: 0.8;'>
                ${msg.replace(/\n/g, '<br>')}
            </div>
        </div>`;
    
    box.insertAdjacentHTML('beforeend', tempBubble);
    box.scrollTop = box.scrollHeight; // Force scroll to bottom
    
    // Temporarily mess up lastChatHTML so the next auto-load is forced to refresh and show the real timestamp
    lastChatHTML = "FORCE_REFRESH"; 

    // Send to backend
    fetch('../app/Api/send_chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ client_id: currentClientId, message: msg })
    })
    .then(() => loadChats()) // Immediately load the real chat once the email sends
    .catch(err => console.error("Send Error:", err));
}

// WHEN THE PAGE LOADS:
if (currentClientId) {
    document.addEventListener("DOMContentLoaded", () => {
        loadChats(); // Load immediately on open
        
        // 🔥 THE MAGIC SAUCE: Auto-check for new messages every 3 seconds! 🔥
        setInterval(loadChats, 3000); 
    });
}
</script>
<?php require_once '../portal/includes/footer.php'; ?>