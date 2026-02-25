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

            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'milestones.php') ? 'active-glass' : ''; ?>" 
                   href="milestones.php">
                    <i class="bi bi-flag-fill me-3"></i> Milestones
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'documents.php') ? 'active-glass' : ''; ?>" 
                   href="documents.php">
                    <i class="bi bi-file-earmark-text-fill me-3"></i> Documents
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
            <a href="payroll.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'payroll.php' || basename($_SERVER['PHP_SELF']) == 'user-payroll.php') ? 'active bg-rooq-primary text-white shadow-sm' : 'text-white-50 hover-white'; ?> d-flex align-items-center rounded px-3 py-2" style="transition: all 0.3s ease;">
                <i class="bi bi-cash-coin fs-5 me-3 text-gold"></i>
                <span class="fw-bold">Payroll</span>
            </a>
        </li>
            <?php if ($_SESSION['role'] == '2'): ?>
            <li class="nav-item mb-2">
                <a class="nav-link <?php echo ($current_page == 'activity-logs.php') ? 'active-glass' : ''; ?>" 
                   href="activity-logs.php">
                    <i class="bi bi-activity me-3"></i> Activity Logs
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active-glass' : ''; ?>" 
                   href="settings.php">
                    <i class="bi bi-gear-fill me-3"></i> Settings
                </a>
            </li>
        </ul>
    </div>
</aside>