<aside class="portal-sidebar d-none d-lg-block">
    <div class="sidebar-header p-4">
        <small class="text-uppercase text-gold fw-bold" style="letter-spacing: 1px;">Menu</small>
    </div>
    
    <ul class="nav flex-column gap-2 px-3">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link active d-flex align-items-center">
                <i class="bi bi-grid-fill me-3"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="milestones.php" class="nav-link d-flex align-items-center">
                <i class="bi bi-diagram-3-fill me-3"></i>
                <span>Milestones</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="documents.php" class="nav-link d-flex align-items-center">
                <i class="bi bi-file-earmark-text-fill me-3"></i>
                <span>Documents</span>
            </a>
        </li>
        <li class="nav-item mt-4">
            <small class="text-uppercase text-gold fw-bold px-3" style="letter-spacing: 1px;">Account</small>
        </li>
        <li class="nav-item">
            <a href="profile.php" class="nav-link d-flex align-items-center">
                <i class="bi bi-person-circle me-3"></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo BASE_URL; ?>public/logout.php" class="nav-link text-danger d-flex align-items-center">
                <i class="bi bi-box-arrow-left me-3"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>