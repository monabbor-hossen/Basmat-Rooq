<?php
// portal/chat.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$db = (new Database())->getConnection();

// Fetch all active client applications
$stmt = $db->prepare("SELECT client_id, company_name FROM clients WHERE is_active = 1 ORDER BY company_name ASC");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$active_client = $_GET['client_id'] ?? ($clients[0]['client_id'] ?? 0);
?><div class="container-fluid py-4 h-100 chat-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-white fw-bold mb-0"><i class="bi bi-chat-dots text-gold me-2"></i>Support Messages</h3>
    </div>
    
    <div class="row g-0 rounded overflow-hidden chat-container-box" style="border: 1px solid rgba(255,255,255,0.1);">
        
        <div class="col-md-4 bg-dark bg-opacity-75 border-end border-light border-opacity-10 overflow-auto h-100 <?php echo $is_mobile_chat_active ? 'd-none d-md-block' : ''; ?>">
            <div class="p-3 border-bottom border-light border-opacity-10 text-gold fw-bold position-sticky top-0 bg-dark z-1">
                Active Projects
            </div>
            <div class="list-group list-group-flush bg-transparent pb-5">
                <?php if (count($clients) > 0): ?>
                    <?php foreach ($clients as $c): 
                        $is_active = ($c['client_id'] == $active_client) ? 'bg-rooq-primary text-white' : 'text-white-50 hover-white';
                    ?>
                        <a href="chat.php?client_id=<?php echo $c['client_id']; ?>" class="list-group-item bg-transparent <?php echo $is_active; ?> py-3 border-bottom border-light border-opacity-10 d-flex align-items-center">
                            <div class="avatar-circle-refined bg-dark text-gold border border-gold me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <?php echo strtoupper(substr($c['company_name'], 0, 1)); ?>
                            </div>
                            <div class="text-truncate">
                                <h6 class="mb-0 fw-bold text-truncate"><?php echo htmlspecialchars($c['company_name']); ?></h6>
                                <small class="opacity-75">Tap to view messages</small>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-white-50">No active projects found.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8 flex-column h-100 <?php echo $is_mobile_chat_active ? 'd-flex' : 'd-none d-md-flex'; ?>" style="background: rgba(0,0,0,0.3);">
            <?php if ($active_client): ?>
                
                <div class="p-3 border-bottom border-light border-opacity-10 d-flex justify-content-between align-items-center bg-dark z-1" style="background: rgba(128,0,32,0.3) !important;">
                    <div class="d-flex align-items-center">
                        <a href="chat.php" class="text-white me-3 d-md-none text-decoration-none">
                            <i class="bi bi-arrow-left fs-3"></i>
                        </a>
                        <div>
                            <h6 class="text-white fw-bold mb-0">Conversation History</h6>
                            <?php 
                                // Find the active company name for the header
                                $active_name = '';
                                foreach($clients as $c) { if($c['client_id'] == $active_client) $active_name = $c['company_name']; }
                            ?>
                            <small class="text-gold text-truncate d-block" style="max-width: 200px;"><?php echo htmlspecialchars($active_name); ?></small>
                        </div>
                    </div>
                    <a href="project-details.php?id=<?php echo $active_client; ?>" class="btn btn-sm btn-outline-light rounded-pill d-none d-sm-block">View Project</a>
                </div>
                
                <div id="chatBox" class="flex-grow-1 p-3 p-md-4 overflow-auto d-flex flex-column" style="scroll-behavior: smooth;">
                    <div class="text-center text-white-50 mt-5"><div class="spinner-border spinner-border-sm me-2"></div> Loading chats...</div>
                </div>

                <div class="p-2 p-md-3 border-top border-light border-opacity-10 bg-dark mt-auto z-1">
                    <div class="input-group align-items-end glass-search p-1 rounded-pill">
                        <textarea id="chatInput" class="form-control bg-transparent border-0 text-white shadow-none ps-3 py-2" placeholder="Type your message..." rows="1" style="resize:none; max-height: 100px; overflow-y: auto;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        <button onclick="sendMessage()" class="btn btn-rooq-primary rounded-circle m-1 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; flex-shrink: 0;">
                            <i class="bi bi-send-fill fs-5"></i>
                        </button>
                    </div>
                </div>

            <?php else: ?>
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-white-50 p-5 text-center">
                    <i class="bi bi-chat-square-dots text-gold mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                    <h5>No Conversation Selected</h5>
                    <p class="small">Choose a project from the sidebar to view or send messages.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Responsive Chat Heights */
.chat-container-box {
    height: calc(100vh - 200px); /* Desktop default */
    min-height: 500px;
}
/* Mobile Specific Adjustments */
@media (max-width: 767.98px) {
    .chat-container-box {
        height: calc(100vh - 160px); /* Taller on mobile to fit screen */
        border: none !important; /* Remove borders on mobile for edge-to-edge feel */
    }
    .portal-wrapper main {
        padding: 0 !important; /* Remove main container padding on mobile for full width */
    }
    .chat-wrapper {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
}
</style>
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
<?php require_once 'includes/footer.php'; ?>