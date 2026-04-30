@extends('layouts.app')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl md:text-4xl font-extrabold text-black mb-2 tracking-tight">Pusat Rekomendasi & Edukasi</h1>
    <p class="text-primary-800 text-lg font-bold uppercase tracking-widest opacity-60">Materi Edukasi Berkualitas Untuk Warga Binaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Video Edukasi</span>
        </div>
        <div id="videosContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-blue-100 font-bold italic opacity-60 animate-pulse">Memuat materi...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Materi PDF</span>
        </div>
        <div id="materisContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-indigo-100 font-bold italic opacity-60 animate-pulse">Memuat materi...</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Gambar Edukasi</span>
        </div>
        <div id="gambarsContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-emerald-100 font-bold italic opacity-60 animate-pulse">Memuat materi...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-[2.5rem] p-8 text-white shadow-2xl border border-white/10 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-bl-[5rem] -mr-10 -mt-10 transition-all group-hover:scale-110"></div>
        <div class="flex items-center gap-4 mb-6 relative z-10">
            <div class="p-3 bg-white/20 rounded-2xl">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </div>
            <span class="text-xl font-black uppercase tracking-widest opacity-80">Program Olahraga</span>
        </div>
        <div id="olahragasContainer" class="space-y-4 relative z-10">
            <div class="text-center py-6 text-orange-100 font-bold italic opacity-60 animate-pulse">Memuat materi...</div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDetailModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white/80 backdrop-blur-md p-6 border-b border-gray-100 flex justify-between items-center z-10">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Detail Rekomendasi</h3>
            <button onclick="closeDetailModal()" class="p-2 hover:bg-gray-100 rounded-xl transition">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="p-8"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadAllRekomendasi() {
    try {
        const [vidRes, matRes, gamRes, olhRes] = await Promise.all([
            apiCall('/video'),
            apiCall('/materi'),
            apiCall('/gambar'),
            apiCall('/olahraga')
        ]);
        renderItems('videosContainer', vidRes?.data || [], 'blue', showVideoDetail);
        renderItems('materisContainer', matRes?.data || [], 'purple', showMateriDetail);
        renderItems('gambarsContainer', gamRes?.data || [], 'green', showGambarDetail);
        renderOlahragas(olhRes?.data || []);
    } catch (e) { console.error(e); }
}

function renderItems(containerId, items, color, detailFn) {
    const container = document.getElementById(containerId);
    if (items.length === 0) {
        container.innerHTML = `<p class="text-center py-4 text-${color}-100">Tidak ada data</p>`;
        return;
    }
    container.innerHTML = items.map(item => `
        <div onclick='${detailFn.name}(${JSON.stringify(item).replace(/'/g, "&apos;")})' 
             class="bg-white/10 hover:bg-white/30 rounded-2xl p-5 cursor-pointer transition-all flex items-center justify-between group border border-white/5">
            <p class="font-black text-lg truncate mr-6 uppercase tracking-tight">${item.judul}</p>
            <div class="p-2 bg-white/20 rounded-xl group-hover:bg-white group-hover:text-black transition-all transform group-hover:translate-x-1">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"/></svg>
            </div>
        </div>
    `).join('');
}

function renderOlahragas(olahragas) {
    const container = document.getElementById('olahragasContainer');
    if (olahragas.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-orange-100">Tidak ada rekomendasi</p>';
        return;
    }
    container.innerHTML = olahragas.map(o => `
        <div class="bg-white/10 rounded-2xl p-5 flex items-center gap-4 border border-white/5">
            <div class="p-3 bg-white/20 rounded-xl">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M12 2v20M2 12h20"/></svg>
            </div>
            <p class="font-black text-lg uppercase tracking-tight">${o.nama_olahraga}</p>
        </div>
    `).join('');
}

function showVideoDetail(video) {
    document.getElementById('modalTitle').textContent = video.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="aspect-video bg-black rounded-2xl overflow-hidden shadow-lg mb-6">
            <iframe src="${video.link_embed}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider">Kategori TD: ${video.kategori_td}</span>
            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-bold uppercase tracking-wider">Kategori GAD: ${video.kategori_gad}</span>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showMateriDetail(materi) {
    document.getElementById('modalTitle').textContent = materi.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="p-12 bg-gray-50 rounded-3xl flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-200 mb-6">
            <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <p class="text-gray-500 mb-6 italic">Dokumen materi tersedia untuk diunduh</p>
            <a href="/public/${materi.file_path}" target="_blank" class="inline-flex items-center gap-3 bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-2xl font-bold transition shadow-lg shadow-purple-200">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Unduh Materi (PDF)
            </a>
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider">Kategori TD: ${materi.kategori_td}</span>
            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-bold uppercase tracking-wider">Kategori GAD: ${materi.kategori_gad}</span>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showGambarDetail(gambar) {
    document.getElementById('modalTitle').textContent = gambar.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="bg-gray-100 rounded-2xl overflow-hidden mb-6">
            <img src="/public/${gambar.file_path}" alt="${gambar.judul}" class="w-full">
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider">Kategori TD: ${gambar.kategori_td}</span>
            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-bold uppercase tracking-wider">Kategori GAD: ${gambar.kategori_gad}</span>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', loadAllRekomendasi);
</script>
@endsection
