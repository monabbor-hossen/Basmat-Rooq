<?php
// portal/client-edit.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";
$client_id = $_GET['id'] ?? null;
if (!$client_id) { header("Location: clients.php"); exit(); }

$db = (new Database())->getConnection();

// --- HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);
    
    // Basic Info
    $company = Security::clean($_POST['company_name']);
    $client  = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    $trade   = Security::clean($_POST['trade_name_application']);
    $value   = floatval($_POST['contract_value']);

    try {
        $db->beginTransaction();

        // 1. Update Client
        $stmt = $db->prepare("UPDATE clients SET company_name=?, client_name=?, phone_number=?, email=?, trade_name_application=?, contract_value=? WHERE client_id=?");
        $stmt->execute([$company, $client, $phone, $email, $trade, $value, $client_id]);

        // 2. Update Workflow (Statuses + Notes)
        $wf_data = [
            'scope_st' => $_POST['status_scope'], 'scope_note' => $_POST['note_scope'],
            'hire_st' => $_POST['status_hire'],   'hire_note' => $_POST['note_hire'],
            'misa_st' => $_POST['status_misa'],   'misa_note' => $_POST['note_misa'],
            'sbc_st' => $_POST['status_sbc'],     'sbc_note' => $_POST['note_sbc'],
            'art_st' => $_POST['status_article'], 'art_note' => $_POST['note_article'],
            'gosi_st' => $_POST['status_gosi'],   'gosi_note' => $_POST['note_gosi'],
            'qiwa_st' => $_POST['status_qiwa'],   'qiwa_note' => $_POST['note_qiwa'],
            'muqeem_st' => $_POST['status_muqeem'], 'muqeem_note' => $_POST['note_muqeem'],
            'coc_st' => $_POST['status_coc'],     'coc_note' => $_POST['note_coc'],
            'update_at' => date('Y-m-d H:i:s'),
            'cid' => $client_id
        ];

        // Check exist
        $check = $db->prepare("SELECT id FROM workflow_tracking WHERE client_id = ?");
        $check->execute([$client_id]);
        
        if ($check->rowCount() > 0) {
            $sql_wf = "UPDATE workflow_tracking SET 
                update_date_at=:update_at,
                license_scope_status=:scope_st, license_scope_note=:scope_note,
                hire_foreign_company=:hire_st, hire_foreign_company_note=:hire_note,
                misa_application=:misa_st, misa_application_note=:misa_note,
                sbc_application=:sbc_st, sbc_application_note=:sbc_note,
                article_association=:art_st, article_association_note=:art_note,
                gosi=:gosi_st, gosi_note=:gosi_note,
                qiwa=:qiwa_st, qiwa_note=:qiwa_note,
                muqeem=:muqeem_st, muqeem_note=:muqeem_note,
                chamber_commerce=:coc_st, chamber_commerce_note=:coc_note
                WHERE client_id=:cid";
        } else {
            // Logic to insert if missing (rare case for edit page)
            // For brevity, assuming row exists or using INSERT logic similar to add page
             $sql_wf = "INSERT INTO workflow_tracking (client_id, update_date_at) VALUES (:cid, :update_at)"; // simplified fallback
        }
        
        $stmt_wf = $db->prepare($sql_wf);
        $stmt_wf->execute($wf_data);

        $db->commit();
        header("Location: client-edit.php?id=" . $client_id . "&msg=updated");
        exit();

    } catch (PDOException $e) {
        $db->rollBack();
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// Success Msg
if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Updated successfully!</div>";
}

// --- FETCH DATA ---
$stmt = $db->prepare("SELECT c.*, w.* FROM clients c LEFT JOIN workflow_tracking w ON c.client_id = w.client_id WHERE c.client_id = ? LIMIT 1");
$stmt->execute([$client_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Mapping
$workflow_map = [
    'status_scope'   => ['label' => 'License Processing Scope', 'db_st' => 'license_scope_status', 'db_note' => 'license_scope_note', 'note_field' => 'note_scope'],
    'status_hire'    => ['label' => 'Hire Foreign Company',     'db_st' => 'hire_foreign_company', 'db_note' => 'hire_foreign_company_note', 'note_field' => 'note_hire'],
    'status_misa'    => ['label' => 'MISA Application',         'db_st' => 'misa_application', 'db_note' => 'misa_application_note', 'note_field' => 'note_misa'],
    'status_sbc'     => ['label' => 'SBC Application',          'db_st' => 'sbc_application', 'db_note' => 'sbc_application_note', 'note_field' => 'note_sbc'],
    'status_article' => ['label' => 'Article of Association',   'db_st' => 'article_association', 'db_note' => 'article_association_note', 'note_field' => 'note_article'],
    'status_gosi'    => ['label' => 'GOSI',                     'db_st' => 'gosi', 'db_note' => 'gosi_note', 'note_field' => 'note_gosi'],
    'status_qiwa'    => ['label' => 'QIWA',                     'db_st' => 'qiwa', 'db_note' => 'qiwa_note', 'note_field' => 'note_qiwa'],
    'status_muqeem'  => ['label' => 'MUQEEM',                   'db_st' => 'muqeem', 'db_note' => 'muqeem_note', 'note_field' => 'note_muqeem'],
    'status_coc'     => ['label' => 'Chamber of Commerce',      'db_st' => 'chamber_commerce', 'db_note' => 'chamber_commerce_note', 'note_field' => 'note_coc']
];
$last_update = $data['update_date_at'] ? date('M d, Y h:i A', strtotime($data['update_date_at'])) : 'Never';
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white"><i class="bi bi-arrow-left me-2"></i> Back</a>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light border-opacity-10 pb-3">
                            <div><h4 class="text-white fw-bold mb-0">Edit Client</h4><small class="text-gold">Last Updated: <?php echo $last_update; ?></small></div>
                            <span class="badge bg-gold text-dark">ID: #<?php echo $data['client_id']; ?></span>
                        </div>
                        <?php echo $message; ?>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <h5 class="text-gold mb-3">Basic Information</h5>
                            <div class="row g-3 mb-5">
                                <div class="col-md-6"><label class="form-label text-white-50 small fw-bold">Company</label><input type="text" name="company_name" class="form-control glass-input" value="<?php echo htmlspecialchars($data['company_name']); ?>" required></div>
                                <div class="col-md-6"><label class="form-label text-white-50 small fw-bold">Client Rep</label><input type="text" name="client_name" class="form-control glass-input" value="<?php echo htmlspecialchars($data['client_name']); ?>" required></div>
                                <div class="col-md-6"><label class="form-label text-white-50 small fw-bold">Phone</label><input type="tel" name="phone_number" class="form-control glass-input" value="<?php echo htmlspecialchars($data['phone_number']); ?>" required></div>
                                <div class="col-md-6"><label class="form-label text-white-50 small fw-bold">Email</label><input type="email" name="email" class="form-control glass-input" value="<?php echo htmlspecialchars($data['email']); ?>" required></div>
                                <div class="col-md-6"><label class="form-label text-white-50 small fw-bold">Trade Name</label><input type="text" name="trade_name_application" class="form-control glass-input" value="<?php echo htmlspecialchars($data['trade_name_application']); ?>"></div>
                                <div class="col-md-6"><label class="form-label text-gold small fw-bold">Contract Value</label><input type="number" step="0.01" name="contract_value" class="form-control glass-input" value="<?php echo htmlspecialchars($data['contract_value']); ?>" required></div>
                            </div>

                            <h5 class="text-gold mb-3">Workflow Status</h5>
                            <div class="row g-3">
                                <?php foreach($workflow_map as $field_name => $info): 
                                    $current_st = $data[$info['db_st']] ?? 'In Process';
                                    $current_note = $data[$info['db_note']] ?? '';
                                ?>
                                    <input type="hidden" name="<?php echo $info['note_field']; ?>" id="hidden_<?php echo $info['note_field']; ?>" value="<?php echo htmlspecialchars($current_note); ?>">
                                    
                                    <div class="col-md-4 col-sm-6">
                                        <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <label class="text-white fw-bold small text-uppercase mb-0"><?php echo $info['label']; ?></label>
                                                <button type="button" class="btn btn-link text-gold p-0 ms-2" 
                                                        onclick="openStatusModal('<?php echo $field_name; ?>', '<?php echo $info['label']; ?>', '<?php echo $info['note_field']; ?>')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </div>

                                            <select name="<?php echo $field_name; ?>" id="select_<?php echo $field_name; ?>" class="form-select glass-select-sm">
                                                 <?php if ($field_name === 'status_scope'): ?>
                                                    <option value="Trading License Processing" <?php echo ($current_st == 'Trading License Processing') ? 'selected' : ''; ?>>Trading License Processing</option>
                                                    <option value="Service License Processing" <?php echo ($current_st == 'Service License Processing') ? 'selected' : ''; ?>>Service License Processing</option>
                                                    <option value="Service License Upgrade to Trading License" <?php echo ($current_st == 'Service License Upgrade to Trading License') ? 'selected' : ''; ?>>Service License Upgrade...</option>
                                                <?php else: ?>
                                                    <option value="In Progress" <?php echo ($current_st == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="Applied" <?php echo ($current_st == 'Applied') ? 'selected' : ''; ?>>Applied</option>
                                                    <option value="Pending Application" <?php echo ($current_st == 'Pending Application') ? 'selected' : ''; ?>>Pending Application</option>
                                                    <option value="Approved" <?php echo ($current_st == 'Approved') ? 'selected' : ''; ?> class="text-success fw-bold">Approved</option>
                                                <?php endif; ?>
                                            </select>

                                            <small class="text-white-50 mt-1 fst-italic note-indicator" id="indicator_<?php echo $info['note_field']; ?>" 
                                                   style="display: <?php echo !empty($current_note) ? 'block' : 'none'; ?>; font-size: 0.7rem;">
                                                <i class="bi bi-sticky me-1"></i> Note added
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="col-12 mt-5 d-flex gap-3">
                                <button type="submit" class="btn btn-rooq-primary flex-grow-1 py-3 fw-bold">Save Changes</button>
                                <a href="clients.php" class="btn btn-outline-light py-3 px-5">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: rgba(30, 30, 30, 0.95); backdrop-filter: blur(15px); border: 1px solid #D4AF37;">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title text-white" id="modalLabel">Update Status</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_target_field">
                    <input type="hidden" id="modal_note_field">
                    <div class="mb-3">
                        <label class="form-label text-gold small fw-bold">Status Option</label>
                        <select id="modal_status_select" class="form-select glass-input"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-gold small fw-bold">Add Note / Message</label>
                        <textarea id="modal_note_text" class="form-control glass-input" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-rooq-primary btn-sm" onclick="saveModalData()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-input, .glass-select-sm { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; }
    .glass-input:focus { border-color: #D4AF37; background: rgba(255,255,255,0.1); color: white; }
    .glass-select-sm option, .glass-input option { background: #33000d; color: white; }
    .workflow-card { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; }
</style>
<script>
    var statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    function openStatusModal(fieldName, label, noteFieldName) {
        document.getElementById('modalLabel').innerText = label;
        document.getElementById('modal_target_field').value = fieldName;
        document.getElementById('modal_note_field').value = noteFieldName;
        var sourceSelect = document.getElementById('select_' + fieldName);
        var modalSelect = document.getElementById('modal_status_select');
        modalSelect.innerHTML = sourceSelect.innerHTML;
        modalSelect.value = sourceSelect.value;
        var existingNote = document.getElementById('hidden_' + noteFieldName).value;
        document.getElementById('modal_note_text').value = existingNote;
        statusModal.show();
    }
    function saveModalData() {
        var fieldName = document.getElementById('modal_target_field').value;
        var noteFieldName = document.getElementById('modal_note_field').value;
        var newVal = document.getElementById('modal_status_select').value;
        document.getElementById('select_' + fieldName).value = newVal;
        var newNote = document.getElementById('modal_note_text').value;
        document.getElementById('hidden_' + noteFieldName).value = newNote;
        var indicator = document.getElementById('indicator_' + noteFieldName);
        if(newNote.trim() !== "") { indicator.style.display = "block"; } else { indicator.style.display = "none"; }
        statusModal.hide();
    }
</script>

<?php
require_once 'includes/footer.php'
?>