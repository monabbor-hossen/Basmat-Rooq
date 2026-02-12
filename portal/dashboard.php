<?php require_once 'includes/header.php'; ?>

<div class="d-flex portal-wrapper">
    <?php require_once 'includes/sidebar.php'; ?>

    <main class="portal-main-content p-4 w-100">
        <div class="container-fluid">
            <h2 class="mb-4">Welcome back, <span class="text-gold"><?php echo $username; ?></span></h2>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="rooq-card p-4 h-100">
                        <h5>MISA License</h5>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-gold" style="width: 75%;"></div>
                        </div>
                        <p class="small mt-2 opacity-75">Status: In Progress</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<!-- <script src="<php echo BASE_URL; ?>assets/js/bootstrap.min.js"></script> -->
<?php require_once '../includes/footer.php'; ?>