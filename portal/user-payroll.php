<?php
// portal/user-payroll.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    header("Location: users.php");
    exit();
}

$db = (new Database())->getConnection();

// --- HANDLE ADDING A NEW PAYMENT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    Security::checkCSRF($_POST['csrf_token']);
    
    $month = Security::clean($_POST['pay_month']);
    $year = intval($_POST['pay_year']);
    $amount = floatval($_POST['amount']);
    $method = Security::clean($_POST['payment_method']);
    $date = Security::clean($_POST['payment_date']);
    $notes = Security::clean($_POST['notes']);

    try {
        $sql = "INSERT INTO payroll (user_id, pay_month, pay_year, amount, payment_method, payment_date, notes) 
                VALUES (:uid, :month, :year, :amount, :method, :date, :notes)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':uid' => $user_id, ':month' => $month, ':year' => $year, 
            ':amount' => $amount, ':method' => $method, ':date' => $date, ':notes' => $notes
        ]);
        $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>Salary payment recorded successfully!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// --- FETCH USER INFO ---
$stmt = $db->prepare("SELECT full_name, username, job_title, basic_salary FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger m-4'>User not found.</div>";
    exit();
}

$display_name = !empty($user['full_name']) ? $user['full_name'] : $user['username'];
$base_salary = floatval($user['basic_salary']);

// --- FILTER LOGIC ---
// Default to current month and year if no filter is applied
$f_month = $_GET['f_month'] ?? date('F');
$f_year  = $_GET['f_year'] ?? date('Y');
$f_date  = $_GET['f_date'] ?? '';

$where_clauses = ["user_id = ?"];
$params = [$user_id];

if (!empty($f_month)) {
    $where_clauses[] = "pay_month = ?";
    $params[] = $f_month;
}
if (!empty($f_year)) {
    $where_clauses[] = "pay_year = ?";
    $params[] = $f_year;
}
if (!empty($f_date)) {
    $where_clauses[] = "payment_date = ?";
    $params[] = $f_date;
}

$where_sql = implode(" AND ", $where_clauses);

// --- FETCH PAYMENT HISTORY WITH FILTERS ---
$stmt = $db->prepare("SELECT * FROM payroll WHERE $where_sql ORDER BY pay_year DESC, payment_date DESC");
$stmt->execute($params);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- CALCULATE DUE ---
$total_paid_filtered = 0;
foreach ($payments as $pay) {
    $total_paid_filtered += floatval($pay['amount']);
}

// Due calculation (Only accurate if viewing a specific month/year)
$due_amount = 0;
$due_label = "Remaining Due";
if (!empty($f_month) && !empty($f_year)) {
    $due_amount = max(0, $base_salary - $total_paid_filtered);
    $due_label = "Due for $f_month $f_year";
} else {
    $due_label = "Select Month & Year to see Due";
    $due_amount = null; // Hide due if viewing 'All' months
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="users.php" class="text-white-50 text-decoration-none mb-2 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>
            <h3 class="text-white fw-bold mb-0">Payroll: <?php echo htmlspecialchars($display_name); ?></h3>
            <p class="text-gold small mb-0"><?php echo htmlspecialchars($user['job_title'] ?? 'Staff'); ?></p>
        </div>
        <button class="btn btn-rooq-primary rounded-pill px-4 shadow-lg" data-bs-toggle="modal" data-bs-target="#payModal">
            <i class="bi bi-wallet2 me-2"></i> Record Payment
        </button>
    </div>

    <?php echo $message; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="glass-panel p-3 text-center h-100" style="border-bottom: 3px solid #3498db;">
                <h6 class="text-white-50 small text-uppercase fw-bold mb-2">Basic Salary (Monthly)</h6>
                <h3 class="text-white mb-0 fw-bold"><?php echo number_format($base_salary, 2); ?> <small class="fs-6 text-white-50">SAR</small></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel p-3 text-center h-100" style="border-bottom: 3px solid #2ecc71;">
                <h6 class="text-white-50 small text-uppercase fw-bold mb-2">Total Paid (Filtered)</h6>
                <h3 class="text-success mb-0 fw-bold"><?php echo number_format($total_paid_filtered, 2); ?> <small class="fs-6 text-white-50">SAR</small></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel p-3 text-center h-100" style="border-bottom: 3px solid <?php echo ($due_amount > 0) ? '#e74c3c' : '#f1c40f'; ?>;">
                <h6 class="text-white-50 small text-uppercase fw-bold mb-2"><?php echo $due_label; ?></h6>
                <?php if ($due_amount !== null): ?>
                    <h3 class="text-white mb-0 fw-bold <?php echo ($due_amount > 0) ? 'text-danger' : 'text-warning'; ?>">
                        <?php echo number_format($due_amount, 2); ?> <small class="fs-6 text-white-50">SAR</small>
                    </h3>
                <?php else: ?>
                    <h5 class="text-white-50 mt-2 fst-italic">- N/A -</h5>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card-box p-3 mb-4">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="id" value="<?php echo $user_id; ?>">
            
            <div class="col-md-3">
                <label class="form-label text-gold small fw-bold"><i class="bi bi-calendar-month me-1"></i>Pay Month</label>
                <select name="f_month" class="form-select glass-input">
                    <option value="">-- All Months --</option>
                    <?php 
                    $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                    foreach($months as $m) {
                        $sel = ($m == $f_month) ? 'selected' : '';
                        echo "<option value='$m' $sel>$m</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label text-gold small fw-bold"><i class="bi bi-calendar-event me-1"></i>Pay Year</label>
                <input type="number" name="f_year" class="form-control glass-input" placeholder="e.g. 2024" value="<?php echo htmlspecialchars($f_year); ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label text-gold small fw-bold"><i class="bi bi-calendar-date me-1"></i>Exact Payment Date</label>
                <input type="date" name="f_date" class="form-control glass-input" value="<?php echo htmlspecialchars($f_date); ?>">
            </div>

            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-light w-100"><i class="bi bi-funnel me-2"></i>Filter</button>
                    <a href="user-payroll.php?id=<?php echo $user_id; ?>" class="btn btn-outline-danger" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-box p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <th class="py-3 ps-4 text-gold text-uppercase small">Month / Year</th>
                        <th class="py-3 text-gold text-uppercase small">Payment Date</th>
                        <th class="py-3 text-gold text-uppercase small">Method</th>
                        <th class="py-3 text-gold text-uppercase small">Amount Paid</th>
                        <th class="py-3 text-gold text-uppercase small">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $pay): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-white"><?php echo htmlspecialchars($pay['pay_month']) . ' ' . $pay['pay_year']; ?></td>
                            <td class="text-white-50"><i class="bi bi-calendar me-2"></i><?php echo date('d M, Y', strtotime($pay['payment_date'])); ?></td>
                            <td><span class="badge bg-secondary opacity-75"><?php echo htmlspecialchars($pay['payment_method']); ?></span></td>
                            <td class="text-success fw-bold">+<?php echo number_format($pay['amount'], 2); ?> SAR</td>
                            <td class="text-white-50 small fst-italic"><?php echo htmlspecialchars($pay['notes']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-white-50">No salary payments match your filter.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>

<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-wallet2 me-2 text-gold"></i>Record Salary Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">
                    <input type="hidden" name="add_payment" value="1">
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Salary Month</label>
                            <select name="pay_month" class="form-select glass-input" required>
                                <?php 
                                foreach($months as $m) {
                                    // Pre-select the month they are currently filtering by
                                    $sel = ($m == ($f_month ?: date('F'))) ? 'selected' : '';
                                    echo "<option value='$m' $sel>$m</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Year</label>
                            <input type="number" name="pay_year" class="form-control glass-input" value="<?php echo htmlspecialchars($f_year ?: date('Y')); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-gold small fw-bold">Amount to Pay (SAR)</label>
                            <input type="number" step="0.01" name="amount" class="form-control glass-input fw-bold text-success" 
                                   value="<?php echo ($due_amount !== null && $due_amount > 0) ? $due_amount : $base_salary; ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control glass-input" value="<?php echo htmlspecialchars($f_date ?: date('Y-m-d')); ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Transfer Method</label>
                            <select name="payment_method" class="form-select glass-input">
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-white-50 small">Notes / Deductions</label>
                            <textarea name="notes" class="form-control glass-input" rows="2" placeholder="Any deductions or bonuses?"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-rooq-primary px-4">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>