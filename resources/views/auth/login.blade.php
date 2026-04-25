@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center -mt-6">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-700 z-[-1]"></div>
    
    <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl p-10 w-full max-w-md border border-white/20">
        <div class="text-center mb-8">
            <div class="inline-flex justify-center items-center p-3 bg-primary-50 rounded-full mb-4">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="text-primary-600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Tani Sehat</h1>
            <p class="text-gray-500 text-sm">Sistem Pemantauan Kesehatan Warga</p>
        </div>

        <form id="loginForm" class="space-y-5">
            <div>
                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                <input type="text" id="nik" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required placeholder="Masukkan NIK">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" required placeholder="Masukkan Password">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all flex justify-center items-center">
                <span id="btnText">Login ke Sistem</span>
                <div class="loader" id="loader"></div>
            </button>
        </form>
        
        <div class="mt-8 text-center text-sm text-gray-500 bg-gray-50 p-4 rounded-lg border border-gray-100">
            <p class="font-medium text-gray-700 mb-1">Demo Credentials:</p>
            <p>Admin NIK: <span class="font-mono text-primary-600">1111111111111111</span></p>
            <p>Password: <span class="font-mono text-primary-600">password</span></p>
            <p class="mt-2 text-xs">Memanggil <code class="bg-gray-200 px-1 rounded text-gray-700">POST /api/login</code></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    if (localStorage.getItem('token')) {
        window.location.href = '/';
    }

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const nik = document.getElementById('nik').value;
        const password = document.getElementById('password').value;
        const btnText = document.getElementById('btnText');
        const loader = document.getElementById('loader');
        
        btnText.style.display = 'none';
        loader.style.display = 'block';
        
        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ nik, password })
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                localStorage.setItem('token', result.data.token);
                localStorage.setItem('user', JSON.stringify(result.data.user));
                window.location.href = '/';
            } else {
                showAlert(result.message || 'Login gagal. Periksa NIK dan password.', 'error');
            }
        } catch (err) {
            showAlert('Terjadi kesalahan jaringan.', 'error');
        } finally {
            btnText.style.display = 'block';
            loader.style.display = 'none';
        }
    });

    // Override the generic showAlert for Tailwind styles in login page
    window.showAlert = function(message, type = 'error') {
        const alertEl = document.getElementById('alertMessage');
        if (!alertEl) return;
        
        alertEl.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-500', 'bg-green-50', 'text-green-800', 'border-green-500');
        
        if (type === 'error') {
            alertEl.classList.add('bg-red-50', 'text-red-800', 'border-red-500', 'block');
        } else {
            alertEl.classList.add('bg-green-50', 'text-green-800', 'border-green-500', 'block');
        }
        
        alertEl.innerHTML = message;
        
        setTimeout(() => {
            alertEl.classList.add('hidden');
            alertEl.classList.remove('block');
        }, 5000);
    }
</script>
@endsection
