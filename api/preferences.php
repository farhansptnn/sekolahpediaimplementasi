<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT priorities FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    echo json_encode(['priorities' => explode(',', $user['priorities'] ?? '1,2,3,4')]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $priorities = $data['priorities'] ?? '';

    // Validate priorities (must be 4 values)
    $p_arr = explode(',', $priorities);
    if (count($p_arr) !== 4) {
        echo json_encode(['success' => false, 'message' => 'Format prioritas tidak valid (Harus 4 kriteria)']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE users SET priorities = ? WHERE id = ?');
    if ($stmt->execute([$priorities, $user_id])) {
        echo json_encode(['success' => true, 'message' => 'Preferences updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }
}
?>
