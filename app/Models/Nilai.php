<?php
namespace App\Models;

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/DetailNilai.php';

use PDO;
use Exception;

class Nilai extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'nilai_id';

    /**
     * FUNGSI UTAMA: Simpan Baru atau Revisi Nilai
     * Menggunakan Database Transaction
     */
    public static function submit($headerData, $dataNilaiMany, $revise = false, $nilaiId = null)
    {
        try {
            // 1. Mulai Transaksi
            Model::beginTransaction();

            // 2. Handle Header (Tabel 'nilai')
            if ($revise) {
                // Mode UPDATE/REVISI
                // Kita update status jadi Draft lagi agar Wali Kelas bisa cek ulang
                $resultHeader = self::update($nilaiId, [
                    'status_validasi' => 'Draft',
                    'updated_at'      => date('Y-m-d H:i:s')
                    // Kita tidak ubah guru_id/mapel_id/kelas_id saat revisi demi keamanan data
                ]);
            } else {
                // Mode CREATE BARU
                $resultHeader = self::create([
                    'tahun_id'        => $headerData['tahun_id'],
                    'kelas_id'        => $headerData['kelas_id'],
                    'mapel_id'        => $headerData['mapel_id'],
                    'guru_id'         => $headerData['guru_id'],
                    'status_validasi' => 'Draft'
                ]);
            }

            if (!$resultHeader['status']) {
                throw new Exception("Gagal menyimpan data utama nilai: " . $resultHeader['error']);
            }

            // Ambil ID (Entah dari update atau insert baru)
            $nilaiId = $nilaiId ?? $resultHeader['lastInsertId'];

            // 3. Handle Details (Tabel 'detail_nilai')
            if ($revise) {
                // Hapus detail lama agar bersih (mencegah duplikat)
                DetailNilai::deleteByHeaderId($nilaiId);
            }

            // Siapkan data detail baru
            foreach ($dataNilaiMany as &$detail) {
                $detail['nilai_id'] = $nilaiId;
                // Pastikan kalkulasi nilai akhir sudah dilakukan di Controller sebelum masuk sini,
                // atau hitung ulang di sini jika ingin strict.
                // $detail['nilai_akhir'] = ... (Opsional)
            }

            // Insert Batch (Banyak siswa sekaligus)
            $resultDetail = DetailNilai::createMany($dataNilaiMany);

            if (!$resultDetail['status']) {
                throw new Exception("Gagal menyimpan detail nilai siswa: " . $resultDetail['error']);
            }

            // 4. Commit Transaksi (Simpan Permanen)
            Model::commit();

            return [
                "status"  => true,
                "message" => "Data nilai berhasil disimpan.",
                "id"      => $nilaiId
            ];

        } catch (Exception $e) {
            // 5. Rollback (Batalkan jika ada error)
            Model::rollBack();

            return [
                "status"  => false,
                "message" => $e->getMessage()
            ];
        }
    }

    /**
     * Cek apakah Nilai untuk (Mapel X, Kelas Y, Tahun Z) sudah pernah diinput?
     * Dipakai di Controller create() untuk mencegah header ganda.
     */
    public static function checkExisting($kelas_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT * FROM {$instance->table} 
                  WHERE kelas_id = :kelas_id 
                  AND mapel_id = :mapel_id 
                  AND tahun_id = :tahun_id";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->execute([
            ':kelas_id' => $kelas_id,
            ':mapel_id' => $mapel_id,
            ':tahun_id' => $tahun_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil Header beserta Info Lengkapnya (Nama Guru, Nama Mapel, Nama Kelas)
     * Dipakai untuk Judul Halaman Form atau Halaman Validasi
     */
    public static function findWithInfo($nilai_id)
    {
        $instance = new static();
        $query = "SELECT 
                    n.*,
                    k.nama_kelas,
                    m.nama_mapel,
                    g.nama_lengkap AS nama_guru,
                    t.tahun,
                    t.semester
                  FROM {$instance->table} n
                  JOIN kelas k ON n.kelas_id = k.kelas_id
                  JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                  JOIN guru g ON n.guru_id = g.guru_id
                  JOIN tahun_ajaran t ON n.tahun_id = t.tahun_id
                  WHERE n.nilai_id = :nilai_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':nilai_id' => $nilai_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil Detail Nilai Siswa (List Siswa + Angka Nilainya)
     * Dipakai untuk mengisi Tabel di Form Edit atau View Validasi
     */
    public static function getDetails($nilai_id)
    {
        $instance = new static();
        $query = "SELECT 
                    d.*,
                    s.nis,
                    s.nama_lengkap
                  FROM detail_nilai d
                  JOIN siswa s ON d.siswa_id = s.siswa_id
                  WHERE d.nilai_id = :nilai_id
                  ORDER BY s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':nilai_id' => $nilai_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil Nilai yang statusnya 'Draft' (Pending) berdasarkan Kelas
     * Dipakai di Dashboard Wali Kelas untuk Validasi
     */
    public static function getPendingByKelas($kelas_id)
    {
        $instance = new static();
        $query = "SELECT 
                    n.*, 
                    m.nama_mapel, 
                    g.nama_lengkap as nama_guru,
                    n.updated_at as tgl_input
                  FROM {$instance->table} n
                  JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                  JOIN guru g ON n.guru_id = g.guru_id
                  WHERE n.kelas_id = :kelas_id
                  AND n.status_validasi = 'Draft'
                  ORDER BY n.updated_at DESC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':kelas_id' => $kelas_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil KHS (Kartu Hasil Studi) Siswa per Tahun Ajaran
     * Digunakan untuk halaman Profil Siswa
     * 
     * @param int $siswa_id
     * @param int $tahun_id (opsional, jika tidak diisi maka ambil tahun aktif)
     * @return array dengan keys per row: mapel_id, nama_mapel, kelompok, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, status_validasi
     */
    public static function getKHS($siswa_id, $tahun_id = null)
    {
        $instance = new static();

        // Jika tahun_id tidak diberikan, ambil tahun yang aktif
        if ($tahun_id === null) {
            $tahun = self::getActiveTahunAjaran();
            $tahun_id = $tahun['tahun_id'] ?? 2; 
        }

        // Perbaikan Query: Tambahkan JOIN ke detail_nilai (dn)
        $query = "SELECT
                    n.nilai_id,
                    n.mapel_id,
                    m.nama_mapel,
                    m.kelompok,
                    m.kkm,
                    dn.nilai_tugas,   -- Ambil dari detail_nilai
                    dn.nilai_uts,     -- Ambil dari detail_nilai
                    dn.nilai_uas,     -- Ambil dari detail_nilai
                    dn.nilai_akhir,   -- Ambil dari detail_nilai
                    n.status_validasi
                  FROM {$instance->table} n
                  JOIN detail_nilai dn ON n.nilai_id = dn.nilai_id
                  JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                  WHERE dn.siswa_id = :siswa_id  -- Filter siswa ada di tabel detail
                  AND n.tahun_id = :tahun_id
                  ORDER BY m.kelompok ASC, m.nama_mapel ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':siswa_id', $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(':tahun_id', $tahun_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
    }


    /**
     * Helper: Ambil Tahun Ajaran yang Aktif
     */
    private static function getActiveTahunAjaran()
    {
        $instance = new static();
        $query = "SELECT tahun_id, tahun, semester FROM tahun_ajaran WHERE is_active = 1 LIMIT 1";
        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?? [];
    }
    
	/**
     * Mendapatkan daftar input nilai yang statusnya belum Final
     * Digunakan untuk Dashboard Kepsek (Monitoring Guru)
     */
    public static function getPendingSubmission($limit = 5)
    {
        $instance = new static();

        // Ambil data nilai yang statusnya BUKAN 'Final' (Draft atau Submitted)
        // Join ke Kelas, Mapel, dan Guru untuk info lengkap
        $query = "SELECT 
                    n.status_validasi as status,
                    k.nama_kelas as kelas,
                    m.nama_mapel as mapel,
                    g.nama_lengkap as guru
                  FROM nilai n
                  JOIN kelas k ON n.kelas_id = k.kelas_id
                  JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                  JOIN guru g ON n.guru_id = g.guru_id
                  WHERE n.status_validasi != 'Final'
                  ORDER BY n.updated_at DESC
                  LIMIT :limit";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}