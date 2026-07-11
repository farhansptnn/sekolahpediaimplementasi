<?php
require_once 'api/config.php';
$pdo->exec("UPDATE users SET priorities = '1,2,3,4'");
echo "All users updated to 4 criteria priorities.";
?>
