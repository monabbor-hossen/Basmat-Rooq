<?php
// chat.php (Universal - works in both portal/ and management/ folders)

// Auto-detect which folder we are in to load the right header
$is_client_folder = strpos($_SERVER['SCRIPT_NAME'], '/management/') !== false;
if ($is_client_folder) {
    require_once '../portal/includes/header.php';
} else {
    require_once 'includes/header.php';
}
require_once __DIR__ . '/../app/Config/Database.php';

$db = (new Database())->getConnection();

// 1. SMART FETCHING
if ($_SESSION['role'] === 'client') {
    $account_id = $_SESSION['account_id'] ?? $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT client_id, company_name FROM clients WHERE account_id = ? AND is_active = 1 ORDER BY company_name ASC");
    $stmt->execute([$account_id]);
} else {
    $stmt = $db->prepare("SELECT client_id, company_name FROM clients WHERE is_active = 1 ORDER BY company_name ASC");
    $stmt->execute();
}
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$active_client = $_GET['client_id'] ?? ($clients[0]['client_id'] ?? 0);

// Security Check
$owns_project = false;
foreach($clients as $c) { if($c['client_id'] == $active_client) $owns_project = true; }
if (!$owns_project && count($clients) > 0) $active_client = $clients[0]['client_id'];

$is_mobile_chat_active = isset($_GET['client_id']) ? true : false;
$active_name = '';
foreach($clients as $c) { if($c['client_id'] == $active_client) $active_name = $c['company_name']; }
?>

<div class="container-fluid py-4 h-100 chat-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-white fw-bold mb-0"><i class="bi bi-chat-dots text-gold me-2"></i>Support Messages</h3>
    </div>
    
    <div class="row g-0 rounded overflow-hidden chat-container-box" style="border: 1px solid rgba(255,255,255,0.1);">
        
        <div id="chatSidebarList" class="col-md-4 bg-dark bg-opacity-75 border-end border-light border-opacity-10 overflow-auto h-100 <?php echo $is_mobile_chat_active ? 'd-none d-md-block' : 'd-block'; ?>">
            <div class="p-3 border-bottom border-light border-opacity-10 text-gold fw-bold position-sticky top-0 bg-dark z-1">
                Active Projects
            </div>
            <div class="list-group list-group-flush bg-transparent pb-5">
                <?php if (count($clients) > 0): ?>
                    <?php foreach ($clients as $c): 
                        $is_active = ($c['client_id'] == $active_client) ? 'bg-rooq-primary text-white' : 'text-white-50 hover-white';
                    ?>
                        <a href="#" onclick="switchChat(event, <?php echo $c['client_id']; ?>, '<?php echo htmlspecialchars(addslashes($c['company_name']), ENT_QUOTES); ?>', this)" class="list-group-item client-chat-link bg-transparent <?php echo $is_active; ?> py-3 border-bottom border-light border-opacity-10 d-flex align-items-center">
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

        <div id="chatMainBox" class="col-md-8 flex-column h-100 <?php echo $is_mobile_chat_active ? 'd-flex' : 'd-none d-md-flex'; ?>" style="background: rgba(0,0,0,0.3);">
            <?php if ($active_client): ?>
                
                <div class="p-3 border-bottom border-light border-opacity-10 d-flex justify-content-between align-items-center bg-dark z-1" style="background: rgba(128,0,32,0.3) !important;">
                    <div class="d-flex align-items-center">
                        <a href="#" onclick="closeMobileChat(event)" class="text-white me-3 d-md-none text-decoration-none">
                            <i class="bi bi-arrow-left fs-3"></i>
                        </a>
                        <div>
                            <h6 class="text-white fw-bold mb-0">Conversation History</h6>
                            <small id="chatHeaderSub" class="text-gold text-truncate d-block" style="max-width: 200px;"><?php echo htmlspecialchars($active_name); ?></small>
                        </div>
                    </div>
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
.chat-container-box { height: calc(100vh - 200px); min-height: 500px; }
@media (max-width: 767.98px) {
    .chat-container-box { height: calc(100vh - 160px); border: none !important; }
    .portal-wrapper main { padding: 0 !important; }
    /*.chat-wrapper { padding-left: 0 !important; padding-right: 0 !important; }*/
}
</style>
<script>
// 1. Safely handle empty client IDs
let currentClientId = <?php echo $active_client ? $active_client : 0; ?>;
let lastChatHTML = "INITIAL_LOAD";

// --- SPA CHAT SWITCHER (NO PAGE RELOAD) ---
function switchChat(e, id, name, element) {
    e.preventDefault();
    if(currentClientId === id) return;
    
    currentClientId = id;
    lastChatHTML = "FORCE_REFRESH";
    
    document.querySelectorAll('.client-chat-link').forEach(el => {
        el.classList.remove('bg-rooq-primary', 'text-white');
        el.classList.add('text-white-50', 'hover-white');
    });
    element.classList.remove('text-white-50', 'hover-white');
    element.classList.add('bg-rooq-primary', 'text-white');
    
    const headerSub = document.getElementById('chatHeaderSub');
    if(headerSub) headerSub.innerText = name;
    
    const box = document.getElementById('chatBox');
    if(box) box.innerHTML = "<div class='text-center text-white-50 mt-5'><div class='spinner-border spinner-border-sm me-2'></div> Loading messages...</div>";
    
    if (window.innerWidth < 768) {
        document.getElementById('chatSidebarList').classList.remove('d-block');
        document.getElementById('chatSidebarList').classList.add('d-none');
        document.getElementById('chatMainBox').classList.remove('d-none');
        document.getElementById('chatMainBox').classList.add('d-flex');
    }
    
    loadChats();
}

function closeMobileChat(e) {
    e.preventDefault();
    document.getElementById('chatMainBox').classList.remove('d-flex');
    document.getElementById('chatMainBox').classList.add('d-none');
    document.getElementById('chatSidebarList').classList.remove('d-none');
    document.getElementById('chatSidebarList').classList.add('d-block');
}

// --- STANDARD CHAT FUNCTIONS ---
function loadChats() {
    if (currentClientId === 0) return;
    
    fetch(`../app/Api/fetch_chats.php?client_id=${currentClientId}`)
    .then(r => r.text())
    .then(html => {
        let content = html.trim();
        
        // If the database returns completely blank, force it to show the empty message
        if (content === "") {
            content = "<div class='text-center text-white-50 mt-5'>No messages yet. Start the conversation!</div>";
        }
        
        if (content !== lastChatHTML) {
            const box = document.getElementById('chatBox');
            if(!box) return;
            
            const isScrolledToBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 100;
            box.innerHTML = content;
            if (isScrolledToBottom) box.scrollTop = box.scrollHeight;
            
            lastChatHTML = content;
        }
    });
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg || currentClientId === 0) return;
    
    input.value = ''; 
    input.style.height = '48px'; 

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
    box.scrollTop = box.scrollHeight; 
    lastChatHTML = "FORCE_REFRESH"; 

    fetch('../app/Api/send_chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ client_id: currentClientId, message: msg })
    })
    .then(r => {
        if (!r.ok) throw new Error("Failed to send message");
        loadChats();
    })
    .catch(err => {
        console.error("Send Error:", err);
        alert("Message failed to send. Check your internet or server.");
    });
}

// Ensure the page actually starts the loader instantly!
if (currentClientId !== 0) {
    loadChats(); 
    setInterval(loadChats, 3000); 
    
    document.addEventListener("DOMContentLoaded", () => {
        const chatInputBox = document.getElementById('chatInput');
        if(chatInputBox) {
            chatInputBox.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
    });
} else {
    // If there is no client selected, instantly hide the loading spinner
    document.addEventListener("DOMContentLoaded", () => {
        const box = document.getElementById('chatBox');
        if(box) box.innerHTML = "<div class='text-center text-white-50 mt-5'>Please select a project from the sidebar to start chatting.</div>";
    });
}
</script>
<?php 
if ($is_client_folder) { require_once '../portal/includes/footer.php'; } 
else { require_once 'includes/footer.php'; }
?>