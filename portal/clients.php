<?php
// portal/clients.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

// 1. Fetch Clients
$db = (new Database())->getConnection();
$query = "SELECT client_id, company_name, client_name, phone_number, email, license_scope, created_at 
          FROM clients 
          ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
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
                                <th class="py-3 text-gold text-uppercase small">License Scope</th>
                                <th class="py-3 text-end pe-4 text-gold text-uppercase small">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($clients) > 0): ?>
                                <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td class="ps-4 text-white-50">#<?php echo $client['client_id']; ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-icon me-3">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-white"><?php echo htmlspecialchars($client['company_name']); ?></div>
                                                <div class="small text-white-50"><?php echo htmlspecialchars($client['client_name']); ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column small">
                                            <span class="text-white-50"><i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($client['email']); ?></span>
                                            <span class="text-white-50"><i class="bi bi-telephone me-2"></i><?php echo htmlspecialchars($client['phone_number']); ?></span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-gold text-white-50 border border-warning">
                                            <?php echo htmlspecialchars($client['license_scope']); ?>
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="client-edit.php?id=<?php echo $client['client_id']; ?>" 
                                                class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100" 
                                                title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            <button class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-white-50">No clients found in the database.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .avatar-icon {
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
    }
</style>

<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>