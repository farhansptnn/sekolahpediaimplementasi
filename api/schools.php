<?php
require_once 'config.php';

header('Content-Type: application/json');

// Auth check (only admin can manage schools)
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? null;
        $year = $_GET['year'] ?? $_GET['tahun'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("SELECT id_sekolah AS id, id_sekolah, nama_sekolah AS name, nama_sekolah, tahun, nilai_utbk AS c1_utbk, nilai_utbk, akreditasi AS c2_akreditasi, akreditasi, rasio_guru_siswa AS c3_rasio_siswa_guru, rasio_guru_siswa, akses_transportasi AS c4_akses_transportasi, akses_transportasi, image_url FROM sekolah WHERE id_sekolah = ?");
            $stmt->execute([$id]);
            $school = $stmt->fetch();
            if ($school) {
                echo json_encode(['success' => true, 'data' => $school]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Sekolah tidak ditemukan']);
            }
        } else {
            if ($year && is_numeric($year)) {
                $stmt = $pdo->prepare("SELECT id_sekolah AS id, id_sekolah, nama_sekolah AS name, nama_sekolah, tahun, nilai_utbk AS c1_utbk, nilai_utbk, akreditasi AS c2_akreditasi, akreditasi, rasio_guru_siswa AS c3_rasio_siswa_guru, rasio_guru_siswa, akses_transportasi AS c4_akses_transportasi, akses_transportasi, image_url FROM sekolah WHERE tahun = ? ORDER BY nama_sekolah ASC");
                $stmt->execute([$year]);
            } else {
                $stmt = $pdo->query("SELECT id_sekolah AS id, id_sekolah, nama_sekolah AS name, nama_sekolah, tahun, nilai_utbk AS c1_utbk, nilai_utbk, akreditasi AS c2_akreditasi, akreditasi, rasio_guru_siswa AS c3_rasio_siswa_guru, rasio_guru_siswa, akses_transportasi AS c4_akses_transportasi, akses_transportasi, image_url FROM sekolah ORDER BY tahun DESC, nama_sekolah ASC");
            }
            $schools = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $schools]);
        }
        break;

    case 'POST':
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $name = $data['name'] ?? $data['nama_sekolah'] ?? '';
        $tahun = intval($data['tahun'] ?? $data['year'] ?? 2026);
        $c1_utbk = (isset($data['c1_utbk']) && $data['c1_utbk'] !== '') ? floatval($data['c1_utbk']) : ((isset($data['nilai_utbk']) && $data['nilai_utbk'] !== '') ? floatval($data['nilai_utbk']) : null);
        $c2_akreditasi = (isset($data['c2_akreditasi']) && $data['c2_akreditasi'] !== '') ? $data['c2_akreditasi'] : ($data['akreditasi'] ?? null);
        $c3_rasio_siswa_guru = (isset($data['c3_rasio_siswa_guru']) && $data['c3_rasio_siswa_guru'] !== '') ? floatval($data['c3_rasio_siswa_guru']) : ((isset($data['rasio_guru_siswa']) && $data['rasio_guru_siswa'] !== '') ? floatval($data['rasio_guru_siswa']) : null);
        $c4_akses_transportasi = (isset($data['c4_akses_transportasi']) && $data['c4_akses_transportasi'] !== '') ? $data['c4_akses_transportasi'] : ($data['akses_transportasi'] ?? null);
        $image_url = $data['image_url'] ?? '';

        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = '../uploads/';
                if (!file_exists($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                
                $uploadSuccess = (php_sapi_name() === 'cli') ? copy($fileTmpPath, $dest_path) : move_uploaded_file($fileTmpPath, $dest_path);
                if ($uploadSuccess) {
                    $image_url = 'uploads/' . $newFileName;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal mengupload file gambar']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Format file tidak diizinkan']);
                exit;
            }
        }

        if (empty($image_url)) {
            $image_url = 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800';
        }
        $id = $data['id'] ?? $data['id_sekolah'] ?? null;

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Nama sekolah harus diisi']);
            exit;
        }

        if ($id) {
            // Update sekolah
            $stmt = $pdo->prepare("UPDATE sekolah SET nama_sekolah = ?, tahun = ?, nilai_utbk = ?, akreditasi = ?, rasio_guru_siswa = ?, akses_transportasi = ?, image_url = ? WHERE id_sekolah = ?");
            $stmt->execute([$name, $tahun, $c1_utbk, $c2_akreditasi, $c3_rasio_siswa_guru, $c4_akses_transportasi, $image_url, $id]);
            $schoolId = $id;
            $msg = 'Sekolah berhasil diperbarui';
        } else {
            // Insert sekolah
            $stmt = $pdo->prepare("INSERT INTO sekolah (nama_sekolah, tahun, nilai_utbk, akreditasi, rasio_guru_siswa, akses_transportasi, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $tahun, $c1_utbk, $c2_akreditasi, $c3_rasio_siswa_guru, $c4_akses_transportasi, $image_url]);
            $schoolId = $pdo->lastInsertId();
            $msg = 'Sekolah berhasil ditambahkan';
        }

        // Sync into Penilaian table (LRS: PENILAIAN)
        $stmtP = $pdo->prepare("INSERT INTO penilaian (id_sekolah, id_kriteria, nilai) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)");
        if ($c1_utbk !== null) $stmtP->execute([$schoolId, 1, $c1_utbk]);
        if ($c2_akreditasi !== null) $stmtP->execute([$schoolId, 2, is_numeric($c2_akreditasi) ? $c2_akreditasi : ($c2_akreditasi === 'A' ? 4 : ($c2_akreditasi === 'B' ? 3 : 2))]);
        if ($c3_rasio_siswa_guru !== null) $stmtP->execute([$schoolId, 3, $c3_rasio_siswa_guru]);
        if ($c4_akses_transportasi !== null) $stmtP->execute([$schoolId, 4, is_numeric($c4_akses_transportasi) ? $c4_akses_transportasi : 3]);

        echo json_encode(['success' => true, 'message' => $msg]);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM sekolah WHERE id_sekolah = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Sekolah berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID missing']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
