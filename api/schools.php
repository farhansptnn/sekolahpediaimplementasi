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
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM schools WHERE id = ?");
            $stmt->execute([$id]);
            $school = $stmt->fetch();
            if ($school) {
                echo json_encode(['success' => true, 'data' => $school]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Sekolah tidak ditemukan']);
            }
        } else {
            $stmt = $pdo->query("SELECT * FROM schools ORDER BY name ASC");
            $schools = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $schools]);
        }
        break;

    case 'POST':
        // Detect if request is JSON or multipart/form-data
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST;
        }

        $name = $data['name'] ?? '';
        
        // Handle empty or blank inputs as NULL
        $c1_utbk = (isset($data['c1_utbk']) && $data['c1_utbk'] !== '') ? floatval($data['c1_utbk']) : null;
        $c2_akreditasi = (isset($data['c2_akreditasi']) && $data['c2_akreditasi'] !== '') ? intval($data['c2_akreditasi']) : null;
        $c3_rasio_siswa_guru = (isset($data['c3_rasio_siswa_guru']) && $data['c3_rasio_siswa_guru'] !== '') ? floatval($data['c3_rasio_siswa_guru']) : null;
        $c4_akses_transportasi = (isset($data['c4_akses_transportasi']) && $data['c4_akses_transportasi'] !== '') ? intval($data['c4_akses_transportasi']) : null;
        $image_url = $data['image_url'] ?? '';
        
        // Handle file upload if present
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image_file']['tmp_name'];
            $fileName = $_FILES['image_file']['name'];
            $fileSize = $_FILES['image_file']['size'];
            $fileType = $_FILES['image_file']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            // Check allowed file extensions
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Directory where uploaded files will be saved
                $uploadFileDir = '../uploads/';
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                // Generate a unique filename using MD5
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                
                $uploadSuccess = false;
                if (php_sapi_name() === 'cli') {
                    $uploadSuccess = copy($fileTmpPath, $dest_path);
                } else {
                    $uploadSuccess = move_uploaded_file($fileTmpPath, $dest_path);
                }

                if ($uploadSuccess) {
                    // Update image_url to relative path
                    $image_url = 'uploads/' . $newFileName;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal mengupload file gambar ke server.']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Format file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP.']);
                exit;
            }
        }

        if (empty($image_url)) {
            $image_url = 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=800'; // Default school image
        }
        $id = $data['id'] ?? null;

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Nama sekolah harus diisi']);
            exit;
        }

        if ($id) {
            // Update
            $stmt = $pdo->prepare("UPDATE schools SET name = ?, c1_utbk = ?, c2_akreditasi = ?, c3_rasio_siswa_guru = ?, c4_akses_transportasi = ?, image_url = ? WHERE id = ?");
            $stmt->execute([$name, $c1_utbk, $c2_akreditasi, $c3_rasio_siswa_guru, $c4_akses_transportasi, $image_url, $id]);
            echo json_encode(['success' => true, 'message' => 'Sekolah berhasil diperbarui']);
        } else {
            // Add
            $stmt = $pdo->prepare("INSERT INTO schools (name, c1_utbk, c2_akreditasi, c3_rasio_siswa_guru, c4_akses_transportasi, image_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $c1_utbk, $c2_akreditasi, $c3_rasio_siswa_guru, $c4_akses_transportasi, $image_url]);
            echo json_encode(['success' => true, 'message' => 'Sekolah berhasil ditambahkan']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM schools WHERE id = ?");
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
