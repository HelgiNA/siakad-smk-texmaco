<?php
// app/Models/Jadwal.php
namespace App\Models;

use PDO;
use Exception;

class Jadwal extends Model
{
    protected $table = "jadwal_pelajaran";
    protected $primaryKey = "jadwal_id";

    /**
     * =================================================================
     * BAGIAN 1: MANIPULASI DATA (WRITE) - GAYA 'ABSENSI::SUBMIT'
     * =================================================================
     */

    // Satu fungsi untuk Simpan Baru atau Update (Revisi)
    public static function save($data, $id = null)
    {
        try {
            // 1. Validasi Tabrakan Jadwal
            $conflict = self::checkConflict(
                $data['guru_id'],
                $data['kelas_id'],
                $data['hari'],
                $data['jam_mulai'],
                $data['jam_selesai'],
                $data['tahun_id'],
                $id // Kirim ID jika sedang edit agar tidak bentrok dengan diri sendiri
            );

            if ($conflict) {
                throw new Exception($conflict); // Lempar error jika bentrok
            }

            // 2. Proses Simpan
            if ($id) {
                // Mode UPDATE
                $result = self::update($id, [
                    'tahun_id'    => $data['tahun_id'],
                    'kelas_id'    => $data['kelas_id'],
                    'mapel_id'    => $data['mapel_id'],
                    'guru_id'     => $data['guru_id'],
                    'hari'        => $data['hari'],
                    'jam_mulai'   => $data['jam_mulai'],
                    'jam_selesai' => $data['jam_selesai']
                ]);
                $action = "mengubah";
            } else {
                // Mode CREATE
                $result = self::create([
                    'tahun_id'    => $data['tahun_id'],
                    'kelas_id'    => $data['kelas_id'],
                    'mapel_id'    => $data['mapel_id'],
                    'guru_id'     => $data['guru_id'],
                    'hari'        => $data['hari'],
                    'jam_mulai'   => $data['jam_mulai'],
                    'jam_selesai' => $data['jam_selesai']
                ]);
                $action = "menyimpan";
            }

            if (!$result['status']) {
                throw new Exception("Gagal $action data: " . $result['error']);
            }

            return [
                "status" => true,
                "message" => "Jadwal berhasil disimpan.",
                "id" => $id ?? $result['lastInsertId']
            ];

        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    // Fungsi Validasi Cerdas (Cek Bentrok)
    public static function checkConflict($guru_id, $kelas_id, $hari, $mulai, $selesai, $tahun_id, $excludeId = null)
    {
        $instance = new static();
        
        // Logika: Cari jadwal di TAHUN & HARI yang sama, yang JAM-nya beririsan
        // Cek 1: Apakah GURU ini sudah mengajar di kelas lain di jam segitu?
        // Cek 2: Apakah KELAS ini sudah ada guru lain di jam segitu?
        
        $sql = "SELECT * FROM {$instance->table} 
                WHERE tahun_id = :tahun_id 
                AND hari = :hari
                AND (guru_id = :guru_id OR kelas_id = :kelas_id)
                AND (
                    (jam_mulai < :selesai AND jam_selesai > :mulai)
                )";

        $params = [
            ':tahun_id' => $tahun_id,
            ':hari'     => $hari,
            ':guru_id'  => $guru_id,
            ':kelas_id' => $kelas_id,
            ':mulai'    => $mulai,
            ':selesai'  => $selesai
        ];

        // Jika sedang Edit, jangan cek jadwal diri sendiri
        if ($excludeId) {
            $sql .= " AND jadwal_id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $instance->conn->prepare($sql);
        $stmt->execute($params);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($existing['guru_id'] == $guru_id) {
                return "Guru tersebut sudah memiliki jadwal lain pada jam ini (Kelas ID: {$existing['kelas_id']}).";
            }
            if ($existing['kelas_id'] == $kelas_id) {
                return "Kelas tersebut sudah terisi mata pelajaran lain pada jam ini.";
            }
        }

        return null; // Aman, tidak ada konflik
    }

    /**
     * =================================================================
     * BAGIAN 2: PENGAMBILAN DATA (READ) - SUDAH DIOPTIMASI
     * =================================================================
     */

    public static function getAllByTahun($tahun_id)
    {
        $instance = new static();
        $query = "SELECT
                    j.*,
                    k.nama_kelas,
                    m.nama_mapel,
                    m.kode_mapel,
                    m.kelompok,
                    g.nama_lengkap AS nama_guru,
                    t.tahun,
                    t.semester
                FROM {$instance->table} j
                JOIN kelas k ON j.kelas_id = k.kelas_id
                JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                JOIN guru g ON j.guru_id = g.guru_id
                JOIN tahun_ajaran t ON j.tahun_id = t.tahun_id
                WHERE j.tahun_id = :tahun_id
                ORDER BY
                    k.tingkat ASC,
                    k.nama_kelas ASC,
                    FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
                    j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':tahun_id' => $tahun_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Detail Single Jadwal (Header Style)
    public static function findWithDetails($id)
    {
        $instance = new static();
        $query = "SELECT
                    j.*,
                    k.nama_kelas,
                    m.nama_mapel,
                    g.nama_lengkap AS nama_guru,
                    t.tahun,
                    t.semester
                FROM {$instance->table} j
                JOIN kelas k ON j.kelas_id = k.kelas_id
                JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                JOIN guru g ON j.guru_id = g.guru_id
                JOIN tahun_ajaran t ON j.tahun_id = t.tahun_id
                WHERE j.jadwal_id = :id";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Flexible Getter (Pengganti getByGuru & getByGuruWithDetails)
    public static function getJadwalGuru($guru_id, $tahun_id, $hari = null)
    {
        $instance = new static();
        $sql = "SELECT
                    j.*,
                    k.nama_kelas,
                    m.nama_mapel,
                    m.kode_mapel
                FROM {$instance->table} j
                JOIN kelas k ON j.kelas_id = k.kelas_id
                JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                WHERE j.guru_id = :guru_id
                AND j.tahun_id = :tahun_id";

        $params = [
            ":guru_id" => $guru_id,
            ":tahun_id" => $tahun_id
        ];

        if ($hari !== null) {
            $sql .= " AND j.hari = :hari";
            $params[':hari'] = $hari;
        }

        $sql .= " ORDER BY 
                  FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'),
                  j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Distinct Getter (Untuk Dropdown Input Nilai)
    public static function getListKelasMapelGuru($guru_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT DISTINCT
                    j.kelas_id,
                    k.nama_kelas,
                    j.mapel_id,
                    m.kode_mapel,
                    m.nama_mapel
                FROM {$instance->table} j
                JOIN kelas k ON j.kelas_id = k.kelas_id
                JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                WHERE j.guru_id = :guru_id
                AND j.tahun_id = :tahun_id
                ORDER BY k.nama_kelas ASC, m.nama_mapel ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([
            ":guru_id" => $guru_id,
            ":tahun_id" => $tahun_id,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil jadwal mengajar guru untuk hari ini
     * Digunakan untuk Dashboard Guru
     * 
     * @param int $guru_id
     * @param string $hari (nama hari: Senin, Selasa, dst)
     * @param int|null $tahun_id (opsional, jika tidak disediakan ambil tahun aktif)
     * @return array
     */
    public static function getJadwalGuruHariIni($guru_id, $hari, $tahun_id = null)
    {
        $instance = new static();
        
        // Jika tahun_id tidak diberikan, cari tahun aktif
        if ($tahun_id === null) {
            // Asumsikan ada fungsi atau query untuk tahun aktif
            // Untuk sekarang, kita ambil tahun paling terbaru
            $queryTahun = "SELECT tahun_id FROM tahun_ajaran ORDER BY tahun_id DESC LIMIT 1";
            $stmtTahun = $instance->conn->prepare($queryTahun);
            $stmtTahun->execute();
            $resultTahun = $stmtTahun->fetch(PDO::FETCH_ASSOC);
            $tahun_id = $resultTahun['tahun_id'] ?? null;
        }
        
        if ($tahun_id === null) {
            return [];
        }
        
        $sql = "SELECT
                    j.*,
                    k.nama_kelas,
                    m.nama_mapel,
                    m.kode_mapel,
                    g.nama_lengkap as nama_guru
                FROM {$instance->table} j
                JOIN kelas k ON j.kelas_id = k.kelas_id
                JOIN mata_pelajaran m ON j.mapel_id = m.mapel_id
                JOIN guru g ON j.guru_id = g.guru_id
                WHERE j.guru_id = :guru_id
                AND j.hari = :hari
                AND j.tahun_id = :tahun_id
                ORDER BY j.jam_mulai ASC";

        $stmt = $instance->conn->prepare($sql);
        $stmt->execute([
            ':guru_id' => $guru_id,
            ':hari' => $hari,
            ':tahun_id' => $tahun_id
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}