@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Frekuensi Rekomendasi</h1>
        <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Statistik Kunjungan Warga ke Menu Rekomendasi</p>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary-900/5 border border-primary-100 overflow-hidden mb-10">
    <div class="p-8 border-b-2 border-primary-50 flex gap-4">
        <input type="text" id="searchName" placeholder="Cari nama warga..." class="w-full md:w-1/3 px-6 py-4 rounded-2xl bg-primary-50/50 border-2 border-transparent focus:border-primary-600 focus:bg-white transition-all font-bold text-black">
        <button onclick="loadData()" class="bg-primary-800 hover:bg-black text-white font-black py-4 px-8 rounded-2xl transition-all shadow-xl uppercase tracking-widest text-sm">Cari</button>
    </div>
    <div class="overflow-x-auto p-8">
        <table class="border-collapse w-full">
            <thead>
                <tr>
                    <th class="text-left py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Nama Warga</th>
                    <th class="text-center py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Materi</th>
                    <th class="text-center py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Video</th>
                    <th class="text-center py-4 px-4 border-b-2 border-primary-50 text-xs font-black text-primary-800 uppercase tracking-widest">Gambar</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr><td colspan="5" class="px-6 py-20 text-center text-primary-300 font-bold italic animate-pulse text-xl uppercase tracking-widest">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadData() {
    const search = document.getElementById('searchName').value;
    try {
        const url = search ? `/frekuensi?nama=${encodeURIComponent(search)}` : '/frekuensi';
        const res = await apiCall(url);
        if (res && res.data) {
            renderTable(res.data);
        }
    } catch (e) { console.error(e); showAlert('Gagal memuat data', 'error'); }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(item => {
        return `<tr class="border-b border-primary-50 hover:bg-primary-50/50 transition-colors">
            <td class="px-4 py-4 font-bold text-primary-800 text-lg">${item.nama_lengkap}</td>
            <td class="px-4 py-4 text-center"><span class="inline-flex items-center justify-center w-full h-10 rounded-md bg-blue-100 text-blue-800 font-black">${item.materi}x Lihat</span></td>
            <td class="px-4 py-4 text-center"><span class="inline-flex items-center justify-center w-full h-10 rounded-md bg-red-100 text-red-800 font-black">${item.video}x Lihat</span></td>
            <td class="px-4 py-4 text-center"><span class="inline-flex items-center justify-center w-full h-10 rounded-md bg-emerald-100 text-emerald-800 font-black">${item.gambar}x Lihat</span></td>
        </tr>`;
    }).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    loadData();
});
</script>
@endsection
