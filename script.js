document.addEventListener('DOMContentLoaded', () => {
    const studentList = document.getElementById('studentList');
    const counters = {
        hadir: document.getElementById('countHadir'),
        sakit: document.getElementById('countSakit'),
        izin: document.getElementById('countIzin'),
        alfa: document.getElementById('countAlfa')
    };

    // Fungsi untuk menghitung ulang total berdasarkan radio button yang tercentang
    function updateSummary() {
        const counts = { hadir: 0, sakit: 0, izin: 0, alfa: 0 };
        
        // Ambil semua radio yang checked di dalam form
        const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');
        
        checkedRadios.forEach(radio => {
            if (counts[radio.value] !== undefined) {
                counts[radio.value]++;
            }
        });

        // Update DOM text
        counters.hadir.innerText = counts.hadir;
        counters.sakit.innerText = counts.sakit;
        counters.izin.innerText = counts.izin;
        counters.alfa.innerText = counts.alfa;
    }

    // Fungsi helper untuk merender ulang area kontrol baris
    function renderControlArea(row, statusValue = null) {
        const controlArea = row.querySelector('.att-control-area');
        
        // Bersihkan area (kecuali elemen option selector aslinya yang kita simpan di memori/markup)
        // Strategi: Toggle class hidden antara Trigger, Selector, dan SelectedIcon
        
        const triggerBtn = controlArea.querySelector('.att-trigger-btn');
        const selectorDiv = controlArea.querySelector('.att-options-selector');
        let displayIcon = controlArea.querySelector('.att-selected-display');

        // Jika belum ada elemen displayIcon, buat baru
        if (!displayIcon) {
            displayIcon = document.createElement('div');
            displayIcon.className = 'att-selected-display';
            displayIcon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>'; // Default placeholder path
            // Event: Klik icon hasil -> kembali ke mode edit (selector)
            displayIcon.addEventListener('click', () => {
                displayIcon.classList.add('hidden');
                selectorDiv.classList.remove('hidden');
            });
            controlArea.appendChild(displayIcon);
        }

        if (statusValue) {
            // Mode: Sudah Memilih
            triggerBtn.classList.add('hidden');
            selectorDiv.classList.add('hidden');
            displayIcon.classList.remove('hidden');
            
            // Update Visual Icon & Class Warna
            displayIcon.className = `att-selected-display ${statusValue}`;
            
            // Set SVG path sesuai status
            let pathHtml = '';
            if(statusValue === 'hadir') pathHtml = '<polyline points="20 6 9 17 4 12"></polyline>';
            if(statusValue === 'sakit') pathHtml = '<path d="M12 5v14M5 12h14"></path>';
            if(statusValue === 'izin') pathHtml = '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>';
            if(statusValue === 'alfa') pathHtml = '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>';
            
            displayIcon.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">${pathHtml}</svg>`;

        } else {
            // Mode: Belum Memilih / Reset
            triggerBtn.classList.remove('hidden');
            selectorDiv.classList.add('hidden'); // Selector muncul hanya saat trigger diklik
            displayIcon.classList.add('hidden');
        }
    }

    // Event Delegation pada List Container
    studentList.addEventListener('click', (e) => {
        const target = e.target;
        const row = target.closest('.att-student-row');
        if (!row) return;

        // 1. Handle Klik Tombol Ellipsis (Trigger)
        if (target.closest('.att-trigger-btn')) {
            const triggerBtn = row.querySelector('.att-trigger-btn');
            const selectorDiv = row.querySelector('.att-options-selector');
            
            triggerBtn.classList.add('hidden');
            selectorDiv.classList.remove('hidden');
        }

        // 2. Handle Klik Opsi (Radio Label)
        // Input radio change event akan menangani logikanya, 
        // tapi kita perlu handling visual penutupan selector di sini jika event bubbling.
        // Namun, lebih aman menggunakan event 'change' pada input.
    });

    // Event Delegation untuk Perubahan Input Radio
    studentList.addEventListener('change', (e) => {
        if (e.target.type === 'radio') {
            const radio = e.target;
            const row = radio.closest('.att-student-row');
            const statusValue = radio.value;

            // Update UI Baris (Ganti selector jadi icon)
            renderControlArea(row, statusValue);

            // Update Summary Card
            updateSummary();
        }
    });

    // Inisialisasi awal (jika browser menyimpan cache input saat refresh)
    updateSummary();
});
