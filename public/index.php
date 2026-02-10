<?php
// Start session and include header (which handles Config and Translator)
require_once '../includes/header.php';
?>

<header class="rooq-navbar py-5 mb-5 text-center">
    <div class="container">
        <h1 class="display-4 text-white">Basmat Rooq</h1>
        <p class="lead text-white-50">Digitizing MISA License Tracking for Global Investors</p>
        <div class="mt-4">
            <a href="login.php" class="btn-rooq-outline">Client Login</a>
            <a href="services.php" class="btn-rooq-primary ms-2">Our Services</a>
        </div>
    </div>
</header>

<main class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="rooq-card">
                <div class="rooq-card-header">
                    <h2><?php echo $text['milestones'] ?? 'Milestone Tracking'; ?></h2>
                </div>
                <p>We provide real-time tracking for Saudi Ministry of Investment (MISA) license applications. Our portal allows clients to monitor every stage of their business setup in the Kingdom.</p>
                
                <div class="row text-center mt-4">
                    <div class="col-4">
                        <img src="<?php echo BASE_URL; ?>assets/img/icons/qiwa.png" alt="Qiwa" style="height: 50px;">
                        <p class="small mt-2">QIWA</p>
                    </div>
                    <div class="col-4">
                        <img src="<?php echo BASE_URL; ?>assets/img/icons/MUQEEM.png" alt="Muqeem" style="height: 50px;">
                        <p class="small mt-2">MUQEEM</p>
                    </div>
                    <div class="col-4">
                        <img src="<?php echo BASE_URL; ?>assets/img/icons/GOSI-General-Organization-for-Social-Insurance.jpg" alt="GOSI" style="height: 50px;">
                        <p class="small mt-2">GOSI</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rooq-card" style="border-top-color: var(--rooq-gold);">
                <h3>Company Info</h3>
                <ul class="list-unstyled">
                    <li><strong>Location:</strong> Unaizah, Al-Qassim</li>
                    <li><strong>Email:</strong> Kh70007980@gmail.com</li>
                </ul>
                <hr>
                <p class="text-muted small">Specializing in Service License Processing and SBC compliance.</p>
            </div>
        </div>
    </div>
</main>

<?php 
// Include the footer fragment
require_once '../includes/footer.php'; 
?>