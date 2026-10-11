<?php 
require_once 'api/config.php'; 
if(!isset($_SESSION['user_id'])) header('Location: login.php'); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Prioritas - SekolahPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #000; color: #fff; }
        .spotify-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: grab; }
        .spotify-card:active { cursor: grabbing; scale: 0.95; }
        .bg-mesh { background: radial-gradient(at 0% 0%, rgb(37, 99, 235) 0, transparent 50%), radial-gradient(at 50% 0%, rgb(220, 38, 38) 0, transparent 50%), radial-gradient(at 100% 0%, rgb(245, 158, 11) 0, transparent 50%); filter: blur(100px); opacity: 0.15; position: fixed; inset: 0; z-index: -1; }
    </style>
</head>
<body class="min-h-screen bg-black flex flex-col items-center py-12 px-6 sm:px-12">

    <div class="bg-mesh"></div>

    <div class="max-w-2xl w-full space-y-12 relative z-10 transition-all duration-1000 animate-in fade-in slide-in-from-bottom-8">
        <div class="space-y-4">
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-center">Apa yang paling <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">penting</span> bagimu?</h1>
            <p class="text-gray-400 text-center text-lg max-w-md mx-auto">Tarik dan susun kriteria di bawah ini mulai dari yang paling utama hingga yang kurang penting.</p>
        </div>

        <div id="sortable-list" class="grid grid-cols-1 gap-4">
            <div data-id="c1" class="spotify-card group bg-zinc-900 border border-zinc-800 hover:border-blue-500 hover:bg-zinc-800 p-6 rounded-3xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-2xl flex items-center justify-center font-bold text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">1</div>
                    <div>
                        <h3 class="text-xl font-bold">Akademik (UTBK)</h3>
                        <p class="text-zinc-500 text-sm">Fokus pada nilai rata-rata UTBK sekolah.</p>
                    </div>
                </div>
                <div class="text-zinc-600 group-hover:text-blue-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
            </div>

            <div data-id="c2" class="spotify-card group bg-zinc-900 border border-zinc-800 hover:border-emerald-500 hover:bg-zinc-800 p-6 rounded-3xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center font-bold text-xl group-hover:bg-emerald-500 group-hover:text-white transition-all">2</div>
                    <div>
                        <h3 class="text-xl font-bold">Akreditasi & Kualitas</h3>
                        <p class="text-zinc-500 text-sm">Fokus pada status akreditasi nasional.</p>
                    </div>
                </div>
                <div class="text-zinc-600 group-hover:text-emerald-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
            </div>

            <div data-id="c3" class="spotify-card group bg-zinc-900 border border-zinc-800 hover:border-red-500 hover:bg-zinc-800 p-6 rounded-3xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 bg-red-500/10 text-red-400 rounded-2xl flex items-center justify-center font-bold text-xl group-hover:bg-red-500 group-hover:text-white transition-all">3</div>
                    <div>
                        <h3 class="text-xl font-bold">Rasio Guru & Murid</h3>
                        <p class="text-zinc-500 text-sm">Kedekatan bimbingan antara guru dan siswa.</p>
                    </div>
                </div>
                <div class="text-zinc-600 group-hover:text-red-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
            </div>

            <div data-id="c4" class="spotify-card group bg-zinc-900 border border-zinc-800 hover:border-amber-500 hover:bg-zinc-800 p-6 rounded-3xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-2xl flex items-center justify-center font-bold text-xl group-hover:bg-amber-500 group-hover:text-white transition-all">4</div>
                    <div>
                        <h3 class="text-xl font-bold">Sarana & Fasilitas</h3>
                        <p class="text-zinc-500 text-sm">Kelengkapan laboratorium dan fasilitas umum.</p>
                    </div>
                </div>
                <div class="text-zinc-600 group-hover:text-amber-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
            </div>
            </div>

        <div class="flex justify-center pt-8">
            <button id="save-btn" class="px-12 py-5 bg-white text-black text-xl font-bold rounded-full hover:scale-105 transition-all shadow-2xl active:scale-95">
                Mulai Lihat Rekomendasi
            </button>
        </div>
    </div>

    <script>
        const el = document.getElementById('sortable-list');
        const sortable = Sortable.create(el, {
            animation: 350,
            ghostClass: 'opacity-20',
            onSort: () => {
                const items = el.querySelectorAll('.spotify-card');
                items.forEach((item, index) => {
                    item.querySelector('.w-12').textContent = index + 1;
                });
            }
        });

        document.getElementById('save-btn').addEventListener('click', async () => {
            const items = Array.from(el.querySelectorAll('.spotify-card'));
            const order = items.map(item => item.getAttribute('data-id'));
            
            // Map 4 criteria
            const priorities = [];
            for(let i=1; i<=4; i++) {
                priorities.push(order.indexOf('c' + i) + 1);
            }

            try {
                const response = await fetch('api/preferences.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ priorities: priorities.join(',') })
                });

                const result = await response.json();
                if (result.success) {
                    window.location.href = 'index.php';
                } else {
                    alert(result.message || 'Gagal menyimpan preferensi.');
                }
            } catch (e) {
                alert('Terjadi kesalahan koneksi ke server.');
            }
        });
    </script>
</body>
</html>
