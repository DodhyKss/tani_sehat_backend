@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="/warga" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 transition mb-4">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Rekomendasi Kesehatan</h1>
    <p class="text-gray-500 text-sm">Berdasarkan hasil kesehatan Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
            <span class="font-semibold">Video Edukasi</span>
        </div>
        <div id="videosContainer" class="space-y-3">
            <div class="text-center py-4 text-blue-100">Memuat...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <span class="font-semibold">Materi Pembelajaran</span>
        </div>
        <div id="materisContainer" class="space-y-3">
            <div class="text-center py-4 text-purple-100">Memuat...</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <span class="font-semibold">Gambar Edukasi</span>
        </div>
        <div id="gambarsContainer" class="space-y-3">
            <div class="text-center py-4 text-green-100">Memuat...</div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-white/20 rounded-lg">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </div>
            <span class="font-semibold">Rekomendasi Olahraga</span>
        </div>
        <div id="olahragasContainer" class="space-y-3">
            <div class="text-center py-4 text-orange-100">Memuat...</div>
        </div>
    </div>
</div>

<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeDetailModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Detail</h3>
            <button onclick="closeDetailModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="p-6"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
async function loadRekomendasi() {
    try {
        const [vidRes, matRes, gamRes, olhRes] = await Promise.all([
            apiCall('/video'),
            apiCall('/materi'),
            apiCall('/gambar'),
            apiCall('/olahraga')
        ]);
        renderVideos(vidRes?.data || []);
        renderMateris(matRes?.data || []);
        renderGambars(gamRes?.data || []);
        renderOlahragas(olhRes?.data || []);
    } catch (e) { console.error(e); }
}

function renderVideos(videos) {
    const container = document.getElementById('videosContainer');
    if (videos.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-blue-100">Tidak ada video rekomendasi</p>';
        return;
    }
    container.innerHTML = videos.map(v => `
        <div onclick="showVideoDetail(${JSON.stringify(v).replace(/"/g, '&quot;')})" class="bg-white/10 hover:bg-white/20 rounded-lg p-3 cursor-pointer transition">
            <p class="font-medium text-sm">${v.judul}</p>
        </div>
    `).join('');
}

function renderMateris(materis) {
    const container = document.getElementById('materisContainer');
    if (materis.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-purple-100">Tidak ada materi rekomendasi</p>';
        return;
    }
    container.innerHTML = materis.map(m => `
        <div onclick="showMateriDetail(${JSON.stringify(m).replace(/"/g, '&quot;')})" class="bg-white/10 hover:bg-white/20 rounded-lg p-3 cursor-pointer transition">
            <p class="font-medium text-sm">${m.judul}</p>
        </div>
    `).join('');
}

function renderGambars(gambars) {
    const container = document.getElementById('gambarsContainer');
    if (gambars.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-green-100">Tidak ada gambar rekomendasi</p>';
        return;
    }
    container.innerHTML = gambars.map(g => `
        <div onclick="showGambarDetail(${JSON.stringify(g).replace(/"/g, '&quot;')})" class="bg-white/10 hover:bg-white/20 rounded-lg p-3 cursor-pointer transition">
            <p class="font-medium text-sm">${g.judul}</p>
        </div>
    `).join('');
}

function renderOlahragas(olahragas) {
    const container = document.getElementById('olahragasContainer');
    if (olahragas.length === 0) {
        container.innerHTML = '<p class="text-center py-4 text-orange-100">Tidak ada olahraga rekomendasi</p>';
        return;
    }
    container.innerHTML = olahragas.map(o => `
        <div class="bg-white/10 rounded-lg p-3">
            <p class="font-medium text-sm">${o.nama_olahraga}</p>
        </div>
    `).join('');
}

function showVideoDetail(video) {
    document.getElementById('modalTitle').textContent = video.judul;
    document.getElementById('modalContent').innerHTML = `
        <div class="aspect-video bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
            <iframe src="${video.link_embed}" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
        </div>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showMateriDetail(materi) {
    document.getElementById('modalTitle').textContent = materi.judul;
    document.getElementById('modalContent').innerHTML = `
        <a href="/storage/${materi.file_path}" target="_blank" class="inline-flex items-center gap-2 bg-primary-600 text-white px-4 py-2 rounded-lg mb-4">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download File
        </a>
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function showGambarDetail(gambar) {
    document.getElementById('modalTitle').textContent = gambar.judul;
    document.getElementById('modalContent').innerHTML = `
        <img src="/storage/${gambar.file_path}" alt="${gambar.judul}" class="w-full rounded-lg">
    `;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    loadRekomendasi();
});
</script>
@endsection