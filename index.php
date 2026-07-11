<?php
require_once 'api/config.php';
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SekolahPedia - Peringkat Sekolah Terbaik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0, 0, 0, 0.05); }
        .card-horizontal { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .card-horizontal:hover { transform: scale(1.01); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); }
        .main-container { max-width: 1000px; margin: 0 auto; background: white; border: 1px solid #e2e8f0; border-radius: 3rem; }
        
        /* Sidebar styles for admin */
        .sidebar { background: #1e293b; color: white; }
        .nav-link { transition: all 0.2s; border-radius: 12px; cursor: pointer; color: #94a3b8; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.1); color: white; }
        .nav-link.active { background: #3b82f6; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); color: white !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased <?= $is_admin ? 'flex min-h-screen overflow-hidden' : '' ?>">

    <?php if ($is_admin): ?>
    <!-- Sidebar -->
    <aside class="w-64 sidebar hidden lg:flex flex-col p-6 space-y-8 sticky top-0 h-screen flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="bg-blue-500 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="font-bold text-xl tracking-tight">Admin SPK</span>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="index.php" class="nav-link active flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Lihat Website
            </a>
            <a href="admin.php?section=dashboard" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Dashboard Admin
            </a>
            <a href="admin.php?section=schools" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Kelola Sekolah
            </a>
            <a href="admin.php?section=users" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Kelola User
            </a>
            <a href="admin.php?section=calculation" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Langkah Perhitungan
            </a>
        </nav>

        <div class="pt-6 border-t border-slate-700">
            <button id="logout-btn-sidebar" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-400 hover:bg-red-500/10 rounded-xl text-left transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </div>
    </aside>
    <?php endif; ?>

    <!-- Main Content wrapper -->
    <div class="<?= $is_admin ? 'flex-1 overflow-y-auto h-screen' : 'w-full' ?>">

    <!-- Navigation -->
    <nav class="glass-nav sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 text-white p-2.5 rounded-2xl shadow-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight">SekolahPedia</h1>
                    <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Decision Support System</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <?php if ($is_logged_in): ?>
                    <div class="hidden md:flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-100 shadow-sm">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-semibold text-gray-600">Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    </div>
                    <a href="preferences.php" class="text-sm font-bold text-blue-600 hover:text-blue-700">Ubah Prioritas</a>
                    <button id="logout-btn" class="bg-gray-900 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-xl shadow-gray-200 hover:bg-black transition-all">Logout</button>
                <?php else: ?>
                    <a href="login.php" class="bg-blue-600 text-white px-8 py-3 rounded-full text-sm font-bold shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="main-container shadow-2xl overflow-hidden border-4 border-white">
            <div class="p-8 sm:p-16 space-y-16">
                <!-- Hero Section -->
                <div class="text-center space-y-6 max-w-2xl mx-auto">
                    <h2 class="text-4xl sm:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                        Temukan Sekolah <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Terbaik</span>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed font-medium">
                        <?php if ($is_logged_in): ?>
                            Menampilkan hasil rekomendasi yang telah dipersonalisasi berdasarkan prioritas utama Anda.
                        <?php else: ?>
                            Gunakan metode cerdas TOPSIS untuk mendapatkan rekomendasi sekolah terbaik berdasarkan data objektif terbaru.
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Rankings Section -->
                <div class="space-y-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between border-b border-gray-100 pb-8 gap-4">
                        <div>
                            <h3 class="text-2xl font-black text-gray-800">Peringkat Sekolah</h3>
                            <p class="text-sm text-gray-400 mt-1">Updated berdasarkan algoritma SPK TOPSIS.</p>
                        </div>
                        <div id="status-badge" class="px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100 shadow-sm">
                            Loading...
                        </div>
                    </div>

                    <div id="results-container" class="space-y-6">
                        <!-- Horizontal Cards will be injected by JS -->
                    </div>

                    <!-- Pagination Controls -->
                    <div id="pagination-controls" class="flex items-center justify-center gap-4 pt-12 border-t border-gray-100 pb-8">
                        <button id="page-1-btn" class="px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all bg-blue-600 text-white shadow-xl shadow-blue-200">
                            1. Rekomendasi Utama
                        </button>
                        <button id="page-2-btn" class="px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all bg-gray-100 text-gray-400 hover:bg-gray-200">
                            2. Peringkat Lengkap
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>    <!-- Detail Modal -->
        <div id="detail-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-6">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm shadow-2xl" id="modal-overlay"></div>
            <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-2xl animate-in zoom-in fade-in duration-300">
                <div id="modal-content">
                    <!-- Injected by JS -->
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-100 mt-20 py-12">
        <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
            <p class="text-gray-400 text-sm italic">"Memilih sekolah bukan hanya soal nama, tapi soal masa depan."</p>
            <p class="text-gray-500 font-bold">&copy; 2025 SekolahPedia. SPK Metode TOPSIS. Copyright by Farhan Septian.</p>
        </div>
    </footer>
    </div>

    <script src="js/app.js?v=<?= time() ?>"></script>
    <script>
        document.getElementById('logout-btn')?.addEventListener('click', async () => {
            const res = await fetch('api/auth.php?action=logout');
            if (res.ok) window.location.reload();
        });
        document.getElementById('logout-btn-sidebar')?.addEventListener('click', async () => {
            const res = await fetch('api/auth.php?action=logout');
            if (res.ok) window.location.href = 'login.php';
        });
    </script>
</body>
</html>
