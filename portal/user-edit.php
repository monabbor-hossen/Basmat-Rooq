<?php
// portal/user-edit.php
require_once 'includes/header.php';
require_once __DIR__ . '/../app/Config/Database.php';

$message = "";
$user_id = $_GET['id'] ?? null;

if (!$user_id) {
    echo "<script>window.location.href='users.php';</script>";
    exit();
}

$db = (new Database())->getConnection();

// --- HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::checkCSRF($_POST['csrf_token']);

    $username  = Security::clean($_POST['username']);
    $role      = Security::clean($_POST['role']);
    $password  = $_POST['password']; 
    
    $full_name = Security::clean($_POST['full_name']);
    $email     = Security::clean($_POST['email']);
    $phone     = Security::clean($_POST['phone']);
    $job_title = Security::clean($_POST['job_title']);
    $basic_salary = floatval($_POST['basic_salary']);
    
    $joining_date = !empty($_POST['joining_date']) ? Security::clean($_POST['joining_date']) : null;
    $resigning_date = !empty($_POST['resigning_date']) ? Security::clean($_POST['resigning_date']) : null;

    try {
        $sql = "UPDATE users SET 
                username = :user, role = :role, 
                full_name = :full_name, email = :email, 
                phone = :phone, job_title = :job_title, 
                basic_salary = :basic_salary, 
                joining_date = :joining_date, 
                resigning_date = :resigning_date";
        
        $params = [
            ':user'      => $username,
            ':role'      => $role,
            ':full_name' => $full_name,
            ':email'     => $email,
            ':phone'     => $phone,
            ':job_title' => $job_title,
            ':basic_salary' => $basic_salary,
            ':joining_date' => $joining_date,
            ':resigning_date' => $resigning_date,
            ':id'        => $user_id
        ];

        if (!empty($password)) {
            if (strlen($password) < 6) {
                $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Password must be at least 6 characters.</div>";
            } else {
                $sql .= ", password = :pass";
                $params[':pass'] = password_hash($password, PASSWORD_DEFAULT);
            }
        }
        
        $sql .= " WHERE id = :id";

        if (empty($message)) {
            $stmt = $db->prepare($sql);
            if ($stmt->execute($params)) {
                $message = "<div class='alert alert-success bg-success bg-opacity-25 text-white border-success'>User profile updated successfully!</div>";
            }
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger bg-danger bg-opacity-25 text-white border-danger'>Database Error: " . $e->getMessage() . "</div>";
    }
}

// --- FETCH CURRENT USER DATA SECURELY ---
try {
    $stmt = $db->prepare("SELECT id, username, role, full_name, email, phone, job_title, basic_salary, joining_date, resigning_date FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<div class='alert alert-warning m-4'>User not found.</div>";
        require_once 'includes/footer.php';
        exit();
    }
} catch (PDOException $e) {
    // If columns are missing, show error instead of crashing the page and freezing the loader
    echo "<div class='alert alert-danger m-4 fw-bold'>Database Error: Please ensure you ran the SQL commands to add joining_date and resigning_date columns! <br><small>" . $e->getMessage() . "</small></div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<div class="container-fluid">
    <a href="users.php" class="text-white-50 text-decoration-none mb-3 d-inline-block hover-white">
        <i class="bi bi-arrow-left me-2"></i> Back to Users
    </a>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-box">
                <div class="d-flex align-items-center mb-4 border-bottom border-light border-opacity-10 pb-3">
                    <div class="avatar-icon me-3" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <h4 class="text-white fw-bold mb-0">Edit User Profile</h4>
                        <p class="text-white-50 small mb-0">ID: #<?php echo htmlspecialchars($user['id']); ?></p>
                    </div>
                </div>

                <?php echo $message; ?>

                <form method="POST" action="user-edit.php?id=<?php echo $user_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRF(); ?>">

                    <h6 class="text-gold mb-3 text-uppercase fw-bold" style="font-size: 0.8rem;"><i class="bi bi-person-lines-fill me-2"></i>Personal Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control glass-input" required value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Job Title / Designation</label>
                            <input type="text" name="job_title" class="form-control glass-input" value="<?php echo htmlspecialchars($user['job_title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control glass-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Phone Number</label>
                            <input type="tel" name="phone" class="form-control glass-input" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="col-md-12 border-top border-secondary border-opacity-25 pt-3 mt-3">
                            <label class="form-label text-gold small fw-bold"><i class="bi bi-cash-stack me-2"></i>Basic Salary (Monthly)</label>
                            <div class="input-group">
                                <span class="input-group-text glass-input border-end-0 text-white-50">SAR</span>
                                <input type="number" step="0.01" name="basic_salary" class="form-control glass-input border-start-0 ps-0" value="<?php echo htmlspecialchars($user['basic_salary'] ?? '0.00'); ?>" required>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label text-gold small fw-bold"><i class="bi bi-calendar-check me-2"></i>Joining Date</label>
                            <input type="text" name="joining_date" class="form-control glass-input rooq-date" data-hide-buttons="true" value="<?php echo htmlspecialchars($user['joining_date'] ?? date('Y-m-d')); ?>" required>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label text-danger small fw-bold"><i class="bi bi-calendar-x me-2"></i>Resigning Date (If left)</label>
                            <input type="text" name="resigning_date" class="form-control glass-input rooq-date border-danger text-danger" data-hide-buttons="true" placeholder="Leave blank if active" value="<?php echo htmlspecialchars($user['resigning_date'] ?? ''); ?>">
                        </div>
                    </div>

                    <h6 class="text-gold mb-3 text-uppercase fw-bold mt-4" style="font-size: 0.8rem;"><i class="bi bi-shield-lock me-2"></i>Account Security</h6>
                    <div class="row g-3 mb-4 p-3 rounded" style="background: rgba(0,0,0,0.2);">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">System Username</label>
                            <div class="input-group">
                                <span class="input-group-text glass-input border-end-0 text-white-50"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control glass-input border-start-0 ps-0" required value="<?php echo htmlspecialchars($user['username']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 small fw-bold">Access Level</label>
                            <select name="role" class="form-select glass-input">
                                <option value="1" <?php echo ($user['role'] == '1') ? 'selected' : ''; ?>>Staff (Standard Access)</option>
                                <option value="2" <?php echo ($user['role'] == '2') ? 'selected' : ''; ?>>Admin (Full Access)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-white-50 small fw-bold">Reset Password (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text glass-input border-end-0 text-white-50"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control glass-input border-start-0 ps-0" placeholder="Leave blank to keep current password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-rooq-primary w-100 py-3 fw-bold mt-2">Update User Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>