@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight mb-1">Kesehatan Reproduksi</h1>
        <p class="text-gray-500 text-sm font-medium">Pantau siklus menstruasi dan catatan kesehatan Anda</p>
    </div>

    <!-- Input Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-6 md:p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-rose-400 to-rose-600"></div>
        
        <form id="reproduksiForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tanggal Menstruasi</label>
                    <input type="date" id="tgl_menstruasi" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:bg-white transition-all outline-none font-bold text-gray-800" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Keterangan / Catatan</label>
                    <input type="text" id="keterangan" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-rose-500/10 focus:bg-white transition-all outline-none font-bold text-gray-800 placeholder:text-gray-300" placeholder="Contoh: Hari pertama, Nyeri, dll" required>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-rose-100 transition-all transform active:scale-95 flex justify-center items-center gap-3">
                <span>SIMPAN DATA</span>
                <div class="loader !border-t-white !w-5 !h-5 hidden" id="loader"></div>
            </button>
        </form>
    </div>

    <!-- History -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Riwayat Input</h2>
            <div id="paginationInfo" class="text-[10px] font-bold text-gray-400"></div>
        </div>

        <div id="historyList" class="space-y-3">
            <!-- Data will be loaded here -->
            <div class="py-12 text-center text-gray-400 font-medium bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
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
                    <div class="py-12 text-center text-gray-400 font-medium bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        Belum ada data input.
                    </div>
                `;
                return;
            }

            historyList.innerHTML = res.data.data.map(item => `
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm font-black text-gray-900">${new Date(item.tgl_menstruasi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">${item.keterangan}</p>
                            <p class="text-[9px] text-gray-400 mt-2 font-bold uppercase tracking-wider">Diinput pada: ${new Date(item.created_at).toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="deleteData(${item.id})" class="p-2 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
            html += `<button onclick="loadHistory(${data.current_page - 1})" class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Prev</button>`;
        }
        
        html += `<span class="px-4 py-2 bg-primary-50 text-primary-600 rounded-xl text-xs font-black">${data.current_page} / ${data.last_page}</span>`;

        if (data.next_page_url) {
            html += `<button onclick="loadHistory(${data.current_page + 1})" class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Next</button>`;
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
