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
                    "jadwal_id" => $headerData["jadwal_id"],
                    "status_validasi" => "Draft",
                    "catatan_harian" => $headerData["catatan_harian"],
                ]);
            } else {
                $resultAbsensi = Absensi::create([
                    "jadwal_id" => $headerData["jadwal_id"],
                    "tanggal" => $headerData["tanggal"],
                    "status_validasi" => "Draft",
                    "catatan_harian" => $headerData["catatan_harian"],
                ]);
            }

            $message = $revise ? "mengubah" : "menyimpan";

            if (!$resultAbsensi["status"]) {
                throw new \Exception(
                    "Gagal $message data siswa: " . $resultAbsensi["error"]
                );
                exit();
            }

            if ($revise) {
                DetailAbsensi::delete($absensiId);
            }

            $absensiId = $absensiId ?? $resultAbsensi["lastInsertId"];

            foreach ($dataAbsensiMany as &$dataAbsensi) {
                $dataAbsensi["absensi_id"] = $absensiId;
            }

            $resultDetailAbsensi = DetailAbsensi::createMany($dataAbsensiMany);

            if (!$resultDetailAbsensi["status"]) {
                throw new \Exception(
                    "Gagal $message data detail siswa: " .
                        $resultDetailAbsensi["error"]
                );
                exit();
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
                  AND a.status_validasi = 'Draft'
                  ORDER BY a.tanggal DESC, j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":kelas_id" => $kelas_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get Detail Absensi
    public static function getDetails($absensi_id)
    {
        $instance = new static();
        // Join with Siswa to get names
        $query = "SELECT 
                        d.*, 
                        s.nis, 
                        s.nama_lengkap
                    FROM detail_absensi d
                    JOIN siswa s ON d.siswa_id = s.siswa_id
                    WHERE d.detail_id IN (
                        SELECT MAX(sub_d.detail_id)
                        FROM detail_absensi sub_d
                        WHERE sub_d.absensi_id = :absensi_id
                        GROUP BY sub_d.siswa_id
                    )
                    ORDER BY s.nama_lengkap ASC;";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":absensi_id" => $absensi_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update Validation Status
    public static function updateStatus($absensi_id, $status, $alasan = null)
    {
        $instance = new static();

        $query =
            "UPDATE " . $instance->table . " SET status_validasi = :status";
        $params = [":status" => $status, ":absensi_id" => $absensi_id];

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

    public static function findWithDetails($absensi_id)
    {
        $instance = new static();
        $query = "SELECT
                            a.*,
                            j.hari,
                            j.jam_mulai,
                            j.jam_selesai,
                            m.nama_mapel,
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
}
