<?php
// portal/clients.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$db = (new Database())->getConnection();

// 1. Fetch Clients
$query = "SELECT c.*, 
          w.license_scope_status, w.hire_foreign_company, w.misa_application, w.sbc_application, 
          w.article_association, w.qiwa, w.muqeem, w.gosi, w.chamber_commerce,
          COALESCE((SELECT SUM(amount) FROM payments WHERE client_id = c.client_id AND payment_status = 'Completed'), 0) as total_paid
          FROM clients c 
          LEFT JOIN workflow_tracking w ON c.client_id = w.client_id";
$stmt = $db->prepare($query);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Pre-calculate Data
foreach ($clients as &$client) {
    // Progress
    $steps_to_check = [
        $client['hire_foreign_company'] ?? '', $client['misa_application'] ?? '',
        $client['sbc_application'] ?? '',      $client['article_association'] ?? '',
        $client['qiwa'] ?? '',                 $client['muqeem'] ?? '',
        $client['gosi'] ?? '',                 $client['chamber_commerce'] ?? ''
    ];
    $approved_count = 0;
    foreach($steps_to_check as $status) { if($status === 'Approved') $approved_count++; }
    $client['progress_val'] = ($approved_count / 8) * 100;
    $client['approved_count'] = $approved_count;

    // Due
    $client['due_val'] = $client['contract_value'] - $client['total_paid'];
}
unset($client);

// 3. Sort Logic
$sort = $_GET['sort'] ?? 'id';
$dir  = $_GET['dir'] ?? 'desc';
$next_dir = ($dir === 'asc') ? 'desc' : 'asc';

usort($clients, function($a, $b) use ($sort, $dir) {
    $valA = $valB = 0;
    switch ($sort) {
        case 'company': $valA = strtolower($a['company_name']); $valB = strtolower($b['company_name']); return ($dir === 'asc') ? strcmp($valA, $valB) : strcmp($valB, $valA);
        case 'payment': $valA = $a['due_val']; $valB = $b['due_val']; break;
        case 'progress': $valA = $a['progress_val']; $valB = $b['progress_val']; break;
        case 'id': default: $valA = $a['client_id']; $valB = $b['client_id']; break;
    }
    if ($valA == $valB) return 0;
    return ($dir === 'asc') ? (($valA < $valB) ? -1 : 1) : (($valA > $valB) ? -1 : 1);
});

function sortLink($key, $label, $currentSort, $nextDir) {
    $active = ($currentSort === $key) ? 'text-white fw-bold' : 'text-gold';
    $icon = ($currentSort === $key) ? (($nextDir === 'asc') ? '<i class="bi bi-arrow-down-short"></i>' : '<i class="bi bi-arrow-up-short"></i>') : '';
    return "<a href='?sort=$key&dir=$nextDir' class='text-decoration-none text-uppercase small $active'>$label $icon</a>";
}
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div><h3 class="text-white fw-bold mb-0">Client Portfolios</h3><p class="text-white-50 small mb-0">Manage active MISA licenses and investments</p></div>
                <a href="client-add.php" class="btn btn-rooq-primary px-4 rounded-pill"><i class="bi bi-plus-lg me-2"></i> Add New Client</a>
            </div>

            <div class="card-box p-0 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.05);">
                                <th class="py-3 ps-2 text-end"><?php echo sortLink('id', 'SL', $sort, $next_dir); ?></th>
                                <th class="py-3"><?php echo sortLink('company', 'Company Info', $sort, $next_dir); ?></th>
                                <th class="py-3"><?php echo sortLink('progress', 'Progress', $sort, $next_dir); ?></th>
                                <th class="py-3 text-gold text-uppercase small">Contact Details</th>
                                <th class="py-3"><?php echo sortLink('payment', 'Payment', $sort, $next_dir); ?></th>
                                <th class="py-3 text-center pe-4 text-gold text-uppercase small">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($clients) > 0): ?>
                            <?php foreach ($clients as $client): 
                                    $due = $client['due_val'];
                                    $prog = round($client['progress_val']);
                                    $prog_color = ($prog == 100) ? 'bg-success' : (($prog > 30) ? 'bg-warning' : 'bg-danger');
                                    
                                    if ($client['contract_value'] == 0) $status_badge = '<span class="badge bg-secondary">No Contract</span>';
                                    elseif ($due <= 0) $status_badge = '<span class="badge bg-success text-dark">Paid</span>';
                                    elseif ($client['total_paid'] > 0) $status_badge = '<span class="badge bg-warning text-dark">Partial</span>';
                                    else $status_badge = '<span class="badge bg-danger">Unpaid</span>';

                                    // PREPARE JSON FOR VIEW MODAL
                                    $clientJson = htmlspecialchars(json_encode($client), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td class="ps-4 text-white-50 fw-bold">#<?php echo $client['client_id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-icon me-2 flex-shrink-0"><i class="bi bi-building"></i></div>
                                        <div>
                                            <div class="fw-bold text-white"><?php echo htmlspecialchars($client['company_name']); ?></div>
                                            <div class="small text-white-50"><?php echo htmlspecialchars($client['client_name']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px; background: rgba(255,255,255,0.1); width: 80px;">
                                            <div class="progress-bar <?php echo $prog_color; ?>" role="progressbar" style="width: <?php echo $prog; ?>%"></div>
                                        </div>
                                        <span class="small text-white fw-bold"><?php echo $prog; ?>%</span>
                                    </div>
                                    <div class="text-white-50" style="font-size: 0.7rem;"><?php echo $client['approved_count']; ?>/8 Approved</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex align-items-center text-nowrap"><i class="bi bi-envelope text-gold me-2"></i><span class="text-white-50 small"><?php echo htmlspecialchars($client['email']); ?></span></div>
                                        <div class="d-flex align-items-center text-nowrap"><i class="bi bi-telephone text-gold me-2"></i><span class="text-white-50 small"><?php echo htmlspecialchars($client['phone_number']); ?></span></div>
                                    </div>
                                </td>
                                <td><?php echo $status_badge; ?><div class="small text-white-50 mt-1">Due: <?php echo number_format(max(0, $due)); ?> SAR</div></td>
                                
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="client-finance.php?id=<?php echo $client['client_id']; ?>" class="btn btn-sm btn-outline-warning border-0 opacity-75 hover-opacity-100"><i class="bi bi-cash-stack"></i></a>
                                        <a href="client-edit.php?id=<?php echo $client['client_id']; ?>" class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100"><i class="bi bi-pencil-square"></i></a>
                                        
                                        <button class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100" 
                                                title="View Details"
                                                data-client='<?php echo $clientJson; ?>'
                                                onclick="openViewModal(this)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5 text-white-50">No clients found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .avatar-icon { width: 40px; height: 40px; background: rgba(212, 175, 55, 0.1); color: #D4AF37; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 1px solid rgba(212, 175, 55, 0.2); }
</style>

<?php
require_once "includes/footer.php"
?>