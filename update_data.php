<?php
require_once 'api/config.php';

try {
    // Clear old data
    $pdo->exec("DELETE FROM schools");

    // Data: C1=Nilai UTBK, C2=Akreditasi(4=A), C3=Rasio Siswa/Guru(Cost), C4=Akses Transportasi(4=Sangat Mudah,3=Mudah,2=Sedang,1=Sulit)
    // NULL c1_utbk = "Tidak terdaftar" di LTMPT
    $schools = [
        ['SMAN 8 Jakarta',          635.347, 4, 17.51, 2],
        ['SMAN 28 Jakarta',         625.145, 4, 17.31, 4],
        ['SMAN 34 Jakarta',         613.287, 4, 16.85, 4],
        ['SMAN 47 Jakarta',         603.293, 4, 15.91, 3],
        ['SMAN 26 Jakarta',         602.227, 4, 17.53, 3],
        ['SMAN 38 Jakarta',         593.853, 4, 18.23, 4],
        ['SMAN 49 Jakarta',         593.747, 4, 15.76, 4],
        ['SMAN 70 Jakarta',         586.564, 4, 18.36, 3],
        ['SMAN 66 Jakarta',         580.146, 4, 17.08, 3],
        ['SMAN 90 Jakarta',         579.872, 4, 11.16, 3],
        ['SMAN 55 Jakarta',         576.486, 4, 17.58, 3],
        ['SMAN 6 Jakarta',          567.459, 4, 19.69, 3],
        ['SMAN 3 Jakarta',          564.401, 4, 16.18, 3],
        ['SMAN 109 Jakarta',        563.560, 4, 16.91, 3],
        ['SMAN 29 Jakarta',         561.658, 4, 15.94, 4],
        ['SMAN 60 Jakarta',         557.405, 4, 20.03, 3],
        ['SMAN 82 Jakarta',         555.318, 4, 17.13, 3],
        ['SMAN 63 Jakarta',         553.776, 4, 18.42, 3],
        ['SMAN 37 Jakarta',         553.468, 4, 15.76, 4],
        ['SMAN 46 Jakarta',         550.797, 4, 21.76, 3],
        ['SMAN 86 Jakarta',         635.347, 4, 17.68, 3],
        ['SMAN 87 Jakarta',         526.788, 4, 18.63, 3],
        ['SMAN 108 Jakarta',        null,    4, 15.19, 3],
        ['SMAN 74 Jakarta',         null,    4, 18.19, 3],
        ['SMAN 32 Jakarta',         511.222, 4, 18.40, 3],
        ['SMAN 97 Jakarta',         526.307, 4, 17.34, 3],
        ['SMAN 43 Jakarta',         522.841, 4, 19.11, 2],
        ['SMAN 79 Jakarta',         null,    4, 19.39, 2],
        ['SMAN Ragunan Jakarta',    null,    4, 15.68, 3],
    ];

    $stmt = $pdo->prepare("INSERT INTO schools (name, c1_utbk, c2_akreditasi, c3_rasio_siswa_guru, c4_akses_transportasi, image_url) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($schools as $s) {
        $img = "assets/images/jakarta_school_" . rand(1, 4) . "_1777964920163.png";
        $stmt->execute([$s[0], $s[1], $s[2], $s[3], $s[4], $img]);
    }

    echo "Data sekolah berhasil diperbarui dengan 4 kriteria yang benar.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
