# SIAKAD SMK Texmaco Subang 🎓

**Sistem Informasi Akademik (SIAKAD)** yang dirancang khusus untuk memodernisasi manajemen data akademik di SMK Texmaco Subang. Aplikasi ini memfasilitasi pengelolaan data siswa, guru, jadwal, absensi, hingga penilaian dan pencetakan rapor dalam satu platform terintegrasi.

Platform ini dibangun dengan arsitektur **MVC (Model-View-Controller)** menggunakan **Native PHP**, menjadikannya ringan, cepat, dan mudah untuk dikembangkan lebih lanjut.

---

## ⚡ Preview & Demo

Lihat langsung bagaimana sistem ini bekerja melalui link demo berikut:

🔗 **Website Demo:** [https://hans.page.gd](https://hans.page.gd)

### 🔐 Akun Demo (Login Info)

Gunakan kredensial berikut untuk menguji fitur berdasarkan role user:

| Role | Username | Password | Deskripsi Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `admin` | Akses penuh (Master Data, User Management, Konfigurasi) |
| **Guru** | `guru_rina` | `123456` | Kelola Absensi, Input Nilai, Wali Kelas |
| **Guru** | `guru_sandi` | `123456` | Kelola Absensi, Input Nilai |
| **Siswa** | `24001` | `123456` | Lihat Jadwal, Absensi, Nilai, dan Rapor |

---

## 🚀 Fitur Utama

### 👨‍💼 Administrator

- **Dashboard Statistik**: Ringkasan jumlah siswa, guru, dan status aktif.
- **Manajemen User**: Pengelolaan akun pengguna dengan level akses (Admin, Guru, Siswa).
- **Master Data**: Pengelolaan data induk sekolah (Tahun Ajaran, Kelas, Mata Pelajaran, Data Guru, Data Siswa).
- **Plotting Kelas**: Manajemen penempatan siswa ke dalam kelas (Rombel).

### 👩‍🏫 Guru & Wali Kelas

- **Input Absensi Harian**: Mencatat kehadiran siswa (Hadir, Sakit, Izin, Alpa).
- **Input Nilai**: Memasukkan nilai Tugas, UTS, dan UAS.
- **Validasi Data**: Fitur approval untuk validasi absensi dan nilai oleh Wali Kelas.
- **Manajemen Kelas**: Monitoring siswa perwalian.

### 👨‍🎓 Siswa

- **Monitoring Kehadiran**: Melihat rekapitulasi absensi pribadi.
- **Lihat Nilai**: Mengakses transkrip nilai per semester.
- **Cetak Rapor**: Unduh rapor format PDF secara mandiri.
- **Profil Siswa**: Melihat dan memverifikasi data diri.

---

## 🛠️ Teknologi yang Digunakan

Dibangun dengan standar coding yang rapi dan terstruktur untuk performa maksimal.

- **Backend**: PHP 7.4+ (Native MVC Architecture)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, Vanilla JavaScript, Bootstrap Icons
- **Server**: Apache / Nginx

---

## 📂 Struktur Folder

Aplikasi menggunakan struktur MVC untuk pemisahan _logic_ yang jelas:

```bash
/siakad-texmaco/
├── app/
│   ├── Controllers/   # Logika bisnis & alur aplikasi
│   ├── Models/        # Akses & manipulasi data database
│   └── Core/          # Konfigurasi Database, Router, Middleware
├── config/            # Pengaturan sistem & database
├── public/            # Aset statis (CSS, JS, Images)
├── views/             # Tampilan antarmuka pengguna (UI)
│   ├── admin/         # View khusus Admin
│   ├── guru/          # View khusus Guru
│   ├── siswa/         # View khusus Siswa
│   └── layouts/       # Template utama (Header, Sidebar, Footer)
└── index.php          # Entry point aplikasi
```

---

## 💻 Cara Install & Menjalankan

Ikuti langkah mudah ini untuk menjalankan project di lokal komputer Anda:

1. **Clone Repository**

    ```bash
    git clone https://github.com/HelgiNA/siakad-smk-texmaco.git
    cd siakad-smk-texmaco
    ```

2. **Konfigurasi Database**
    - Buat database baru di MySQL dengan nama `db_siakad_texmaco`.
    - Import file database `db_siakad_texmaco.sql` yang tersedia di root folder.

3. **Setup Koneksi**
    - Buka file `config/config.php`.
    - Sesuaikan konfigurasi database:

      ```php
      define("DB_HOST", "localhost");
      define("DB_USERNAME", "root");
      define("DB_PASSWORD", "");
      define("DB_NAME", "db_siakad_texmaco");
      ```

4. **Jalankan Project**
    - Jika menggunakan XAMPP/Laragon, letakkan folder di `htdocs` atau `www`.
    - Akses melalui browser: `http://localhost/siakad-smk-texmaco`

---

## 👨‍💻 Tentang Developer

Project ini dikembangkan dengan dedikasi tinggi untuk memberikan solusi digital bagi dunia pendidikan.

- **Nama**: Hans
- **Status**: Mahasiswa IT
- **Portofolio**: [https://hans.page.gd](https://hans.page.gd)

> _"Technology is best when it brings people together."_

---

## 📝 Catatan Tambahan

- Pastikan ekstensi `mysqli` aktif pada konfigurasi PHP Anda.
- Gunakan PHP versi 7.4 atau yang lebih baru untuk kompatibilitas terbaik.
- Default password untuk semua akun user adalah hash bcrypt yang aman.

---

© 2024 **Siakad SMK Texmaco**. All Rights Reserved.
