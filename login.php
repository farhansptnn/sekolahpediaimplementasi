<?php require_once 'api/config.php'; if(isset($_SESSION['user_id'])) header('Location: index.php'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SekolahPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-image: linear-gradient(135deg, rgba(239, 246, 255, 0.45), rgba(254, 242, 242, 0.45)), url('assets/images/login_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .glass { background: rgba(255, 255, 255, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.3); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="flex flex-col items-center gap-4 max-w-md w-full animate-in fade-in zoom-in duration-500">
        <div class="w-full glass rounded-3xl shadow-2xl overflow-hidden p-8 space-y-8">
            <div class="text-center space-y-2">
                <div class="bg-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.14c1.89-1.282 3.094-3.412 3.094-5.703 0-1.92-.158-3.778-.464-5.586m-6.73 3.374a12.039 12.039 0 01.659-4.341m5.101 1.491c.149-.517.228-1.066.228-1.631 0-2.772-2.246-5.018-5.018-5.018s-5.018 2.246-5.018 5.018c0 .565.079 1.114.228 1.631m5.101 1.491a12.11 12.11 0 011.83 5.404M8.118 4.419a12.094 12.094 0 013.111-2.909m0 0a12.09 12.09 0 013.111 2.909m-3.111-2.909V11m0 0c3.517 0 6.799-1.009 9.571-2.753m-2.14-3.44c-1.282 1.89-3.412 3.094-5.703 3.094-1.92 0-3.778-.158-5.586-.464m3.374 6.73a12.039 12.039 0 00-4.341.659m1.491-5.101c-.517.149-1.066.228-1.631.228-2.772 0-5.018-2.246-5.018-5.018s2.246-5.018 5.018-5.018c.565 0 1.114.079 1.631.228m1.491 5.101a12.11 12.11 0 005.404 1.83m-5.404-1.83a12.094 12.094 0 00-2.909 3.111m0 0a12.09 12.09 0 002.909 3.111" />
                    </svg>
                </div>
                <h1 id="login-title" class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang</h1>
                <p id="subtitle" class="text-gray-500 text-sm leading-relaxed">Sistem Penentuan Sekolah Terbaik Negeri Jakarta Selatan menggunakan Metode TOPSIS</p>
            </div>

            <form id="login-form" class="space-y-6">
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Username</label>
                        <input type="text" id="username" required class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none bg-white/50">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" required class="w-full px-5 py-3 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none bg-white/50 pr-12">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path id="eye-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path id="eye-path-outer" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="error-msg" class="hidden text-red-500 text-sm bg-red-50 p-3 rounded-xl border border-red-100"></div>

                <button type="submit" id="submit-btn" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold shadow-xl shadow-gray-200 hover:bg-black transition-all transform hover:scale-[1.02] active:scale-95">
                    Masuk Sekarang
                </button>
            </form>

            <div class="text-center">
                <p id="toggle-mode-text" class="text-sm text-gray-500">Belum punya akun? <a href="#" onclick="toggleMode()" class="text-blue-600 font-bold hover:underline">Daftar</a></p>
            </div>
        </div>
        <p class="text-xs font-semibold text-slate-500/80 tracking-wider text-center mt-2">&copy; Copyright by Farhan Septian</p>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const errorDiv = document.getElementById('error-msg');
        let mode = 'login';

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        function toggleMode() {
            mode = mode === 'login' ? 'register' : 'login';
            document.getElementById('login-title').textContent = mode === 'login' ? 'Selamat Datang' : 'Buat Akun';
            document.getElementById('subtitle').textContent = mode === 'login' 
                ? 'Sistem Penentuan Sekolah Terbaik Negeri Jakarta Selatan menggunakan Metode TOPSIS' 
                : 'Daftar untuk mengakses Sistem Penentuan Sekolah Terbaik Negeri Jakarta Selatan menggunakan Metode TOPSIS';
            document.getElementById('submit-btn').textContent = mode === 'login' ? 'Masuk Sekarang' : 'Daftar Sekarang';
            document.getElementById('toggle-mode-text').innerHTML = mode === 'login' 
                ? 'Belum punya akun? <a href="#" onclick="toggleMode()" class="text-blue-600 font-bold hover:underline">Daftar</a>' 
                : 'Sudah punya akun? <a href="#" onclick="toggleMode()" class="text-blue-600 font-bold hover:underline">Masuk</a>';
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorDiv.classList.add('hidden');
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            const response = await fetch(`api/auth.php?action=${mode}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });

            const result = await response.json();

            if (result.success) {
                if (mode === 'login') {
                    if (result.role === 'admin') {
                        window.location.href = 'admin.php';
                    } else {
                        window.location.href = 'preferences.php';
                    }
                } else {
                    alert('Registrasi berhasil! Silakan login.');
                    toggleMode();
                }
            } else {
                errorDiv.textContent = result.message;
                errorDiv.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
