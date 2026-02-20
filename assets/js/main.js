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

document.addEventListener("DOMContentLoaded", function () {
    var viewEl = document.getElementById('viewClientModal');
    if (viewEl) {
        viewModalElement = new bootstrap.Modal(viewEl);
    }
});

function openViewModal(button) {
    // 1. Retrieve Data
    try {
        var client = JSON.parse(button.getAttribute('data-client'));
    } catch (e) {
        console.error("Error parsing client data", e);
        return;
    }

    // 2. Set Header Info
    document.getElementById('view_company_name').innerText = client.company_name;
    document.getElementById('view_client_id').innerText = "#" + client.client_id;

    var editBtn = document.getElementById('view_edit_btn');
    if (editBtn) editBtn.href = "client-edit.php?id=" + client.client_id;

    // 3. Populate Basic Info helper
    function setVal(id, val) {
        var el = document.getElementById(id);
        if (el) el.innerText = val ? val : '-';
    }

    setVal('v_name', client.client_name);
    setVal('v_phone', client.phone_number);
    setVal('v_email', client.email);
    setVal('v_trade', client.trade_name_application); // Add if you have this field
    // --- LICENSE SCOPE SECTION ---
    var scopeStatus = client.license_scope_status || 'Pending';
    var scopeNote = client.license_scope_note || ''; // Ensure column name matches DB

    // 1. Set Status Text
    var scopeBadge = document.getElementById('badge_scope');
    if (scopeBadge) {
        scopeBadge.innerText = scopeStatus;

        // Apply Colors
        scopeBadge.className = 'view-badge'; // Reset
        if (scopeStatus === 'Approved' || scopeStatus.includes('Done')) {
            scopeBadge.classList.add('badge-approved');
        } else if (scopeStatus === 'Pending' || scopeStatus === 'Applied') {
            scopeBadge.classList.add('badge-pending');
        } else {
            scopeBadge.classList.add('badge-default');
        }
    }

    // 2. Set Note Text
    var scopeNoteEl = document.getElementById('note_scope');
    if (scopeNoteEl) {
        if (scopeNote && scopeNote !== '-') {
            scopeNoteEl.innerText = scopeNote;
            scopeNoteEl.style.display = 'block'; // Show if exists
        } else {
            scopeNoteEl.style.display = 'none'; // Hide if empty
        }
    }
    // 4. Financials
    var totalPaid = parseFloat(client.total_paid || 0);
    var contract = parseFloat(client.contract_value || 0);
    var due = contract - totalPaid;

    setVal('v_contract', contract.toLocaleString('en-US') + ' SAR');
    setVal('v_paid', totalPaid.toLocaleString('en-US') + ' SAR');
    setVal('v_due', due > 0 ? due.toLocaleString('en-US') + ' SAR' : 'Paid');

    // 5. GENERATE WORKFLOW CARDS
    var grid = document.getElementById('workflow_grid');
    if (grid) {
        grid.innerHTML = ''; // Clear previous

        // Define Steps Map
        var steps = [

            {
                key: 'hire',
                label: 'Foreign Hire',
                icon: 'bi-briefcase',
                status: client.hire_foreign_company,
                note: client.hire_foreign_company_note
            },
            {
                key: 'misa',
                label: 'MISA License',
                icon: 'bi-award',
                status: client.misa_application,
                note: client.misa_application_note
            },
            {
                key: 'sbc',
                label: 'SBC App',
                icon: 'bi-building',
                status: client.sbc_application,
                note: client.sbc_application_note
            },
            {
                key: 'art',
                label: 'Art. Assoc.',
                icon: 'bi-file-text',
                status: client.article_association,
                note: client.article_association_note
            },
            {
                key: 'qiwa',
                label: 'Qiwa',
                icon: 'bi-people',
                status: client.qiwa,
                note: client.qiwa_note
            },
            {
                key: 'muq',
                label: 'Muqeem',
                icon: 'bi-person-badge',
                status: client.muqeem,
                note: client.muqeem_note
            },
            {
                key: 'gosi',
                label: 'GOSI',
                icon: 'bi-shield-check',
                status: client.gosi,
                note: client.gosi_note
            },
            {
                key: 'coc',
                label: 'Chamber',
                icon: 'bi-bank',
                status: client.chamber_commerce,
                note: client.chamber_commerce_note
            }
        ];
        steps.forEach(step => {
            var status = step.status || 'Pending';
            
            // Skip this card completely if it's turned off (Not Required)
            if (status === 'Not Required') return; 
            
            // Determine Color Class
            var colorClass = 'card-status-default';
            var badgeColor = 'text-white-50';
            
            if (status === 'Approved' || status.includes('Done')) {
                colorClass = 'card-status-approved';
                badgeColor = 'text-success';
            } else if (status === 'Pending' || status === 'Applied') {
                colorClass = 'card-status-pending';
                badgeColor = 'text-warning';
            } else if (status === 'In Process') {
                colorClass = 'card-status-process';
                badgeColor = 'text-info';
            }

            // NEW: Only generate the Note HTML if a note actually exists
            var noteHtml = '';
            if (step.note && step.note.trim() !== '') {
                noteHtml = `<div class="wf-note mt-2">${step.note}</div>`;
            }

            // Create HTML
            var card = document.createElement('div');
            card.className = `workflow-card ${colorClass}`;
            card.innerHTML = `
                <div class="wf-title"><i class="bi ${step.icon} text-gold"></i> ${step.label}</div>
                <div class="wf-status ${badgeColor} m-0">${status}</div>
                ${noteHtml}
            `;
            grid.appendChild(card);
        });
    }

    // 6. Show Modal
    if (viewModalElement) viewModalElement.show();
}
/* --- Live Search Logic --- */
/* --- Live Search Logic (Updated for Debugging) --- */
document.addEventListener("DOMContentLoaded", function () {
    setupLiveSearch('desktopSearchInput', 'desktopSearchResults');
    setupLiveSearch('mobileSearchInput', 'mobileSearchResults');
});

function setupLiveSearch(inputId, resultsId) {
    const input = document.getElementById(inputId);
    const resultsBox = document.getElementById(resultsId);

    if (!input || !resultsBox) return;

    let timeout = null;

    input.addEventListener('input', function () {
        clearTimeout(timeout);
        const term = this.value.trim();

        if (term.length < 2) {
            resultsBox.classList.add('d-none');
            return;
        }

        // Debounce 300ms
        timeout = setTimeout(() => {
            fetch(`search_api.php?term=${encodeURIComponent(term)}`)
                .then(async response => {
                    const text = await response.text(); // Read raw text first

                    // Try parsing JSON
                    try {
                        const data = JSON.parse(text);
                        if (!response.ok) throw new Error(data.message || "Server Error " + response.status);
                        return data;
                    } catch (e) {
                        // If JSON parse fails, throw the raw text (HTML Error)
                        throw new Error("Invalid Response: " + text.substring(0, 100) + "...");
                    }
                })
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length > 0) {
                        resultsBox.classList.remove('d-none');
                        data.forEach(client => {
                            const item = document.createElement('div');
                            item.className = 'search-result-item p-2 border-bottom border-secondary border-opacity-25';
                            item.style.cursor = 'pointer';
                            item.innerHTML = `<div class="d-flex align-items-center">
                                            <div class="avatar-small me-2" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:rgba(212,175,55,0.2);color:#D4AF37;border-radius:50%;font-weight:bold;">
                                                ${client.company_name.substring(0,1).toUpperCase()}
                                            </div>
                                            <div>
                                                <div class="text-white small fw-bold">${client.company_name}</div>
                                                <div class="text-white-50" style="font-size: 0.7rem;">
                                                    #${client.client_id} • ${client.client_name || ''} • ${client.phone_number || ''} • ${client.email || ''}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                            item.addEventListener('click', () => {
                                const dummyBtn = document.createElement('button');
                                dummyBtn.setAttribute('data-client', JSON.stringify(client));
                                openViewModal(dummyBtn);
                                resultsBox.classList.add('d-none');
                                input.value = '';
                                toggleMobileSearch();
                            });
                            resultsBox.appendChild(item);
                        });
                    } else {
                        resultsBox.classList.add('d-none');
                    }
                })
                .catch(err => {
                    console.error('SEARCH DEBUG ERROR:', err);
                    // Optional: Alert the user for easier debugging
                    // alert("Search Error: " + err.message); 
                });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('d-none');
        }
    });
}

// Mobile Toggle Function
/* --- Mobile Search Toggle (Updated) --- */
function toggleMobileSearch() {
    var overlay = document.getElementById('mobileSearchOverlay');
    if (overlay) {
        // Toggle the 'show' class defined in theme.css
        if (overlay.classList.contains('show')) {
            overlay.classList.remove('show');
        } else {
            overlay.classList.add('show');
            // Auto-focus input
            var input = overlay.querySelector('input');
            if (input) setTimeout(() => input.focus(), 100);
        }
    }
}


/* --- Workflow Toggle (Optional Cards) --- */
function toggleWorkflowCard(key) {
    const checkbox = document.getElementById('enable_' + key);
    const card = document.getElementById('card_' + key);
    const select = document.getElementById('select_' + key);
    const editBtn = document.getElementById('btn_edit_' + key);

    if (checkbox.checked) {
        // Turn ON
        card.style.opacity = '1';
        select.disabled = false;
        editBtn.disabled = false;
    } else {
        // Turn OFF (Optional)
        card.style.opacity = '0.5';
        select.disabled = true;
        editBtn.disabled = true;
    }
}