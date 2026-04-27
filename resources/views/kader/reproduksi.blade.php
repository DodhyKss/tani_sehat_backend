@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-black text-gray-900 tracking-tight mb-1">Monitoring Reproduksi</h1>
    <p class="text-gray-500 text-sm font-medium">Pantau siklus kesehatan reproduksi warga pendampingan Anda</p>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <input type="text" id="searchWarga" placeholder="Cari nama warga..." 
                class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-primary-500/10 focus:bg-white transition-all outline-none font-bold text-gray-800 placeholder:text-gray-400">
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-3">Filter: Semua Warga</span>
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
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500"></div>
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 font-black text-lg">
                        ${item.user.nama_lengkap.charAt(0)}
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 leading-tight">${item.user.nama_lengkap}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">NIK: ${item.user.nik}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tgl Menstruasi</span>
                        </div>
                        <p class="text-sm font-black text-gray-800">${new Date(item.tgl_menstruasi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</span>
                        </div>
                        <p class="text-xs font-bold text-gray-600 leading-relaxed">${item.keterangan}</p>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Input: ${new Date(item.created_at).toLocaleDateString('id-ID')}</span>
                    <button onclick="window.location.href='/chat?warga_id=${item.user.id}'" class="text-[10px] font-black text-primary-600 hover:text-primary-700 uppercase tracking-widest flex items-center gap-1.5 bg-primary-50 px-3 py-2 rounded-xl transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
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
