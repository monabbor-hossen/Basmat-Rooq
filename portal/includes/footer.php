<div class="modal fade" id="viewClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom border-white border-opacity-10">
                <div>
                    <h5 class="modal-title text-white fw-bold" id="view_company_name">Company Name</h5>
                    <span class="badge bg-gold text-dark mt-1" id="view_client_id">#ID</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="#" id="view_edit_btn" class="btn btn-sm btn-outline-warning d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2"></i> Edit
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 border-end border-white border-opacity-10">
                        <h6 class="view-section-title" style="color:#D4AF37;font-size:0.8rem;text-transform:uppercase;margin-bottom:15px;">Contact Info</h6>
                        <div class="mb-3"><small class="text-white-50 d-block">Client Name</small><span class="text-white" id="v_name">-</span></div>
                        <div class="mb-3"><small class="text-white-50 d-block">Phone</small><span class="text-white" id="v_phone">-</span></div>
                        <div class="mb-3"><small class="text-white-50 d-block">Email</small><span class="text-white" id="v_email">-</span></div>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="view-section-title" style="color:#D4AF37;font-size:0.8rem;text-transform:uppercase;margin-bottom:15px;">Finance</h6>
                        <div class="row">
                            <div class="col-6 mb-3"><small class="text-white-50 d-block">Contract</small><span class="text-gold fw-bold" id="v_contract">-</span></div>
                            <div class="col-6 mb-3"><small class="text-white-50 d-block">Paid</small><span class="text-success fw-bold" id="v_paid">-</span></div>
                            <div class="col-12"><small class="text-white-50 d-block">Due Balance</small><span class="text-white fw-bold" id="v_due">-</span></div>
                        </div>
                    </div>
                </div>
                <h6 class="view-section-title mt-4" style="color:#D4AF37;font-size:0.8rem;text-transform:uppercase;margin-bottom:15px;">Workflow Status</h6>
                <div class="row g-2">
                    <div class="col-6 col-md-3"><small class="text-white-50">MISA</small><div id="badge_misa" class="badge bg-secondary d-block">-</div></div>
                    <div class="col-6 col-md-3"><small class="text-white-50">Commercial</small><div id="badge_coc" class="badge bg-secondary d-block">-</div></div>
                    <div class="col-6 col-md-3"><small class="text-white-50">Qiwa</small><div id="badge_qiwa" class="badge bg-secondary d-block">-</div></div>
                    <div class="col-6 col-md-3"><small class="text-white-50">Muqeem</small><div id="badge_muqeem" class="badge bg-secondary d-block">-</div></div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>assets/js/bootstrap.bundle.min.js"></script> 
<script src="<?php echo BASE_URL; ?>assets/js/all.min.js"></script> 
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script> 
<script>
document.addEventListener("DOMContentLoaded", function() {
    var toggleBtn = document.getElementById("sidebarToggle");
    var sidebar = document.getElementById("portalSidebar");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", function(e) {
            e.preventDefault(); // Stop page from jumping
            sidebar.classList.toggle("show"); // Add/Remove 'show' CSS class
        });
        
        // Optional: Close sidebar if clicking outside on mobile
        document.addEventListener("click", function(e) {
            if (window.innerWidth < 992 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove("show");
            }
        });
    } else {
        console.error("Sidebar or Toggle Button ID not found!");
    }
});
</script>

</body>

</html>