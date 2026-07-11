<?php
require_once 'api/config.php';

$username = 'admin';
$new_password = 'admin';
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

try {
    // Check if user exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Update
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE username = ?');
        $stmt->execute([$hashed, $username]);
        echo "Password for '$username' has been reset to '$new_password'.";
    } else {
        // Insert
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([$username, $hashed]);
        echo "User '$username' created with password '$new_password'.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
