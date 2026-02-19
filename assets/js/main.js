var modalElement;

// Initialize Modal on Page Load
document.addEventListener("DOMContentLoaded", function () {
    var modalEl = document.getElementById('workflowModal');
    if (modalEl) {
        modalElement = new bootstrap.Modal(modalEl);
    }
});

// 1. OPEN MODAL
function openEditModal(key, label) {
    // Set Title
    document.getElementById('modalTitle').innerText = "Update: " + label;
    document.getElementById('current_field_key').value = key;

    // Get Elements from the Card
    const cardSelect = document.getElementById('select_' + key);
    const cardNote = document.getElementById('input_note_' + key);

    // Sync Dropdown Options
    const modalSelect = document.getElementById('modal_status_select');
    modalSelect.innerHTML = cardSelect.innerHTML; // Copy options
    modalSelect.value = cardSelect.value; // Select current value

    // Sync Note Text
    document.getElementById('modal_note_text').value = cardNote.value;

    // Show Modal
    if (modalElement) modalElement.show();
}

// 2. SAVE CHANGES (Sync back to Card)
function saveModalChanges() {
    const key = document.getElementById('current_field_key').value;
    const newStatus = document.getElementById('modal_status_select').value;
    const newNote = document.getElementById('modal_note_text').value;

    // Update Card Dropdown
    document.getElementById('select_' + key).value = newStatus;

    // Update Hidden Note Input (for form submission)
    document.getElementById('input_note_' + key).value = newNote;

    // Show/Hide Note Indicator Icon
    const indicator = document.getElementById('note_indicator_' + key);
    if (newNote.trim() !== "") {
        indicator.classList.remove('d-none');
    } else {
        indicator.classList.add('d-none');
    }

    // Hide Modal
    if (modalElement) modalElement.hide();
}

/**
 * Toggles Password Visibility
 * @param {string} inputId - The ID of the password input field
 * @param {string} iconId - The ID of the icon element to toggle classes
 */
function togglePassword(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon = document.getElementById(iconId);

    if (input && icon) {
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }
}

/* --- View Client Modal Logic --- */
var viewModalElement;

document.addEventListener("DOMContentLoaded", function() {
    // Initialize View Modal
    var viewEl = document.getElementById('viewClientModal');
    if(viewEl) {
        viewModalElement = new bootstrap.Modal(viewEl);
    }
});

function openViewModal(button) {
    // 1. Retrieve Data
    // We parse the JSON string stored in the data-client attribute
    try {
        var client = JSON.parse(button.getAttribute('data-client'));
    } catch (e) {
        console.error("Error parsing client data", e);
        return;
    }

    // 2. Set Header Info & Edit Link
    document.getElementById('view_company_name').innerText = client.company_name;
    document.getElementById('view_client_id').innerText = "#" + client.client_id;
    
    // Set Edit Button Href
    var editBtn = document.getElementById('view_edit_btn');
    editBtn.href = "client-edit.php?id=" + client.client_id;

    // 3. Populate Basic Fields helper
    function setVal(id, val) {
        var el = document.getElementById(id);
        if(el) el.innerText = val ? val : '-';
    }

    // Basic Info
    setVal('v_name', client.client_name);
    setVal('v_phone', client.phone_number);
    setVal('v_email', client.email);
    setVal('v_trade', client.trade_name_application);
    setVal('v_contract', parseFloat(client.contract_value).toLocaleString('en-US', {minimumFractionDigits: 2}) + ' SAR');
    
    // Financial Info
    // We need to calculate Due here since it might be calculated in PHP
    var totalPaid = parseFloat(client.total_paid || 0);
    var contract = parseFloat(client.contract_value || 0);
    var due = contract - totalPaid;

    setVal('v_paid', totalPaid.toLocaleString('en-US', {minimumFractionDigits: 2}) + ' SAR');
    setVal('v_due', due > 0 ? due.toLocaleString('en-US', {minimumFractionDigits: 2}) + ' SAR' : 'Fully Paid');
    
    // Colorize Due Amount
    var dueEl = document.getElementById('v_due');
    if(due > 0) dueEl.classList.add('text-danger');
    else dueEl.classList.remove('text-danger');

    // 4. Populate Workflow Statuses
    var steps = {
        'scope': client.license_scope_status,
        'hire': client.hire_foreign_company,
        'misa': client.misa_application,
        'sbc':  client.sbc_application,
        'art':  client.article_association,
        'qiwa': client.qiwa,
        'muqeem': client.muqeem,
        'gosi': client.gosi,
        'coc':  client.chamber_commerce
    };

    for (var key in steps) {
        var status = steps[key] || 'In Process';
        var badge = document.getElementById('badge_' + key);
        
        if (badge) {
            badge.innerText = status;
            // Reset Classes
            badge.className = 'view-badge';
            
            // Add Color Class
            if (status === 'Approved' || status.includes('Trading') || status.includes('Service')) {
                badge.classList.add('badge-approved');
            } else if (status === 'Applied' || status === 'Pending Application') {
                badge.classList.add('badge-pending');
            } else {
                badge.classList.add('badge-default');
            }
        }
    }

    // 5. Show Modal
    if(viewModalElement) viewModalElement.show();
}

/* --- Mobile Search Toggle --- */
function toggleMobileSearch() {
    var overlay = document.getElementById('mobileSearchOverlay');
    if (overlay) {
        if (overlay.classList.contains('d-none')) {
            // Show Overlay
            overlay.classList.remove('d-none');
            // Focus on the input automatically
            var input = overlay.querySelector('input');
            if(input) setTimeout(() => input.focus(), 100);
        } else {
            // Hide Overlay
            overlay.classList.add('d-none');
        }
    }
}

/* --- Live Search Logic --- */
document.addEventListener("DOMContentLoaded", function() {
    setupLiveSearch('desktopSearchInput', 'desktopSearchResults');
    setupLiveSearch('mobileSearchInput', 'mobileSearchResults');
});

function setupLiveSearch(inputId, resultsId) {
    const input = document.getElementById(inputId);
    const resultsBox = document.getElementById(resultsId);

    if (!input || !resultsBox) return;

    let timeout = null;

    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const term = this.value.trim();

        if (term.length < 2) {
            resultsBox.classList.add('d-none');
            return;
        }

        // Debounce API Call (Wait 300ms after typing stops)
        timeout = setTimeout(() => {
            fetch(`search_api.php?term=${encodeURIComponent(term)}`)
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    
                    if (data.length > 0) {
                        resultsBox.classList.remove('d-none');
                        data.forEach(client => {
                            // Create List Item
                            const item = document.createElement('div');
                            item.className = 'search-result-item p-2 border-bottom border-secondary border-opacity-25';
                            item.style.cursor = 'pointer';
                            
                            // Highlight matching text logic could go here, keeping it simple:
                            item.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-gold text-dark rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:30px;height:30px;font-weight:bold;">
                                        ${client.company_name.substring(0,1).toUpperCase()}
                                    </div>
                                    <div>
                                        <div class="text-white small fw-bold">${client.company_name}</div>
                                        <div class="text-white-50" style="font-size: 0.7rem;">#${client.client_id} • ${client.email}</div>
                                    </div>
                                </div>
                            `;

                            // CLICK ACTION: Open Modal
                            item.addEventListener('click', () => {
                                // We simulate the button object expected by openViewModal
                                // We reuse the function defined in previous answers
                                const dummyBtn = document.createElement('button');
                                dummyBtn.setAttribute('data-client', JSON.stringify(client));
                                openViewModal(dummyBtn);
                                
                                // Hide results and mobile overlay
                                resultsBox.classList.add('d-none');
                                input.value = ''; // Clear input
                                toggleMobileSearch(); // Close mobile overlay if open
                            });

                            resultsBox.appendChild(item);
                        });
                    } else {
                        resultsBox.classList.add('d-none');
                    }
                })
                .catch(err => console.error('Search Error:', err));
        }, 300);
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('d-none');
        }
    });
}

