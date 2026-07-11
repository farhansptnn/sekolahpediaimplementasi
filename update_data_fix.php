<?php
require_once 'api/config.php';

try {
    $pdo->exec("DELETE FROM schools");

    // C1=Nilai UTBK 2022, C2=Akreditasi(4=A), C3=Rasio Siswa/Guru(Cost), C4=Akses Transportasi(4=Sangat Mudah,3=Mudah,2=Sedang,1=Sulit)
    // Format: [name, c1_utbk, c2_akreditasi, c3_rasio_siswa_guru, c4_akses_transportasi, image_url]
    $data = [
        ['SMAN 8 Jakarta',       635.347, 4, 16.88, 2, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQjNcKRIx8XAmM2nY0bGTWSzvd7dUgwAkPXlA&s'],
        ['SMAN 28 Jakarta',      625.145, 4, 21.51, 4, 'https://www.aida.or.id/wp-content/uploads/2023/05/AIDA-Makna-Ketangguhan-Menurut-Pelajar-SMAN-28-Jakarta.jpg'],
        ['SMAN 34 Jakarta',      613.287, 4, 15.41, 4, 'https://static.republika.co.id/uploads/member/images/5gaoyltuhri.jpg'],
        ['SMAN 47 Jakarta',      603.293, 4, 19.09, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcdiVho_YesyIl_VyNGeFes1aQRrU5cg&s'],
        ['SMAN 26 Jakarta',      602.227, 4, 16.41, 3, 'https://media.licdn.com/dms/image/v2/C511BAQHAoed0zTnHMw/company-background_10000/company-background_10000/0/1584075190076/sman_26_jakarta_cover?e=2147483647&v=beta&t=YTPSvr9Srmr9nFdIzlsQMaHF7MWw-zaz13_yOp3gyfc'],
        ['SMAN 38 Jakarta',      593.853, 4, 15.56, 4, 'https://i0.wp.com/sman38.wordpress.com/2017/04/img_20161026_120230.jpg?fit=1200%2C900&ssl=1'],
        ['SMAN 49 Jakarta',      593.747, 4, 11.12, 4, 'https://file.data.kemdikdasmen.go.id/sekolahkita/20/2010/20102592-4.jpg'],
        ['SMAN 70 Jakarta',      586.564, 4, 23.43, 3, 'https://asset.tribunews.com/3kvYFLON6xXlU6vUSS0E_P5s=/1200x675/filters:upscale():quality(30):format(webp):focal(0.5x0.5:0.5x0.5)/wartakota/foto/bank/originals/sman-70-bulungan.jpg'],
        ['SMAN 66 Jakarta',      580.146, 4, 16.42, 3, 'https://sman66jkt.sch.id/wp-content/uploads/2024/05/ged66.jpeg'],
        ['SMAN 90 Jakarta',      579.872, 4, 22.47, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCjK2YrlxGeg3DoyWd9EnrQctpUlnfs7_OgljQ&s'],
        ['SMAN 55 Jakarta',      576.486, 4, 19.84, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9Gc5g6eDZEMQGIRNNOsAgDF-ddRqwHlnIwBwkuw&s'],
        ['SMAN 6 Jakarta',       567.459, 4, 23.63, 3, 'https://awsimages.detik.net.id/community/media/visual/2022/01/14/suasaan-di-sekolah-menengah-atas-negeri-6-jakarta-kebayoran-baru-jakarta-selatan-setelah-ditutup-karena-satu-siswa-terkonfirmasi_169.jpeg?w=1200'],
        ['SMAN 3 Jakarta',       564.401, 4, 21.02, 3, 'https://multimedia.beritajakarta.id/photo/2014_50&c75c8507a2ae5223efd2faeb9812/9169989d81df41fa8c6450bc83c322e0.jpg'],
        ['SMAN 109 Jakarta',     563.560, 4, 19.76, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRo1F5XYw-pQxeIzrMftqOeBwKigOjnen3Yw&s'],
        ['SMAN 29 Jakarta',      561.658, 4, 18.90, 4, 'https://www.sman29jakarta.sch.id/~img/115249_sma29-b37ed-3176_155-levelop80.webp'],
        ['SMAN 60 Jakarta',      557.405, 4, 19.97, 3, 'https://media.licdn.com/dms/image/v2/C5B1BAQEEfSp5uimoyQ/company-background_10000/company-background_10000/0/1591158703622/sma_negeri_60_jkt_cover?e=2147483647&v=beta&t=wDLN_5SMhJ2QruMhCRS_lv35-g7poc6hSrgSAmkWZY'],
        ['SMAN 82 Jakarta',      555.318, 4, 19.61, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRlu9ZvprA93-3eDLklGOn0BY1DFQmrrUTsVzQ&s'],
        ['SMAN 63 Jakarta',      553.776, 4, 16.10, 3, 'https://lytimg.com/vi/8GmnNjd4BlQ/maxresdefault.jpg'],
        ['SMAN 37 Jakarta',      553.468, 4, 15.89, 4, 'https://sman37.sch.id/wp-content/uploads/2025/09/WhatsApp-Image-2025-09-05-at-19.06.52.jpeg'],
        ['SMAN 46 Jakarta',      550.797, 4, 22.26, 3, 'https://media.licdn.com/dms/image/v2/C5B1BAQHJSmaGrKWIBg/company-background_10000/company-background_10000/0/1630137712092/sma_negeri_46_jakarta_cover?e=2147483647&v=beta&t=Spw_JMDKeCkk5LdelRyEcf8s527yaCnrUNAP9DnUoL0'],
        ['SMAN 86 Jakarta',      635.347, 4, 15.38, 3, 'https://file.data.kemdikdasmen.go.id/sekolahkita/20/2010/20102565-1.jpg'],
        ['SMAN 87 Jakarta',      526.788, 4, 19.49, 3, 'https://www.sma87jakarta.sch.id/images/slider/sman87_d.jpeg'],
        ['SMAN 108 Jakarta',     null,    4, 15.28, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9Gc9Nf6Y1UPlgFmGrmuLTaVM3LnFZM5YEuG_kJvA&s'],
        ['SMAN 74 Jakarta',      null,    4, 18.19, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9Gc9s4ylUPlgFmGrmuLTaVM3LnFZM5YEuG_kjvA&s'],
        ['SMAN 32 Jakarta',      511.222, 4, 18.40, 3, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcGsKUp-G3ga4_qnnhKkJuCFUltl2FskKlsW53A&s'],
        ['SMAN 97 Jakarta',      526.307, 4, 20.08, 3, 'https://sman97jkt.sch.id/wp-content/uploads/2021/11/1060150-scaled.jpg'],
        ['SMAN 43 Jakarta',      522.841, 4, 18.62, 2, 'https://file.data.kemdikdasmen.go.id/sekolahkita/20/2010/20102211-1.jpg'],
        ['SMAN 79 Jakarta',      null,    4, 20.33, 2, 'https://file.data.kemdikdasmen.go.id/sekolahkita/20/2010/20107320-1.jpg'],
        ['SMAN Ragunan Jakarta', null,    4, 27.63, 3, 'https://asset.tribunews.com/DrjrT_QR1eIK0ktyImEgL8rr78=/1200x675/filters:upscale():quality(30):format(webp):focal(0.5x0.5:0.5x0.5)/wartakota/foto/bank/originals/20150413-sma-olah-raga-ragunan.jpg'],
    ];

    $stmt = $pdo->prepare("INSERT INTO schools (name, c1_utbk, c2_akreditasi, c3_rasio_siswa_guru, c4_akses_transportasi, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($data as $s) {
        $stmt->execute([$s[0], $s[1], $s[2], $s[3], $s[4], $s[5]]);
    }
    echo "Data dan foto sekolah berhasil diperbarui dengan 4 kriteria yang benar.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
