@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Pengaturan Jadwal Pengisian</h1>
    <p class="text-gray-500 text-sm">Atur interval waktu pengisian kesehatan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-red-50 rounded-xl">
                <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Tekanan Darah</h2>
                <p class="text-sm text-gray-500">Popup saat login</p>
            </div>
        </div>
        
        <form id="tdForm" class="space-y-4">
            <div class="flex items-center gap-4">
                <label class="text-sm text-gray-600 w-20">Setiap</label>
                <input type="number" id="tdJumlah" min="1" max="365" class="w-24 px-3 py-2 rounded-lg border border-gray-200 text-center font-semibold focus:ring-2 focus:ring-primary-500" value="1">
                <select id="tdTipe" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500">
                    <option value="hours">Jam</option>
                    <option value="day" selected>Hari</option>
                    <option value="week">Minggu</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-lg transition shadow-sm">
                Simpan Pengaturan TD
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-yellow-50 rounded-xl">
                <svg class="w-6 h-6 text-yellow-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Kuesioner GAD7</h2>
                <p class="text-sm text-gray-500">Setiap 2 minggu sekali</p>
            </div>
        </div>
        
        <form id="gadForm" class="space-y-4">
            <div class="flex items-center gap-4">
                <label class="text-sm text-gray-600 w-20">Setiap</label>
                <input type="number" id="gadJumlah" min="1" max="365" class="w-24 px-3 py-2 rounded-lg border border-gray-200 text-center font-semibold focus:ring-2 focus:ring-primary-500" value="2">
                <select id="gadTipe" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary-500">
                    <option value="hours">Jam</option>
                    <option value="day" selected>hari</option>
                    <option value="week">Minggu</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-lg transition shadow-sm">
                Simpan Pengaturan GAD7
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Pengaturan Saat Ini</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-4 py-3 text-left">Jenis</th>
                    <th class="px-4 py-3 text-left">Interval</th>
                    <th class="px-4 py-3 text-left">Terakhir Diupdate</th>
                </tr>
            </thead>
            <tbody id="jadwalTable" class="divide-y divide-gray-100">
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadJadwal() {
    try {
        const res = await apiCall('/admin/jadwal');
        const tbody = document.getElementById('jadwalTable');
        
        if (res && res.data && res.data.success && res.data.data && res.data.data.length > 0) {
            res.data.data.forEach(j => {
                if (j.jenis_pengisian === 'td') {
                    document.getElementById('tdJumlah').value = j.jumlah;
                    document.getElementById('tdTipe').value = j.tipe;
                } else {
                    document.getElementById('gadJumlah').value = j.jumlah;
                    document.getElementById('gadTipe').value = j.tipe;
                }
            });
            
            tbody.innerHTML = res.data.data.map(j => {
                const tipeLabel = { hours: 'jam', day: 'hari', week: 'minggu' };
                return `<tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">${j.jenis_pengisian === 'td' ? 'Tekanan Darah' : 'GAD7'}</td>
                    <td class="px-4 py-3">Setiap ${j.jumlah} ${tipeLabel[j.tipe]}</td>
                    <td class="px-4 py-3 text-gray-500">${new Date(j.updated_at).toLocaleString('id-ID')}</td>
                </tr>`;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada pengaturan jadwal</td></tr>';
        }
    } catch (e) { console.error(e); }
}

document.getElementById('tdForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = { jenis_pengisian: 'td', jumlah: parseInt(document.getElementById('tdJumlah').value), tipe: document.getElementById('tdTipe').value };
    const res = await apiCall('/admin/jadwal', 'POST', data);
    if (res && res.success) showAlert('Pengaturan TD berhasil disimpan', 'success');
    loadJadwal();
});

document.getElementById('gadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = { jenis_pengisian: 'gad7', jumlah: parseInt(document.getElementById('gadJumlah').value), tipe: document.getElementById('gadTipe').value };
    const res = await apiCall('/admin/jadwal', 'POST', data);
    if (res && res.success) showAlert('Pengaturan GAD7 berhasil disimpan', 'success');
    loadJadwal();
});

document.addEventListener('DOMContentLoaded', () => {
    loadJadwal();
});
</script>
@endsection