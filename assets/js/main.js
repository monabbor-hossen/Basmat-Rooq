
    var modalElement;

    // Initialize Modal on Page Load
    document.addEventListener("DOMContentLoaded", function() {
        var modalEl = document.getElementById('workflowModal');
        if(modalEl) {
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
        const cardNote   = document.getElementById('input_note_' + key);
        
        // Sync Dropdown Options
        const modalSelect = document.getElementById('modal_status_select');
        modalSelect.innerHTML = cardSelect.innerHTML; // Copy options
        modalSelect.value = cardSelect.value;         // Select current value

        // Sync Note Text
        document.getElementById('modal_note_text').value = cardNote.value;

        // Show Modal
        if(modalElement) modalElement.show();
    }

    // 2. SAVE CHANGES (Sync back to Card)
    function saveModalChanges() {
        const key = document.getElementById('current_field_key').value;
        const newStatus = document.getElementById('modal_status_select').value;
        const newNote   = document.getElementById('modal_note_text').value;

        // Update Card Dropdown
        document.getElementById('select_' + key).value = newStatus;

        // Update Hidden Note Input (for form submission)
        document.getElementById('input_note_' + key).value = newNote;

        // Show/Hide Note Indicator Icon
        const indicator = document.getElementById('note_indicator_' + key);
        if(newNote.trim() !== "") {
            indicator.classList.remove('d-none');
        } else {
            indicator.classList.add('d-none');
        }

        // Hide Modal
        if(modalElement) modalElement.hide();
    }
