<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tani Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50: '#ecfdf5', 100: '#d1fae5', 500: '#10b981', 600: '#059669', 700: '#047857' },
                        secondary: { 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .loader { display: none; width: 24px; height: 24px; border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: spin 1s ease-in-out infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .sidebar-transition { transition: transform 0.3s ease-in-out; }
        @media (max-width: 768px) {
            .sidebar-open { transform: translateX(0); }
            .sidebar-closed { transform: translateX(-100%); }
            .content-with-bottom-nav { padding-bottom: 5rem; }
            .overflow-x-auto { 
                margin-left: -1rem; 
                margin-right: -1rem; 
                padding-left: 1rem; 
                padding-right: 1rem; 
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col font-sans">
    
    <?php $isLoginPage = Request::is('login'); ?>
    
    @if(!$isLoginPage)
    <header class="md:hidden sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 px-4 py-3 flex justify-between items-center shadow-sm">
        <button id="mobileMenuBtn" class="p-2 rounded-lg hover:bg-gray-100 transition">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <a href="/" class="flex items-center gap-2 text-lg font-bold text-primary-600">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
            Tani Sehat
        </a>
        <div class="w-10"></div>
    </header>

    <div class="flex flex-1">
        <aside id="sidebar" class="sidebar-closed md:sidebar-open fixed md:static inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 shadow-lg md:shadow-none flex flex-col sidebar-transition pt-16 md:pt-0">
            <div class="p-6 border-b border-gray-100 hidden md:block">
                <a href="/" class="flex items-center gap-2 text-xl font-bold text-primary-600">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    Tani Sehat
                </a>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto" id="sidebarNav"></nav>
            
            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                        <span id="sidebarAvatar" class="text-primary-600 font-semibold text-sm">-</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="sidebarName" class="font-semibold text-sm text-gray-800 truncate">-</p>
                        <p id="sidebarRole" class="text-xs text-gray-500 capitalize">-</p>
                    </div>
                </div>
                <button id="logoutBtn" class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </div>
        </aside>
        
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/50 z-30 md:hidden"></div>
        
        <main class="flex-1 min-h-screen md:p-6 p-4 pt-20 md:pt-6">
            <div id="alertMessage" class="hidden mb-6 p-4 rounded-lg shadow-sm border-l-4"></div>
            @yield('content')
        </main>
    </div>
    @else
    <main class="flex-1">
        <div id="alertMessage" class="hidden mb-6 p-4 rounded-lg shadow-sm border-l-4"></div>
        @yield('content')
    </main>
    @endif
    
    <script src="{{ asset('public/js/app.js') }}"></script>
    @if(!$isLoginPage)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');
            var mobileMenuBtn = document.getElementById('mobileMenuBtn');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-open');
                    sidebar.classList.toggle('sidebar-closed');
                    overlay.classList.toggle('hidden');
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('sidebar-open');
                    sidebar.classList.add('sidebar-closed');
                    overlay.classList.add('hidden');
                });
            }
        });
    </script>
    @endif
    @yield('scripts')
</body>
</html>