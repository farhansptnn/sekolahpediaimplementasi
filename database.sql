-- Database for SPK Sekolah (Berdasarkan ERD & LRS)
CREATE DATABASE IF NOT EXISTS spksek;
USE spksek;

-- 1. Table Admin (ERD: Admin)
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Seed default admin account (password: admin123)
INSERT INTO admin (id_admin, username, password) VALUES
(1, 'admin', '$2y$10$e.w/9M9e/xJ.8q/a3zSg/.v0p7t8Xg.r2w1H.Q3e1.w9r0Q9Y1u.C')
ON DUPLICATE KEY UPDATE username = VALUES(username);

-- 2. Table Kriteria (LRS: KRITERIA)
CREATE TABLE IF NOT EXISTS kriteria (
    id_kriteria INT AUTO_INCREMENT PRIMARY KEY,
    kode_kriteria VARCHAR(10) NOT NULL UNIQUE,
    nama_kriteria VARCHAR(100) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    jenis VARCHAR(20) NOT NULL
);

-- Seed Criteria Data
INSERT INTO kriteria (id_kriteria, kode_kriteria, nama_kriteria, bobot, jenis) VALUES
(1, 'C1', 'Nilai UTBK', 0.30, 'benefit'),
(2, 'C2', 'Akreditasi', 0.20, 'benefit'),
(3, 'C3', 'Rasio Guru/Siswa', 0.30, 'cost'),
(4, 'C4', 'Akses Transportasi', 0.20, 'benefit')
ON DUPLICATE KEY UPDATE
kode_kriteria = VALUES(kode_kriteria),
nama_kriteria = VALUES(nama_kriteria),
bobot = VALUES(bobot),
jenis = VALUES(jenis);

-- 3. Table Sekolah (LRS: SEKOLAH)
CREATE TABLE IF NOT EXISTS sekolah (
    id_sekolah INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(100) NOT NULL,
    tahun INT NOT NULL DEFAULT 2026,
    nilai_utbk DECIMAL(10,3) DEFAULT NULL,
    akreditasi VARCHAR(50) DEFAULT NULL,
    rasio_guru_siswa DECIMAL(5,2) DEFAULT NULL,
    akses_transportasi VARCHAR(50) DEFAULT NULL,
    image_url TEXT DEFAULT NULL
);

-- 4. Table Penilaian (LRS: PENILAIAN)
CREATE TABLE IF NOT EXISTS penilaian (
    id_penilaian INT AUTO_INCREMENT PRIMARY KEY,
    id_sekolah INT NOT NULL,
    id_kriteria INT NOT NULL,
    nilai DECIMAL(10,3) DEFAULT NULL,
    UNIQUE KEY uk_sekolah_kriteria (id_sekolah, id_kriteria),
    FOREIGN KEY (id_sekolah) REFERENCES sekolah(id_sekolah) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_kriteria) REFERENCES kriteria(id_kriteria) ON DELETE CASCADE ON UPDATE CASCADE
);

-- 5. Table Hasil (LRS: HASIL)
CREATE TABLE IF NOT EXISTS hasil (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_sekolah INT NOT NULL,
    nilai_preferensi DECIMAL(10,4) DEFAULT NULL,
    ranking INT DEFAULT NULL,
    UNIQUE KEY uk_sekolah_hasil (id_sekolah),
    FOREIGN KEY (id_sekolah) REFERENCES sekolah(id_sekolah) ON DELETE CASCADE ON UPDATE CASCADE
);

-- 6. Table Users (Supporting user preferences & authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    priorities VARCHAR(20) DEFAULT '1,2,3,4',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Backwards-compatible View for legacy compatibility
CREATE OR REPLACE VIEW schools AS 
SELECT 
    id_sekolah AS id,
    nama_sekolah AS name,
    tahun,
    nilai_utbk AS c1_utbk,
    akreditasi AS c2_akreditasi,
    rasio_guru_siswa AS c3_rasio_siswa_guru,
    akses_transportasi AS c4_akses_transportasi,
    image_url
FROM sekolah;
