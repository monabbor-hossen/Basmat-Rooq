<?php
// Get the current page name (e.g., 'dashboard.php')
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="portal-sidebar" id="portalSidebar">
    <div class="sidebar-content h-100 py-4">
        <p class="px-4 text-white-50 small text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">Main Menu</p>

        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active-glass' : ''; ?>"
                    href="dashboard.php">
                    <i class="bi bi-grid-fill me-3"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (in_array($current_page, ['clients.php', 'client-add.php', 'client-edit.php'])) ? 'active-glass' : ''; ?>"
                    href="clients.php">
                    <i class="bi bi-people-fill me-3"></i> Clients
                </a>
            </li>

        </ul>

        <p class="px-4 text-white-50 small text-uppercase fw-bold mb-3 mt-4" style="letter-spacing: 1px;">System</p>

        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link <?php echo (in_array($current_page, ['users.php', 'user-add.php', 'user-edit.php'])) ? 'active-glass' : ''; ?>"
                    href="users.php">
                    <i class="bi bi-shield-lock-fill me-3"></i> User Access
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="payroll.php"
                    class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'payroll.php' || basename($_SERVER['PHP_SELF']) == 'user-payroll.php') ? 'active-glass' : ''; ?> "
                    style="transition: all 0.3s ease;">
                    <i class="bi bi-cash-coin me-3"></i>
                    <span class="fw-bold">Payroll</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active-glass' : ''; ?>"
                    href="settings.php">
                    <i class="bi bi-gear-fill me-3"></i> Settings
                </a>
            </li>
            <?php if ($_SESSION['role'] == '2'): ?>
            
            <li class="nav-item mb-2 mt-4">
                <div class="text-uppercase text-white-50 small fw-bold px-3 mb-2"
                    style="font-size: 0.7rem; letter-spacing: 1px;">Security</div>
                <a href="activity-logs.php"
                    class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'activity-logs.php') ? 'active-glass' : ''; ?>"
                    style="transition: all 0.3s ease;">
                    <i class="bi bi-activity me-3"></i>
                    <span class="fw-bold">Activity Logs</span>
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="audit-finance.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'audit-finance.php') ? 'active-glass' : ''; ?>" style="transition: all 0.3s ease;">
                    <i class="bi bi-bank me-3"></i>
                    <span class="fw-bold">Financial Audit</span>
                </a>
            </li>
            <?php endif; ?>
            
        </ul>
    </div>
</aside>