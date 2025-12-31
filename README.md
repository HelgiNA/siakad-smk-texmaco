**TUGAS: Implementasi Full Stack Master Data Guru (One-to-One User Relation)**

**Konteks:**
Kita perlu membuat manajemen data Guru. Karena setiap Guru wajib bisa login, maka setiap pembuatan data Guru **HARUS** otomatis membuat akun di tabel `users` dalam satu transaksi database (Atomic Operation).

**Instruksi Eksekusi:**

1. **Database & Model (`app/models/Guru.php`):**

-   Eksekusi SQL berikut untuk membuat tabel:

```sql
CREATE TABLE guru (
    guru_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    nip VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

```

-   Buat Model `Guru` (extending `Model`). Tambahkan method `getAllWithUser()` yang melakukan `JOIN` ke tabel `users` untuk mengambil username/role.

2. **Controller (`app/controllers/GuruController.php`):**

-   **Security:** Pastikan hanya role 'admin' yang bisa akses (cek di `__construct`).
-   **Method `store()` (CRITICAL):**
-   Gunakan `Database::beginTransaction()`.
-   _Step 1:_ Insert ke tabel `users` (Username = NIP, Password = Hash(NIP), Role = 'guru').
-   _Step 2:_ Ambil `lastInsertId()` sebagai `user_id`.
-   _Step 3:_ Insert ke tabel `guru` (`user_id`, `nip`, `nama_lengkap`).
-   _Commit_ jika sukses, _Rollback_ jika ada error.

-   **Method `update()`:** Update data `nip` dan `nama_lengkap`. Opsional: update username di tabel `users` jika NIP berubah.
-   **Method `destroy($id)`:** Hapus data via Model `User` (berdasarkan `user_id` yang terasosiasi). Biarkan `ON DELETE CASCADE` di database menangani penghapusan data di tabel `guru`.

3. **Views (`views/master/guru/`):**

-   Buat folder dan file: `index.php`, `create.php`, `edit.php`.
-   **Style:** Gunakan template AdminLTE yang sudah ada (copy struktur dari `views/user/` atau `views/product/`).
-   **Index:** Tampilkan tabel (No, NIP, Nama Lengkap, Username).

4. **Routing (`config/routes.php`):**

-   Daftarkan route: `/guru` (index), `/guru/create`, `/guru/store` (POST), `/guru/edit`, `/guru/update` (POST), `/guru/delete`.
-   Pastikan route dilindungi middleware auth.

=== CAKUPAN PEKERJAAN ===
Agent harus menyelesaikan file-file berikut:

1. **Database:** Eksekusi Query pembuatan tabel `guru`.
2. **Model:** `app/models/Guru.php`
3. **Controller:** `app/controllers/GuruController.php`
4. **Views:** `views/master/guru/` (index, create, edit).
5. **Config:** Update `config/routes.php`.
6. **Navbar:** Tambahkan menu "Data Guru" di `views/components/navbar.php` (atau sidebar).
