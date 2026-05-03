@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Riwayat Kesehatan Global</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Monitoring Tekanan Darah & GAD-7 Seluruh Warga</p>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6 mb-10 bg-white p-8 rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100">
    <div class="flex-1 space-y-2">
        <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Pilih Warga</label>
        <select id="filterWarga" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-primary-800 cursor-pointer focus:border-primary-600 focus:bg-white transition-all font-black text-black appearance-none">
            <option value="">Semua Warga</option>
        </select>
    </div>
    <div class="flex flex-col md:flex-row gap-6">
        <div class="space-y-2">
            <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Dari Tanggal</label>
            <input type="date" id="startDate" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-primary-800 cursor-pointer focus:border-primary-600 focus:bg-white transition-all font-bold text-black">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Sampai Tanggal</label>
            <input type="date" id="endDate" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-primary-800 cursor-pointertransparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black">
        </div>
    </div>
    <div class="flex items-end gap-3">
        <button onclick="loadData()" class="bg-primary-800 hover:bg-black text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-lg uppercase tracking-widest">CARI</button>
        {{-- <button onclick="resetFilter()" class="bg-primary-50 hover:bg-primary-100 text-primary-800 px-6 py-4 rounded-2xl font-black text-sm transition-all uppercase tracking-widest">RESET</button> --}}
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-[2rem] p-6 md:p-8 text-white shadow-xl border border-white/10">
        <div class="flex items-center justify-between mb-4">
            <span class="text-emerald-100 text-sm font-black uppercase tracking-widest opacity-60">Status Normal</span>
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
        </div>
        <div class="text-4xl md:text-5xl font-black" id="statNormal">-</div>
        <div class="text-emerald-100 text-xs font-bold mt-3 uppercase tracking-wider">Kondisi sangat sehat</div>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-700 rounded-[2rem] p-6 md:p-8 text-white shadow-xl border border-white/10">
        <div class="flex items-center justify-between mb-4">
            <span class="text-amber-100 text-sm font-black uppercase tracking-widest opacity-60">Status Waspada</span>
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
        </div>
        <div class="text-4xl md:text-5xl font-black" id="statWaspada">-</div>
        <div class="text-amber-100 text-xs font-bold mt-3 uppercase tracking-wider">Pra-hipertensi / Ringan</div>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-[2rem] p-6 md:p-8 text-white shadow-xl border border-white/10">
        <div class="flex items-center justify-between mb-4">
            <span class="text-orange-100 text-sm font-black uppercase tracking-widest opacity-60">Risiko Tinggi</span>
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
        </div>
        <div class="text-4xl md:text-5xl font-black" id="statRisiko">-</div>
        <div class="text-orange-100 text-xs font-bold mt-3 uppercase tracking-wider">Hipertensi / Sedang-Tinggi</div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mb-10 overflow-hidden">
    <div class="flex items-center justify-between mb-8 pb-4 border-b-2 border-primary-50">
        <h2 class="text-2xl md:text-3xl font-black text-black tracking-tight">Riwayat Tekanan Darah</h2>
        <button onclick="exportTdToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black text-xs flex items-center gap-2 transition-all shadow-lg shadow-emerald-900/10 uppercase tracking-widest">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            EXCEL
        </button>
    </div>
    
    <div class="hidden md:block overflow-x-auto -mx-10 px-10">
        <table class="border-collapse">
            <thead>
                <tr>
                    <th>Nama Warga (NIK)</th>
                    <th class="text-center">Profil</th>
                    <th>Tanggal Cek</th>
                    <th class="text-center">Hasil (mmHg)</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody id="tdTable">
                <tr><td colspan="5" class="px-6 py-12 text-center text-primary-300 font-bold italic">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="tdCards" class="md:hidden space-y-3">
        <div class="text-center py-8 text-gray-500">Memuat data...</div>
    </div>
    
    <div id="tdPagination" class="mt-4 flex justify-center gap-2"></div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mb-10 overflow-hidden">
    <div class="flex items-center justify-between mb-8 pb-4 border-b-2 border-primary-50">
        <h2 class="text-2xl md:text-3xl font-black text-black tracking-tight">Riwayat GAD-7</h2>
        <button onclick="exportGadToExcel()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black text-xs flex items-center gap-2 transition-all shadow-lg shadow-emerald-900/10 uppercase tracking-widest">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            EXCEL
        </button>
    </div>
    
    <div class="hidden md:block overflow-x-auto -mx-10 px-10">
        <table class="border-collapse">
            <thead>
                <tr>
                    <th>Nama Warga (NIK)</th>
                    <th class="text-center">Profil</th>
                    <th>Tanggal Cek</th>
                    <th class="text-center">Skor GAD-7</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody id="gadTable">
                <tr><td colspan="5" class="px-6 py-12 text-center text-primary-300 font-bold italic">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div id="gadCards" class="md:hidden space-y-3">
        <div class="text-center py-8 text-gray-500">Memuat data...</div>
    </div>

    <div id="gadPagination" class="mt-4 flex justify-center gap-2"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script>
let tdFullData = [];
let gadFullData = [];

async function exportTdToExcel() {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const res = await apiCall(`/tekanan-darah?warga_id=${wargaId}&start_date=${start}&end_date=${end}&per_page=10000`);
    const allData = res?.data?.data || [];
    if (allData.length === 0) { showAlert('Tidak ada data untuk diekspor'); return; }
    const excelData = allData.map(td => ({
        'Nama Warga': td.warga?.nama_lengkap || '-',
        'NIK': td.warga?.nik || '-',
        'Umur': td.warga?.tanggal_lahir ? calculateAge(td.warga.tanggal_lahir) : '-',
        'L/P': td.warga?.jenis_kelamin || '-',
        'Tanggal Cek': new Date(td.tgl_cek).toLocaleDateString('id-ID'),
        'Systolic': td.systolic,
        'Diastolic': td.diastolic,
        'Status': getStatusTd(td.systolic, td.diastolic).label
    }));
    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat TD");
    XLSX.writeFile(workbook, `Riwayat_TD_TaniSehat_${new Date().toISOString().split('T')[0]}.xlsx`);
}

async function exportGadToExcel() {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const res = await apiCall(`/gad?warga_id=${wargaId}&start_date=${start}&end_date=${end}&per_page=10000`);
    const allData = res?.data?.data || [];
    if (allData.length === 0) { showAlert('Tidak ada data untuk diekspor'); return; }
    const excelData = allData.map(gad => ({
        'Nama Warga': gad.warga?.nama_lengkap || '-',
        'NIK': gad.warga?.nik || '-',
        'Umur': gad.warga?.tanggal_lahir ? calculateAge(gad.warga.tanggal_lahir) : '-',
        'L/P': gad.warga?.jenis_kelamin || '-',
        'Tanggal Cek': new Date(gad.tgl_gad).toLocaleDateString('id-ID'),
        'Skor GAD-7': gad.skor,
        'Status': getStatusGad(gad.skor).label
    }));
    const worksheet = XLSX.utils.json_to_sheet(excelData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Riwayat GAD7");
    XLSX.writeFile(workbook, `Riwayat_GAD7_TaniSehat_${new Date().toISOString().split('T')[0]}.xlsx`);
}
let currentFilter = 'week';

function calculateAge(birthDate) {
    if (!birthDate) return '-';
    const birth = new Date(birthDate);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return age;
}

function getStatusTd(systolic, diastolic) {
    if (systolic >= 140 && diastolic >= 90) return { label: 'Hipertensi', color: 'bg-orange-100 text-orange-800' };
    if (systolic < 120 && diastolic < 80) return { label: 'Normal', color: 'bg-emerald-100 text-emerald-800' };
    return { label: 'Pra-Hipertensi', color: 'bg-amber-100 text-amber-800' };
}

function getStatusGad(skor) {
    if (skor <= 4) return { label: 'Normal', color: 'bg-emerald-100 text-emerald-800' };
    if (skor <= 9) return { label: 'Ringan', color: 'bg-amber-100 text-amber-800' };
    return { label: 'Sedang-Tinggi', color: 'bg-orange-100 text-orange-800' };
}

function filterData(filter) {
    currentFilter = filter;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'bg-primary-50', 'text-primary-600'));
    event.target.classList.add('active', 'bg-primary-50', 'text-primary-600');
    loadData();
}

async function loadData() {
    loadTdData(1);
    loadGadData(1);
}

function renderTdTable(data) {
    const tbody = document.getElementById('tdTable');
    const cards = document.getElementById('tdCards');
    
    if (!data || data.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        if (cards) cards.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data</div>';
        return;
    }
    
    let normal = 0, waspada = 0, risiko = 0;
    const rowsHtml = data.map(td => {
        const status = getStatusTd(td.systolic, td.diastolic);
        if (status.label === 'Normal') normal++;
        else if (status.label === 'Pra-Hipertensi') waspada++;
        else risiko++;
        
        return `<tr class="hover:bg-primary-50/50 transition-colors">
            <td class="px-6 py-4 border border-primary-50">
                <div class="font-bold text-black">${td.warga?.nama_lengkap || '-'}</div>
                <div class="text-[10px] text-primary-400 font-medium">NIK: ${td.warga?.nik || '-'}</div>
            </td>
            <td class="px-6 py-4 text-center border border-primary-50">
                <div class="font-bold text-primary-800 text-sm">${td.warga?.tanggal_lahir ? calculateAge(td.warga.tanggal_lahir) : '-'} Thn</div>
                <div class="text-[10px] text-primary-400 font-medium uppercase">${td.warga?.jenis_kelamin === 'L' ? 'L' : 'P'}</div>
            </td>
            <td class="px-6 py-4 font-medium text-gray-500 border border-primary-50">${new Date(td.tgl_cek).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</td>
            <td class="px-6 py-4 text-center font-black text-xl text-primary-800 border border-primary-50 bg-primary-50/20">${td.systolic}/${td.diastolic}</td>
            <td class="px-6 py-4 text-center border border-primary-50"><span class="px-3 py-1 text-[10px] font-black rounded-lg uppercase tracking-widest ${status.color}">${status.label}</span></td>
        </tr>`;
    }).join('');

    const cardsHtml = data.map(td => {
        const status = getStatusTd(td.systolic, td.diastolic);
        return `
            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-primary-900/5 border border-primary-100 mb-4">
                <div class="flex justify-between items-start mb-4 border-b-2 border-primary-50 pb-4">
                    <div>
                        <p class="font-black text-black text-xl leading-tight">${td.warga?.nama_lengkap || '-'}</p>
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">NIK: ${td.warga?.nik || '-'} • ${td.warga?.tanggal_lahir ? calculateAge(td.warga.tanggal_lahir) : '-'} Thn • ${td.warga?.jenis_kelamin}</p>
                    </div>
                    <span class="px-3 py-1 text-[9px] font-black rounded-lg uppercase tracking-widest shadow-sm ${status.color}">${status.label}</span>
                </div>
                <div class="flex justify-between items-center bg-primary-50/50 p-4 rounded-2xl">
                    <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">${new Date(td.tgl_cek).toLocaleDateString('id-ID')}</span>
                    <span class="text-xl font-black text-primary-800">${td.systolic}/${td.diastolic} <span class="text-xs font-bold opacity-60">mmHg</span></span>
                </div>
            </div>
        `;
    }).join('');
    
    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
    
    document.getElementById('statNormal').textContent = normal;
    document.getElementById('statWaspada').textContent = waspada;
    document.getElementById('statRisiko').textContent = risiko;
}

function renderGadTable(data) {
    const tbody = document.getElementById('gadTable');
    const cards = document.getElementById('gadCards');
    
    if (!data || data.length === 0) {
        if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        if (cards) cards.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data</div>';
        return;
    }
    
    const rowsHtml = data.map(gad => {
        const status = getStatusGad(gad.skor);
        return `<tr class="hover:bg-primary-50/50 transition-colors">
            <td class="px-6 py-4 border border-primary-50">
                <div class="font-bold text-black">${gad.warga?.nama_lengkap || '-'}</div>
                <div class="text-[10px] text-primary-400 font-medium">NIK: ${gad.warga?.nik || '-'}</div>
            </td>
            <td class="px-6 py-4 text-center border border-primary-50">
                <div class="font-bold text-primary-800 text-sm">${gad.warga?.tanggal_lahir ? calculateAge(gad.warga.tanggal_lahir) : '-'} Thn</div>
                <div class="text-[10px] text-primary-400 font-medium uppercase">${gad.warga?.jenis_kelamin === 'L' ? 'L' : 'P'}</div>
            </td>
            <td class="px-6 py-4 font-medium text-gray-500 border border-primary-50">${new Date(gad.tgl_gad).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</td>
            <td class="px-6 py-4 text-center font-black text-2xl text-primary-800 border border-primary-50 bg-primary-50/20">${gad.skor}</td>
            <td class="px-6 py-4 text-center border border-primary-50"><span class="px-3 py-1 text-[10px] font-black rounded-lg uppercase tracking-widest ${status.color}">${status.label}</span></td>
        </tr>`;
    }).join('');

    const cardsHtml = data.map(gad => {
        const status = getStatusGad(gad.skor);
        return `
            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-primary-900/5 border border-primary-100 mb-4">
                <div class="flex justify-between items-start mb-4 border-b-2 border-primary-50 pb-4">
                    <div>
                        <p class="font-black text-black text-xl leading-tight">${gad.warga?.nama_lengkap || '-'}</p>
                        <p class="text-[10px] text-primary-400 font-black uppercase tracking-widest mt-1">NIK: ${gad.warga?.nik || '-'} • ${gad.warga?.tanggal_lahir ? calculateAge(gad.warga.tanggal_lahir) : '-'} Thn • ${gad.warga?.jenis_kelamin}</p>
                    </div>
                    <span class="px-3 py-1 text-[9px] font-black rounded-lg uppercase tracking-widest shadow-sm ${status.color}">${status.label}</span>
                </div>
                <div class="flex justify-between items-center bg-primary-50/50 p-4 rounded-2xl">
                    <span class="text-[10px] font-black text-primary-400 uppercase tracking-widest">${new Date(gad.tgl_gad).toLocaleDateString('id-ID')}</span>
                    <span class="text-2xl font-black text-primary-800"><span class="text-xs font-bold opacity-60">SKOR:</span> ${gad.skor}</span>
                </div>
            </div>
        `;
    }).join('');
    
    if (tbody) tbody.innerHTML = rowsHtml;
    if (cards) cards.innerHTML = cardsHtml;
}

async function loadWargaList() {
    try {
        const user = JSON.parse(localStorage.getItem('user'));
        const endpoint = user.role === 'kader' ? `/users/kader/${user.id}/warga` : '/users?role=warga&per_page=1000';
        const res = await apiCall(endpoint);
        if (res && res.success) {
            const select = document.getElementById('filterWarga');
            const data = user.role === 'kader' ? res.data : res.data.data;
            data.forEach(w => {
                const opt = document.createElement('option');
                opt.value = w.id;
                opt.textContent = w.nama_lengkap;
                select.appendChild(opt);
            });
        }
    } catch (e) { console.error(e); }
}

document.addEventListener('DOMContentLoaded', () => {
    loadWargaList();
    loadData();
    
    document.getElementById('filterWarga').addEventListener('change', loadData);
});

async function loadTdData(page = 1) {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const res = await apiCall(`/tekanan-darah?page=${page}&per_page=10&warga_id=${wargaId}&start_date=${start}&end_date=${end}`);
    if (res && res.success) {
        tdFullData = res.data.data || [];
        renderTdTable(res.data.data || []);
        window.renderTablePagination(res.data, 'tdPagination', 'loadTdData');
    }
}

async function loadGadData(page = 1) {
    const wargaId = document.getElementById('filterWarga').value;
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const res = await apiCall(`/gad?page=${page}&per_page=10&warga_id=${wargaId}&start_date=${start}&end_date=${end}`);
    if (res && res.success) {
        gadFullData = res.data.data || [];
        renderGadTable(res.data.data || []);
        window.renderTablePagination(res.data, 'gadPagination', 'loadGadData');
    }
}

function resetFilter() {
    document.getElementById('filterWarga').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    loadData();
}
</script>
@endsection