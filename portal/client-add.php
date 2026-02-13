<?php
// portal/client-add.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Security Check
    Security::checkCSRF($_POST['csrf_token']);

    // 2. Sanitize Input
    $company = Security::clean($_POST['company_name']);
    $client  = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    $scope   = Security::clean($_POST['license_scope']);

    try {
        $db = (new Database())->getConnection();
        $sql = "INSERT INTO clients (company_name, client_name, phone_number, email, license_scope) 
                VALUES (:company, :client, :phone, :email, :scope)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':client', $client);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client profile created successfully!</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Database Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Clients
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-box">
                        <h4 class="text-white fw-bold mb-4 border-bottom border-light border-opacity-10 pb-3">
                            Add New Client Portfolio
                        </h4>
                        
                        <?php echo $message; ?>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control glass-input" required placeholder="e.g. Jahangir Contracting">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Client Rep Name</label>
                                    <input type="text" name="client_name" class="form-control glass-input" required placeholder="e.g. Abdullah Al-Saud">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control glass-input" required placeholder="+966 50...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control glass-input" required placeholder="contact@company.com">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-gold small text-uppercase fw-bold">License Scope</label>
                                    <select name="license_scope" class="form-select glass-input">
                                        <option value="Construction">Construction</option>
                                        <option value="Service License Processing">Service License Processing</option>
                                        <option value="Service License Upgrade to Trading Licens">Service License Upgrade to Trading Licens</option>
                                        <option value="Industrial">Industrial</option>
                                    </select>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold">
                                        Create Client Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

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
    
    /* Dark Select Options */
    select.glass-input option {
        background-color: #33000d;
        color: white;
    }
</style>

<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>