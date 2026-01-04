document.addEventListener('DOMContentLoaded', () => {
    // Referensi Elemen
    const form = document.getElementById('validationForm');
    const statusInput = document.getElementById('validationStatusInput');
    const rejectReasonInput = document.getElementById('rejectReason');

    // Modal Elements
    const modalReject = document.getElementById('modalReject');
    const modalApprove = document.getElementById('modalApprove');

    // Buttons
    const btnTriggerReject = document.getElementById('btnTriggerReject');
    const btnTriggerApprove = document.getElementById('btnTriggerApprove');
    
    const btnSubmitReject = document.getElementById('submitReject');
    const btnSubmitApprove = document.getElementById('submitApprove');
    
    // Tombol Close (semua elemen dengan atribut data-close-modal)
    const closeButtons = document.querySelectorAll('[data-close-modal]');

    // --- Helper Functions ---
    const openModal = (modal) => {
        modal.classList.remove('hidden');
        // Focus trap simple: fokus ke elemen pertama yang bisa diinteraksi
        const focusable = modal.querySelector('textarea, button:not([data-close-modal])');
        if(focusable) focusable.focus();
    };

    const closeModal = (modal) => {
        modal.classList.add('hidden');
    };

    const closeAllModals = () => {
        modalReject.classList.add('hidden');
        modalApprove.classList.add('hidden');
    };

    // --- Event Listeners: Triggers ---
    
    // 1. Buka Modal Tolak
    btnTriggerReject.addEventListener('click', () => {
        // Reset required field saat dibuka
        rejectReasonInput.value = '';
        rejectReasonInput.setAttribute('required', 'true'); // Wajib diisi jika menolak
        openModal(modalReject);
    });

    // 2. Buka Modal Approve
    btnTriggerApprove.addEventListener('click', () => {
        rejectReasonInput.removeAttribute('required'); // Tidak wajib jika approve
        openModal(modalApprove);
    });

    // --- Event Listeners: Actions ---

    // 3. Konfirmasi Tolak (Di dalam Modal)
    btnSubmitReject.addEventListener('click', (e) => {
        // Validasi manual input textarea
        if(!rejectReasonInput.value.trim()) {
            // Biarkan browser handle required validation, jangan preventDefault jika kosong
            return; 
        }
        // Set hidden input value
        statusInput.value = 'REJECTED';
        // Form akan submit secara native karena type="submit"
    });

    // 4. Konfirmasi Approve (Di dalam Modal)
    btnSubmitApprove.addEventListener('click', () => {
        statusInput.value = 'APPROVED';
        // Form akan submit secara native
    });

    // 5. Tutup Modal (Tombol Batal)
    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            closeAllModals();
        });
    });

    // 6. Tutup Modal (Klik di luar box/overlay)
    window.addEventListener('click', (e) => {
        if (e.target === modalReject) closeModal(modalReject);
        if (e.target === modalApprove) closeModal(modalApprove);
    });
});
