<?php
require_once 'api/config.php';

// Auth check
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch stats
$stmt = $pdo->query("SELECT COUNT(*) FROM schools");
$total_schools = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM schools ORDER BY name ASC");
$schools = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SekolahPedia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f0f2f5; }
        .sidebar { background: #1e293b; color: white; }
        .nav-link { transition: all 0.2s; border-radius: 12px; cursor: pointer; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.1); }
        .nav-link.active { background: #3b82f6; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); color: white !important; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); }
        
        .section-content { display: none; }
        .section-content.active { display: block; animation: fadeIn 0.3s ease-out; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .matrix-table th {
            position: sticky;
            top: 0;
            background-color: #f3f4f6; /* bg-gray-100 equivalent */
            z-index: 10;
            padding: 10px 12px;
            font-size: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            text-align: center;
        }
        .matrix-table th:first-child {
            text-align: left;
        }
        .matrix-table td {
            padding: 8px 12px;
            font-size: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
        }
        .matrix-table td:first-child {
            text-align: left;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 sidebar hidden lg:flex flex-col p-6 space-y-8 sticky top-0 h-screen">
        <div class="flex items-center gap-3">
            <div class="bg-blue-500 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="font-bold text-xl tracking-tight">Admin SPK</span>
        </div>

        <nav class="flex-1 space-y-2">
            <a href="index.php" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Website
            </a>
            <div onclick="showSection('dashboard')" id="nav-dashboard" class="nav-link active flex items-center gap-3 px-4 py-3 text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </div>
            <div onclick="showSection('schools')" id="nav-schools" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Kelola Sekolah
            </div>
            <div onclick="showSection('users')" id="nav-users" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Kelola User
            </div>
            <div onclick="showSection('calculation')" id="nav-calculation" class="nav-link flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Langkah Perhitungan
            </div>
        </nav>

        <div class="pt-6 border-t border-slate-700">
            <button id="logout-btn" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-400 hover:bg-red-500/10 rounded-xl text-left transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto h-screen">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 id="section-title" class="text-2xl font-bold text-gray-800">Dashboard</h2>
                <p class="text-gray-500">Selamat datang kembali, Admin!</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-800"><?= htmlspecialchars($_SESSION['username']) ?></p>
                    <p class="text-xs text-blue-500 font-bold uppercase">Administrator</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </header>

        <!-- DASHBOARD SECTION -->
        <div id="section-dashboard" class="section-content active">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-blue-600 p-6 rounded-3xl shadow-xl shadow-blue-200 text-white relative overflow-hidden cursor-pointer" onclick="showSection('schools')">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-sm font-bold uppercase tracking-wider mb-1">Total Sekolah</p>
                        <h3 class="text-4xl font-black"><?= $total_schools ?></h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 absolute -right-8 -bottom-8 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5 cursor-pointer" onclick="showSection('users')">
                    <div class="bg-orange-100 p-4 rounded-2xl text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Total User</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?= $total_users ?> User</h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                    <div class="bg-green-100 p-4 rounded-2xl text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Metode Digunakan</p>
                        <h3 class="text-2xl font-bold text-gray-800">TOPSIS</h3>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Selamat Datang di Panel Admin</h3>
                <p class="text-gray-500 leading-relaxed mb-6">Gunakan menu di samping untuk mengelola data sekolah, mengelola pengguna, atau melihat detail langkah perhitungan Sistem Pendukung Keputusan (SPK) dengan metode TOPSIS.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button onclick="showSection('schools')" class="p-4 border-2 border-dashed border-gray-200 rounded-2xl hover:border-blue-400 hover:bg-blue-50 transition-all text-left group">
                        <p class="font-bold text-gray-700 group-hover:text-blue-600">Kelola Sekolah &rarr;</p>
                        <p class="text-xs text-gray-400">Update data kriteria sekolah</p>
                    </button>
                    <button onclick="showSection('calculation')" class="p-4 border-2 border-dashed border-gray-200 rounded-2xl hover:border-blue-400 hover:bg-blue-50 transition-all text-left group">
                        <p class="font-bold text-gray-700 group-hover:text-blue-600">Lihat Matriks &rarr;</p>
                        <p class="text-xs text-gray-400">Transparansi perhitungan TOPSIS</p>
                    </button>
                </div>
            </div>
        </div>

        <!-- SCHOOLS SECTION -->
        <div id="section-schools" class="section-content">
            <section class="bg-white rounded-3xl shadow-sm border border-gray-100 mb-10">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Sekolah</h3>
                    <button onclick="openSchoolModal()" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Sekolah
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">Nama Sekolah</th>
                                <th class="px-6 py-4">C1 - Nilai UTBK</th>
                                <th class="px-6 py-4">C2 - Akreditasi</th>
                                <th class="px-6 py-4">C3 - Rasio Guru/Siswa</th>
                                <th class="px-6 py-4">C4 - Akses Transport</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($schools as $school): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-700"><?= htmlspecialchars($school['name']) ?></td>
                                <td class="px-6 py-4 text-gray-500"><?= $school['c1_utbk'] ?? '<span class="text-red-400 italic">Tidak terdaftar</span>' ?></td>
                                <td class="px-6 py-4 text-gray-500"><?= $school['c2_akreditasi'] == 4 ? 'A' : ($school['c2_akreditasi'] == 3 ? 'B' : ($school['c2_akreditasi'] == 2 ? 'C' : $school['c2_akreditasi'])) ?></td>
                                <td class="px-6 py-4 text-gray-500"><?= $school['c3_rasio_siswa_guru'] ?></td>
                                <?php $akses_labels = [4=>'Sangat Mudah',3=>'Mudah',2=>'Sedang',1=>'Sulit']; ?>
                                <td class="px-6 py-4 text-gray-500"><?= $akses_labels[$school['c4_akses_transportasi']] ?? $school['c4_akses_transportasi'] ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="editSchool(<?= $school['id'] ?>)" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button onclick="deleteSchool(<?= $school['id'] ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- USERS SECTION -->
        <div id="section-users" class="section-content">
            <section class="bg-white rounded-3xl shadow-sm border border-gray-100 mb-10">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Daftar User</h3>
                    <button onclick="openUserModal()" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah User
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">Username</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Dibuat Pada</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="user-table-body" class="divide-y divide-gray-100">
                            <!-- Users will be loaded here via JS -->
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Loading users...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- CALCULATION SECTION -->
        <div id="section-calculation" class="section-content">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Transparansi Logika TOPSIS</h3>
                <button onclick="loadCalculationSteps()" class="text-blue-600 text-sm font-bold flex items-center gap-1 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Data
                </button>
            </div>
            
            <div class="grid grid-cols-1 gap-8">
                <!-- Step 1: Normalized Matrix -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-blue-600 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-xs">1</span>
                        Matriks Ternormalisasi (R)
                    </h4>
                    <p class="text-sm text-gray-500 mb-4">Mengonversi data real sekolah menjadi skala perbandingan (0-1) yang seragam.</p>
                    <div id="matrix-r-container" class="overflow-auto max-h-[450px] bg-gray-50 rounded-2xl">
                        <div class="flex items-center justify-center min-h-[168px]">
                            <p class="text-gray-400 italic">Memuat data...</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Weighted Normalized Matrix -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-indigo-600 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-xs">2</span>
                        Matriks Ternormalisasi Terbobot (Y)
                    </h4>
                    <p class="text-sm text-gray-500 mb-4">Hasil perkalian matriks ternormalisasi dengan bobot kriteria yang telah ditentukan.</p>
                    <div id="matrix-y-container" class="overflow-auto max-h-[450px] bg-gray-50 rounded-2xl">
                        <div class="flex items-center justify-center min-h-[168px]">
                            <p class="text-gray-400 italic">Memuat data...</p>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Ideal Solutions -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-purple-600 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-xs">3</span>
                        Solusi Ideal Positif & Negatif
                    </h4>
                    <p class="text-sm text-gray-500 mb-4">Menentukan nilai terbaik (A+) dan terburuk (A-) untuk setiap kriteria.</p>
                    <div id="ideal-solutions-container" class="overflow-auto bg-gray-50 rounded-2xl">
                        <div class="flex items-center justify-center min-h-[68px]">
                            <p class="text-gray-400 italic">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- USER MODAL -->
    <div id="user-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 id="user-modal-title" class="text-xl font-bold text-gray-800">Tambah User</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="user-form" onsubmit="saveUser(event)" class="p-6 space-y-4">
                <input type="hidden" id="user-id">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Username</label>
                    <input type="text" id="user-username" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                    <input type="password" id="user-password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                    <select id="user-role" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                        <option value="user">User (Pencari Sekolah)</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCHOOL MODAL -->
    <div id="school-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center flex-shrink-0">
                <h3 id="school-modal-title" class="text-xl font-bold text-gray-800">Tambah Sekolah</h3>
                <button onclick="closeSchoolModal()" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="school-form" onsubmit="saveSchool(event)" class="p-6 space-y-4 overflow-y-auto flex-1">
                <input type="hidden" id="school-id">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Sekolah</label>
                    <input type="text" id="school-name" required placeholder="Contoh: SMAN 1 Jakarta" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">C1 - Nilai UTBK (Benefit)</label>
                    <input type="number" step="0.001" id="school-utbk" placeholder="Biarkan kosong jika tidak terdaftar" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">C2 - Akreditasi (Benefit)</label>
                    <select id="school-akreditasi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                        <option value="4">A</option>
                        <option value="3">B</option>
                        <option value="2">C</option>
                        <option value="1">Tidak Terakreditasi / Lainnya</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">C3 - Rasio Siswa/Guru (Cost - Makin Kecil Makin Bagus)</label>
                    <input type="number" step="0.01" id="school-rasio" required placeholder="Contoh: 15.5" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">C4 - Akses Transportasi (Benefit)</label>
                    <select id="school-akses" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                        <option value="4">Sangat Mudah</option>
                        <option value="3">Mudah</option>
                        <option value="2">Sedang</option>
                        <option value="1">Sulit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Gambar Sekolah</label>
                    <!-- Image preview container -->
                    <div id="school-image-preview-container" class="mb-3 hidden">
                        <p class="text-xs text-gray-400 mb-1 font-semibold">Preview Gambar:</p>
                        <img id="school-image-preview" src="" alt="Preview Gambar" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800';" class="w-full h-40 object-cover rounded-xl border border-gray-200 shadow-sm">
                    </div>
                    <input type="file" id="school-image-file" accept="image/*, .jpg, .jpeg, .png, .gif, .webp" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="hidden" id="school-image" value="">
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // NAVIGATION
        function showSection(sectionId) {
            // Update UI
            document.querySelectorAll('.section-content').forEach(s => s.classList.remove('active'));
            document.getElementById('section-' + sectionId).classList.add('active');
            
            document.querySelectorAll('.nav-link').forEach(l => {
                l.classList.remove('active');
                l.classList.add('text-gray-400');
            });
            const activeNav = document.getElementById('nav-' + sectionId);
            activeNav.classList.add('active');
            activeNav.classList.remove('text-gray-400');

            // Update Title
            const titles = {
                'dashboard': 'Dashboard',
                'schools': 'Kelola Sekolah',
                'users': 'Kelola User',
                'calculation': 'Langkah Perhitungan'
            };
            document.getElementById('section-title').innerText = titles[sectionId];

            // Trigger data load if needed
            if (sectionId === 'users') fetchUsers();
            if (sectionId === 'calculation') loadCalculationSteps();
        }

        // USERS MANAGEMENT
        async function fetchUsers() {
            const container = document.getElementById('user-table-body');
            try {
                const res = await fetch('api/users.php');
                const result = await res.json();
                
                if (result.success) {
                    container.innerHTML = result.data.map(user => `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-700">${user.username}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold ${user.role === 'admin' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600'}">
                                    ${user.role.toUpperCase()}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">${new Date(user.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button onclick='editUser(${JSON.stringify(user)})' class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteUser(${user.id})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (e) {
                container.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-red-400">Gagal memuat data.</td></tr>';
            }
        }

        function openUserModal() {
            document.getElementById('user-id').value = '';
            document.getElementById('user-username').value = '';
            document.getElementById('user-password').value = '';
            document.getElementById('user-password').required = true;
            document.getElementById('user-role').value = 'user';
            document.getElementById('user-modal-title').innerText = 'Tambah User';
            document.getElementById('user-modal').classList.remove('hidden');
        }

        function editUser(user) {
            document.getElementById('user-id').value = user.id;
            document.getElementById('user-username').value = user.username;
            document.getElementById('user-password').value = '';
            document.getElementById('user-password').required = false;
            document.getElementById('user-role').value = user.role;
            document.getElementById('user-modal-title').innerText = 'Edit User';
            document.getElementById('user-modal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('user-modal').classList.add('hidden');
        }

        async function saveUser(e) {
            e.preventDefault();
            const data = {
                id: document.getElementById('user-id').value,
                username: document.getElementById('user-username').value,
                password: document.getElementById('user-password').value,
                role: document.getElementById('user-role').value
            };

            try {
                const res = await fetch('api/users.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.success) {
                    alert(result.message);
                    closeUserModal();
                    fetchUsers();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('Terjadi kesalahan sistem.');
            }
        }

        async function deleteUser(id) {
            if (!confirm('Hapus user ini?')) return;
            try {
                const res = await fetch(`api/users.php?id=${id}`, { method: 'DELETE' });
                const result = await res.json();
                if (result.success) {
                    fetchUsers();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('Gagal menghapus user.');
            }
        }

        // CALCULATION STEPS
        async function loadCalculationSteps() {
            const rContainer = document.getElementById('matrix-r-container');
            const yContainer = document.getElementById('matrix-y-container');
            const idealContainer = document.getElementById('ideal-solutions-container');

            try {
                const res = await fetch('api/rankings.php');
                const result = await res.json();

                if (result.success && result.steps) {
                    const steps = result.steps;
                    
                    // Render Matrix R
                    rContainer.innerHTML = renderMatrix(steps.schools, steps.criteria, steps.normalized);
                    
                    // Render Matrix Y
                    yContainer.innerHTML = renderMatrix(steps.schools, steps.criteria, steps.weighted);
                    
                    // Render Ideals
                    idealContainer.innerHTML = `
                        <div class="p-4 pb-6">
                            <table class="matrix-table w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-left">Solusi</th>
                                        ${steps.criteria.map(c => `<th class="text-center">${c}</th>`).join('')}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-bold text-green-600 text-left">Ideal Positif (A+)</td>
                                        ${steps.ideal_pos.map(v => `<td class="text-center">${v.toFixed(4)}</td>`).join('')}
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-red-600 text-left">Ideal Negatif (A-)</td>
                                        ${steps.ideal_neg.map(v => `<td class="text-center">${v.toFixed(4)}</td>`).join('')}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                }
            } catch (e) {
                console.error(e);
                rContainer.innerHTML = '<p class="text-red-400">Gagal memuat data perhitungan.</p>';
            }
        }

        function renderMatrix(schools, criteria, data) {
            return `
                <div class="p-4 pb-8">
                    <table class="matrix-table w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left">Sekolah</th>
                                ${criteria.map(c => `<th class="text-center">${c}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${schools.map((s, i) => `
                                <tr>
                                    <td class="font-semibold text-gray-700 text-left">${s}</td>
                                    ${data[i].map(v => `<td class="text-center">${v.toFixed(4)}</td>`).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function openSchoolModal() {
            document.getElementById('school-id').value = '';
            document.getElementById('school-name').value = '';
            document.getElementById('school-utbk').value = '';
            document.getElementById('school-akreditasi').value = '4';
            document.getElementById('school-rasio').value = '';
            document.getElementById('school-akses').value = '3';
            document.getElementById('school-image').value = '';
            document.getElementById('school-image-file').value = '';
            
            const previewContainer = document.getElementById('school-image-preview-container');
            const previewImg = document.getElementById('school-image-preview');
            previewImg.src = '';
            previewContainer.classList.add('hidden');
            
            document.getElementById('school-modal-title').innerText = 'Tambah Sekolah';
            document.getElementById('school-modal').classList.remove('hidden');
        }

        async function editSchool(id) {
            try {
                const res = await fetch(`api/schools.php?id=${id}`);
                const result = await res.json();
                if (result.success) {
                    const school = result.data;
                    document.getElementById('school-id').value = school.id;
                    document.getElementById('school-name').value = school.name;
                    document.getElementById('school-utbk').value = school.c1_utbk !== null ? school.c1_utbk : '';
                    document.getElementById('school-akreditasi').value = school.c2_akreditasi || '4';
                    document.getElementById('school-rasio').value = school.c3_rasio_siswa_guru || '';
                    document.getElementById('school-akses').value = school.c4_akses_transportasi || '3';
                    document.getElementById('school-image').value = school.image_url || '';
                    document.getElementById('school-image-file').value = '';
                    
                    const previewContainer = document.getElementById('school-image-preview-container');
                    const previewImg = document.getElementById('school-image-preview');
                    if (school.image_url) {
                        previewImg.src = school.image_url;
                        previewContainer.classList.remove('hidden');
                    } else {
                        previewImg.src = '';
                        previewContainer.classList.add('hidden');
                    }
                    
                    document.getElementById('school-modal-title').innerText = 'Edit Sekolah';
                    document.getElementById('school-modal').classList.remove('hidden');
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('Gagal mengambil data sekolah.');
            }
        }

        function closeSchoolModal() {
            document.getElementById('school-modal').classList.add('hidden');
        }

        async function saveSchool(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('id', document.getElementById('school-id').value);
            formData.append('name', document.getElementById('school-name').value);
            formData.append('c1_utbk', document.getElementById('school-utbk').value);
            formData.append('c2_akreditasi', document.getElementById('school-akreditasi').value);
            formData.append('c3_rasio_siswa_guru', document.getElementById('school-rasio').value);
            formData.append('c4_akses_transportasi', document.getElementById('school-akses').value);
            formData.append('image_url', document.getElementById('school-image').value);

            const fileInput = document.getElementById('school-image-file');
            if (fileInput.files.length > 0) {
                formData.append('image_file', fileInput.files[0]);
            }

            try {
                const res = await fetch('api/schools.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    alert(result.message);
                    closeSchoolModal();
                    // Reload page and direct back to the schools section tab
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('section', 'schools');
                    window.location.href = currentUrl.toString();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('Terjadi kesalahan sistem.');
            }
        }

        // Add file input change listener for previewing image
        document.getElementById('school-image-file')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewContainer = document.getElementById('school-image-preview-container');
                    const previewImg = document.getElementById('school-image-preview');
                    previewImg.src = event.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        async function deleteSchool(id) {
            if (!confirm('Hapus sekolah ini beserta seluruh datanya?')) return;
            try {
                const res = await fetch(`api/schools.php?id=${id}`, { method: 'DELETE' });
                const result = await res.json();
                if (result.success) {
                    alert(result.message);
                    // Reload page and direct back to the schools section tab
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('section', 'schools');
                    window.location.href = currentUrl.toString();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('Gagal menghapus sekolah.');
            }
        }

        // LOGOUT
        document.getElementById('logout-btn')?.addEventListener('click', async () => {
            const res = await fetch('api/auth.php?action=logout');
            if (res.ok) window.location.href = 'login.php';
        });

        // SECTION URL ROUTING
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const sectionParam = urlParams.get('section');
            if (sectionParam && ['dashboard', 'schools', 'users', 'calculation'].includes(sectionParam)) {
                showSection(sectionParam);
            }
        });
    </script>
</body>
</html>
