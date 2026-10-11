<?php
require_once 'config.php';

header('Content-Type: application/json');

function getRankingsByYear($pdo, $year) {
    // 1. Get Criteria
    $stmtK = $pdo->query("SELECT * FROM kriteria ORDER BY id_kriteria ASC");
    $dbCriteria = $stmtK->fetchAll();
    
    $cMap = ['C1' => 'c1_utbk', 'C2' => 'c2_akreditasi', 'C3' => 'c3_rasio_siswa_guru', 'C4' => 'c4_akses_transportasi'];
    $criteria = [];
    foreach ($dbCriteria as $row) {
        $key = $cMap[$row['kode_kriteria']] ?? strtolower($row['kode_kriteria']);
        $criteria[] = [
            'id'     => $key,
            'name'   => $row['nama_kriteria'],
            'type'   => strtolower($row['jenis']),
            'weight' => floatval($row['bobot'])
        ];
    }

    // 2. Fetch Schools
    $stmt = $pdo->prepare("SELECT id_sekolah AS id, id_sekolah, nama_sekolah AS name, nama_sekolah, tahun, nilai_utbk AS c1_utbk, akreditasi AS c2_akreditasi, rasio_guru_siswa AS c3_rasio_siswa_guru, akses_transportasi AS c4_akses_transportasi, image_url FROM sekolah WHERE tahun = ?");
    $stmt->execute([$year]);
    $schools = $stmt->fetchAll();

    if (empty($schools)) return [];

    $num_criteria = count($criteria);
    $weights = array_fill(0, $num_criteria, 0.25);
    foreach ($criteria as $i => $c) {
        $weights[$i] = $c['weight'];
    }

    // 3. Divisors for TOPSIS
    $divisors = array_fill(0, $num_criteria, 0);
    foreach ($schools as $s) {
        foreach ($criteria as $j => $c) {
            $divisors[$j] += pow($s[$c['id']] ?? 0, 2);
        }
    }
    for ($j = 0; $j < $num_criteria; $j++) { $divisors[$j] = sqrt($divisors[$j]) ?: 1; }

    // 4. Normalized & Weighted
    $normalized = [];
    $weighted = [];
    foreach ($schools as $s) {
        $norm_row = [];
        foreach ($criteria as $j => $c) {
            $norm_row[$j] = ($s[$c['id']] ?? 0) / $divisors[$j];
        }
        $normalized[] = $norm_row;
        
        $weight_row = [];
        for ($j = 0; $j < $num_criteria; $j++) {
            $weight_row[$j] = $norm_row[$j] * $weights[$j];
        }
        $weighted[] = $weight_row;
    }

    // 5. Ideal Solutions
    $ideal_pos = []; $ideal_neg = [];
    for ($j = 0; $j < $num_criteria; $j++) {
        $col = array_column($weighted, $j);
        if ($criteria[$j]['type'] === 'cost') {
            $ideal_pos[$j] = min($col); $ideal_neg[$j] = max($col);
        } else {
            $ideal_pos[$j] = max($col); $ideal_neg[$j] = min($col);
        }
    }

    // 6. Preferensi V
    $results = [];
    foreach ($weighted as $i => $row) {
        $d_pos = 0; $d_neg = 0;
        for ($j = 0; $j < $num_criteria; $j++) {
            $d_pos += pow($row[$j] - $ideal_pos[$j], 2);
            $d_neg += pow($row[$j] - $ideal_neg[$j], 2);
        }
        $d_pos = sqrt($d_pos); $d_neg = sqrt($d_neg);
        $v = ($d_neg + $d_pos) == 0 ? 0 : ($d_neg / ($d_neg + $d_pos));

        $results[] = [
            'id'                    => $schools[$i]['id_sekolah'],
            'name'                  => $schools[$i]['nama_sekolah'],
            'image_url'             => $schools[$i]['image_url'],
            'v'                     => $v,
            'score'                 => number_format($v, 4),
            'c1_utbk'               => $schools[$i]['c1_utbk'],
            'c2_akreditasi'         => $schools[$i]['c2_akreditasi'],
            'c3_rasio_siswa_guru'   => $schools[$i]['c3_rasio_siswa_guru'],
            'c4_akses_transportasi' => $schools[$i]['c4_akses_transportasi'],
        ];
    }

    usort($results, function($a, $b) { return $b['v'] <=> $a['v']; });

    $map = [];
    foreach ($results as $index => $res) {
        $res['rank'] = $index + 1;
        $map[$res['name']] = $res;
    }
    return $map;
}

try {
    $year1 = isset($_GET['year1']) ? intval($_GET['year1']) : (isset($_GET['tahun1']) ? intval($_GET['tahun1']) : 2025);
    $year2 = isset($_GET['year2']) ? intval($_GET['year2']) : (isset($_GET['tahun2']) ? intval($_GET['tahun2']) : 2026);

    // Fetch distinct available years in DB
    $stmtYears = $pdo->query("SELECT DISTINCT tahun FROM sekolah ORDER BY tahun ASC");
    $dbYears = $stmtYears->fetchAll(PDO::FETCH_COLUMN);
    
    // Default options pool if DB only has a few
    $availableYears = array_unique(array_merge([2025, 2026, 2027, 2028, 2029, 2030], array_map('intval', $dbYears)));
    sort($availableYears);

    $rankYear1 = getRankingsByYear($pdo, $year1);
    $rankYear2 = getRankingsByYear($pdo, $year2);

    // Combine school names
    $allSchoolNames = array_unique(array_merge(array_keys($rankYear1), array_keys($rankYear2)));
    sort($allSchoolNames);

    $comparison = [];
    foreach ($allSchoolNames as $name) {
        $d1 = $rankYear1[$name] ?? null;
        $d2 = $rankYear2[$name] ?? null;

        $utbk1 = $d1['c1_utbk'] ?? null;
        $utbk2 = $d2['c1_utbk'] ?? null;

        $utbkDiff = ($utbk1 !== null && $utbk2 !== null) ? round($utbk2 - $utbk1, 3) : null;
        $rasioDiff = ($d1 && $d2 && $d1['c3_rasio_siswa_guru'] && $d2['c3_rasio_siswa_guru']) ? round($d2['c3_rasio_siswa_guru'] - $d1['c3_rasio_siswa_guru'], 2) : null;
        $scoreDiff = ($d1 && $d2) ? round($d2['v'] - $d1['v'], 4) : null;
        $rankChange = ($d1 && $d2) ? ($d1['rank'] - $d2['rank']) : null; // Positive = Rank Improved

        $comparison[] = [
            'name' => $name,
            'image_url' => $d2['image_url'] ?? $d1['image_url'] ?? '',
            'data_year1' => $d1,
            'data_year2' => $d2,
            'data_2025' => $d1, // Alias for backward compatibility
            'data_2026' => $d2, // Alias for backward compatibility
            'delta' => [
                'utbk_diff' => $utbkDiff,
                'rasio_diff' => $rasioDiff,
                'score_diff' => $scoreDiff,
                'rank_change' => $rankChange,
            ]
        ];
    }

    echo json_encode([
        'success' => true,
        'year1' => $year1,
        'year2' => $year2,
        'available_years' => $availableYears,
        'data' => $comparison
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
