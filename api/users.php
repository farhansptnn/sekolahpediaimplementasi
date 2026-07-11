<?php
require_once 'config.php';

header('Content-Type: application/json');

// Auth check (only admin can manage users)
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // List users
        $stmt = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $users]);
        break;

    case 'POST':
        // Add or Update user
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'user';
        $id = $data['id'] ?? null;

        if ($id) {
            // Update
            if ($password) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $hashed_password, $role, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $role, $id]);
            }
            echo json_encode(['success' => true, 'message' => 'User updated']);
        } else {
            // Add
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hashed_password, $role]);
                echo json_encode(['success' => true, 'message' => 'User added']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Username already exists']);
            }
        }
        break;

    case 'DELETE':
        // Delete user
        $id = $_GET['id'] ?? null;
        if ($id) {
            // Prevent deleting self
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['success' => false, 'message' => 'Cannot delete yourself']);
                exit;
            }
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'User deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID missing']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
