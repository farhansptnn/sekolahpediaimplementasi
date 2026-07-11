<?php
require_once 'config.php';
$stmt = $pdo->query("DESCRIBE schools");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
