<?php
// portal/client-edit.php

// 1. SETUP & SECURITY (Must be at the top)
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

// Check Login
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../public/login.php"); exit(); }

$message = "";
$client_id = $_GET['id'] ?? null;

if (!$client_id) {
    header("Location: clients.php");
    exit();
}

$db = (new Database())->getConnection();

// 2. HANDLE FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    // Sanitize Basic Inputs
    $company = Security::clean($_POST['company_name']);
    $name    = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    // Assuming you migrated to 'trade_name_application' and 'contract_value' based on previous steps
    // If you are still using 'license_scope', change this back.
    $trade   = Security::clean($_POST['trade_name_application'] ?? ''); 
    $val     = floatval($_POST['contract_value'] ?? 0);

    try {
        $db->beginTransaction();

        // A. Update Clients Table
        $query_client = "UPDATE clients 
                         SET company_name = :company, 
                             client_name = :name, 
                             phone_number = :phone, 
                             email = :email, 
                             trade_name_application = :trade,
                             contract_value = :val
                         WHERE client_id = :id";
        
        $stmt = $db->prepare($query_client);
        $stmt->execute([
            ':company' => $company, ':name' => $name, ':phone' => $phone,
            ':email' => $email, ':trade' => $trade, ':val' => $val, ':id' => $client_id
        ]);

        // B. Update Workflow Table (Upsert Logic)
        $wf_data = [
            'license_scope_status' => $_POST['status_scope'], 'license_scope_note' => $_POST['note_scope'],
            'hire_foreign_company' => $_POST['status_hire'],  'hire_foreign_company_note' => $_POST['note_hire'],
            'misa_application'     => $_POST['status_misa'],  'misa_application_note' => $_POST['note_misa'],
            'sbc_application'      => $_POST['status_sbc'],   'sbc_application_note' => $_POST['note_sbc'],
            'article_association'  => $_POST['status_article'],'article_association_note' => $_POST['note_article'],
            'gosi'                 => $_POST['status_gosi'],  'gosi_note' => $_POST['note_gosi'],
            'qiwa'                 => $_POST['status_qiwa'],  'qiwa_note' => $_POST['note_qiwa'],
            'muqeem'               => $_POST['status_muqeem'],'muqeem_note' => $_POST['note_muqeem'],
            'chamber_commerce'     => $_POST['status_coc'],   'chamber_commerce_note' => $_POST['note_coc'],
            'client_id'            => $client_id,
            'update_at'            => date('Y-m-d H:i:s')
        ];

        // Check if workflow row exists
        $check = $db->prepare("SELECT id FROM workflow_tracking WHERE client_id = ?");
        $check->execute([$client_id]);

        if ($check->rowCount() > 0) {
            // Update
            $sql_wf = "UPDATE workflow_tracking SET 
                        license_scope_status = :license_scope_status, license_scope_note = :license_scope_note,
                        hire_foreign_company = :hire_foreign_company, hire_foreign_company_note = :hire_foreign_company_note,
                        misa_application = :misa_application, misa_application_note = :misa_application_note,
                        sbc_application = :sbc_application, sbc_application_note = :sbc_application_note,
                        article_association = :article_association, article_association_note = :article_association_note,
                        gosi = :gosi, gosi_note = :gosi_note,
                        qiwa = :qiwa, qiwa_note = :qiwa_note,
                        muqeem = :muqeem, muqeem_note = :muqeem_note,
                        chamber_commerce = :chamber_commerce, chamber_commerce_note = :chamber_commerce_note,
                        update_date_at = :update_at
                       WHERE client_id = :client_id";
        } else {
            // Insert (for old clients)
            $sql_wf = "INSERT INTO workflow_tracking 
                        (license_scope_status, license_scope_note, hire_foreign_company, hire_foreign_company_note, 
                         misa_application, misa_application_note, sbc_application, sbc_application_note,
                         article_association, article_association_note, gosi, gosi_note, qiwa, qiwa_note,
                         muqeem, muqeem_note, chamber_commerce, chamber_commerce_note, update_date_at, client_id)
                       VALUES 
                        (:license_scope_status, :license_scope_note, :hire_foreign_company, :hire_foreign_company_note,
                         :misa_application, :misa_application_note, :sbc_application, :sbc_application_note,
                         :article_association, :article_association_note, :gosi, :gosi_note, :qiwa, :qiwa_note,
                         :muqeem, :muqeem_note, :chamber_commerce, :chamber_commerce_note, :update_at, :client_id)";
        }

        $stmt_wf = $db->prepare($sql_wf);
        $stmt_wf->execute($wf_data);

        $db->commit();
        
        // Redirect to show success message
        header("Location: client-edit.php?id=" . $client_id . "&msg=updated");
        exit();

    } catch (PDOException $e) {
        $db->rollBack();
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// 3. START HTML OUTPUT
require_once 'includes/header.php';

// Display Message from Redirect
if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client profile updated successfully!</div>";
}

// 4. FETCH DATA (Join Clients + Workflow)
$sql_fetch = "SELECT c.*, w.* FROM clients c 
              LEFT JOIN workflow_tracking w ON c.client_id = w.client_id 
              WHERE c.client_id = :id LIMIT 1";
$stmt = $db->prepare($sql_fetch);
$stmt->bindParam(':id', $client_id);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) exit("<div class='p-5 text-white'>Client not found.</div>");

$last_update = $data['update_date_at'] ? date('M d, Y h:i A', strtotime($data['update_date_at'])) : 'Never';

// Workflow Map configuration
$workflow_steps = [
    'scope'   => ['label' => 'License Processing Scope', 'db_st' => 'license_scope_status', 'db_nt' => 'license_scope_note'],
    'hire'    => ['label' => 'Hire Foreign Company',     'db_st' => 'hire_foreign_company', 'db_nt' => 'hire_foreign_company_note'],
    'misa'    => ['label' => 'MISA Application',         'db_st' => 'misa_application',     'db_nt' => 'misa_application_note'],
    'sbc'     => ['label' => 'SBC Application',          'db_st' => 'sbc_application',      'db_nt' => 'sbc_application_note'],
    'article' => ['label' => 'Article of Association',   'db_st' => 'article_association',  'db_nt' => 'article_association_note'],
    'gosi'    => ['label' => 'GOSI',                     'db_st' => 'gosi',                 'db_nt' => 'gosi_note'],
    'qiwa'    => ['label' => 'QIWA',                     'db_st' => 'qiwa',                 'db_nt' => 'qiwa_note'],
    'muqeem'  => ['label' => 'MUQEEM',                   'db_st' => 'muqeem',               'db_nt' => 'muqeem_note'],
    'coc'     => ['label' => 'Chamber of Commerce',      'db_st' => 'chamber_commerce',     'db_nt' => 'chamber_commerce_note']
];
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Client List
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light border-opacity-10 pb-3">
                            <div>
                                <h4 class="text-white fw-bold mb-0">Edit Client Portfolio</h4>
                                <small class="text-gold"><i class="bi bi-clock-history me-1"></i> Last Updated: <?php echo $last_update; ?></small>
                            </div>
                            <span class="badge bg-gold text-dark">ID: #<?php echo $data['client_id']; ?></span>
                        </div>
                        
                        <?php echo $message; ?>

                        <form method="POST" id="mainForm">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <h5 class="text-gold mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                            <div class="row g-3 mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small fw-bold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control glass-input" value="<?php echo htmlspecialchars($data['company_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small fw-bold">Client Rep Name</label>
                                    <input type="text" name="client_name" class="form-control glass-input" value="<?php echo htmlspecialchars($data['client_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small fw-bold">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control glass-input" value="<?php echo htmlspecialchars($data['phone_number']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control glass-input" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small fw-bold">Trade Name Application</label>
                                    <input type="text" name="trade_name_application" class="form-control glass-input" value="<?php echo htmlspecialchars($data['trade_name_application'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-gold small fw-bold">Total Contract Value (SAR)</label>
                                    <input type="number" step="0.01" name="contract_value" class="form-control glass-input" value="<?php echo htmlspecialchars($data['contract_value'] ?? 0); ?>">
                                </div>
                            </div>

                            <h5 class="text-gold mb-3"><i class="bi bi-kanban me-2"></i>Workflow Status</h5>
                            <div class="row g-3">
                                <?php foreach($workflow_steps as $key => $info): 
                                    $current_val = $data[$info['db_st']] ?? 'In Process';
                                    $current_note = $data[$info['db_nt']] ?? '';
                                    $field_status = "status_" . $key;
                                    $field_note   = "note_" . $key;
                                ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between position-relative">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="text-white fw-bold small text-uppercase mb-0"><?php echo $info['label']; ?></label>
                                            
                                            <button type="button" class="btn btn-sm btn-link text-gold p-0 ms-2" 
                                                    onclick="openEditModal('<?php echo $key; ?>', '<?php echo $info['label']; ?>')">
                                                <i class="bi bi-pencil-square fs-6"></i>
                                            </button>
                                        </div>

                                        <select name="<?php echo $field_status; ?>" id="select_<?php echo $key; ?>" class="form-select glass-select-sm">
                                            <?php if ($key === 'scope'): ?>
                                                <option value="Trading License Processing" <?php echo ($current_val == 'Trading License Processing') ? 'selected' : ''; ?>>Trading License Processing</option>
                                                <option value="Service License Processing" <?php echo ($current_val == 'Service License Processing') ? 'selected' : ''; ?>>Service License Processing</option>
                                                <option value="Service License Upgrade to Trading License" <?php echo ($current_val == 'Service License Upgrade to Trading License') ? 'selected' : ''; ?>>Service License Upgrade to Trading License</option>
                                            <?php else: ?>
                                                <option value="In Progress" <?php echo ($current_val == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Applied" <?php echo ($current_val == 'Applied') ? 'selected' : ''; ?>>Applied</option>
                                                <option value="Pending Application" <?php echo ($current_val == 'Pending Application') ? 'selected' : ''; ?>>Pending Application</option>
                                                <option value="Approved" <?php echo ($current_val == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                            <?php endif; ?>
                                        </select>

                                        <div id="note_indicator_<?php echo $key; ?>" class="mt-2 text-gold small fst-italic <?php echo empty($current_note) ? 'd-none' : ''; ?>">
                                            <i class="bi bi-sticky-fill me-1"></i> Note added
                                        </div>

                                        <input type="hidden" name="<?php echo $field_note; ?>" id="input_note_<?php echo $key; ?>" value="<?php echo htmlspecialchars($current_note); ?>">

                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="col-12 mt-5">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-rooq-primary flex-grow-1 py-3 fw-bold">Save Changes</button>
                                    <a href="clients.php" class="btn btn-outline-light py-3 px-5">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="workflowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom border-white border-opacity-10">
                <h5 class="modal-title text-white fw-bold" id="modalTitle">Update Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="current_field_key">
                
                <div class="mb-3">
                    <label class="form-label text-gold small fw-bold">Status</label>
                    <select id="modal_status_select" class="form-select glass-input">
                        </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-gold small fw-bold">Note / Remark</label>
                    <textarea id="modal_note_text" class="form-control glass-input" rows="3" placeholder="Enter details..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top border-white border-opacity-10">
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-rooq-primary btn-sm px-4" onclick="saveModalChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- <style>
    .glass-input { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 10px; }
    .glass-input:focus { border-color: #D4AF37; background: rgba(255,255,255,0.1); color: white; }
    .glass-select-sm { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #D4AF37; }
    .glass-select-sm option, .glass-input option { background: #33000d; color: white; }
    .workflow-card { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; }
    .glass-modal { background: rgba(20, 5, 10, 0.98); backdrop-filter: blur(20px); border: 1px solid #D4AF37; }
</style> -->



<?php
require_once 'includes/footer.php'
?>