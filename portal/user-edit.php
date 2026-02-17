<?php
// portal/user-edit.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";
$user_id = $_GET['id'] ?? null;

// Redirect if no ID provided
if (!$user_id) {
    header("Location: users.php");
    exit();
}

$db = (new Database())->getConnection();

// Handle Form Submission (Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    $username = Security::clean($_POST['username']);
    $role     = Security::clean($_POST['role']);
    $password = $_POST['password']; // Raw password

    try {
        // Dynamic Query: Update password only if provided
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $message = "<div class='alert alert-danger'>Password must be at least 6 characters.</div>";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE users SET username = :user, role = :role, password = :pass WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':pass', $hashed);
            }
        } else {
            $query = "UPDATE users SET username = :user, role = :role WHERE id = :id";
            $stmt = $db->prepare($query);
        }

        if (empty($message)) {
            $stmt->bindParam(':user', $username);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $user_id);
            
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>User updated successfully!</div>";
            }
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $message = "<div class='alert alert-danger'>Username already exists.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
}

// Fetch Current User Data
$stmt = $db->prepare("SELECT id, username, role FROM users WHERE id = :id LIMIT 1");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger m-4'>User not found.</div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="w-100 p-4">
        <div class="container-fluid">
            <a href="users.php" class="text-white-50 text-decoration-none mb-3 d-inline-block">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="text-white fw-bold mb-4">Edit User: <?php echo htmlspecialchars($user['username']); ?></h4>
                        <?php echo $message; ?>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <div class="mb-3">
                                <label class="text-gold small fw-bold">Username</label>
                                <input type="text" name="username" class="form-control glass-input" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="text-gold small fw-bold">Role</label>
                                <select name="role" class="form-select glass-input">
                                    <option value="1" <?php echo ($user['role'] == '1') ? 'selected' : ''; ?>>Staff</option>
                                    <option value="2" <?php echo ($user['role'] == '2') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="text-gold small fw-bold">New Password (Optional)</label>
                                <input type="password" name="password" class="form-control glass-input" placeholder="Leave blank to keep current password">
                            </div>

                            <button type="submit" class="btn btn-rooq-primary w-100 fw-bold">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    .glass-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }
    .glass-input:focus {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-color: #D4AF37;
    }
    select.glass-input option { color: black; }
</style>

<?php

require_once 'includes/footer.php'
?>