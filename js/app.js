/**
 * SekolahPedia - SPK Ranking Logic & Comparison (2025 vs 2026)
 */

let schoolData = [];
let comparisonData = [];
let currentPage = 1;
let selectedYear = 2026;
let selectedCompYear1 = 2025;
let selectedCompYear2 = 2026;
let currentViewMode = 'ranking';
let isPersonalized = false;

const AKSES_LABEL = { 4: 'Sangat Mudah', 3: 'Mudah', 2: 'Sedang', 1: 'Sulit' };

async function fetchRankings() {
    const badge = document.getElementById('status-badge');
    try {
        const response = await fetch(`api/rankings.php?year=${selectedYear}`);
        const result = await response.json();
        if (result.success) {
            schoolData = result.data;
            isPersonalized = result.is_personalized;
            renderRankings();
            if (badge) {
                const yearLabel = result.year || selectedYear;
                badge.textContent = isPersonalized ? `Personal (${yearLabel})` : `Global (${yearLabel})`;
                badge.classList.remove('bg-blue-50', 'text-blue-600', 'bg-emerald-50', 'text-emerald-600', 'border-blue-100', 'border-emerald-100');
                badge.classList.add(
                    isPersonalized ? 'bg-emerald-50' : 'bg-blue-50',
                    isPersonalized ? 'text-emerald-600' : 'text-blue-600'
                );
            }
            updateYearButtonsUI();
            updatePaginationUI();
        }
    } catch (error) {
        console.error('Error:', error);
        const container = document.getElementById('results-container');
        if (container) container.innerHTML = `<p class="text-red-500 text-center font-bold py-12">Terjadi kesalahan koneksi.</p>`;
    }
}

function selectYear(year) {
    selectedYear = parseInt(year);
    const sel = document.getElementById('ranking-year-select');
    if (sel) sel.value = selectedYear;
    fetchRankings();
}

function updateYearButtonsUI() {
    const sel = document.getElementById('ranking-year-select');
    if (sel) sel.value = selectedYear;
}

function onCompYearChange() {
    const y1 = parseInt(document.getElementById('comp-select-year1')?.value || 2025);
    const y2 = parseInt(document.getElementById('comp-select-year2')?.value || 2026);
    selectedCompYear1 = y1;
    selectedCompYear2 = y2;
    fetchComparison(y1, y2);
}

function switchViewMode(mode) {
    currentViewMode = mode;
    const rankingView = document.getElementById('section-ranking-view');
    const comparisonView = document.getElementById('section-comparison-view');
    const tabRanking = document.getElementById('view-tab-ranking');
    const tabComparison = document.getElementById('view-tab-comparison');

    if (mode === 'ranking') {
        rankingView.classList.remove('hidden');
        comparisonView.classList.add('hidden');
        tabRanking.className = "flex-1 py-3 px-5 rounded-xl font-extrabold text-xs uppercase tracking-wider transition-all bg-white text-blue-600 shadow-md";
        tabComparison.className = "flex-1 py-3 px-5 rounded-xl font-extrabold text-xs uppercase tracking-wider transition-all text-gray-500 hover:text-gray-900";
    } else {
        rankingView.classList.add('hidden');
        comparisonView.classList.remove('hidden');
        tabRanking.className = "flex-1 py-3 px-5 rounded-xl font-extrabold text-xs uppercase tracking-wider transition-all text-gray-500 hover:text-gray-900";
        tabComparison.className = "flex-1 py-3 px-5 rounded-xl font-extrabold text-xs uppercase tracking-wider transition-all bg-white text-blue-600 shadow-md";
        fetchComparison();
    }
}

async function fetchComparison(y1, y2) {
    if (!y1) y1 = selectedCompYear1;
    if (!y2) y2 = selectedCompYear2;
    selectedCompYear1 = y1;
    selectedCompYear2 = y2;

    const container = document.getElementById('comparison-results-container');
    if (!container) return;
    container.innerHTML = `<div class="p-12 text-center text-gray-400 font-bold">Memuat data perbandingan ${y1} vs ${y2}...</div>`;

    try {
        const response = await fetch(`api/comparison.php?year1=${y1}&year2=${y2}`);
        const result = await response.json();
        if (result.success) {
            comparisonData = result.data;
            selectedCompYear1 = result.year1 || y1;
            selectedCompYear2 = result.year2 || y2;
            renderComparison();
        } else {
            container.innerHTML = `<p class="text-red-500 text-center font-bold py-12">Gagal memuat data komparasi.</p>`;
        }
    } catch (e) {
        console.error(e);
        container.innerHTML = `<p class="text-red-500 text-center font-bold py-12">Terjadi kesalahan sistem.</p>`;
    }
}

function renderComparison() {
    const container = document.getElementById('comparison-results-container');
    if (!container) return;
    container.innerHTML = '';

    const y1 = selectedCompYear1;
    const y2 = selectedCompYear2;

    const summaryHeader = document.createElement('div');
    summaryHeader.className = "p-6 bg-gradient-to-r from-indigo-900 via-blue-900 to-slate-900 text-white rounded-3xl shadow-xl space-y-2 mb-8";
    summaryHeader.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="font-black text-xl">Ikhtisar Perbandingan Performa Sekolah (${y1} vs ${y2})</h4>
            <span class="px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full">Total ${comparisonData.length} Sekolah</span>
        </div>
        <p class="text-xs text-blue-200">Perubahan tren nilai UTBK, rasio guru/siswa, dan posisi peringkat TOPSIS dari Tahun Ajaran ${y1} ke ${y2}.</p>
    `;
    container.appendChild(summaryHeader);

    const list = document.createElement('div');
    list.className = "grid grid-cols-1 gap-6";

    comparisonData.forEach(item => {
        const d1 = item.data_year1 || item.data_2025;
        const d2 = item.data_year2 || item.data_2026;
        const delta = item.delta;

        const utbk1 = d1 && d1.c1_utbk ? parseFloat(d1.c1_utbk).toFixed(3) : 'Tidak terdaftar';
        const utbk2 = d2 && d2.c1_utbk ? parseFloat(d2.c1_utbk).toFixed(3) : 'Tidak terdaftar';

        let rankBadge = '';
        if (delta.rank_change !== null) {
            if (delta.rank_change > 0) {
                rankBadge = `<span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-700">▲ Naik ${delta.rank_change} Peringkat</span>`;
            } else if (delta.rank_change < 0) {
                rankBadge = `<span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-700">▼ Turun ${Math.abs(delta.rank_change)} Peringkat</span>`;
            } else {
                rankBadge = `<span class="px-3 py-1 rounded-full text-xs font-black bg-gray-100 text-gray-600">▬ Peringkat Tetap</span>`;
            }
        }

        let utbkBadge = '';
        if (delta.utbk_diff !== null) {
            if (delta.utbk_diff > 0) {
                utbkBadge = `<span class="text-xs font-bold text-emerald-600">(+${delta.utbk_diff.toFixed(3)})</span>`;
            } else if (delta.utbk_diff < 0) {
                utbkBadge = `<span class="text-xs font-bold text-rose-600">(${delta.utbk_diff.toFixed(3)})</span>`;
            } else {
                utbkBadge = `<span class="text-xs font-bold text-gray-400">(0)</span>`;
            }
        }

        const card = document.createElement('div');
        card.className = "bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-md transition-all space-y-6";
        card.innerHTML = `
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                <div class="flex items-center gap-4">
                    <img src="${item.image_url}" class="w-14 h-14 rounded-2xl object-cover border border-gray-100" onerror="this.src='https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800'">
                    <div>
                        <h4 class="text-xl font-black text-gray-900">${item.name}</h4>
                        <p class="text-xs font-bold text-gray-400">Analisis Komparatif Performa ${y1} vs ${y2}</p>
                    </div>
                </div>
                <div>${rankBadge}</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Year 1 Box -->
                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-wider">Tahun ${y1}</span>
                        <span class="text-xs font-black text-gray-600">Rank TOPSIS: #${d1 ? d1.rank : '—'}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Nilai UTBK</p>
                            <p class="font-black text-gray-800">${utbk1}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Rasio Siswa/Guru</p>
                            <p class="font-black text-gray-800">${d1 ? d1.c3_rasio_siswa_guru : '—'}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Skor Preferensi (V)</p>
                            <p class="font-black text-blue-600">${d1 ? d1.score : '—'}</p>
                        </div>
                    </div>
                </div>

                <!-- Year 2 Box -->
                <div class="bg-indigo-50/50 p-5 rounded-2xl border border-indigo-100/60 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-wider">Tahun ${y2}</span>
                        <span class="text-xs font-black text-gray-600">Rank TOPSIS: #${d2 ? d2.rank : '—'}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Nilai UTBK</p>
                            <p class="font-black text-gray-800">${utbk2} ${utbkBadge}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Rasio Siswa/Guru</p>
                            <p class="font-black text-gray-800">${d2 ? d2.c3_rasio_siswa_guru : '—'}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Skor Preferensi (V)</p>
                            <p class="font-black text-indigo-600">${d2 ? d2.score : '—'}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        list.appendChild(card);
    });

    container.appendChild(list);
}

function renderRankings() {
    const container = document.getElementById('results-container');
    if (!container) return;
    container.innerHTML = '';
    const listContainer = document.createElement('div');
    listContainer.className = "flex flex-col gap-6 w-full";
    container.appendChild(listContainer);

    if (currentPage === 1) {
        const topHeader = document.createElement('div');
        topHeader.className = "mb-2 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-3xl shadow-lg flex items-center justify-between";
        topHeader.innerHTML = `<div><h3 class="text-white font-black text-lg">Top 5 Rekomendasi Utama (${selectedYear})</h3></div>`;
        listContainer.appendChild(topHeader);
        schoolData.slice(0, 5).forEach((item, index) => {
            listContainer.innerHTML += generateHorizontalCardHTML(item, index);
        });
    } else {
        const fullHeader = document.createElement('div');
        fullHeader.className = "mb-2 px-6 py-4 bg-gray-900 rounded-3xl shadow-lg flex items-center justify-between";
        fullHeader.innerHTML = `<div><h3 class="text-white font-black text-lg">Daftar Peringkat Lengkap (${selectedYear})</h3></div>`;
        listContainer.appendChild(fullHeader);
        schoolData.forEach((item, index) => {
            listContainer.innerHTML += generateHorizontalCardHTML(item, index);
        });
    }
}

function generateHorizontalCardHTML(item, index) {
    const rankColor = index === 0 ? 'bg-yellow-400' : (index === 1 ? 'bg-slate-300' : (index === 2 ? 'bg-orange-400' : 'bg-blue-50 text-blue-600 border border-blue-100 shadow-sm'));
    const textColor = index < 3 ? 'text-white' : 'text-blue-600';

    const utbk   = item.c1_utbk   ? parseFloat(item.c1_utbk).toFixed(3)   : '—';
    const akred  = item.c2_akreditasi == 4 ? 'A' : (item.c2_akreditasi == 3 ? 'B' : (item.c2_akreditasi == 2 ? 'C' : (item.c2_akreditasi || '—')));
    const rasio  = item.c3_rasio_siswa_guru ? parseFloat(item.c3_rasio_siswa_guru).toFixed(2) : '—';
    const akses  = AKSES_LABEL[item.c4_akses_transportasi] || '—';

    return `
        <div class="card-horizontal group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm flex flex-col sm:flex-row relative">
            <div class="absolute top-4 left-4 z-20 ${rankColor} ${textColor} w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm shadow-md">${index + 1}</div>
            <div class="w-full sm:w-64 h-48 sm:h-auto overflow-hidden relative flex-shrink-0">
                <img src="${item.image_url}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800'">
            </div>
            <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                    <div>
                        <h4 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight">${item.name}</h4>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tahun ${selectedYear}</span>
                    </div>
                    <div class="flex flex-row sm:flex-col items-center sm:items-end gap-1">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none">Skor Kelayakan</p>
                        <span class="text-3xl font-black text-blue-600 leading-none">${item.score}</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-1 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="text-center border-r border-gray-200">
                        <p class="text-[6px] font-black text-gray-400 uppercase tracking-widest">C1 UTBK</p>
                        <p class="text-[9px] font-bold text-gray-800">${utbk}</p>
                    </div>
                    <div class="text-center border-r border-gray-200">
                        <p class="text-[6px] font-black text-gray-400 uppercase tracking-widest">C2 Akred</p>
                        <p class="text-[9px] font-bold text-gray-800">${akred}</p>
                    </div>
                    <div class="text-center border-r border-gray-200">
                        <p class="text-[6px] font-black text-gray-400 uppercase tracking-widest">C3 Rasio</p>
                        <p class="text-[9px] font-bold text-gray-800">${rasio}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[6px] font-black text-gray-400 uppercase tracking-widest">C4 Akses</p>
                        <p class="text-[9px] font-bold text-gray-800">${akses}</p>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button onclick="showDetail(${item.id})" class="text-xs font-black text-blue-600 hover:text-blue-700 flex items-center gap-1 group/btn">LIHAT ANALISIS TOPSIS →</button>
                </div>
            </div>
        </div>
    `;
}

function showDetail(id) {
    const school = schoolData.find(s => s.id == id);
    if (!school) return;

    const modal = document.getElementById('detail-modal');
    const content = document.getElementById('modal-content');

    const utbk  = school.c1_utbk ? parseFloat(school.c1_utbk).toFixed(3) : 'Tidak terdaftar';
    const akred = school.c2_akreditasi == 4 ? 'A' : (school.c2_akreditasi == 3 ? 'B' : (school.c2_akreditasi == 2 ? 'C' : '—'));
    const rasio = school.c3_rasio_siswa_guru ? parseFloat(school.c3_rasio_siswa_guru).toFixed(2) + ' siswa/guru' : '—';
    const akses = AKSES_LABEL[school.c4_akses_transportasi] || '—';
    const rank  = schoolData.findIndex(s => s.id == id) + 1;

    content.innerHTML = `
        <div class="relative h-48 sm:h-64">
             <img src="${school.image_url}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800'">
             <div class="absolute inset-0 bg-gradient-to-t from-white via-white/20 to-transparent"></div>
        </div>
        <div class="p-8 -mt-12 relative bg-white rounded-t-[2.5rem] space-y-6">
            <div class="space-y-1">
                <h2 class="text-2xl font-black text-gray-900">${school.name}</h2>
                <p class="text-blue-600 font-bold uppercase tracking-widest text-xs">Analisis TOPSIS Data Tahun ${selectedYear}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded-3xl space-y-1 border border-blue-100">
                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Preferensi Akhir (V)</p>
                    <p class="text-3xl font-black text-blue-600">${school.score}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-3xl space-y-1 border border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Peringkat (${selectedYear})</p>
                    <p class="text-3xl font-black text-gray-800">#${rank}</p>
                </div>
            </div>

            <div class="space-y-3">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Detail Kriteria (${selectedYear})</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">C1 - Nilai UTBK</p>
                        <p class="text-sm font-black text-gray-800 mt-0.5">${utbk}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">C2 - Akreditasi</p>
                        <p class="text-sm font-black text-gray-800 mt-0.5">${akred}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">C3 - Rasio Guru/Siswa</p>
                        <p class="text-sm font-black text-gray-800 mt-0.5">${rasio}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">C4 - Akses Transport</p>
                        <p class="text-sm font-black text-gray-800 mt-0.5">${akses}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-5 rounded-[1.5rem] border border-blue-100">
                <p class="text-blue-800 text-sm font-medium leading-relaxed">
                    Sekolah ini memiliki tingkat kecocokan sebesar <strong>${(parseFloat(school.score) * 100).toFixed(1)}%</strong> pada data <strong>${selectedYear}</strong>.
                </p>
            </div>

            <button onclick="closeModal()" class="w-full py-4 bg-gray-900 text-white rounded-[1.5rem] font-bold hover:bg-black transition-all">Tutup Analisis</button>
        </div>
    `;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('detail-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function changePage(p) {
    currentPage = p;
    renderRankings();
    updatePaginationUI();
    window.scrollTo({ top: document.getElementById('results-container').offsetTop - 100, behavior: 'smooth' });
}

function updatePaginationUI() {
    const btn1 = document.getElementById('page-1-btn');
    const btn2 = document.getElementById('page-2-btn');
    if (!btn1 || !btn2) return;
    if (currentPage === 1) {
        btn1.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest bg-blue-600 text-white shadow-xl shadow-blue-200";
        btn2.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest bg-gray-100 text-gray-400 hover:bg-gray-200 transition-all";
    } else {
        btn1.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest bg-gray-100 text-gray-400 hover:bg-gray-200 transition-all";
        btn2.className = "px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest bg-gray-900 text-white shadow-xl shadow-gray-200";
    }
}

document.getElementById('page-1-btn')?.addEventListener('click', () => changePage(1));
document.getElementById('page-2-btn')?.addEventListener('click', () => changePage(2));
document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
document.addEventListener('click', (e) => { if (e.target.id === 'modal-overlay') closeModal(); });

fetchRankings();
