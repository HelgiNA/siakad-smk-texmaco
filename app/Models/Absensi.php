<?php
namespace App\Models;

require_once __DIR__ . "/../Models/Model.php";
require_once __DIR__ . "/../Models/DetailAbsensi.php";

use App\Models\Model;
use App\Models\DetailAbsensi;
use PDO;
use PDOException;

class Absensi extends Model
{
    protected $table = "absensi";
    protected $primaryKey = "absensi_id";

    // Create header and details in one transaction
    public static function submit(
        $headerData,
        $dataAbsensiMany,
        $revise = false,
        $absensiId = null
    ) {
        try {
            Model::beginTransaction();

            if ($revise) {
                $resultAbsensi = Absensi::update($absensiId, [
                    "status_validasi" => "Pending",
                    "catatan_harian" => $headerData["catatan_harian"],
                ]);
            } else {
                $resultAbsensi = Absensi::create([
                    "jadwal_id" => $headerData["jadwal_id"],
                    "tanggal" => $headerData["tanggal"],
                    "status_validasi" => "Pending",
                    "catatan_harian" => $headerData["catatan_harian"],
                ]);
            }

            if (!$resultAbsensi["status"]) {
                throw new \Exception(
                    "Gagal menyimpan header absensi: " . $resultAbsensi["error"]
                );
            }

            $absensiId = $absensiId ?? $resultAbsensi["lastInsertId"];

            if ($revise) {
                $instance = new static();
                $sqlDelete =
                    "DELETE FROM detail_absensi WHERE absensi_id = :aid";
                $stmt = $instance->conn->prepare($sqlDelete);
                $stmt->execute([":aid" => $absensiId]);
            }

            foreach ($dataAbsensiMany as &$dataAbsensi) {
                $dataAbsensi["absensi_id"] = $absensiId;
            }

            $resultDetailAbsensi = DetailAbsensi::createMany($dataAbsensiMany);

            if (!$resultDetailAbsensi["status"]) {
                throw new \Exception(
                    "Gagal menyimpan detail siswa: " .
                        $resultDetailAbsensi["error"]
                );
            }

            Model::commit();

            return [
                "status" => true,
                "message" => "Absensi berhasil disimpan.",
            ];
        } catch (\Exception $e) {
            // 6. Jika ada ERROR di tahap manapun, BATALKAN SEMUA
            Model::rollBack();

            return [
                "status" => false,
                "message" => $e->getMessage(),
            ];
        }
    }

    // Revise (Update Header + Replace Details)
    // Check if attendance already exists for a schedule on a date
    public static function checkExisting($jadwal_id, $tanggal)
    {
        $instance = new static();
        $query = "SELECT * FROM $instance->table WHERE jadwal_id = :jadwal_id AND tanggal = :tanggal";
        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":jadwal_id" => $jadwal_id, ":tanggal" => $tanggal]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get Pending Absensi for Validation by Homeroom Teacher's Class
    public static function getPendingByKelas($kelas_id)
    {
        $instance = new static();
        // Join with jadwal_pelajaran to verify class match
        // Join with mapel and guru to show details
        $query =
            "SELECT a.*, j.hari, j.jam_mulai, j.jam_selesai, m.nama_mapel, g.nama_lengkap as nama_guru
                  FROM " .
            $instance->table .
            " a
                  JOIN jadwal_pelajaran j ON a.jadwal_id = j.jadwal_id
                  JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                  JOIN guru g ON j.guru_id = g.guru_id
                  WHERE j.kelas_id = :kelas_id
                  AND a.status_validasi = 'Pending'
                  ORDER BY a.tanggal DESC, j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":kelas_id" => $kelas_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Detail Absensi
    public static function getDetails($absensi_id)
    {
        $instance = new static();
        $query = "SELECT 
                    d.*, 
                    s.nis, 
                    s.nama_lengkap
                FROM detail_absensi d
                JOIN siswa s ON d.siswa_id = s.siswa_id
                WHERE d.absensi_id = :absensi_id
                ORDER BY s.nama_lengkap ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":absensi_id" => $absensi_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findWithDetails($absensi_id)
    {
        $instance = new static();
        $query = "SELECT
                            a.*,
                            j.hari,
                            j.jam_mulai,
                            j.jam_selesai,
                            m.nama_mapel,
                            k.kelas_id,
                            k.nama_kelas,
                            g.nama_lengkap AS nama_guru
                        FROM
                            $instance->table a
                            JOIN jadwal_pelajaran j ON a.jadwal_id = j.jadwal_id
                            JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                            JOIN kelas k ON j.kelas_id = k.kelas_id
                            JOIN guru g ON j.guru_id = g.guru_id
                        WHERE
                            a.absensi_id = :absensi_id";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":absensi_id" => $absensi_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Hitung total absensi pending (Draft) untuk satu kelas
     * Digunakan untuk notifikasi Guru yang menjadi Wali Kelas
     * 
     * @param int $kelas_id
     * @return int
     */
    public static function countPendingByKelas($kelas_id)
    {
        $instance = new static();
        $query = "SELECT COUNT(a.absensi_id) as total
                  FROM " . $instance->table . " a
                  JOIN jadwal_pelajaran j ON a.jadwal_id = j.jadwal_id
                  WHERE j.kelas_id = :kelas_id
                  AND a.status_validasi = 'Pending'";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->bindParam(':kelas_id', $kelas_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
}
