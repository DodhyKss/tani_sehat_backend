@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Master Tindak Lanjut</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Kelola Data Master Tindak Lanjut</p>
    </div>
    <button onclick="openModal()" class="bg-primary-800 hover:bg-black text-white font-black py-4 px-8 rounded-2xl shadow-xl shadow-primary-900/20 transition-all flex items-center gap-3 group uppercase tracking-widest text-sm">
        <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        Tambah Data
    </button>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden mb-10">
    <div class="overflow-x-auto p-8">
        <table class="border-collapse w-full">
            <thead>
                <tr>
                    <th class="text-left py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Nama Tindakan</th>
                    <th class="text-left py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Jenis Tindakan</th>
                    <th class="text-left py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Kategori</th>
                    <th class="text-center py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="4" class="px-6 py-20 text-center text-primary-300 font-bold italic animate-pulse text-xl uppercase tracking-widest">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="dataModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-primary-900/80 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white/95 backdrop-blur-md p-8 border-b-2 border-primary-50 flex justify-between items-center z-10">
            <div>
                <h3 id="modalTitle" class="text-2xl font-black text-black tracking-tight">Tambah Master Tindak Lanjut</h3>
            </div>
            <button onclick="closeModal()" class="p-3 hover:bg-primary-50 rounded-2xl text-primary-300 hover:text-primary-800 transition-all">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="dataForm" class="p-8 space-y-6">
            <input type="hidden" id="dataId">
            <div class="space-y-2">
                <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Nama Tindakan</label>
                <input type="text" id="nama_tindakan" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Jenis Tindakan</label>
                    <select id="jenis_tindakan" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black text-black appearance-none" required>
                        <option value="td">Tekanan Darah (TD)</option>
                        <option value="gad7">GAD-7</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-black text-primary-800 uppercase tracking-widest ml-1">Kategori</label>
                    <select id="kategori" class="w-full px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-black text-black appearance-none" required>
                        <option value="normal">Normal</option>
                        <option value="pra_hipertensi">Pra Hipertensi</option>
                        <option value="hipertensi">Hipertensi</option>
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="tinggi">Tinggi</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-4 pt-6">
                <button type="button" onclick="closeModal()" class="flex-1 px-8 py-4 border-2 border-primary-100 rounded-2xl text-primary-800 font-black hover:bg-primary-50 transition-all uppercase tracking-widest text-sm">BATAL</button>
                <button type="submit" class="flex-1 bg-primary-800 hover:bg-black text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-primary-900/20 uppercase tracking-widest text-sm">SIMPAN DATA</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(data = null) {
    document.getElementById('dataModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = data ? 'Edit Master Tindak Lanjut' : 'Tambah Master Tindak Lanjut';
    if (data) {
        document.getElementById('dataId').value = data.id;
        document.getElementById('nama_tindakan').value = data.nama_tindakan;
        document.getElementById('jenis_tindakan').value = data.jenis_tindakan;
        document.getElementById('kategori').value = data.kategori;
    } else {
        document.getElementById('dataForm').reset();
        document.getElementById('dataId').value = '';
    }
}

function closeModal() {
    document.getElementById('dataModal').classList.add('hidden');
}

async function loadData() {
    try {
        const res = await apiCall('/master-tindak-lanjut');
        if (res && res.data) {
            renderTable(res.data);
        }
    } catch (e) { console.error(e); showAlert('Gagal memuat data', 'error'); }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(item => {
        let badgeType = item.jenis_tindakan === 'td' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800';
        return `<tr class="border-b border-primary-50 hover:bg-primary-50/50 transition-colors">
            <td class="px-4 py-4 font-bold text-primary-800">${item.nama_tindakan}</td>
            <td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold uppercase ${badgeType}">${item.jenis_tindakan}</span></td>
            <td class="px-4 py-4 text-primary-600 font-semibold uppercase text-sm">${item.kategori.replace('_', ' ')}</td>
            <td class="px-4 py-4">
                <div class="flex items-center justify-center gap-2">
                    <button onclick='openModal(${JSON.stringify(item)})' class="p-2 bg-primary-50 hover:bg-primary-100 rounded-lg text-primary-600" title="Edit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button onclick="deleteData(${item.id})" class="p-2 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-600" title="Hapus">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

document.getElementById('dataForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('dataId').value;
    const data = {
        nama_tindakan: document.getElementById('nama_tindakan').value,
        jenis_tindakan: document.getElementById('jenis_tindakan').value,
        kategori: document.getElementById('kategori').value
    };
    
    const res = await apiCall(id ? `/master-tindak-lanjut/${id}` : '/master-tindak-lanjut', id ? 'PUT' : 'POST', data);
    if (res && res.success) {
        showAlert(id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan', 'success');
        closeModal();
        loadData();
    } else {
        showAlert(res?.message || 'Gagal menyimpan data');
    }
});

async function deleteData(id) {
    if (!confirm('Yakin hapus data ini?')) return;
    const res = await apiCall(`/master-tindak-lanjut/${id}`, 'DELETE');
    if (res && res.success) {
        showAlert('Data berhasil dihapus', 'success');
        loadData();
    } else {
        showAlert(res?.message || 'Gagal menghapus data');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadData();
});
</script>
@endsection
