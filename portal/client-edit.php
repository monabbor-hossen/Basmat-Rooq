<?php
// portal/client-edit.php

// 1. Load Dependencies & Security FIRST (Before any HTML)
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Security.php';

// 2. Check Login
Security::requireLogin();

$message = "";
$client_id = $_GET['id'] ?? null;

if (!$client_id) {
    header("Location: clients.php");
    exit();
}

$db = (new Database())->getConnection();

// 3. Handle Form Submission (MUST BE BEFORE HTML OUTPUT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    $company = Security::clean($_POST['company_name']);
    $client  = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    $trade   = Security::clean($_POST['trade_name_application']);

    try {
        $db->beginTransaction();

        // Update Client Info
        $query_client = "UPDATE clients 
                         SET company_name = :company, 
                             client_name = :client, 
                             phone_number = :phone, 
                             email = :email, 
                             trade_name_application = :trade
                         WHERE client_id = :id";
        
        $stmt = $db->prepare($query_client);
        $stmt->execute([
            ':company' => $company, ':client' => $client, ':phone' => $phone,
            ':email' => $email, ':trade' => $trade, ':id' => $client_id
        ]);

        // Update Workflow (with update_date_at)
        $wf_data = [
            'license_scope_status' => $_POST['status_scope'],
            'hire_foreign_company' => $_POST['status_hire'],
            'misa_application'     => $_POST['status_misa'],
            'sbc_application'      => $_POST['status_sbc'],
            'article_association'  => $_POST['status_article'],
            'gosi'                 => $_POST['status_gosi'],
            'qiwa'                 => $_POST['status_qiwa'],
            'muqeem'               => $_POST['status_muqeem'],
            'chamber_commerce'     => $_POST['status_coc'],
            'client_id'            => $client_id,
            'update_at'            => date('Y-m-d H:i:s')
        ];

        $check = $db->prepare("SELECT id FROM workflow_tracking WHERE client_id = ?");
        $check->execute([$client_id]);
        
        if ($check->rowCount() > 0) {
            $sql_wf = "UPDATE workflow_tracking SET 
                        license_scope_status = :license_scope_status,
                        hire_foreign_company = :hire_foreign_company,
                        misa_application = :misa_application,
                        sbc_application = :sbc_application,
                        article_association = :article_association,
                        gosi = :gosi,
                        qiwa = :qiwa,
                        muqeem = :muqeem,
                        chamber_commerce = :chamber_commerce,
                        update_date_at = :update_at
                       WHERE client_id = :client_id";
        } else {
            $sql_wf = "INSERT INTO workflow_tracking 
                        (license_scope_status, hire_foreign_company, misa_application, sbc_application, 
                         article_association, gosi, qiwa, muqeem, chamber_commerce, update_date_at, client_id)
                       VALUES 
                        (:license_scope_status, :hire_foreign_company, :misa_application, :sbc_application, 
                         :article_association, :gosi, :qiwa, :muqeem, :chamber_commerce, :update_at, :client_id)";
        }

        $stmt_wf = $db->prepare($sql_wf);
        $stmt_wf->execute($wf_data);

        $db->commit();
        
        // 4. REDIRECT NOW (Before any HTML is loaded)
        header("Location: client-edit.php?id=" . $client_id . "&msg=updated");
        exit();

    } catch (PDOException $e) {
        $db->rollBack();
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// 5. NOW Include HTML Headers (Output Starts Here)
// NOTE: We do not need require_once Config/Database again as they are loaded
require_once 'includes/header.php'; 

// Success Message Logic
if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client profile updated successfully!</div>";
}

// Fetch Data for Display
$sql_fetch = "SELECT c.*, w.* FROM clients c 
              LEFT JOIN workflow_tracking w ON c.client_id = w.client_id 
              WHERE c.client_id = :id LIMIT 1";
$stmt = $db->prepare($sql_fetch);
$stmt->bindParam(':id', $client_id);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) exit("Client not found.");

// Workflow Map
$workflow_map = [
    'status_scope'   => ['label' => 'License Processing Scope', 'db_col' => 'license_scope_status'],
    'status_hire'    => ['label' => 'Hire Foreign Company',     'db_col' => 'hire_foreign_company'],
    'status_misa'    => ['label' => 'MISA Application',         'db_col' => 'misa_application'],
    'status_sbc'     => ['label' => 'SBC Application',          'db_col' => 'sbc_application'],
    'status_article' => ['label' => 'Article of Association',   'db_col' => 'article_association'],
    'status_gosi'    => ['label' => 'GOSI',                     'db_col' => 'gosi'],
    'status_qiwa'    => ['label' => 'QIWA',                     'db_col' => 'qiwa'],
    'status_muqeem'  => ['label' => 'MUQEEM',                   'db_col' => 'muqeem'],
    'status_coc'     => ['label' => 'Chamber of Commerce',      'db_col' => 'chamber_commerce']
];
$last_update = $data['update_date_at'] ? date('M d, Y h:i A', strtotime($data['update_date_at'])) : 'Never';
?>


        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Clients
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

                        <form method="POST">
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
                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-bold">Trade Name Application</label>
                                    <input type="text" name="trade_name_application" class="form-control glass-input" value="<?php echo htmlspecialchars($data['trade_name_application'] ?? ''); ?>">
                                </div>
                            </div>

                            <h5 class="text-gold mb-3"><i class="bi bi-kanban me-2"></i>Workflow Status</h5>
                            <div class="row g-3">
                                <?php foreach($workflow_map as $field_name => $info): 
                                    $current_val = $data[$info['db_col']] ?? 'In Process';
                                ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between">
                                        <label class="text-white fw-bold mb-2 small text-uppercase"><?php echo $info['label']; ?></label>
                                        <select name="<?php echo $field_name; ?>" class="form-select glass-select-sm">
                                            <?php if ($field_name === 'status_scope'): ?>
                                                <option value="Trading License Processing" <?php echo ($current_val == 'Trading License Processing') ? 'selected' : ''; ?>>Trading License Processing</option>
                                                <option value="Service License Processing" <?php echo ($current_val == 'Service License Processing') ? 'selected' : ''; ?>>Service License Processing</option>
                                                <option value="Service License Upgrade to Trading License" <?php echo ($current_val == 'Service License Upgrade to Trading License') ? 'selected' : ''; ?>>Service License Upgrade to Trading License</option>
                                            <?php else: ?>
                                                <option value="In Progress" <?php echo ($current_val == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Applied" <?php echo ($current_val == 'Applied') ? 'selected' : ''; ?>>Applied</option>
                                                <option value="Pending Application" <?php echo ($current_val == 'Pending Application') ? 'selected' : ''; ?>>Pending Application</option>
                                                <option value="Approved" <?php echo ($current_val == 'Approved') ? 'selected' : ''; ?> class="text-success fw-bold">Approved</option>
                                            <?php endif; ?>
                                        </select>
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

    <style>
        .glass-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        padding: 12px 15px;
    }
    .glass-input:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: #D4AF37;
        color: white;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
    }
    
    .workflow-card {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .workflow-card:hover {
        background: rgba(212, 175, 55, 0.05);
        border-color: rgba(212, 175, 55, 0.3);
    }

    .glass-select-sm {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #D4AF37;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .glass-select-sm option {
        background-color: #33000d;
        color: white;
        padding: 8px;
    }
    </style>

    <?php
require_once 'includes/footer.php'
?>