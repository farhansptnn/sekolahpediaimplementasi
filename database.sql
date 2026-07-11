-- Database for SPK Sekolah
CREATE DATABASE IF NOT EXISTS spksek;
USE spksek;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    priorities VARCHAR(20) DEFAULT '1,2,3,4',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Schools table
CREATE TABLE IF NOT EXISTS schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    c1_utbk DECIMAL(10,3),
    c2_akreditasi INT,
    c3_rasio_siswa_guru DECIMAL(5,2),
    c4_akses_transportasi INT,
    image_url TEXT
);

-- Seed schools data with REAL images from school websites
DELETE FROM schools;
INSERT INTO schools (name, c1_utbk, c2_akreditasi, c3_rasio_siswa_guru, c4_akses_transportasi, image_url) VALUES
('SMAN 8 Jakarta', 635.347, 5, 17.50, 3, 'https://sman8jkt.sch.id/wp-content/uploads/2021/04/gedung-sman-8.jpg'),
('SMAN 28 Jakarta', 625.145, 5, 17.31, 5, 'https://sman28jkt.sch.id/wp-content/uploads/2020/06/gedung-sman-28.jpg'),
('SMAN 34 Jakarta', 613.287, 5, 16.85, 5, 'https://sman34jkt.sch.id/wp-content/uploads/2021/03/GEDUNG-3.jpg'),
('SMAN 47 Jakarta', 603.293, 5, 15.91, 4, 'https://sman47jkt.sch.id/wp-content/uploads/2022/01/IMG_20220120_103323-scaled.jpg'),
('SMAN 26 Jakarta', 602.227, 5, 17.52, 4, 'http://sman26jakarta.com/wp-content/uploads/2021/05/Gedung-SMAN-26-Jakarta-1.jpg'),
('SMAN 38 Jakarta', 593.853, 5, 18.23, 5, 'https://sman38jkt.sch.id/wp-content/uploads/2020/08/Gedung-SMAN-38-Jakarta.jpg'),
('SMAN 49 Jakarta', 593.747, 5, 15.76, 5, 'https://sman49jkt.sch.id/wp-content/uploads/2022/01/FB_IMG_1642646241352.jpg'),
('SMAN 70 Jakarta', 586.564, 5, 18.36, 4, 'https://sman70-jkt.sch.id/wp-content/uploads/2022/07/GEDUNG-SMAN-70.jpg'),
('SMAN 66 Jakarta', 580.146, 5, 17.08, 4, 'https://sman66jkt.sch.id/wp-content/uploads/2020/09/gedung-sman-66.jpg'),
('SMAN 90 Jakarta', 579.872, 5, 11.16, 4, 'https://sman90jkt.sch.id/wp-content/uploads/2021/03/gedung.jpg'),
('SMAN 55 Jakarta', 576.486, 5, 17.58, 4, 'https://sman55jakarta.sch.id/wp-content/uploads/2021/05/WhatsApp-Image-2021-05-24-at-13.36.00.jpeg'),
('SMAN 6 Jakarta', 567.459, 5, 19.69, 4, 'https://sman6jkt.sch.id/wp-content/uploads/2019/04/sman6jkt.jpg'),
('SMAN 3 Jakarta', 564.401, 5, 16.18, 4, 'https://sman3jkt.sch.id/wp-content/uploads/2021/03/gedung-sman3.jpg'),
('SMAN 109 Jakarta', 563.560, 5, 16.91, 4, 'https://sman109jkt.sch.id/wp-content/uploads/2021/03/gedung-sman-109.jpg'),
('SMAN 29 Jakarta', 561.658, 5, 15.94, 5, 'https://sman29jakarta.sch.id/wp-content/uploads/2021/05/20210524_102434.jpg'),
('SMAN 60 Jakarta', 557.405, 5, 20.03, 4, 'https://sman60jkt.sch.id/wp-content/uploads/2021/03/gedung-sman-60.jpg'),
('SMAN 82 Jakarta', 555.318, 5, 17.13, 4, 'https://sman82jkt.sch.id/wp-content/uploads/2020/10/gedung-sman-82.jpg'),
('SMAN 63 Jakarta', 553.776, 5, 18.42, 4, 'http://sman63jakarta.sch.id/wp-content/uploads/2021/05/20210524_102645.jpg'),
('SMAN 37 Jakarta', 553.468, 5, 15.76, 5, 'https://sman37jkt.sch.id/wp-content/uploads/2021/03/gedung.jpg'),
('SMAN 46 Jakarta', 550.797, 5, 21.76, 4, 'http://sman46jkt.sch.id/wp-content/uploads/2021/05/20210524_134512.jpg');
