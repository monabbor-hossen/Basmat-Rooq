<?php
// portal/client-add.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    $company = Security::clean($_POST['company_name']);
    $client  = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    $trade   = Security::clean($_POST['trade_name_application']);
    $value   = floatval($_POST['contract_value']);

    try {
        $db = (new Database())->getConnection();
        $db->beginTransaction();

        // 1. Insert Client
        $sql = "INSERT INTO clients (company_name, client_name, phone_number, email, trade_name_application, contract_value) 
                VALUES (:company, :client, :phone, :email, :trade_app, :val)";
        $stmt = $db->prepare($sql);
        $stmt->execute([':company'=>$company, ':client'=>$client, ':phone'=>$phone, ':email'=>$email, ':trade_app'=>$trade, ':val'=>$value]);
        $new_client_id = $db->lastInsertId();

        // 2. Insert Workflow (Status from Dropdown, Note from Hidden Input)
        $statuses = [
            ':cid' => $new_client_id,
            ':scope_st' => $_POST['status_scope'], ':scope_nt' => $_POST['note_scope'],
            ':hire_st'  => $_POST['status_hire'],  ':hire_nt'  => $_POST['note_hire'],
            ':misa_st'  => $_POST['status_misa'],  ':misa_nt'  => $_POST['note_misa'],
            ':sbc_st'   => $_POST['status_sbc'],   ':sbc_nt'   => $_POST['note_sbc'],
            ':art_st'   => $_POST['status_article'],':art_nt'  => $_POST['note_article'],
            ':gosi_st'  => $_POST['status_gosi'],  ':gosi_nt'  => $_POST['note_gosi'],
            ':qiwa_st'  => $_POST['status_qiwa'],  ':qiwa_nt'  => $_POST['note_qiwa'],
            ':muqeem_st'=> $_POST['status_muqeem'],':muqeem_nt'=> $_POST['note_muqeem'],
            ':coc_st'   => $_POST['status_coc'],   ':coc_nt'   => $_POST['note_coc'],
            ':update_at'=> date('Y-m-d H:i:s')
        ];

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
        $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client created successfully!</div>";

    } catch (PDOException $e) {
        $db->rollBack();
        $message = "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
    }
}

$workflow_steps = [
    'scope'   => 'License Processing Scope',
    'hire'    => 'Hire Foreign Company',
    'misa'    => 'MISA Application',
    'sbc'     => 'SBC Application',
    'article' => 'Article of Association',
    'gosi'    => 'GOSI',
    'qiwa'    => 'QIWA',
    'muqeem'  => 'MUQEEM',
    'coc'     => 'Chamber of Commerce'
];
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Clients
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card-box">
                        <h4 class="text-white fw-bold mb-4 border-bottom border-light border-opacity-10 pb-3">
                            Add New Client
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
                                    <input type="number" step="0.01" name="contract_value" class="form-control glass-input" placeholder="0.00" required>
                                </div>
                            </div>

                            <h5 class="text-gold mb-3"><i class="bi bi-kanban me-2"></i>Initial Workflow Status</h5>
                            <div class="row g-3">
                                <?php foreach($workflow_steps as $key => $label): 
                                    $field_status = "status_" . $key;
                                    $field_note   = "note_" . $key;
                                ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between position-relative">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="text-white fw-bold small text-uppercase mb-0"><?php echo $label; ?></label>
                                            
                                            <button type="button" class="btn btn-sm btn-link text-gold p-0 ms-2" 
                                                    onclick="openEditModal('<?php echo $key; ?>', '<?php echo $label; ?>')">
                                                <i class="bi bi-pencil-square fs-6"></i>
                                            </button>
                                        </div>

                                        <select name="<?php echo $field_status; ?>" id="select_<?php echo $key; ?>" class="form-select glass-select-sm">
                                            <?php if ($key === 'scope'): ?>
                                                <option value="Trading License Processing">Trading License Processing</option>
                                                <option value="Service License Processing">Service License Processing</option>
                                                <option value="Service License Upgrade to Trading License">Service License Upgrade</option>
                                            <?php else: ?>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Applied">Applied</option>
                                                <option value="Pending Application">Pending Application</option>
                                                <option value="Approved">Approved</option>
                                            <?php endif; ?>
                                        </select>

                                        <div id="note_indicator_<?php echo $key; ?>" class="mt-2 text-gold small fst-italic d-none">
                                            <i class="bi bi-sticky-fill me-1"></i> <span class="opacity-75">Note added</span>
                                        </div>

                                        <input type="hidden" name="<?php echo $field_note; ?>" id="input_note_<?php echo $key; ?>" value="">

                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold">Create Client</button>
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
                <h5 class="modal-title text-white fw-bold" id="modalTitle">Edit Details</h5>
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
                    <textarea id="modal_note_text" class="form-control glass-input" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-top border-white border-opacity-10">
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-rooq-primary btn-sm px-4" onclick="saveModalChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-input { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 10px; }
    .glass-input:focus { border-color: #D4AF37; background: rgba(255,255,255,0.1); color: white; }
    .glass-select-sm { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #D4AF37; }
    .glass-select-sm option, .glass-input option { background: #33000d; color: white; }
    .workflow-card { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; }
    .glass-modal { background: rgba(20, 5, 10, 0.98); backdrop-filter: blur(20px); border: 1px solid #D4AF37; }
</style>

<script>
    var modalElement;

    // Wait for DOM to load
    document.addEventListener("DOMContentLoaded", function() {
        modalElement = new bootstrap.Modal(document.getElementById('workflowModal'));
    });

    function openEditModal(key, label) {
        // 1. Set Title & Key
        document.getElementById('modalTitle').innerText = label;
        document.getElementById('current_field_key').value = key;

        // 2. Get Values from the CARD
        const cardSelect = document.getElementById('select_' + key);
        const cardNote   = document.getElementById('input_note_' + key);
        
        // 3. Populate Modal Dropdown (Copy options from card)
        const modalSelect = document.getElementById('modal_status_select');
        modalSelect.innerHTML = cardSelect.innerHTML; // Clone options
        modalSelect.value = cardSelect.value;         // Set selected value

        // 4. Populate Note
        document.getElementById('modal_note_text').value = cardNote.value;

        // 5. Show
        modalElement.show();
    }

    function saveModalChanges() {
        const key = document.getElementById('current_field_key').value;
        const newStatus = document.getElementById('modal_status_select').value;
        const newNote   = document.getElementById('modal_note_text').value;

        // 1. Update the CARD Dropdown
        document.getElementById('select_' + key).value = newStatus;

        // 2. Update the HIDDEN Note
        document.getElementById('input_note_' + key).value = newNote;

        // 3. Update Visual Note Indicator
        const indicator = document.getElementById('note_indicator_' + key);
        if(newNote.trim() !== "") {
            indicator.classList.remove('d-none');
        } else {
            indicator.classList.add('d-none');
        }

        modalElement.hide();
    }
</script>
<?php
require_once 'includes/footer.php'
?>