<?php
require_once 'api/config.php';

try {
    // Ensure users table has role column
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user'");
    $pdo->exec("UPDATE users SET role = 'admin' WHERE username = 'admin'");

    // Ensure schools table has the correct 4 criteria columns
    // C1: Nilai UTBK 2022 (Benefit, bobot 0.3)
    // C2: Akreditasi 1-4 (Benefit, bobot 0.2)
    // C3: Rasio Siswa/Guru (Cost, bobot 0.3)
    // C4: Akses Transportasi 1-4 (Benefit, bobot 0.2)
    $pdo->exec("ALTER TABLE schools ADD COLUMN IF NOT EXISTS c1_utbk DECIMAL(10,3) DEFAULT NULL");
    $pdo->exec("ALTER TABLE schools ADD COLUMN IF NOT EXISTS c2_akreditasi INT(11) DEFAULT NULL");
    $pdo->exec("ALTER TABLE schools ADD COLUMN IF NOT EXISTS c3_rasio_siswa_guru DECIMAL(5,2) DEFAULT NULL");
    $pdo->exec("ALTER TABLE schools ADD COLUMN IF NOT EXISTS c4_akses_transportasi INT(11) DEFAULT NULL");

    echo "Database migrated successfully with correct 4-criteria schema.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
