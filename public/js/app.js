// API Handler and Token Management
const API_URL = '/api';

// Authentication
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user'));

// Function to make API calls
async function apiCall(endpoint, method = 'GET', body = null) {
    const currentToken = localStorage.getItem('token');
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    if (currentToken) {
        headers['Authorization'] = `Bearer ${currentToken}`;
    }

    const options = {
        method,
        headers
    };

    if (body) {
        options.body = JSON.stringify(body);
    }

    try {
        const response = await fetch(`${API_URL}${endpoint}`, options);
        
        if (response.status === 401 && window.location.pathname !== '/login') {
            logout();
            return null;
        }

        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            const data = await response.json();
            return data;
        } else {
            const text = await response.text();
            return { success: false, message: 'Server error: ' + response.status };
        }
    } catch (error) {
        console.error('API Error:', error);
        return { success: false, message: 'Network error' };
    }
}

// Show alert message (Tailwind styled)
window.showAlert = function(message, type = 'error') {
    const alertEl = document.getElementById('alertMessage');
    if (!alertEl) return;
    
    // Reset classes
    alertEl.className = 'mb-6 p-4 rounded-lg shadow-sm border-l-4';
    
    if (type === 'error') {
        alertEl.classList.add('bg-red-50', 'text-red-800', 'border-red-500');
    } else {
        alertEl.classList.add('bg-green-50', 'text-green-800', 'border-green-500');
    }
    
    alertEl.classList.remove('hidden');
    alertEl.innerHTML = message;
    
    setTimeout(() => {
        alertEl.classList.add('hidden');
    }, 5000);
}

// Logout handler
async function logout() {
    if (token) {
        await apiCall('/logout', 'POST');
    }
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}

// Ensure auth for protected pages
function requireAuth() {
    const currentToken = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user'));
    const path = window.location.pathname;

    if (!currentToken && path !== '/login') {
        window.location.href = '/login';
        return;
    }

    if (user) {
        // Redirect root to role-specific homepage
        if (path === '/' || path === '/dashboard') {
            if (user.role === 'warga' && path !== '/warga') {
                window.location.href = '/warga';
            } else if (user.role === 'kader' && path !== '/kader') {
                window.location.href = '/kader';
            }
        }
        
        // Block warga from admin routes
        if (user.role === 'warga' && (path === '/users' || path.startsWith('/admin'))) {
            window.location.href = '/warga';
        }
    }
}

// Setup user UI
function setupUserUI() {
    const userNameEl = document.getElementById('userName');
    const userRoleEl = document.getElementById('userRole');
    
    if (userNameEl && user) userNameEl.textContent = user.nama_lengkap;
    if (userRoleEl && user) userRoleEl.textContent = user.role;
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname === '/login') return;
    
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            logout();
        });
    }
    
    requireAuth();
    setupUserUI();
    setupNavAuto();
});

function setupNavAuto() {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;
    
    if (user.role === 'admin') setupAdminNav();
    else if (user.role === 'kader') setupKaderNav();
    else if (user.role === 'warga') setupWargaNav();
}

function setupAdminNav() {
    setupNav([
        { url: '/dashboard', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>', label: 'Dashboard', active: true },
        { url: '/users', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', label: 'Manajemen User' },
        { url: '/admin/warga-kader', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>', label: 'Kader Warga' },
        { url: '/admin/kesehatan', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>', label: 'Data Kesehatan' },
        { url: '/admin/jadwal', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>', label: 'Atur Jadwal' },
        { url: '/admin/kuesioner', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>', label: 'Kuesioner GAD7' },
        { url: '/admin/rekomendasi', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>', label: 'Manajemen Rekomendasi' },
        { url: '/chat', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>', label: 'Chat' }
    ]);
}

function setupWargaNav() {
    setupNav([
        { url: '/warga', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>', label: 'Home', active: true },
        { url: '/warga/input-td', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>', label: 'Input TD' },
        { url: '/warga/input-gad', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>', label: 'Kuesioner GAD7' },
        { url: '/warga/rekomendasi', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>', label: 'Rekomendasi' },
        { url: '/chat', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>', label: 'Chat' }
    ]);
}

function setupKaderNav() {
    setupNav([
        { url: '/kader', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>', label: 'Dashboard', active: true },
        { url: '/kader/warga', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>', label: 'Warga Saya' },
        { url: '/kader/kesehatan', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>', label: 'Data Kesehatan' },
        { url: '/kader/rekomendasi', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>', label: 'Rekomendasi' },
        { url: '/chat', icon: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>', label: 'Chat' }
    ]);
}

function setupNav(items) {
    const user = JSON.parse(localStorage.getItem('user'));
    const nav = document.getElementById('sidebarNav');
    const currentPath = window.location.pathname;
    
    if (nav && user) {
        document.getElementById('sidebarAvatar').textContent = user.nama_lengkap.charAt(0);
        document.getElementById('sidebarName').textContent = user.nama_lengkap;
        document.getElementById('sidebarRole').textContent = user.role;
        
        nav.innerHTML = items.map(item => {
            const isActive = currentPath === item.url || (item.url !== '/' && currentPath.startsWith(item.url));
            return `<a href="${item.url}" class="flex items-center gap-3 px-4 py-3 rounded-lg ${isActive ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-600 hover:bg-gray-50 font-medium'} transition">${item.icon} ${item.label}</a>`;
        }).join('');
    }
}
