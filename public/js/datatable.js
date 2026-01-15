document.addEventListener('DOMContentLoaded', function() {
    const ROWS_PER_PAGE = 10;
    
    // Elements
    const tableBody = document.getElementById('tableBody');
    if (!tableBody) return; // Stop jika halaman tidak punya tabel

    const allRows = Array.from(document.querySelectorAll('.data-row'));
    const noDataRow = document.getElementById('noDataRow');
    const paginationControls = document.getElementById('paginationControls');
    const paginationInfo = document.getElementById('paginationInfo');

    // Filter Inputs (Gunakan optional chaining / null check)
    const searchInput = document.getElementById('searchInput');
    const filterClass = document.getElementById('filterClass');
    const filterStatus = document.getElementById('filterStatus');
    const filterRole = document.getElementById('filterRole'); // Jaga-jaga buat user page

    let currentPage = 1;
    let filteredRows = [...allRows];

    // --- FUNCTION: RENDER TABLE ---
    function renderTable() {
        const totalRows = filteredRows.length;
        const totalPages = Math.ceil(totalRows / ROWS_PER_PAGE);

        if (currentPage > totalPages) currentPage = totalPages || 1;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * ROWS_PER_PAGE;
        const endIndex = startIndex + ROWS_PER_PAGE;

        // Hide All
        allRows.forEach(row => row.style.display = 'none');
        if(noDataRow) noDataRow.style.display = 'none';

        if (totalRows === 0 && noDataRow) {
            noDataRow.style.display = 'table-row';
            if(paginationInfo) paginationInfo.innerText = "0 Data ditemukan";
            if(paginationControls) paginationControls.innerHTML = '';
            return;
        }

        // Show Slice
        const rowsToShow = filteredRows.slice(startIndex, endIndex);
        rowsToShow.forEach(row => row.style.display = 'table-row');

        if(paginationInfo) {
            paginationInfo.innerText = `Menampilkan ${startIndex + 1} - ${Math.min(endIndex, totalRows)} dari ${totalRows} data`;
        }
        if(paginationControls) renderPaginationControls(totalPages);
    }

    // --- FUNCTION: PAGINATION CONTROLS ---
    function renderPaginationControls(totalPages) {
        paginationControls.innerHTML = '';
        
        const createBtn = (html, isDisabled, onClick) => {
            const btn = document.createElement('button');
            btn.className = 'page-btn';
            btn.innerHTML = html;
            btn.disabled = isDisabled;
            btn.onclick = onClick;
            return btn;
        };

        // Prev
        paginationControls.appendChild(createBtn('<i class="bi bi-chevron-left"></i>', currentPage === 1, () => { currentPage--; renderTable(); }));

        // Page Numbers (Logic Simple)
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) paginationControls.appendChild(createBtn('1', false, () => { currentPage = 1; renderTable(); }));
        if (startPage > 2) paginationControls.appendChild(createBtn('...', true, null));

        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
            btn.innerText = i;
            btn.onclick = () => { currentPage = i; renderTable(); };
            paginationControls.appendChild(btn);
        }

        if (endPage < totalPages - 1) paginationControls.appendChild(createBtn('...', true, null));
        if (endPage < totalPages) paginationControls.appendChild(createBtn(totalPages, false, () => { currentPage = totalPages; renderTable(); }));

        // Next
        paginationControls.appendChild(createBtn('<i class="bi bi-chevron-right"></i>', currentPage === totalPages, () => { currentPage++; renderTable(); }));
    }

    // --- FUNCTION: APPLY FILTERS ---
    function applyFilters() {
        // Ambil nilai filter (jika elemen ada)
        const keyword = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedClass = filterClass ? filterClass.value : '';
        const selectedStatus = filterStatus ? filterStatus.value : '';
        const selectedRole = filterRole ? filterRole.value : '';

        filteredRows = allRows.filter(row => {
            // 1. Keyword Search (Cek semua kolom yang punya class 'searchable-*')
            let textContent = '';
            const searchables = row.querySelectorAll('[class*="searchable-"]');
            searchables.forEach(el => textContent += el.innerText.toLowerCase() + ' ');
            
            const matchesKeyword = textContent.includes(keyword);

            // 2. Class Filter
            const rowClass = row.getAttribute('data-kelas');
            const matchesClass = (selectedClass === "") || (rowClass === selectedClass);

            // 3. Status Filter
            const rowStatus = row.getAttribute('data-status');
            const matchesStatus = (selectedStatus === "") || (rowStatus === selectedStatus);

            // 4. Role Filter
            const rowRole = row.getAttribute('data-role');
            const matchesRole = (selectedRole === "") || (rowRole === selectedRole);

            return matchesKeyword && matchesClass && matchesStatus && matchesRole;
        });

        currentPage = 1; 
        renderTable();
    }

    // --- EVENT LISTENERS ---
    if(searchInput) searchInput.addEventListener('keyup', applyFilters);
    if(filterClass) filterClass.addEventListener('change', applyFilters);
    if(filterStatus) filterStatus.addEventListener('change', applyFilters);
    if(filterRole) filterRole.addEventListener('change', applyFilters);

    // Initial Render
    renderTable();
});
