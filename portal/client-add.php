<?php
// portal/client-add.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Security Check
    Security::checkCSRF($_POST['csrf_token']);

    // 2. Sanitize Client Input
    $company = Security::clean($_POST['company_name']);
    $client  = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    // New Field: Trade Name Application (Stored in Clients Table now)
    $trade_app = Security::clean($_POST['trade_name_application']); 

    try {
        $db = (new Database())->getConnection();
        $db->beginTransaction(); // Start Transaction

        // A. Insert into Clients Table (Updated columns)
        $sql = "INSERT INTO clients (company_name, client_name, phone_number, email, trade_name_application) 
                VALUES (:company, :client, :phone, :email, :trade_app)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':client', $client);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':trade_app', $trade_app);
        $stmt->execute();
        
        $new_client_id = $db->lastInsertId();

        // B. Insert into Workflow Tracking Table (Removed trade_name_application)
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
            ':coc_st' => $_POST['status_coc'] ?? 'In Process'
        ];

        // SQL Updated: Removed trade_name_application column
        $sql_wf = "INSERT INTO workflow_tracking 
                   (client_id, license_scope_status, hire_foreign_company, misa_application, 
                    sbc_application, article_association, 
                    gosi, qiwa, muqeem, chamber_commerce) 
                   VALUES 
                   (:cid, :scope_st, :hire_st, :misa_st, 
                    :sbc_st, :art_st, :gosi_st, :qiwa_st, :muqeem_st, :coc_st)";

        $stmt_wf = $db->prepare($sql_wf);
        $stmt_wf->execute($statuses);

        $db->commit(); // Commit Transaction
        $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client and workflow initialized successfully!</div>";

    } catch (PDOException $e) {
        $db->rollBack(); // Undo if error
        $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Database Error: " . $e->getMessage() . "</div>";
    }
}

// Workflow Items Array (Removed 'status_trade')
$workflow_steps = [
    'status_scope' => 'License Processing Scope',
    'status_hire' => 'Hire Foreign Company',
    'status_misa' => 'MISA Application',
    // 'status_trade' REMOVED from here
    'status_sbc' => 'SBC Application',
    'status_article' => 'Article of Association',
    'status_gosi' => 'GOSI',
    'status_qiwa' => 'QIWA',
    'status_muqeem' => 'MUQEEM',
    'status_coc' => 'Chamber of Commerce'
];
?>

        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Clients
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card-box">
                        <h4 class="text-white fw-bold mb-4 border-bottom border-light border-opacity-10 pb-3">
                            Add New Client Portfolio
                        </h4>
                        
                        <?php echo $message; ?>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <h5 class="text-gold mb-3"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                            <div class="row g-3 mb-5">
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control glass-input" required placeholder="e.g. Jahangir Contracting">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Client Rep Name</label>
                                    <input type="text" name="client_name" class="form-control glass-input" required placeholder="e.g. Abdullah Al-Saud">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control glass-input" required placeholder="+966 50...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control glass-input" required placeholder="contact@company.com">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small text-uppercase fw-bold">Trade Name Application</label>
                                    <input type="text" name="trade_name_application" class="form-control glass-input" placeholder="Enter Reserved Trade Name or Application ID">
                                </div>
                            </div>

                            <h5 class="text-gold mb-3"><i class="bi bi-kanban me-2"></i>Initial Workflow Status</h5>
                            <div class="row g-3">
                                <?php foreach($workflow_steps as $field_name => $label): ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="workflow-card p-3 h-100 d-flex flex-column justify-content-between">
                                        <label class="text-white fw-bold mb-2 small text-uppercase"><?php echo $label; ?></label>
                                        <select name="<?php echo $field_name; ?>" class="form-select glass-select-sm">
                                            <option value="In Process" selected>In Process</option>
                                            <option value="Approve">Approve</option>
                                        </select>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold">
                                    Create Client & Initialize
                                </button>
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
    .glass-input::placeholder { color: rgba(255, 255, 255, 0.3); }
    
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