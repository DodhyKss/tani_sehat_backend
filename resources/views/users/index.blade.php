@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen User</h1>
        <p class="text-gray-500">Data dari endpoint <code class="bg-gray-100 text-primary-600 px-2 py-1 rounded-md text-sm font-mono border border-gray-200">GET /api/users</code></p>
    </div>
    <button onclick="showAlert('Demo: Fitur tambah form belum diimplementasi (API Ready)', 'success')" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-sm transition-colors flex items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        Tambah User
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">NIK</th>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">No HP</th>
                    <th class="px-6 py-4">Tgl Lahir</th>
                </tr>
            </thead>
            <tbody id="usersTableBody" class="divide-y divide-gray-100">
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 flex justify-center items-center gap-3">
                        <div class="w-5 h-5 border-2 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                        Memuat data...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="pagination" class="mt-6 flex justify-end items-center gap-2">
    <!-- Pagination buttons -->
</div>
@endsection

@section('scripts')
<script>
    async function loadUsers(page = 1) {
        try {
            const res = await apiCall(`/users?page=${page}`);
            
            if (res && res.success) {
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = '';
                
                const users = res.data.data;
                
                if (users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data user.</td></tr>';
                    return;
                }
                
                users.forEach(u => {
                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-gray-50 transition-colors";
                    
                    let badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200'; // warga
                    if (u.role === 'admin') badgeClass = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                    if (u.role === 'kader') badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                    
                    tr.innerHTML = `
                        <td class="px-6 py-4 font-mono text-gray-500">${u.nik}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">${u.nama_lengkap}</td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full uppercase tracking-wide ${badgeClass}">${u.role}</span></td>
                        <td class="px-6 py-4">${u.no_hp}</td>
                        <td class="px-6 py-4">${new Date(u.tanggal_lahir).toLocaleDateString('id-ID')}</td>
                    `;
                    tbody.appendChild(tr);
                });
                
                renderPagination(res.data);
            }
        } catch (e) {
            console.error(e);
            showAlert('Gagal memuat data user', 'error');
        }
    }
    
    function renderPagination(data) {
        const pag = document.getElementById('pagination');
        pag.innerHTML = '';
        
        if (data.prev_page_url) {
            const btn = document.createElement('button');
            btn.className = 'px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition-colors shadow-sm';
            btn.textContent = 'Sebelumnya';
            btn.onclick = () => loadUsers(data.current_page - 1);
            pag.appendChild(btn);
        }
        
        const span = document.createElement('span');
        span.className = 'px-4 py-2 text-sm text-gray-600 font-medium';
        span.textContent = `Halaman ${data.current_page} dari ${data.last_page}`;
        pag.appendChild(span);
        
        if (data.next_page_url) {
            const btn = document.createElement('button');
            btn.className = 'px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition-colors shadow-sm';
            btn.textContent = 'Selanjutnya';
            btn.onclick = () => loadUsers(data.current_page + 1);
            pag.appendChild(btn);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('token')) {
            const user = JSON.parse(localStorage.getItem('user'));
            if (user && user.role !== 'admin') {
                showAlert('Akses ditolak: Hanya Admin', 'error');
                setTimeout(() => window.location.href = '/', 2000);
            } else {
                loadUsers();
            }
        }
    });
</script>
@endsection
