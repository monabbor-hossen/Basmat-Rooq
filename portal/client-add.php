<?php
// portal/client-add.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    // 1. Sanitize Basic Info
    $company = Security::clean($_POST['company_name']);
    $client  = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    $trade   = Security::clean($_POST['trade_name_application']);
    $value   = floatval($_POST['contract_value']);

    // 2. Sanitize Login Info
    $username = Security::clean($_POST['account_username']);
    $password = $_POST['account_password'];

    try {
        $db = (new Database())->getConnection();
        $db->beginTransaction();

        // A. Insert Client Profile
        $sql = "INSERT INTO clients (company_name, client_name, phone_number, email, trade_name_application, contract_value) 
                VALUES (:company, :client, :phone, :email, :trade_app, :val)";
        $stmt = $db->prepare($sql);
        $stmt->execute([':company'=>$company, ':client'=>$client, ':phone'=>$phone, ':email'=>$email, ':trade_app'=>$trade, ':val'=>$value]);
        $new_client_id = $db->lastInsertId();

        // B. Create Client Account (New Table)
        if (!empty($username) && !empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql_acc = "INSERT INTO client_accounts (client_id, username, password_hash) 
                        VALUES (:cid, :user, :pass)";
            $stmt_acc = $db->prepare($sql_acc);
            $stmt_acc->execute([':cid' => $new_client_id, ':user' => $username, ':pass' => $hashed_password]);
        }
// C. Insert Workflow
        $statuses = [':cid' => $new_client_id, ':update_at' => date('Y-m-d H:i:s')];
        
        $db_keys = [
            'scope' => 'scope', 'hire' => 'hire', 'misa' => 'misa', 'sbc' => 'sbc', 
            'article' => 'art', 'gosi' => 'gosi', 'qiwa' => 'qiwa', 'muqeem' => 'muqeem', 'coc' => 'coc'
        ];
        
        // DEFINE THE 3 REQUIRED STEPS HERE
        $required_steps = ['scope', 'qiwa', 'muqeem']; 
        
        foreach($db_keys as $post_key => $db_key) {
            // If it's a required step, OR if the toggle was turned ON
            if (in_array($post_key, $required_steps) || isset($_POST['enable_'.$post_key])) {
                $statuses[":{$db_key}_st"] = $_POST['status_'.$post_key];
                $statuses[":{$db_key}_nt"] = $_POST['note_'.$post_key];
            } else {
                $statuses[":{$db_key}_st"] = 'Not Required';
                $statuses[":{$db_key}_nt"] = '';
            }
        }

        $sql_wf = "INSERT INTO workflow_tracking 
                   (client_id, license_scope_status, license_scope_note, 
                    hire_foreign_company, hire_foreign_company_note,
                    misa_application, misa_application_note,
                    sbc_application, sbc_application_note,
                    article_association, article_association_note,
                    gosi, gosi_note, qiwa, qiwa_note, muqeem, muqeem_note, 
                    chamber_commerce, chamber_commerce_note, update_date_at) 
                   VALUES 
                   (:cid, :scope_st, :scope_nt, :hire_st, :hire_nt, :misa_st, :misa_nt, 
                    :sbc_st, :sbc_nt, :art_st, :art_nt, :gosi_st, :gosi_nt, 
                    :qiwa_st, :qiwa_nt, :muqeem_st, :muqeem_nt, :coc_st, :coc_nt, :update_at)";

        $stmt_wf = $db->prepare($sql_wf);
        $stmt_wf->execute($statuses);
        $db->commit();
        $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client and Account created successfully!</div>";

    } catch (PDOException $e) {
        $db->rollBack();
        if(strpos($e->getMessage(), 'Duplicate entry') !== false) {
             $message = "<div class='alert alert-danger'>Error: Username already taken.</div>";
        } else {
             $message = "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Workflow Steps Array
$workflow_steps = [
    'scope'   => 'License Processing Scope',
    'hire'    => 'Hire Foreign Company',
    'misa'    => 'MISA Application',
    'sbc'     => 'SBC Application',
    'article' => 'Article of Association',
    'qiwa'    => 'QIWA',
    'muqeem'  => 'MUQEEM',
    'gosi'    => 'GOSI',
    'coc'     => 'Chamber of Commerce'
];
?>

<div class="container-fluid">
    <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
        <i class="bi bi-arrow-left me-2"></i> Back to Clients
    </a>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card-box">
                <h4 class="text-white fw-bold mb-4 border-bottom border-light border-opacity-10 pb-3">Add New Client
                </h4>
                <?php echo $message; ?>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                    <h5 class="text-gold mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Company Name</label>
                            <input type="text" name="company_name" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Client Rep Name</label>
                            <input type="text" name="client_name" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Trade Name Application</label>
                            <input type="text" name="trade_name_application" class="form-control glass-input">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-gold small fw-bold">Total Contract Value (SAR)</label>
                            <input type="number" step="0.01" name="contract_value" class="form-control glass-input"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <h5 class="text-gold mb-3"><i class="bi bi-shield-lock me-2"></i>Client Portal Access</h5>
                    <div class="row g-3 mb-5 p-3 rounded" style="background: rgba(0,0,0,0.2);">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Username</label>
                            <input type="text" name="account_username" class="form-control glass-input"
                                placeholder="Create a username" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" name="account_password" id="acc_pass"
                                    class="form-control glass-input" placeholder="Create a password"
                                    autocomplete="new-password">
                                <button class="btn glass-input border-start-0 text-white-50" type="button"
                                    onclick="togglePassword('acc_pass', 'pass_icon')">
                                    <i class="bi bi-eye" id="pass_icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <h5 class="text-gold mb-3"><i class="bi bi-kanban me-2"></i>Initial Workflow Status</h5>
                    <div class="row g-3">
                        <?php 
                            // DEFINE REQUIRED STEPS FOR UI
                            $required_steps = ['scope', 'qiwa', 'muqeem']; 
                            
                            foreach($workflow_steps as $key => $label): 
                                $is_required = in_array($key, $required_steps);
                            ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between position-relative"
                                id="card_<?php echo $key; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="text-white fw-bold small text-uppercase mb-0">
                                            <?php echo $label; ?>
                                        </label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch m-0 p-0 d-flex align-items-center"
                                            title="<?php echo $is_required ? 'This step is required' : 'Toggle optional step'; ?>">
                                            <input class="form-check-input m-0 form-check-input-gold cursor-pointer <?php echo $is_required ? 'd-none' : ''; ?>" type="checkbox"
                                                name="enable_<?php echo $key; ?>" id="enable_<?php echo $key; ?>"
                                                value="1" checked onchange="toggleWorkflowCard('<?php echo $key; ?>')"
                                                style="width: 2.2em; height: 1.1em;"
                                                <?php echo $is_required ? 'disabled' : ''; ?>>
                                        </div>
                                    <button type="button" class="btn btn-sm btn-link text-gold p-0"
                                        id="btn_edit_<?php echo $key; ?>"
                                        onclick="openEditModal('<?php echo $key; ?>', '<?php echo $label; ?>')"><i
                                            class="bi bi-pencil-square fs-6"></i></button>
                                    </div>
                                </div>
                                <select name="status_<?php echo $key; ?>" id="select_<?php echo $key; ?>"
                                    class="form-select glass-select-sm">
                                    <?php if ($key === 'scope'): ?>
                                    <option value="Trading License Processing">Trading License Processing</option>
                                    <option value="Service License Processing">Service License Processing</option>
                                    <option value="Service License Upgrade to Trading License">Service License Upgrade
                                    </option>
                                    <?php else: ?>
                                    <option value="Pending Application">Pending Application</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Applied">Applied</option>
                                    <option value="Approved">Approved</option>
                                    <?php endif; ?>
                                </select>
                                <div id="note_indicator_<?php echo $key; ?>"
                                    class="mt-2 text-gold small fst-italic d-none"><i
                                        class="bi bi-sticky-fill me-1"></i> Note added</div>
                                <input type="hidden" name="note_<?php echo $key; ?>" id="input_note_<?php echo $key; ?>"
                                    value="">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="col-12 mt-5">
                        <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold">Create Client
                            Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</main>

<?php require_once 'includes/footer.php'; ?>
<div class="modal fade" id="workflowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom border-white border-opacity-10">
                <h5 class="modal-title text-white fw-bold" id="modalTitle">Update Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="current_field_key">
                <div class="mb-3"><label class="form-label text-gold small fw-bold">Status</label><select
                        id="modal_status_select" class="form-select glass-input"></select></div>
                <div class="mb-3"><label class="form-label text-gold small fw-bold">Note / Remark</label><textarea
                        id="modal_note_text" class="form-control glass-input" rows="3"></textarea></div>
            </div>
            <div class="modal-footer border-top border-white border-opacity-10">
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-rooq-primary btn-sm px-4" onclick="saveModalChanges()">Save
                    Changes</button>
            </div>
        </div>
    </div>
</div>


<?php
require_once "includes/footer.php"
?>