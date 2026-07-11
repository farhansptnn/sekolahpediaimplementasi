<?php
require_once 'api/config.php';

// Get all schools
$stmt = $pdo->query("SELECT id, name FROM schools ORDER BY id ASC");
$schools = $stmt->fetchAll();

$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

echo "Downloading photos for " . count($schools) . " schools...\n";

foreach ($schools as $school) {
    $schoolName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $school['name']);
    $fileName = $schoolName . '_' . $school['id'] . '.jpg';
    $filePath = $uploadDir . $fileName;
    
    // Skip if already exists
    if (file_exists($filePath)) {
        echo "✓ {$school['name']} - Already exists\n";
        continue;
    }
    
    // Random Unsplash school/building image
    $queries = ['school', 'classroom', 'education', 'building', 'library', 'campus'];
    $randomQuery = $queries[array_rand($queries)];
    $unsplashUrl = "https://source.unsplash.com/400x300/?" . urlencode($randomQuery);
    
    $imageData = @file_get_contents($unsplashUrl);
    if ($imageData === false) {
        echo "✗ {$school['name']} - Failed to download\n";
        continue;
    }
    
    if (file_put_contents($filePath, $imageData)) {
        echo "✓ {$school['name']} - Downloaded\n";
    } else {
        echo "✗ {$school['name']} - Failed to save\n";
    }
    
    sleep(1); // Avoid rate limiting
}

echo "Done!\n";
?>

