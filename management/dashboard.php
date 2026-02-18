
<?php echo "Jihan";?>
<!-- <php
// management/dashboard.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Auth/SessionManager.php';

// 1. GATEKEEPER: Ensure User is a Client
SessionManager::start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

// 2. Fetch Client Data
$db = (new Database())->getConnection();
$client_id = $_SESSION['client_id'];

// Fetch Profile
$stmt = $db->prepare("SELECT * FROM clients WHERE client_id = ? LIMIT 1");
$stmt->execute([$client_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch Workflow
$stmtW = $db->prepare("SELECT * FROM workflow_tracking WHERE client_id = ? LIMIT 1");
$stmtW->execute([$client_id]);
$workflow = $stmtW->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="<php echo BASE_URL;?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<php echo BASE_URL;?>assets/css/theme.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body class="bg-dark text-white">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5 border-bottom border-secondary pb-3">
            <div>
                <h2 class="text-gold fw-bold">Welcome, <php echo htmlspecialchars($profile['company_name']); ?></h2>
                <p class="text-white-50 mb-0">Client Portal</p>
            </div>
            <a href="<php echo BASE_URL;?>public/logout.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card bg-black bg-opacity-25 border border-secondary p-4 h-100">
                    <h5 class="text-gold mb-3">Project Details</h5>
                    <div class="mb-2 text-white-50">Representative: <span class="text-white"><?php echo htmlspecialchars($profile['client_name']); ?></span></div>
                    <div class="mb-2 text-white-50">Trade Name: <span class="text-white"><?php echo htmlspecialchars($profile['trade_name_application']); ?></span></div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-black bg-opacity-25 border border-secondary p-4 h-100">
                    <h5 class="text-gold mb-3">Live Status</h5>
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent text-white d-flex justify-content-between border-secondary">
                            <span>MISA Application</span>
                            <span class="badge bg-secondary"><?php echo $workflow['misa_application'] ?? 'Pending'; ?></span>
                        </li>
                        <li class="list-group-item bg-transparent text-white d-flex justify-content-between border-secondary">
                            <span>Commercial Reg (CR)</span>
                            <span class="badge bg-secondary"><?php echo $workflow['chamber_commerce'] ?? 'Pending'; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html> -->