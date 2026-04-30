@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-black tracking-tight mb-2">Kesehatan Reproduksi</h1>
        <p class="text-primary-800 text-lg font-bold">Pantau siklus menstruasi dan catatan kesehatan Anda dengan mudah</p>
    </div>

    <!-- Input Card -->
    <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-[2.5rem] shadow-xl p-6 md:p-8 mb-8 relative overflow-hidden text-white border border-primary-500/30">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary-400 to-primary-500"></div>
        
        <form id="reproduksiForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-base font-bold text-white/90 uppercase tracking-widest ml-1">Tanggal Menstruasi</label>
                    <input type="date" id="tgl_menstruasi" class="w-full px-5 py-4 bg-white/10 border-2 border-white/20 rounded-2xl focus:ring-4 focus:ring-primary-500/20 focus:bg-white/20 transition-all outline-none font-bold text-white" required>
                </div>
                <div class="space-y-2">
                    <label class="text-base font-bold text-white/90 uppercase tracking-widest ml-1">Keterangan / Catatan</label>
                    <input type="text" id="keterangan" class="w-full px-5 py-4 bg-white/10 border-2 border-white/20 rounded-2xl focus:ring-4 focus:ring-primary-500/20 focus:bg-white/20 transition-all outline-none font-bold text-white placeholder:text-white/40" placeholder="Contoh: Hari pertama, Nyeri, dll" required>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-white hover:bg-primary-50 text-primary-700 text-2xl font-black py-6 px-6 rounded-2xl shadow-2xl transition-all transform active:scale-95 flex justify-center items-center gap-3">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>SIMPAN DATA</span>
                <div class="loader !border-t-primary-700 !w-6 !h-6 hidden" id="loader"></div>
            </button>
        </form>
    </div>

    <!-- History -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h2 class="text-base font-bold text-primary-800 uppercase tracking-widest">Riwayat Input</h2>
            <div id="paginationInfo" class="text-sm font-black text-primary-700"></div>
        </div>

        <div id="historyList" class="space-y-3">
            <!-- Data will be loaded here -->
            <div class="py-12 text-center text-primary-800 font-bold bg-primary-100/50 rounded-3xl border-2 border-dashed border-primary-300">
                Memuat riwayat kesehatan...
            </div>
        </div>

        <div id="pagination" class="flex justify-center gap-2 mt-6"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentPage = 1;

    async function loadHistory(page = 1) {
        currentPage = page;
        const historyList = document.getElementById('historyList');
        const res = await apiCall(`/reproduksi?page=${page}`);
        
        if (res && res.success) {
            if (res.data.data.length === 0) {
                historyList.innerHTML = `
                    <div class="py-12 text-center text-white font-bold bg-primary-900/30 rounded-3xl border-2 border-dashed border-primary-400/20">
                        Belum ada data input.
                    </div>
                `;
                return;
            }

            historyList.innerHTML = res.data.data.map(item => `
                <div class="bg-gradient-to-br from-primary-600 to-primary-800 p-5 rounded-3xl border border-primary-500/30 shadow-lg hover:shadow-xl transition-all group relative overflow-hidden text-white">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xl font-bold text-white">${new Date(item.tgl_menstruasi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                            </div>
                            <p class="text-lg text-primary-100 font-semibold">${item.keterangan}</p>
                            <p class="text-sm text-white/60 mt-2 font-bold uppercase tracking-wider">Diinput pada: ${new Date(item.created_at).toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="deleteData(${item.id})" class="p-2 text-white/40 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            `).join('');

            renderPagination(res.data);
        }
    }

    function renderPagination(data) {
        const pagination = document.getElementById('pagination');
        if (data.last_page <= 1) {
            pagination.innerHTML = '';
            return;
        }

        let html = '';
        if (data.prev_page_url) {
            html += `<button onclick="loadHistory(${data.current_page - 1})" class="px-5 py-3 bg-white border border-primary-100 rounded-xl text-base font-bold text-gray-700 hover:bg-primary-50 transition shadow-sm">Prev</button>`;
        }
        
        html += `<span class="px-5 py-3 bg-primary-600 text-white rounded-xl text-base font-black shadow-md">${data.current_page} / ${data.last_page}</span>`;

        if (data.next_page_url) {
            html += `<button onclick="loadHistory(${data.current_page + 1})" class="px-5 py-3 bg-white border border-primary-100 rounded-xl text-base font-bold text-gray-700 hover:bg-primary-50 transition shadow-sm">Next</button>`;
        }

        pagination.innerHTML = html;
    }

    async function deleteData(id) {
        if (!confirm('Hapus data ini?')) return;
        
        const res = await apiCall(`/reproduksi/${id}`, 'DELETE');
        if (res && res.success) {
            showAlert('Data berhasil dihapus', 'success');
            loadHistory(currentPage);
        } else {
            showAlert(res.message || 'Gagal menghapus data');
        }
    }

    document.getElementById('reproduksiForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const tgl_menstruasi = document.getElementById('tgl_menstruasi').value;
        const keterangan = document.getElementById('keterangan').value;
        const submitBtn = document.getElementById('submitBtn');
        const loader = document.getElementById('loader');

        submitBtn.disabled = true;
        loader.classList.remove('hidden');
        submitBtn.querySelector('span').classList.add('opacity-50');

        const res = await apiCall('/reproduksi', 'POST', { tgl_menstruasi, keterangan });

        submitBtn.disabled = false;
        loader.classList.add('hidden');
        submitBtn.querySelector('span').classList.remove('opacity-50');

        if (res && res.success) {
            showAlert('Data reproduksi berhasil disimpan!', 'success');
            document.getElementById('reproduksiForm').reset();
            loadHistory(1);
        } else {
            showAlert(res.message || 'Gagal menyimpan data');
        }
    });

    // Initialize
    loadHistory();
</script>
@endsection
