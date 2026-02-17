<?php
// portal/clients.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

// 1. Fetch Clients (Removed license_scope)
$db = (new Database())->getConnection();
$query = "SELECT c.*, 
          COALESCE((SELECT SUM(amount) FROM payments WHERE client_id = c.client_id AND payment_status = 'Completed'), 0) as total_paid
          FROM clients c 
          ORDER BY c.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-white fw-bold mb-0">Client Portfolios</h3>
            <p class="text-white-50 small mb-0">Manage active MISA licenses and investments</p>
        </div>
        <a href="client-add.php" class="btn btn-rooq-primary px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Add New Client
        </a>
    </div>

    <div class="card-box p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <th class="py-3 ps-4 text-gold text-uppercase small">ID</th>
                        <th class="py-3 text-gold text-uppercase small">Company Info</th>
                        <th class="py-3 text-gold text-uppercase small">Contact Details</th>
                        <th class="py-3 text-end pe-4 text-gold text-uppercase small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($clients) > 0): ?>
                    <?php foreach ($clients as $client): 
    $due = $client['contract_value'] - $client['total_paid'];
    
    // Determine Status Badge
    if ($client['contract_value'] == 0) {
        $status_badge = '<span class="badge bg-secondary">No Contract</span>';
    } elseif ($due <= 0) {
        $status_badge = '<span class="badge bg-success text-dark">Paid</span>';
    } elseif ($client['total_paid'] > 0) {
        $status_badge = '<span class="badge bg-warning text-dark">Partial</span>';
    } else {
        $status_badge = '<span class="badge bg-danger">Unpaid</span>';
    }
?>
                    <tr>
                        <td class="ps-4 text-white-50">#<?php echo $client['client_id']; ?></td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-icon me-3">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white">
                                        <?php echo htmlspecialchars($client['company_name']); ?></div>
                                    <div class="small text-white-50">
                                        <?php echo htmlspecialchars($client['client_name']); ?></div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex flex-column small">
                                <span class="text-white-50"><i
                                        class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($client['email']); ?></span>
                                <span class="text-white-50"><i
                                        class="bi bi-telephone me-2"></i><?php echo htmlspecialchars($client['phone_number']); ?></span>
                            </div>
                        </td>
                        <td>
                            <?php echo $status_badge; ?>
                            <div class="small text-white-50 mt-1">
                                Due: <?php echo number_format(max(0, $due)); ?> SAR
                            </div>
                        </td>



                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="client-finance.php?id=<?php echo $client['client_id']; ?>"
                                    class="btn btn-sm btn-outline-warning border-0 opacity-75 hover-opacity-100"
                                    title="Manage Finance">
                                    <i class="bi bi-cash-stack"></i>
                                </a>
                                <a href="client-edit.php?id=<?php echo $client['client_id']; ?>"
                                    class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100"
                                    title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100"
                                    title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-white-50">No clients found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>

<style>
    /* .avatar-icon {
        width: 40px;
        height: 40px;
        background: rgba(212, 175, 55, 0.1);
        color: #D4AF37;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: 1px solid rgba(212, 175, 55, 0.2);
    } */
</style>
<?php 

require_once 'includes/footer.php'
?>