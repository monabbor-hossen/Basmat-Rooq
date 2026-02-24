<?php
// management/dashboard.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../public/login.php");
    exit();
}

$db = (new Database())->getConnection();
$account_id = $_SESSION['user_id'];
$fallback_client_id = $_SESSION['client_id'] ?? 0;
// Fetch ALL ACTIVE Projects/Licenses for this account
$stmt = $db->prepare("SELECT c.*, w.* FROM clients c 
                      LEFT JOIN workflow_tracking w ON c.client_id = w.client_id 
                      WHERE (c.account_id = ? OR c.client_id = ?) 
                      AND c.is_active = 1 
                      ORDER BY c.client_id DESC");
$stmt->execute([$account_id, $fallback_client_id]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$company_name = count($projects) > 0 ? $projects[0]['client_name'] : 'Client';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard | Basmat Rooq</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/css/theme.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .glass-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.05) 0%, rgba(0,0,0,0.2) 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            border-color: var(--rooq-gold);
        }
        .step-item {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .step-item:last-child { border-bottom: none; }
        
        .badge-Approved { background: rgba(46, 204, 113, 0.2); color: #2ecc71; border: 1px solid rgba(46, 204, 113, 0.3); }
        .badge-Pending { background: rgba(241, 196, 15, 0.2); color: #f1c40f; border: 1px solid rgba(241, 196, 15, 0.3); }
        .badge-Applied { background: rgba(52, 152, 219, 0.2); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.3); }
        .badge-In { background: rgba(52, 152, 219, 0.2); color: #3498db; border: 1px solid rgba(52, 152, 219, 0.3); } 
    </style>
</head>
<body class="portal-body">

<div id="global-loader" class="global-loader">
    <div class="rooq-spinner"></div>
</div>

<header class="portal-header sticky-top">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="dashboard.php" class="text-decoration-none d-flex align-items-center">
            <img src="<?php echo BASE_URL; ?>assets/img/logo.png" height="50" alt="Logo">
        </a>
        
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-md-block me-2">
                <div class="text-white fw-bold"><?php echo htmlspecialchars($company_name); ?></div>
                <div class="text-gold small text-uppercase">Client Portal</div>
            </div>
            <a href="<?php echo BASE_URL; ?>public/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>
</header>

<div class="container py-5">
    <div class="mb-5 text-center">
        <h2 class="text-white fw-bold mb-2">My Licenses & Projects</h2>
        <p class="text-white-50">Track the real-time progress of all your active applications.</p>
    </div>

    <div class="row g-4">
        <?php if (count($projects) > 0): ?>
            <?php foreach ($projects as $proj): 
                // Calculate Progress
                $steps = [
                    $proj['hire_foreign_company'], $proj['misa_application'], $proj['sbc_application'], 
                    $proj['article_association'], $proj['qiwa'], $proj['muqeem'], $proj['gosi'], $proj['chamber_commerce']
                ];
                $approved = 0; $active = 0;
                foreach($steps as $s) {
                    if ($s !== 'Not Required') {
                        $active++;
                        if ($s === 'Approved') $approved++;
                    }
                }
                $prog_percent = ($active > 0) ? round(($approved / $active) * 100) : 0;
            ?>
                <div class="col-lg-6">
                    <div class="glass-card overflow-hidden h-100 d-flex flex-column">
                        <div class="p-4 border-bottom border-light border-opacity-10" style="background: rgba(0,0,0,0.3);">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="text-gold fw-bold mb-1"><i class="bi bi-briefcase me-2"></i><?php echo htmlspecialchars($proj['trade_name_application'] ?: 'New Project License'); ?></h5>
                                    <p class="text-white-50 small mb-0">ID: #<?php echo $proj['client_id']; ?> | Rep: <?php echo htmlspecialchars($proj['client_name']); ?></p>
                                </div>
                                <span class="badge bg-gold text-dark fs-6 rounded-pill px-3"><?php echo $prog_percent; ?>%</span>
                            </div>
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                                <div class="progress-bar bg-success" style="width: <?php echo $prog_percent; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="p-0 flex-grow-1">
                            <?php
                            $display_steps = [
                                'Scope' => $proj['license_scope_status'],
                                'MISA Application' => $proj['misa_application'],
                                'Chamber of Commerce' => $proj['chamber_commerce'],
                                'QIWA' => $proj['qiwa'],
                                'MUQEEM' => $proj['muqeem']
                            ];
                            
                            foreach($display_steps as $label => $status) {
                                if ($status === 'Not Required') continue;
                                
                                // Match badge style based on text
                                $badge_class = 'badge-Pending';
                                if (strpos($status, 'Approved') !== false) $badge_class = 'badge-Approved';
                                if (strpos($status, 'Applied') !== false) $badge_class = 'badge-Applied';
                                if (strpos($status, 'In Progress') !== false) $badge_class = 'badge-In';
                                
                                echo "
                                <div class='step-item'>
                                    <span class='text-white small fw-bold'>$label</span>
                                    <span class='badge $badge_class px-3 py-2 rounded-pill'>$status</span>
                                </div>";
                            }
                            ?>
                        </div>
                        
                        <div class="p-3 text-center border-top border-light border-opacity-10" style="background: rgba(0,0,0,0.2);">
                            <small class="text-white-50 fst-italic">Last Updated: <?php echo $proj['update_date_at'] ? date('M d, Y', strtotime($proj['update_date_at'])) : 'N/A'; ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="glass-card p-5 d-inline-block">
                    <i class="bi bi-folder-x text-white-50 mb-3" style="font-size: 3rem;"></i>
                    <h4 class="text-white">No active licenses found</h4>
                    <p class="text-white-50">Please contact our support team if you believe this is a mistake.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>