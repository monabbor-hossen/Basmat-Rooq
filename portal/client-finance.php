<?php
// portal/client-finance.php
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Security.php';
require_once 'includes/header.php'; // Header handles session check

$client_id = $_GET['id'] ?? null;
if (!$client_id) { header("Location: clients.php"); exit(); }

$db = (new Database())->getConnection();
$message = "";

// --- ADD PAYMENT LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    Security::checkCSRF($_POST['csrf_token']);
    
    $amount = floatval($_POST['amount']);
    $method = Security::clean($_POST['payment_method']);
    $status = Security::clean($_POST['payment_status']);
    $note   = Security::clean($_POST['notes']);

    $stmt = $db->prepare("INSERT INTO payments (client_id, amount, payment_method, payment_status, notes) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$client_id, $amount, $method, $status, $note])) {
        $message = "<div class='alert alert-success'>Payment recorded successfully!</div>";
    }
}

// --- FETCH DATA ---
// 1. Client Info
$stmt = $db->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Payments List
$stmt = $db->prepare("SELECT * FROM payments WHERE client_id = ? ORDER BY payment_date DESC");
$stmt->execute([$client_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Calculate Totals
$total_paid = 0;
foreach($payments as $p) {
    if($p['payment_status'] == 'Completed') $total_paid += $p['amount'];
}
$due_amount = $client['contract_value'] - $total_paid;
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="clients.php" class="text-white-50 text-decoration-none hover-white">
                    <i class="bi bi-arrow-left me-2"></i> Back to Clients
                </a>
                <h4 class="text-white fw-bold mb-0">Finance: <?php echo htmlspecialchars($client['company_name']); ?></h4>
            </div>

            <?php echo $message; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card-box text-center border-warning">
                        <small class="text-gold text-uppercase fw-bold">Contract Value</small>
                        <h2 class="text-white mt-2"><?php echo number_format($client['contract_value'], 2); ?> SAR</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box text-center">
                        <small class="text-success text-uppercase fw-bold">Total Paid</small>
                        <h2 class="text-success mt-2"><?php echo number_format($total_paid, 2); ?> SAR</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box text-center <?php echo ($due_amount > 0) ? 'border-danger' : 'border-success'; ?>">
                        <small class="text-danger text-uppercase fw-bold">Due Amount</small>
                        <h2 class="text-danger mt-2"><?php echo number_format(max(0, $due_amount), 2); ?> SAR</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card-box">
                        <h5 class="text-gold mb-3"><i class="bi bi-plus-circle me-2"></i>Add Payment</h5>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">
                            <input type="hidden" name="add_payment" value="1">

                            <div class="mb-3">
                                <label class="text-white-50 small mb-1">Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control glass-input" required placeholder="0.00">
                            </div>

                            <div class="mb-3">
                                <label class="text-white-50 small mb-1">Payment Method</label>
                                <select name="payment_method" class="form-select glass-input">
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="text-white-50 small mb-1">Status</label>
                                <select name="payment_status" class="form-select glass-input">
                                    <option value="Completed">Completed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="text-white-50 small mb-1">Notes</label>
                                <textarea name="notes" class="form-control glass-input" rows="2"></textarea>
                            </div>

                            <button type="submit" class="btn btn-rooq-primary w-100 fw-bold">Record Payment</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card-box">
                        <h5 class="text-white fw-bold mb-3">Transaction History</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                                <thead>
                                    <tr class="text-white-50 border-bottom border-secondary">
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments as $p): ?>
                                    <tr>
                                        <td class="text-white-50 small">#<?php echo $p['payment_id']; ?></td>
                                        <td><?php echo date('d M Y', strtotime($p['payment_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($p['payment_method']); ?></td>
                                        <td>
                                            <?php if($p['payment_status'] == 'Completed'): ?>
                                                <span class="badge bg-success text-dark">Completed</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            <?php echo number_format($p['amount'], 2); ?> SAR
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($payments)): ?>
                                        <tr><td colspan="5" class="text-center text-white-50 py-3">No transactions found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<style>
    .glass-input { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; }
    .glass-input:focus { border-color: #D4AF37; background: rgba(255,255,255,0.1); color: white; }
    .glass-input option { background: #33000d; }
    .border-warning { border: 1px solid rgba(255, 193, 7, 0.3) !important; }
    .border-danger { border: 1px solid rgba(220, 53, 69, 0.3) !important; }
</style>
<?php
require_once 'includes/footer.php'
?>