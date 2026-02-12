<?php
// includes/sidebar.php
?>
<aside class="rooq-sidebar" id="rooqSidebar">
    <div class="sidebar-content py-4">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-th-large"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="milestones.php">
                    <i class="fas fa-tasks"></i> <span>Milestones</span>
                </a>
            </li>
            <?php if ($_SESSION['role'] == '2'): // Admin Only ?>
            <li class="nav-item mt-4 px-3 mb-2 text-uppercase small opacity-50">Administration</li>
            <li class="nav-item">
                <a class="nav-link" href="../management/user-add.php">
                    <i class="fas fa-user-plus"></i> <span>Add New User</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>