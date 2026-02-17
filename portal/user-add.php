<?php
// portal/user-add.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Security Check
    Security::checkCSRF($_POST['csrf_token']);

    // 2. Sanitize & Validate
    $username = Security::clean($_POST['username']);
    $password = $_POST['password']; // Don't clean password yet, hash it directly
    $role     = Security::clean($_POST['role']);

    if (strlen($password) < 6) {
        $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Password must be at least 6 characters.</div>";
    } else {
        // 3. Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $db = (new Database())->getConnection();
            $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>User account created successfully!</div>";
            }
        } catch (PDOException $e) {
            // Handle duplicate username error
            if ($e->getCode() == 23000) {
                 $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Error: Username already exists.</div>";
            } else {
                 $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Database Error: " . $e->getMessage() . "</div>";
            }
        }
    }
}
?>

        <div class="container-fluid">
            <a href="users.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card-box">
                        <div class="text-center mb-4">
                            <div class="avatar-icon mx-auto mb-3">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h4 class="text-white fw-bold mb-0">Create New User</h4>
                            <p class="text-white-50 small">Grant system access to staff or admins</p>
                        </div>
                        
                        <?php echo $message; ?>

                        <form method="POST" autocomplete="off">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                            <div class="mb-3">
                                <label class="form-label text-gold small text-uppercase fw-bold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text glass-input border-end-0 text-white-50"><i class="bi bi-person"></i></span>
                                    <input type="text" name="username" class="form-control glass-input border-start-0 ps-0" required placeholder="johndoe">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-gold small text-uppercase fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text glass-input border-end-0 text-white-50"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control glass-input border-start-0 ps-0" required placeholder="••••••••">
                                </div>
                                <div class="form-text text-white-50 small">Minimum 6 characters required.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-gold small text-uppercase fw-bold">Access Level</label>
                                <select name="role" class="form-select glass-input" required>
                                    <option value="" selected disabled>Select Role...</option>
                                    <option value="1">Staff (Standard Access)</option>
                                    <option value="2">Admin (Full Access)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold">
                                Create Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

<style>

</style>

<?php

require_once 'includes/footer.php'
?>