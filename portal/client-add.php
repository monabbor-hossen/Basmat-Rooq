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
    $value   = floatval($_POST['contract_value']); // <--- NEW

    try {
        $db = (new Database())->getConnection();
        $db->beginTransaction();

        // Added contract_value to INSERT
        $sql = "INSERT INTO clients (company_name, client_name, phone_number, email, trade_name_application, contract_value) 
                VALUES (:company, :client, :phone, :email, :trade_app, :val)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':company' => $company, ':client' => $client, ':phone' => $phone,
            ':email' => $email, ':trade_app' => $trade, ':val' => $value
        ]);
        
        $new_client_id = $db->lastInsertId();

        // [Workflow Insert Logic Remains the Same...]
        $statuses = [
            ':cid' => $new_client_id,
            ':scope_st' => $_POST['status_scope'] ?? 'In Process',
            ':hire_st' => $_POST['status_hire'] ?? 'In Process',
            ':misa_st' => $_POST['status_misa'] ?? 'In Process',
            ':sbc_st' => $_POST['status_sbc'] ?? 'In Process',
            ':art_st' => $_POST['status_article'] ?? 'In Process',
            ':gosi_st' => $_POST['status_gosi'] ?? 'In Process',
            ':qiwa_st' => $_POST['status_qiwa'] ?? 'In Process',
            ':muqeem_st' => $_POST['status_muqeem'] ?? 'In Process',
            ':coc_st' => $_POST['status_coc'] ?? 'In Process',
            ':update_at' => date('Y-m-d H:i:s')
        ];

        $sql_wf = "INSERT INTO workflow_tracking 
                   (client_id, license_scope_status, hire_foreign_company, misa_application, 
                    sbc_application, article_association, 
                    gosi, qiwa, muqeem, chamber_commerce, update_date_at) 
                   VALUES 
                   (:cid, :scope_st, :hire_st, :misa_st, 
                    :sbc_st, :art_st, :gosi_st, :qiwa_st, :muqeem_st, :coc_st, :update_at)";

        $stmt_wf = $db->prepare($sql_wf);
        $stmt_wf->execute($statuses);

        $db->commit();
        $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client created successfully!</div>";

    } catch (PDOException $e) {
        $db->rollBack();
        $message = "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
    }
}
// [Workflow Arrays Code Remains Same...]
$workflow_steps = [
    'status_scope' => 'License Processing Scope',
    'status_hire' => 'Hire Foreign Company',
    'status_misa' => 'MISA Application',
    'status_sbc' => 'SBC Application',
    'status_article' => 'Article of Association',
    'status_gosi' => 'GOSI',
    'status_qiwa' => 'QIWA',
    'status_muqeem' => 'MUQEEM',
    'status_coc' => 'Chamber of Commerce'
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
                                <?php foreach($workflow_steps as $field_name => $label): ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between">
                                        <label class="text-white fw-bold mb-2 small text-uppercase"><?php echo $label; ?></label>
                                        <select name="<?php echo $field_name; ?>" class="form-select glass-select-sm">
                                            <?php if ($field_name === 'status_scope'): ?>
                                                <option value="Trading License Processing">Trading License Processing</option>
                                                <option value="Service License Processing">Service License Processing</option>
                                                <option value="Service License Upgrade to Trading License">Service License Upgrade to Trading License</option>
                                            <?php else: ?>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Applied">Applied</option>
                                                <option value="Pending Application">Pending Application</option>
                                                <option value="Approved" class="text-success fw-bold">Approved</option>
                                            <?php endif; ?>
                                        </select>
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
<style>
    .glass-input { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 10px; }
    .glass-input:focus { background: rgba(255,255,255,0.1); border-color: #D4AF37; color: white; }
    .workflow-card { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; }
    .glass-select-sm { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #D4AF37; }
    .glass-select-sm option { background: #33000d; color: white; }
</style>
<?php

require_once 'includes/footer.php'
?>