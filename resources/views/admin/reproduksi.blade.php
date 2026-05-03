@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Monitoring Reproduksi</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Pantau Siklus & Kesehatan Reproduksi Seluruh Warga</p>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mb-10 overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="relative flex-1 max-w-xl">
            <input type="text" id="searchWarga" placeholder="Cari Nama Warga atau NIK..." 
                class="w-full pl-14 pr-6 py-4 bg-primary-50/50 border-2 border-primary-800 focus:border-primary-600 focus:bg-white rounded-2xl transition-all font-black text-black appearance-none outline-none">
            <svg class="w-6 h-6 text-primary-400 absolute left-5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="exportData()" class="flex-1 lg:flex-none justify-center px-8 py-4 bg-primary-800 text-white rounded-2xl font-black text-xs transition-all shadow-lg shadow-primary-900/20 uppercase tracking-widest flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-1m-4-4-4 4-4-4m4 4V4"/></svg>
                Export PDF
            </button>
        </div>
    </div>
</div>

<!-- Desktop Table -->
<div class="hidden md:block bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden">
    <div class="overflow-x-auto px-6">
        <table class="w-full text-left">
            <thead class="text-primary-400 uppercase text-xs font-black tracking-[0.2em] border-b-2 border-primary-50">
                <tr>
                    <th class="px-6 py-8">Warga Binaan</th>
                    <th class="px-6 py-8 text-center">Tanggal Menstruasi</th>
                    <th class="px-6 py-8">Keterangan Kesehatan</th>
                    <th class="px-6 py-8 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody id="reproduksiTable" class="divide-y-2 divide-primary-50">
                <tr><td colspan="4" class="px-6 py-20 text-center text-primary-300 font-bold italic text-lg animate-pulse">Memuat data reproduksi...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Cards -->
<div id="reproduksiCards" class="md:hidden space-y-4">
    <div class="py-20 text-center text-gray-400 font-medium bg-gray-50/50 rounded-[2rem] border border-dashed border-gray-200">
        Memuat data...
    </div>
</div>

<div id="pagination" class="flex justify-center gap-2 mt-10"></div>

@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let allData = [];

    async function loadData(page = 1) {
        currentPage = page;
        const res = await apiCall(`/reproduksi?page=${page}`);
        
        if (res && res.success) {
            allData = res.data.data;
            renderData(allData);
            renderLocalPagination(res.data);
        }
    }

    function renderData(data) {
        const tbody = document.getElementById('reproduksiTable');
        const cards = document.getElementById('reproduksiCards');
        const search = document.getElementById('searchWarga').value.toLowerCase();
        
        const filtered = data.filter(item => 
            !search || item.user.nama_lengkap.toLowerCase().includes(search) || item.user.nik.includes(search)
        );

        if (filtered.length === 0) {
            const emptyHtml = `<div class="py-20 text-center text-gray-400 font-medium bg-gray-50/50 rounded-[2rem] border border-dashed border-gray-200">Tidak ada data ditemukan.</div>`;
            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-20 text-center text-gray-400 font-medium">Tidak ada data ditemukan.</td></tr>`;
            cards.innerHTML = emptyHtml;
            return;
        }

        // Render Table
        tbody.innerHTML = filtered.map(item => `
            <tr class="hover:bg-primary-50/50 transition-colors group">
                <td class="px-6 py-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-primary-100 flex items-center justify-center text-primary-800 font-black text-xl shadow-inner group-hover:scale-110 transition-all">
                            ${item.user.nama_lengkap.charAt(0)}
                        </div>
                        <div>
                            <p class="font-black text-black text-xl tracking-tight leading-tight group-hover:text-primary-800 transition-colors">${item.user.nama_lengkap}</p>
                            <p class="text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] mt-1">${item.user.nik}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-6 text-center">
                    <span class="px-6 py-2.5 bg-amber-100 text-amber-800 rounded-2xl text-sm font-black border-2 border-amber-200 shadow-sm inline-block">
                        ${new Date(item.tgl_menstruasi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                    </span>
                </td>
                <td class="px-6 py-6">
                    <p class="text-sm font-bold text-primary-800 max-w-xs leading-relaxed" title="${item.keterangan}">${item.keterangan}</p>
                </td>
                <td class="px-6 py-6 text-right">
                    <div class="flex justify-end gap-3">
                        <button onclick="window.location.href='/chat?warga_id=${item.user.id}'" class="p-4 text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-2xl transition-all transform hover:scale-110 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </button>
                        <button onclick="deleteData(${item.id})" class="p-4 text-orange-600 bg-orange-50 hover:bg-orange-100 rounded-2xl transition-all transform hover:scale-110 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        // Render Cards
        cards.innerHTML = filtered.map(item => `
            <div class="bg-white p-5 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600 font-black text-sm">
                            ${item.user.nama_lengkap.charAt(0)}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 leading-tight">${item.user.nama_lengkap}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">NIK: ${item.user.nik}</p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button onclick="window.location.href='/chat?warga_id=${item.user.id}'" class="p-2 text-primary-500 bg-primary-50 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </button>
                        <button onclick="deleteData(${item.id})" class="p-2 text-rose-500 bg-rose-50 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="bg-gray-50 p-3 rounded-xl flex items-center justify-between">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Tgl Menstruasi</span>
                        <span class="text-xs font-black text-rose-600">${new Date(item.tgl_menstruasi).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Keterangan</span>
                        <p class="text-xs font-bold text-gray-600">${item.keterangan}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderLocalPagination(data) {
        const pagination = document.getElementById('pagination');
        if (data.last_page <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = '';
        if (data.prev_page_url) {
            html += `<button onclick="loadData(${data.current_page - 1})" class="px-5 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-black text-gray-600 hover:bg-gray-50 transition shadow-sm">Prev</button>`;
        }
        
        html += `<span class="px-6 py-3 bg-primary-50 text-primary-600 rounded-2xl text-xs font-black flex items-center">${data.current_page} / ${data.last_page}</span>`;

        if (data.next_page_url) {
            html += `<button onclick="loadData(${data.current_page + 1})" class="px-5 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-black text-gray-600 hover:bg-gray-50 transition shadow-sm">Next</button>`;
        }

        pagination.innerHTML = html;
    }

    async function deleteData(id) {
        if (!confirm('Hapus data ini secara permanen?')) return;
        const res = await apiCall(`/reproduksi/${id}`, 'DELETE');
        if (res && res.success) {
            showAlert('Data berhasil dihapus', 'success');
            loadData(currentPage);
        }
    }

    function exportData() {
        window.print();
    }

    document.getElementById('searchWarga').addEventListener('input', () => {
        renderData(allData);
    });

    // Initialize
    loadData();
</script>
@endsection
