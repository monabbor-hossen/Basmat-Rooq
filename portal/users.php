<?php
// portal/users.php

// 1. Include Header (Handles Security Check & Session)
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

// 2. Database Connection
$db = (new Database())->getConnection();

// 3. Fetch ONLY Admin (2) and Staff (1) - Exclude Clients
// Assuming 'role' ENUM: '1' = Staff, '2' = Admin, '3' = Client (if exists)
$query = "SELECT id, username, role,status, created_at FROM users 
          WHERE role IN ('1', '2') 
          ORDER BY role DESC, created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to map role ID to Name
function getRoleName($roleId) {
    return match($roleId) {
        '2' => '<span class="badge bg-gold text-dark">Admin</span>',
        '1' => '<span class="badge bg-light text-dark opacity-75">Staff</span>',
        default => '<span class="badge bg-secondary">Unknown</span>'
    };
}
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-white fw-bold">System Users</h3>
        <a href="user-add.php" class="btn btn-rooq-primary btn-sm px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Add New User
        </a>
    </div>

    <div class="card-box p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle" style="background: transparent;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <th class="py-3 ps-4 text-gold text-uppercase small">ID</th>
                        <th class="py-3 text-gold text-uppercase small">User Identity</th>
                        <th class="py-3 text-gold text-uppercase small">Access Level</th>
                        <th class="py-3 text-gold text-uppercase small">Status</th>

                        <th class="py-3 text-gold text-uppercase small">Joined Date</th>
                        <th class="py-3 text-end pe-4 text-gold text-uppercase small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="ps-4 text-white-50">#<?php echo $user['id']; ?></td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-small me-3">
                                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                </div>
                                <span
                                    class="fw-bold text-white"><?php echo htmlspecialchars($user['username']); ?></span>
                            </div>
                        </td>

                        <td><?php echo getRoleName($user['role']); ?></td>
                        <td>
    <div class="form-check form-switch">
        <input class="form-check-input status-toggle" type="checkbox" 
               id="statusSwitch_<?php echo $user['id']; ?>" 
               data-id="<?php echo $user['id']; ?>" 
               data-type="user" <?php echo ($user['is_active'] == 1) ? 'checked' : ''; ?>>
        <label class="form-check-label" for="statusSwitch_<?php echo $user['id']; ?>">
            <?php echo ($user['is_active'] == 1) ? 'Active' : 'Inactive'; ?>
        </label>
    </div>
</td>
                        <td class="text-white-50">
                            <i class="bi bi-calendar-event me-2 small"></i>
                            <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                        </td>

                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">

                                <a href="user-edit.php?id=<?php echo $user['id']; ?>"
                                    class="btn btn-sm btn-outline-light border-0 opacity-50 hover-opacity-100"
                                    title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <?php if($_SESSION['user_id'] != $user['id']): ?>
                                <form action="user-delete.php" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                                    style="display:inline;">
                                    <input type="hidden" name="csrf_token"
                                        value="<?php echo Security::generateCSRF(); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger border-0 opacity-50 hover-opacity-100"
                                        title="Delete User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-white-50">No admin or staff users found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>


<?php

require_once 'includes/footer.php'

?>