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
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Pilih Kader</h3>
            </div>
            <div class="flex-1 overflow-y-auto" id="chatList">
                <div class="p-4 text-center text-gray-500">Memuat...</div>
            </div>
        </div>
        
        <!-- Chat Window -->
        <div class="flex-1 flex flex-col hidden" id="chatWindow">
            <div class="p-4 border-b border-gray-100 flex items-center gap-3">
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
            
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messagesContainer">
                <div class="text-center text-gray-500 py-8">Memuat pesan...</div>
            </div>
            
            <div class="p-4 border-t border-gray-100">
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
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <p class="text-gray-500">Pilih kader untuk memulai chat</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentKaderId = null;
let currentConversationId = null;
let chatInterval = null;

async function loadKaders() {
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        let endpoint = '/kaders';
        if (user.role === 'kader') endpoint = '/users/kader/' + user.id + '/warga';
        else if (user.role === 'admin') endpoint = '/users?role=kader'; // Admin can chat with kaders
        
        const res = await apiCall(endpoint);
        const container = document.getElementById('chatList');
        
        const list = res?.data?.data || res?.data || [];
        if (list.length > 0) {
            container.innerHTML = list.map(k => `
                <div onclick="selectChat(${k.id}, '${k.nama_lengkap}')" class="flex items-center gap-3 p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-50 transition">
                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-primary-600 font-semibold">${k.nama_lengkap.charAt(0)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">${k.nama_lengkap}</p>
                        <p class="text-sm text-gray-500 truncate">${k.no_hp || k.nik}</p>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<div class="p-4 text-center text-gray-500">Tidak ada daftar chat</div>';
        }
    } catch (e) { console.error(e); }
}

function selectChat(userId, nama) {
    if (chatInterval) clearInterval(chatInterval);
    currentKaderId = userId;
    document.getElementById('chatListContainer').classList.add('hidden', 'md:block');
    document.getElementById('chatWindow').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    
    document.getElementById('kaderAvatar').textContent = nama.charAt(0);
    document.getElementById('kaderName').textContent = nama;
    
    loadOrCreateConversation();
}

function closeChat() {
    if (chatInterval) clearInterval(chatInterval);
    document.getElementById('chatListContainer').classList.remove('hidden', 'md:block');
    document.getElementById('chatWindow').classList.add('hidden');
    currentKaderId = null;
    currentConversationId = null;
}

async function loadOrCreateConversation() {
    const user = JSON.parse(localStorage.getItem('user'));
    try {
        const res = await apiCall('/messages/start', 'POST', {
            receiver_id: currentKaderId
        });
        
        if (res && res.success) {
            currentConversationId = res.data.id;
            loadMessages();
            chatInterval = setInterval(loadMessages, 3000);
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
        container.innerHTML = '<div class="text-center text-gray-500 py-8">Belum ada pesan. Mulai percakapan!</div>';
        return;
    }
    
    container.innerHTML = messages.map(m => {
        const isMe = m.sender_id === user.id;
        return `
            <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                <div class="max-w-[75%] ${isMe ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800'} rounded-2xl px-4 py-3">
                    <p>${m.message}</p>
                    <p class="text-xs ${isMe ? 'text-primary-200' : 'text-gray-400'} mt-1">${new Date(m.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</p>
                </div>
            </div>
        `;
    }).join('');
    
    container.scrollTop = container.scrollHeight;
}

document.getElementById('messageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('messageInput');
    const pesan = input.value.trim();
    if (!pesan || !currentConversationId) return;
    
    const user = JSON.parse(localStorage.getItem('user'));
    const res = await apiCall(`/messages/${currentConversationId}/send`, 'POST', {
        message: pesan
    });
    
    if (res && res.success) {
        input.value = '';
        loadMessages();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const user = JSON.parse(localStorage.getItem('user'));
    if (!user) return;
    
    document.getElementById('emptyState').classList.remove('hidden', 'md:flex');
    loadKaders();
});
</script>
@endsection