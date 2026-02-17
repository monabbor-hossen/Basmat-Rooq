<?php
// portal/client-edit.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";
$client_id = $_GET['id'] ?? null;

// 1. Redirect if no ID provided
if (!$client_id) {
    header("Location: clients.php");
    exit();
}

$db = (new Database())->getConnection();

// 2. Handle Form Submission (Update Logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    // Sanitize Inputs
    $company = Security::clean($_POST['company_name']);
    $name    = Security::clean($_POST['client_name']);
    $phone   = Security::clean($_POST['phone_number']);
    $email   = Security::clean($_POST['email']);
    $scope   = Security::clean($_POST['license_scope']);

    try {
        $query = "UPDATE clients 
                  SET company_name = :company, 
                      client_name = :name, 
                      phone_number = :phone, 
                      email = :email, 
                      license_scope = :scope 
                  WHERE client_id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':scope', $scope);
        $stmt->bindParam(':id', $client_id);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Client profile updated successfully!</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// 3. Fetch Existing Client Data
$stmt = $db->prepare("SELECT * FROM clients WHERE client_id = :id LIMIT 1");
$stmt->bindParam(':id', $client_id);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// If client doesn't exist, stop here
if (!$client) {
    echo "<div class='d-flex portal-wrapper'><main class='w-100 p-5'><div class='alert alert-danger'>Client not found. <a href='clients.php'>Go Back</a></div></main></div>";
    require_once 'includes/footer.php'; // Assuming you might have a footer file or just close tags
    exit();
}
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <a href="clients.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Client List
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-light border-opacity-10 pb-3">
                            <h4 class="text-white fw-bold mb-0">Edit Client Portfolio</h4>
                            <span class="badge bg-gold text-dark">ID: #<?php echo $client['client_id']; ?></span>
                        </div>
                        
                        <?php echo $message; ?>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control glass-input" 
                                           value="<?php echo htmlspecialchars($client['company_name']); ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Client Rep Name</label>
                                    <input type="text" name="client_name" class="form-control glass-input" 
                                           value="<?php echo htmlspecialchars($client['client_name']); ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control glass-input" 
                                           value="<?php echo htmlspecialchars($client['phone_number']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-gold small text-uppercase fw-bold">Email Address</label>
                                    <input type="email" name="email" class="form-control glass-input" 
                                           value="<?php echo htmlspecialchars($client['email']); ?>" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-gold small text-uppercase fw-bold">License Scope</label>
                                    
                                    <select name="license_scope" class="form-select glass-input">
                                        <option value="Construction">Construction</option>
                                        <option value="Service License Processing"<?php echo ($client['license_scope'] == 'Service License Processing') ? 'selected' : ''; ?>>Service License Processing</option>
                                        <option value="Service License Upgrade to Trading Licens"<?php echo ($client['license_scope'] == 'Service License Upgrade to Trading Licens') ? 'selected' : ''; ?>>Service License Upgrade to Trading Licens</option>
                                        <option value="Industrial">Industrial</option>
                                    </select>
                                </div>
                                

                                <div class="col-12 mt-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-rooq-primary flex-grow-1 py-3 fw-bold">
                                        Update Profile
                                    </button>
                                    <a href="clients.php" class="btn btn-outline-light py-3 px-4">Cancel</a>
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
    select.glass-input option {
        background-color: #33000d;
        color: white;
    }
</style>
<?php


require_once 'includes/footer.php'


?>