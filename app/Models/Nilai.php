<?php
namespace App\Models;

require_once __DIR__ . "/../Models/Model.php";

use PDO;
use PDOException;

class Nilai extends Model
{
    protected $table = "nilai";
    protected $primaryKey = "nilai_id";

    /**
     * Ambil nilai yang sudah disimpan berdasarkan Kelas & Mapel & Tahun
     */
    public static function getByKelasMapel($kelas_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT
                        MAX(n.nilai_id) as id_terakhir, -- Mengambil ID paling baru
                        s.nama_lengkap,
                        s.siswa_id,
                        n.mapel_id,
                        n.tahun_id,
                        MAX(n.nilai_tugas) as nilai_tugas, -- Mengambil nilai tugas terbesar (atau terakhir)
                        MAX(n.nilai_uts) as nilai_uts,
                        MAX(n.nilai_uas) as nilai_uas
                    FROM
                        nilai n
                    JOIN siswa s ON
                        n.siswa_id = s.siswa_id
                    WHERE
                        s.kelas_id = :kelas_id
                        AND n.mapel_id = :mapel_id
                        AND n.tahun_id = :tahun_id
                    GROUP BY
                        s.siswa_id, n.mapel_id, n.tahun_id -- Kunci pengelompokan
                    ORDER BY
                        s.nama_lengkap ASC;";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":kelas_id", $kelas_id, PDO::PARAM_INT);
        $stmt->bindParam(":mapel_id", $mapel_id, PDO::PARAM_INT);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cek apakah siswa sudah memiliki nilai untuk mapel & tahun tertentu
     */
    public static function checkExisting($siswa_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT * FROM $instance->table 
                    WHERE siswa_id = :siswa_id 
                    AND mapel_id = :mapel_id 
                    AND tahun_id = :tahun_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":siswa_id", $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(":mapel_id", $mapel_id, PDO::PARAM_INT);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * UPSERT Batch - Insert atau Update nilai untuk banyak siswa sekaligus
     * Menggunakan MySQL ON DUPLICATE KEY UPDATE untuk efisiensi
     */
    public static function saveBatch($dataNilai)
    {
        $instance = new static();

        // Mulai transaction untuk keamanan data
        try {
            $instance->conn->beginTransaction();

            // Query UPSERT (MySQL Syntax: ON DUPLICATE KEY UPDATE)
            // UNIQUE KEY `unique_nilai` (`siswa_id`, `mapel_id`, `tahun_id`)
            // memastikan hanya ada 1 record per siswa-mapel-tahun
            $query = "INSERT INTO $instance->table 
                        (siswa_id, mapel_id, tahun_id, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, status_validasi)
                    VALUES 
                        (:siswa_id, :mapel_id, :tahun_id, :tugas, :uts, :uas, :akhir, 'Draft')
                    ON DUPLICATE KEY UPDATE 
                        nilai_tugas = VALUES(nilai_tugas),
                        nilai_uts = VALUES(nilai_uts),
                        nilai_uas = VALUES(nilai_uas),
                        nilai_akhir = VALUES(nilai_akhir),
                        status_validasi = 'Draft'";

            $stmt = $instance->conn->prepare($query);

            foreach ($dataNilai as $row) {
                // Validasi data
                $tugas = (float) $row["tugas"];
                $uts = (float) $row["uts"];
                $uas = (float) $row["uas"];

                // Cek range nilai (0-100)
                if (
                    $tugas < 0 ||
                    $tugas > 100 ||
                    $uts < 0 ||
                    $uts > 100 ||
                    $uas < 0 ||
                    $uas > 100
                ) {
                    $instance->conn->rollBack();
                    return [
                        "status" => false,
                        "message" => "Nilai harus berada dalam range 0-100",
                    ];
                }

                // Hitung Nilai Akhir di Server Side (PENTING: Jangan dari client)
                // Rumus: (Tugas*20%) + (UTS*30%) + (UAS*50%)
                $akhir = $tugas * 0.2 + $uts * 0.3 + $uas * 0.5;

                // Execute untuk setiap siswa
                $stmt->execute([
                    ":siswa_id" => (int) $row["siswa_id"],
                    ":mapel_id" => (int) $row["mapel_id"],
                    ":tahun_id" => (int) $row["tahun_id"],
                    ":tugas" => $tugas,
                    ":uts" => $uts,
                    ":uas" => $uas,
                    ":akhir" => $akhir,
                ]);
            }

            $instance->conn->commit();
            return [
                "status" => true,
                "message" => "Nilai berhasil disimpan",
            ];
        } catch (PDOException $e) {
            $instance->conn->rollBack();
            return [
                "status" => false,
                "message" => "Database error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Ambil nilai final (untuk laporan/rapor)
     */
    public static function getByTahun($tahun_id)
    {
        $instance = new static();
        $query = "SELECT
                        n.*,
                        s.nama_lengkap,
                        s.nisn,
                        m.nama_mapel,
                        k.nama_kelas
                    FROM
                        $instance->table n
                        JOIN siswa s ON n.siswa_id = s.siswa_id
                        JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                        JOIN kelas k ON s.kelas_id = k.kelas_id
                    WHERE
                        n.tahun_id = :tahun_id
                    ORDER BY
                        k.nama_kelas ASC,
                        s.nama_lengkap ASC,
                        m.nama_mapel ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update status validasi nilai (untuk Wali Kelas)
     * Status: Draft, Revisi, Final
     */
    public static function updateStatus($nilai_id, $status, $alasan = null)
    {
        $instance = new static();

        $query =
            "UPDATE " . $instance->table . " SET status_validasi = :status";
        $params = [":status" => $status, ":nilai_id" => $nilai_id];

        if ($status === "Rejected") {
            $query .= ", alasan_penolakan = :alasan";
            $params[":alasan"] = $alasan;
        } else {
            // Reset reason if valid? Or keep history? Usually reset if Valid.
            $query .= ", alasan_penolakan = NULL";
        }

        $query .= " WHERE absensi_id = :absensi_id";

        $stmt = $instance->conn->prepare($query);
        try {
            $stmt->execute($params);
            return ["status" => true];
        } catch (PDOException $e) {
            return ["status" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Cek apakah nilai sudah dikunci (status = Final)
     * Jika dikunci, Guru tidak bisa edit lagi
     */
    public static function isLocked($siswa_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT status_validasi FROM $instance->table 
                    WHERE siswa_id = :siswa_id 
                    AND mapel_id = :mapel_id 
                    AND tahun_id = :tahun_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":siswa_id", $siswa_id, PDO::PARAM_INT);
        $stmt->bindParam(":mapel_id", $mapel_id, PDO::PARAM_INT);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result["status_validasi"] === "Final";
    }

    /**
     * Ambil rekap nilai untuk Wali Kelas (per Kelas)
     * Join: siswa, mapel, jadwal, guru untuk validasi
     */
    public static function getRekapByKelas($kelas_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT
                        n.*,
                        s.nama_lengkap,
                        s.nisn,
                        m.nama_mapel,
                        g.nama_guru
                    FROM
                        $instance->table n
                        JOIN siswa s ON n.siswa_id = s.siswa_id
                        JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                        JOIN jadwal_pelajaran j ON n.mapel_id = j.mapel_id AND n.tahun_id = j.tahun_id
                        JOIN guru g ON j.guru_id = g.guru_id
                    WHERE
                        s.kelas_id = :kelas_id
                        AND n.tahun_id = :tahun_id
                    ORDER BY
                        m.nama_mapel ASC,
                        s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":kelas_id", $kelas_id, PDO::PARAM_INT);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil nilai dengan filter status tertentu (SIA-008 V2)
     * Untuk Wali Kelas: hanya tampilkan status 'Submitted'
     */
    public static function getByStatus(
        $kelas_id,
        $tahun_id,
        $status = "Submitted"
    ) {
        $instance = new static();
        $query = "SELECT
                        n.*,
                        s.nama_lengkap,
                        s.nisn,
                        m.nama_mapel,
                        g.nama_guru
                    FROM
                        $instance->table n
                        JOIN siswa s ON n.siswa_id = s.siswa_id
                        JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                        JOIN guru g ON m.guru_id = g.guru_id
                    WHERE
                        s.kelas_id = :kelas_id
                        AND n.tahun_id = :tahun_id
                        AND n.status_validasi = :status
                    ORDER BY
                        m.nama_mapel ASC,
                        s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":kelas_id", $kelas_id, PDO::PARAM_INT);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update status + catatan revisi (SIA-008 V2)
     * Digunakan oleh Wali Kelas saat memberikan feedback revisi
     */
    public static function updateStatusWithNote(
        $nilai_id,
        $status,
        $catatan = null
    ) {
        $instance = new static();

        if ($status === "Revisi" && empty($catatan)) {
            return [
                "status" => false,
                "message" => "Catatan revisi wajib diisi jika menolak!",
            ];
        }

        $query = "UPDATE $instance->table 
                 SET status_validasi = :status, 
                     catatan_revisi = :catatan
                 WHERE nilai_id = :nilai_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":catatan", $catatan, PDO::PARAM_STR);
        $stmt->bindParam(":nilai_id", $nilai_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return [
                "status" => true,
                "message" => "Status berhasil diupdate",
            ];
        } catch (PDOException $e) {
            return [
                "status" => false,
                "message" => "Database error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Cek apakah nilai dalam kelas sudah ada yang status 'Submitted' atau 'Final'
     * Digunakan untuk validasi: Guru tidak bisa submit kalau ada yang sudah Submitted
     */
    public static function hasSubmittedOrFinal($kelas_id, $mapel_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT COUNT(*) as cnt FROM $instance->table n
                    JOIN siswa s ON n.siswa_id = s.siswa_id
                    WHERE s.kelas_id = :kelas_id
                    AND n.mapel_id = :mapel_id
                    AND n.tahun_id = :tahun_id
                    AND n.status_validasi IN ('Submitted', 'Final')";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(":kelas_id", $kelas_id, PDO::PARAM_INT);
        $stmt->bindParam(":mapel_id", $mapel_id, PDO::PARAM_INT);
        $stmt->bindParam(":tahun_id", $tahun_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result["cnt"] > 0;
    }
}
