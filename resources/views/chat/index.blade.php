@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Chat Konsultasi</h1>
    <p class="text-gray-500 text-sm">Konsultasi kesehatan dengan kader</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" style="height: calc(100vh - 200px); min-height: 500px;">
    <div class="flex h-full flex-col md:flex-row">
        <!-- Chat List -->
        <div class="w-full md:w-80 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col" id="chatListContainer">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-semibold text-gray-800">Percakapan</h3>
                <button onclick="openNewChatModal()" class="p-2 bg-primary-600 text-white rounded-full hover:bg-primary-700 transition shadow-sm" title="Tambah Chat Baru">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto" id="chatList">
                <div class="p-4 text-center text-gray-500">Memuat...</div>
            </div>
        </div>
        
        <!-- Chat Window -->
        <div class="flex-1 flex flex-col hidden" id="chatWindow">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <button onclick="closeChat()" class="md:hidden p-2 hover:bg-gray-100 rounded-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <span id="kaderAvatar" class="text-primary-600 font-semibold">-</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800" id="kaderName">-</p>
                        <p class="text-xs text-gray-500" id="kaderStatus">Online</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="deleteConversation()" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Percakapan">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="messagesContainer">
                <div class="text-center text-gray-500 py-8">Memuat pesan...</div>
            </div>
            
            <div class="p-4 border-t border-gray-100 bg-white">
                <form id="messageForm" class="flex gap-3">
                    <input type="text" id="messageInput" placeholder="Ketik pesan..." class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500" autocomplete="off">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl transition shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <!-- Empty State -->
        <div class="flex-1 flex items-center justify-center hidden md:flex" id="emptyState">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <p class="text-gray-500">Pilih percakapan untuk memulai chat</p>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800">Pilih Kontak</h3>
            <button onclick="closeNewChatModal()" class="text-gray-400 hover:text-gray-600">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-2 max-h-[60vh] overflow-y-auto" id="contactList">
            <div class="p-4 text-center text-gray-500">Memuat kontak...</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentKaderId = null;
let currentConversationId = null;
let chatInterval = null;

// Mengambil daftar percakapan aktif
async function loadConversations() {
    try {
        const res = await apiCall('/messages');
        const container = document.getElementById('chatList');
        
        const list = res?.data || [];
        if (list.length > 0) {
            container.innerHTML = list.map(c => `
                <div onclick="selectConversation(${c.id}, '${c.partner?.nama_lengkap || 'Unknown'}')" 
                     class="flex items-center gap-3 p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-50 transition ${currentConversationId === c.id ? 'bg-primary-50 border-primary-100' : ''}">
                    <div class="w-12 h-12 rounded-full ${c.admin_id ? 'bg-secondary-100' : 'bg-primary-100'} flex items-center justify-center flex-shrink-0">
                        <span class="${c.admin_id ? 'text-secondary-600' : 'text-primary-600'} font-semibold">${(c.partner?.nama_lengkap || '?').charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="font-semibold text-gray-800 truncate">${c.partner?.nama_lengkap || 'User'}</p>
                            <span class="text-[10px] text-gray-400">${new Date(c.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500 truncate">${c.latest_detail?.message || 'Klik untuk memulai chat'}</p>
                            ${c.unread_count > 0 ? `<span class="bg-primary-600 text-white text-[10px] px-1.5 py-0.5 rounded-full">${c.unread_count}</span>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div class="p-8 text-center text-gray-400"><p class="text-sm">Belum ada percakapan aktif.</p><p class="text-xs mt-1">Klik tombol + untuk memulai.</p></div>';
        }
    } catch (e) { console.error(e); }
}

async function openNewChatModal() {
    document.getElementById('newChatModal').classList.remove('hidden');
    const container = document.getElementById('contactList');
    container.innerHTML = '<div class="p-4 text-center text-gray-500">Memuat kontak...</div>';
    
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        // Fetch contacts based on role
        const endpoints = [];
        if (user.role === 'admin') {
            endpoints.push('/users?role=kader');
            endpoints.push('/users?role=warga');
        } else if (user.role === 'kader') {
            endpoints.push('/users/kader/' + user.id + '/warga');
            endpoints.push('/admins');
        } else if (user.role === 'warga') {
            endpoints.push('/kaders');
            endpoints.push('/admins');
        }
        
        const responses = await Promise.all(endpoints.map(e => apiCall(e)));
        let contacts = [];
        responses.forEach(res => {
            const data = res?.data?.data || res?.data || [];
            contacts = [...contacts, ...data];
        });
        
        // Remove self
        contacts = contacts.filter(c => c.id !== user.id);
        
        if (contacts.length > 0) {
            container.innerHTML = contacts.map(k => `
                <div onclick="startChatWith(${k.id}, '${k.nama_lengkap}')" class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer rounded-xl transition">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-600 font-semibold text-sm">${k.nama_lengkap.charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 truncate">${k.nama_lengkap}</p>
                        <p class="text-xs text-gray-500 capitalize">${k.role || 'User'}</p>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div class="p-4 text-center text-gray-500">Tidak ada kontak tersedia.</div>';
        }
    } catch (e) { 
        console.error(e);
        container.innerHTML = '<div class="p-4 text-center text-red-500">Gagal memuat kontak.</div>';
    }
}

function closeNewChatModal() {
    document.getElementById('newChatModal').classList.add('hidden');
}

async function startChatWith(userId, name) {
    closeNewChatModal();
    try {
        const res = await apiCall('/messages/start', 'POST', { receiver_id: userId });
        if (res && res.success) {
            currentConversationId = res.data.id;
            selectConversation(res.data.id, name);
            loadConversations();
        } else {
            showAlert(res.message || 'Gagal memulai chat');
        }
    } catch (e) { console.error(e); }
}

function selectConversation(id, name) {
    if (chatInterval) clearInterval(chatInterval);
    currentConversationId = id;
    
    document.getElementById('chatListContainer').classList.add('hidden', 'md:block');
    document.getElementById('chatWindow').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    
    document.getElementById('kaderAvatar').textContent = name.charAt(0);
    document.getElementById('kaderName').textContent = name;
    
    loadMessages();
    chatInterval = setInterval(loadMessages, 3000);
    
    // Update active state in sidebar
    loadConversations();
}

function closeChat() {
    if (chatInterval) clearInterval(chatInterval);
    currentConversationId = null;
    document.getElementById('chatWindow').classList.add('hidden');
    document.getElementById('chatListContainer').classList.remove('hidden');
    document.getElementById('emptyState').classList.remove('hidden', 'md:flex');
    document.getElementById('emptyState').classList.add('md:flex');
}

async function deleteConversation() {
    if (!currentConversationId) return;
    if (!confirm('Hapus seluruh percakapan ini?')) return;
    
    try {
        const res = await apiCall(`/messages/${currentConversationId}`, 'DELETE');
        if (res && res.success) {
            showAlert('Percakapan dihapus', 'success');
            closeChat();
            loadConversations();
        }
    } catch (e) { console.error(e); }
}

async function deleteMessage(id) {
    if (!confirm('Hapus pesan ini?')) return;
    try {
        const res = await apiCall(`/messages/detail/${id}`, 'DELETE');
        if (res && res.success) {
            loadMessages();
        }
    } catch (e) { console.error(e); }
}

async function loadMessages() {
    if (!currentConversationId) return;
    try {
        const res = await apiCall(`/messages/${currentConversationId}`);
        if (res && res.success) {
            renderMessages(res.data.details || []);
        }
    } catch (e) { console.error(e); }
}

function renderMessages(messages) {
    const container = document.getElementById('messagesContainer');
    const user = JSON.parse(localStorage.getItem('user'));
    
    if (messages.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-400 py-8 text-sm italic">Belum ada pesan. Ketik sesuatu untuk memulai!</div>';
        return;
    }
    
    const html = messages.map(m => {
        const isMe = m.sender_id === user.id;
        return `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'} group">
                <div class="max-w-[75%] relative">
                    <div class="${isMe ? 'bg-primary-600 text-white' : 'bg-white text-gray-800 border border-gray-200'} rounded-2xl px-4 py-2.5 shadow-sm">
                        <p class="text-sm md:text-base">${m.message}</p>
                        <div class="flex items-center justify-end gap-1 mt-1">
                            <span class="text-[10px] ${isMe ? 'text-primary-100' : 'text-gray-400'}">${new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                            ${isMe && m.is_read ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-primary-200"><path d="M20 6L9 17l-5-5"/></svg>' : ''}
                        </div>
                    </div>
                    <button onclick="deleteMessage(${m.id})" 
                            class="absolute top-0 ${isMe ? '-left-8' : '-right-8'} p-1 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    // Only scroll if content changed
    const isAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;
    container.innerHTML = html;
    if (isAtBottom) container.scrollTop = container.scrollHeight;
}

document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('messageInput');
    const pesan = input.value.trim();
    if (!pesan || !currentConversationId) return;
    
    try {
        const res = await apiCall(`/messages/${currentConversationId}/send`, 'POST', {
            message: pesan
        });
        
        if (res && res.success) {
            input.value = '';
            loadMessages();
            loadConversations();
        }
    } catch (e) { console.error(e); }
});

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;
    
    document.getElementById('emptyState').classList.remove('hidden', 'md:flex');
    loadConversations();
});
</script>
@endsection