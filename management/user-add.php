<?php
require_once __DIR__ . '/../app/Config/Config.php'; //
require_once __DIR__ . '/../app/Config/Database.php'; 
require_once __DIR__ . '/../app/Helpers/Security.php'; //
require_once __DIR__ . '/../includes/header.php'; //

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF Token for security
    Security::checkCSRF($_POST['csrf_token']);

    // Sanitize and hash data
    $username = Security::clean($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing
    $role = Security::clean($_POST['role']);

    try {
        $db = (new Database())->getConnection();
        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>User added successfully!</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container mt-5">
    <div class="rooq-card p-4 mx-auto" style="max-width: 500px;">
        <h2 class="text-center mb-4"><?php echo ($lang == 'ar' ? 'إضافة مستخدم جديد' : 'Add New User'); ?></h2>
        
        <?php echo $message; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="1">Staff (Level 1)</option>
                    <option value="2">Admin (Level 2)</option>
                    <option value="3">Client (Level 3)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-rooq-primary w-100 py-2 fw-bold">
                <?php echo ($lang == 'ar' ? 'حفظ المستخدم' : 'Save User'); ?>
            </button>
        </form>
    </div>
</div>