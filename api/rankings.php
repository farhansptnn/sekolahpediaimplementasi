<?php
require_once 'config.php';

header('Content-Type: application/json');

// --- 1. Get Schools Data ---
$stmt = $pdo->query('SELECT * FROM schools');
$schools = $stmt->fetchAll();

// --- 2. Define Criteria ---
// 4 criteria: C1 Nilai UTBK, C2 Akreditasi, C3 Rasio Guru/Siswa, C4 Akses Transportasi
$criteria = [
    ['id' => 'c1_utbk',               'name' => 'Nilai UTBK',          'type' => 'benefit', 'weight' => 0.3],
    ['id' => 'c2_akreditasi',          'name' => 'Akreditasi',          'type' => 'benefit', 'weight' => 0.2],
    ['id' => 'c3_rasio_siswa_guru',    'name' => 'Rasio Guru/Siswa',    'type' => 'cost',    'weight' => 0.3],
    ['id' => 'c4_akses_transportasi',  'name' => 'Akses Transportasi',  'type' => 'benefit', 'weight' => 0.2],
];
$num_criteria = count($criteria);

// --- 3. Priorities & Weights ---
$priorities = [1, 2, 3, 4];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT priorities FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user && $user['priorities']) {
        $priorities = array_slice(explode(',', $user['priorities']), 0, $num_criteria);
    }
}

$weights = array_fill(0, $num_criteria, 0);
$actual_priorities = array_slice(array_pad($priorities, $num_criteria, $num_criteria), 0, $num_criteria);

foreach ($actual_priorities as $i => $p) {
    $base_w = $criteria[$i]['weight'];
    // Higher priority (lower number) gets higher multiplier
    $multiplier = (($num_criteria + 1) - $p) / ($num_criteria / 2.0);
    $weights[$i] = $base_w * $multiplier;
}
$sum_w = array_sum($weights);
foreach ($weights as $i => $w) { $weights[$i] = $w / ($sum_w ?: 1); }

// --- 4. TOPSIS ---
if (count($schools) === 0) {
    echo json_encode(['success' => true, 'data' => [], 'is_personalized' => false]);
    exit;
}

$divisors = array_fill(0, $num_criteria, 0);
foreach ($schools as $s) {
    foreach ($criteria as $j => $c) {
        $divisors[$j] += pow($s[$c['id']] ?? 0, 2);
    }
}
for ($j = 0; $j < $num_criteria; $j++) { $divisors[$j] = sqrt($divisors[$j]) ?: 1; }

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

$ideal_pos = []; $ideal_neg = [];
for ($j = 0; $j < $num_criteria; $j++) {
    $col = array_column($weighted, $j);
    if ($criteria[$j]['type'] === 'cost') {
        $ideal_pos[$j] = min($col); $ideal_neg[$j] = max($col);
    } else {
        $ideal_pos[$j] = max($col); $ideal_neg[$j] = min($col);
    }
}

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
        'id'                      => $schools[$i]['id'],
        'name'                    => $schools[$i]['name'],
        'image_url'               => $schools[$i]['image_url'],
        'v'                       => $v,
        'score'                   => number_format($v, 4),
        'c1_utbk'                 => $schools[$i]['c1_utbk'],
        'c2_akreditasi'           => $schools[$i]['c2_akreditasi'],
        'c3_rasio_siswa_guru'     => $schools[$i]['c3_rasio_siswa_guru'],
        'c4_akses_transportasi'   => $schools[$i]['c4_akses_transportasi'],
    ];
}

usort($results, function($a, $b) { return $b['v'] <=> $a['v']; });

echo json_encode([
    'success' => true, 
    'data' => $results, 
    'steps' => [
        'normalized' => $normalized,
        'weighted' => $weighted,
        'ideal_pos' => $ideal_pos,
        'ideal_neg' => $ideal_neg,
        'schools' => array_column($schools, 'name'),
        'criteria' => array_column($criteria, 'name')
    ],
    'is_personalized' => isset($_SESSION['user_id'])
]);
