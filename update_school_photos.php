<?php
require_once 'api/config.php';

$schools = [
    ['name' => 'SMA Negeri 1', 'image' => 'uploads/SMA_Negeri_1_1.jpg'],
    ['name' => 'SMA Negeri 2', 'image' => 'uploads/SMA_Negeri_2_2.jpg'],
    ['name' => 'SMA Negeri 3', 'image' => 'uploads/SMA_Negeri_3_3.jpg'],
    ['name' => 'SMK Negeri 1', 'image' => 'uploads/SMK_Negeri_1_4.jpg'],
    ['name' => 'SMP Negeri 1', 'image' => 'uploads/SMP_Negeri_1_5.jpg'],
];

foreach ($schools as $school) {
    $stmt = $pdo->prepare("UPDATE schools SET image_url = ? WHERE name = ?");
    $stmt->execute([$school['image'], $school['name']]);
    echo "Updated: {$school['name']} -> {$school['image']}\n";
}

echo "Done!\n";
?>

