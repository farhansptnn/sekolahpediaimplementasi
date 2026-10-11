# 🏫 SekolahPedia — School Decision Support System (SPK)

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![XAMPP](https://img.shields.io/badge/XAMPP-Apache-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)

**Sistem Pendukung Keputusan (SPK) berbasis web untuk merekomendasikan sekolah terbaik menggunakan metode TOPSIS.**

</div>

---

## 📌 Tentang Proyek

**SekolahPedia** adalah aplikasi web fullstack yang membantu calon siswa dan orang tua dalam memilih sekolah terbaik secara objektif menggunakan algoritma **TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)** — salah satu metode Multi-Criteria Decision Making (MCDM) yang paling diakui secara akademis.

Sistem ini dikembangkan sebagai implementasi nyata dari konsep SPK yang menggabungkan backend PHP dengan frontend dinamis berbasis JavaScript.

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🏆 **Peringkat Sekolah** | Ranking sekolah berdasarkan skor TOPSIS yang dihitung real-time |
| 📊 **Perbandingan Antar Tahun** | Bandingkan data dan performa sekolah lintas tahun |
| 🎯 **Personalisasi Prioritas** | Pengguna bisa mengatur bobot kriteria sesuai kebutuhan pribadi |
| 🔐 **Sistem Autentikasi** | Login/logout dengan role `user` dan `admin` |
| 🛠️ **Dashboard Admin** | CRUD data sekolah, kelola user, dan lihat langkah perhitungan SPK |
| 📸 **Upload Gambar Sekolah** | Admin dapat menambahkan foto sekolah via form upload |
| 📱 **Responsive Design** | Tampilan optimal di desktop maupun mobile |

---

## 🧮 Metode SPK: TOPSIS

Sistem menggunakan **4 kriteria penilaian** dengan bobot berbasis riset:

| Kode | Kriteria | Bobot | Jenis |
|---|---|---|---|
| C1 | Nilai UTBK / Rata-rata Nilai | 30% | Benefit ↑ |
| C2 | Akreditasi | 20% | Benefit ↑ |
| C3 | Rasio Guru/Siswa | 30% | Cost ↓ |
| C4 | Akses Transportasi | 20% | Benefit ↑ |

**Alur Perhitungan TOPSIS:**
1. Normalisasi matriks keputusan
2. Pembobotan matriks ternormalisasi
3. Penentuan solusi ideal positif (A+) dan negatif (A-)
4. Perhitungan jarak Euclidean ke A+ dan A-
5. Perhitungan nilai preferensi (skor akhir 0–1)

---

## 🛠️ Tech Stack

### Backend
- **PHP 8.x** — Server-side logic & REST API endpoints
- **PDO (PHP Data Objects)** — Database abstraction layer dengan prepared statements (SQL injection safe)
- **MySQL 8.0** — Relational database dengan desain ERD + LRS
- **Apache (XAMPP)** — Local development server

### Frontend
- **JavaScript (Vanilla ES6+)** — Dynamic rendering, fetch API untuk komunikasi ke backend
- **Tailwind CSS 3** — Utility-first CSS framework via CDN
- **Plus Jakarta Sans** — Modern typography via Google Fonts

### Arsitektur
- **MVC-inspired** — Pemisahan antara logic API (`/api/`), tampilan (`index.php`, `admin.php`), dan asset (`/css/`, `/js/`)
- **RESTful API** — Endpoint JSON terstruktur untuk data sekolah, ranking, dan perbandingan

---

## 📁 Struktur Proyek

```
spksek/
├── api/                    # Backend REST API endpoints
│   ├── config.php          # Konfigurasi database & session
│   ├── auth.php            # Login, logout, register
│   ├── schools.php         # CRUD data sekolah (admin only)
│   ├── rankings.php        # Kalkulasi & output ranking TOPSIS
│   ├── comparison.php      # Perbandingan antar tahun
│   ├── preferences.php     # Manajemen prioritas kriteria user
│   └── users.php           # Manajemen user (admin only)
├── css/
│   └── style.css           # Custom CSS
├── js/
│   └── app.js              # Frontend logic & API calls
├── uploads/                # Foto sekolah yang di-upload
├── index.php               # Halaman utama (ranking & perbandingan)
├── admin.php               # Dashboard admin
├── login.php               # Halaman autentikasi
├── preferences.php         # Halaman pengaturan prioritas user
├── database.sql            # Skema database + seed data
└── README.md
```

---

## 🚀 Cara Menjalankan Proyek

### Prasyarat
- [XAMPP](https://www.apachefriends.org/) (atau stack LAMP/WAMP lainnya) dengan **PHP 8+** dan **MySQL 8**
- Git

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/farhansptnn/sekolahpediaimplementasi.git
cd sekolahpediaimplementasi

# 2. Pindahkan ke direktori htdocs XAMPP
# (Atau jalankan langsung dari folder ini)

# 3. Import database
# Buka phpMyAdmin → Import file database.sql
# Atau via CLI:
mysql -u root -p < database.sql
```

```php
// 4. Konfigurasi database di api/config.php
$host = 'localhost';
$db   = 'spksek';
$user = 'root';       // sesuaikan dengan user MySQL kamu
$pass = '';           // sesuaikan dengan password MySQL kamu
```

```bash
# 5. Jalankan Apache & MySQL via XAMPP Control Panel
# 6. Buka browser → http://localhost/sekolahpediaimplementasi/
```

### Akun Default

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `admin123` |

> ⚠️ Ganti password default setelah setup pertama kali!

---

## 📸 Tampilan Aplikasi

### Halaman Utama — Peringkat Sekolah
Menampilkan kartu sekolah yang diurutkan berdasarkan skor TOPSIS, dengan badge peringkat, skor preferensi, dan detail kriteria.

### Dashboard Admin
Panel pengelolaan data sekolah dengan form CRUD, langkah-langkah perhitungan TOPSIS, dan manajemen user.

### Fitur Perbandingan
Side-by-side comparison data sekolah lintas tahun untuk melihat tren peningkatan atau penurunan performa.

---

## 💡 Highlight Teknis untuk Reviewer

- **Keamanan**: Semua query menggunakan PDO Prepared Statements — terproteksi dari SQL Injection
- **Session Management**: Role-based access control (RBAC) dengan validasi di sisi server
- **API Design**: Clean JSON API dengan proper HTTP method handling (GET/POST/DELETE)
- **Algorithm Implementation**: Algoritma TOPSIS diimplementasikan dari scratch di PHP tanpa library eksternal
- **Personalization Engine**: Sistem bobot dinamis per-user yang mengubah urutan ranking secara real-time

---

## 👤 Developer

**Farhan Septian**  
📧 [farhansptnn@github](https://github.com/farhansptnn)

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan implementasi akademis.  
© 2025 SekolahPedia — SPK Metode TOPSIS
