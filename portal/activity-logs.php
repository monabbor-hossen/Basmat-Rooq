<?php
// portal/activity-logs.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$db = (new Database())->getConnection();

// Fetch the latest 500 activity logs
$stmt = $db->prepare("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 500");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-white fw-bold mb-0">System Activity Logs</h3>
            <p class="text-white-50 small mb-0">Live tracking of all user logins and page clicks.</p>
        </div>
    </div>

    <div class="card-box p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <th class="py-3 ps-4 text-gold text-uppercase small">Timestamp</th>
                        <th class="py-3 text-gold text-uppercase small">User</th>
                        <th class="py-3 text-gold text-uppercase small">Type</th>
                        <th class="py-3 text-gold text-uppercase small">Action</th>
                        <th class="py-3 text-gold text-uppercase small">URL Path</th>
                        <th class="py-3 text-end pe-4 text-gold text-uppercase small">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($logs) > 0): ?>
                        <?php foreach ($logs as $log): 
                            $badge_color = ($log['user_type'] == 'client') ? 'bg-info' : 'bg-gold text-dark';
                            // Highlight Login actions
                            $action_class = (strpos($log['action'], 'Logged In') !== false) ? 'text-success fw-bold' : 'text-white';
                        ?>
                        <tr>
                            <td class="ps-4 text-white-50 small"><?php echo date('M d, Y - h:i:s A', strtotime($log['created_at'])); ?></td>
                            <td class="fw-bold text-white"><i class="bi bi-person-circle me-2 text-white-50"></i><?php echo htmlspecialchars($log['username']); ?></td>
                            <td><span class="badge <?php echo $badge_color; ?> opacity-75"><?php echo strtoupper($log['user_type']); ?></span></td>
                            <td class="<?php echo $action_class; ?>"><?php echo htmlspecialchars($log['action']); ?></td>
                            <td class="text-white-50 small" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($log['url']); ?>">
                                <?php echo htmlspecialchars($log['url']); ?>
                            </td>
                            <td class="text-end pe-4 text-white-50 small font-monospace"><?php echo htmlspecialchars($log['ip_address']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-white-50">No activity recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>

<?php require_once 'includes/footer.php'; ?>