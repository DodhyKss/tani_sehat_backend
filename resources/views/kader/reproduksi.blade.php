@extends('layouts.app')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Monitoring Reproduksi</h1>
    <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Pantau Siklus & Kesehatan Reproduksi Warga Binaan</p>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 p-8 md:p-10 mb-10 overflow-hidden">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="relative flex-1 max-w-xl">
            <input type="text" id="searchWarga" placeholder="Cari Nama Warga Binaan..." 
                class="w-full pl-14 pr-6 py-4 bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white rounded-2xl transition-all font-black text-black appearance-none outline-none">
            <svg class="w-6 h-6 text-primary-400 absolute left-5 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs font-black text-primary-300 uppercase tracking-widest px-4 border-l-4 border-primary-100">Filter: Semua Warga</span>
        </div>
    </div>
</div>

<div id="reproduksiList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <!-- Data will be loaded here -->
    <div class="col-span-full py-20 text-center text-gray-400 font-medium bg-gray-50/50 rounded-[2.5rem] border border-dashed border-gray-200">
        Memuat data reproduksi warga...
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
        const list = document.getElementById('reproduksiList');
        const res = await apiCall(`/reproduksi?page=${page}`);
        
        if (res && res.success) {
            allData = res.data.data;
            renderList(allData);
            renderPagination(res.data);
        }
    }

    function renderList(data) {
        const list = document.getElementById('reproduksiList');
        const search = document.getElementById('searchWarga').value.toLowerCase();
        
        const filtered = data.filter(item => 
            !search || item.user.nama_lengkap.toLowerCase().includes(search)
        );

        if (filtered.length === 0) {
            list.innerHTML = `
                <div class="col-span-full py-20 text-center text-gray-400 font-medium bg-gray-50/50 rounded-[2.5rem] border border-dashed border-gray-200">
                    Tidak ada data reproduksi yang ditemukan.
                </div>
            `;
            return;
        }

        list.innerHTML = filtered.map(item => `
            <div class="bg-white p-8 rounded-[2.5rem] border-2 border-primary-50 shadow-xl shadow-primary-900/5 hover:border-primary-200 transition-all group relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-2 bg-primary-800"></div>
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-primary-100 flex items-center justify-center text-primary-800 font-black text-2xl shadow-inner group-hover:scale-110 transition-all">
                        ${item.user.nama_lengkap.charAt(0)}
                    </div>
                    <div>
                        <h3 class="font-black text-black text-2xl tracking-tight leading-tight group-hover:text-primary-800 transition-colors">${item.user.nama_lengkap}</h3>
                        <p class="text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] mt-1">NIK: ${item.user.nik}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-amber-50/50 p-6 rounded-2xl border border-amber-100/50">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-black text-amber-800 uppercase tracking-widest">Tgl Menstruasi</span>
                        </div>
                        <p class="text-lg font-black text-amber-900">${new Date(item.tgl_menstruasi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                    </div>
 
                    <div class="bg-primary-50/50 p-6 rounded-2xl border border-primary-100/50">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span class="text-[10px] font-black text-primary-800 uppercase tracking-widest">Keterangan</span>
                        </div>
                        <p class="text-sm font-bold text-primary-800 leading-relaxed italic">"${item.keterangan}"</p>
                    </div>
                </div>
 
                <div class="mt-8 pt-6 border-t-2 border-primary-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <span class="text-[10px] font-black text-primary-200 uppercase tracking-[0.2em]">Input: ${new Date(item.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short'})}</span>
                    <button onclick="window.location.href='/chat?warga_id=${item.user.id}'" class="w-full sm:w-auto text-xs font-black text-white bg-primary-800 hover:bg-black uppercase tracking-widest flex items-center justify-center gap-3 px-6 py-4 rounded-2xl transition-all shadow-xl shadow-primary-900/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Hubungi Warga
                    </button>
                </div>
            </div>
        `).join('');
    }

    function renderPagination(data) {
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

    document.getElementById('searchWarga').addEventListener('input', () => {
        renderList(allData);
    });

    // Initialize
    loadData();
</script>
@endsection
