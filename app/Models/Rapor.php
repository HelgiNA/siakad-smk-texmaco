<?php
namespace App\Models;

require_once __DIR__ . '/Model.php';

use PDO;
use PDOException;

class Rapor extends Model
{/**
     * Ambil Biodata Siswa Lengkap + Wali Kelas + Kepala Sekolah
     */
    public static function getBiodata($siswa_id)
    {
        $instance = new static();

        // 1. Query Utama: Siswa + Kelas + Wali Kelas
        $querySiswa = "SELECT 
                        s.siswa_id, s.user_id, s.kelas_id, s.nis, s.nisn, 
                        s.nama_lengkap, s.tanggal_lahir, s.alamat,
                        k.nama_kelas, k.tingkat, k.jurusan,
                        g.nama_lengkap AS guru_wali,
                        g.nip AS guru_nip
                      FROM siswa s
                      LEFT JOIN kelas k ON s.kelas_id = k.kelas_id
                      LEFT JOIN guru g ON k.guru_wali_id = g.guru_id
                      WHERE s.siswa_id = :siswa_id";

        $stmt = $instance->conn->prepare($querySiswa);
        $stmt->execute([':siswa_id' => $siswa_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return false;

        // 2. Query Kedua: Ambil Data Kepala Sekolah
        // Mencari guru yang user-nya memiliki role 'Kepsek'
        $queryKepsek = "SELECT g.nama_lengkap, g.nip 
                        FROM guru g
                        JOIN users u ON g.user_id = u.user_id
                        WHERE u.role = 'Kepsek' AND u.status_aktif = 1
                        LIMIT 1";
        
        $stmtKepsek = $instance->conn->prepare($queryKepsek);
        $stmtKepsek->execute();
        $kepsek = $stmtKepsek->fetch(PDO::FETCH_ASSOC);

        // 3. Gabungkan Data (Inject ke array biodata)
        if ($kepsek) {
            $data['kepala_sekolah'] = $kepsek['nama_lengkap'];
            $data['kepsek_nip']     = $kepsek['nip'];
        } else {
            // Default jika data kepsek belum ada di database
            $data['kepala_sekolah'] = '.........................';
            $data['kepsek_nip']     = '-';
        }

        return $data;
    }

    /**
     * Ambil Nilai Akhir (Join Header Nilai + Detail Nilai)
     */
    public static function getNilaiAkademik($siswa_id, $tahun_id)
    {
        $instance = new static();
        // JOIN: detail_nilai -> nilai (header) -> mata_pelajaran
        $query = "SELECT 
                    dn.nilai_akhir,
                    m.nama_mapel, 
                    m.kelompok, 
                    m.kkm
                  FROM detail_nilai dn
                  JOIN nilai n ON dn.nilai_id = n.nilai_id
                  JOIN mata_pelajaran m ON n.mapel_id = m.mapel_id
                  WHERE dn.siswa_id = :siswa_id
                  AND n.tahun_id = :tahun_id
                  ORDER BY m.kelompok ASC, m.nama_mapel ASC";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([
            ':siswa_id' => $siswa_id, 
            ':tahun_id' => $tahun_id
        ]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Grouping A, B, C
        $groups = ['A' => [], 'B' => [], 'C' => []];
        foreach ($rows as $r) {
            $kel = strtoupper($r['kelompok']);
            // C1, C2, C3 masuk ke C
            if (strpos($kel, 'C') === 0) { 
                $groups['C'][] = $r;
            } elseif (isset($groups[$kel])) {
                $groups[$kel][] = $r;
            } else {
                $groups['B'][] = $r; // Default fallback
            }
        }
        return $groups;
    }

    /**
     * Ambil Catatan Wali Kelas (Sikap & Akademik)
     */
    public static function getCatatan($siswa_id, $tahun_id)
    {
        $instance = new static();
        $query = "SELECT * FROM catatan_rapor 
                  WHERE siswa_id = :siswa_id AND tahun_id = :tahun_id";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':siswa_id' => $siswa_id, ':tahun_id' => $tahun_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Simpan/Update Catatan Wali Kelas
     */
    public static function saveCatatan($data)
    {
        $instance = new static();
        
        // Cek apakah sudah ada?
        $existing = self::getCatatan($data['siswa_id'], $data['tahun_id']);

        if ($existing) {
            // Update
            $sql = "UPDATE catatan_rapor SET 
                    catatan_sikap = :sikap, 
                    catatan_akademik = :akademik,
                    guru_wali_id = :guru_id,
                    tgl_input = :tgl
                    WHERE catatan_id = :id";
            $params = [
                ':sikap' => $data['catatan_sikap'],
                ':akademik' => $data['catatan_akademik'],
                ':guru_id' => $data['guru_wali_id'],
                ':tgl' => date('Y-m-d'),
                ':id' => $existing['catatan_id']
            ];
        } else {
            // Insert
            $sql = "INSERT INTO catatan_rapor 
                    (siswa_id, tahun_id, guru_wali_id, catatan_sikap, catatan_akademik, tgl_input)
                    VALUES (:siswa_id, :tahun_id, :guru_id, :sikap, :akademik, :tgl)";
            $params = [
                ':siswa_id' => $data['siswa_id'],
                ':tahun_id' => $data['tahun_id'],
                ':guru_id' => $data['guru_wali_id'],
                ':sikap' => $data['catatan_sikap'],
                ':akademik' => $data['catatan_akademik'],
                ':tgl' => date('Y-m-d')
            ];
        }

        $stmt = $instance->conn->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Catat Log Pencetakan ke Database
     */
    public static function logPrint($user_id, $siswa_id, $jenis = 'Rapor')
    {
        $instance = new static();
        $sql = "INSERT INTO riwayat_cetak (user_id, siswa_id, jenis_dokumen, tgl_cetak)
                VALUES (:uid, :sid, :jenis, NOW())";
        
        $stmt = $instance->conn->prepare($sql);
        $stmt->execute([
            ':uid' => $user_id,
            ':sid' => $siswa_id,
            ':jenis' => $jenis
        ]);
    }

    /**
     * Cek Absensi (Menggunakan tabel detail_absensi yg sudah diperbaiki)
     */
    public static function getAbsensi($siswa_id, $tahun_id)
    {
        $instance = new static();
        // Hitung detail_absensi berdasarkan jadwal di tahun aktif
        $query = "SELECT d.status_kehadiran, COUNT(*) as cnt
                  FROM detail_absensi d
                  JOIN absensi a ON d.absensi_id = a.absensi_id
                  JOIN jadwal_pelajaran j ON a.jadwal_id = j.jadwal_id
                  WHERE d.siswa_id = :siswa_id
                  AND j.tahun_id = :tahun_id
                  AND a.status_validasi = 'Valid' 
                  GROUP BY d.status_kehadiran";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':siswa_id' => $siswa_id, ':tahun_id' => $tahun_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = ['Hadir' => 0, 'Sakit' => 0, 'Izin' => 0, 'Alpa' => 0];
        foreach ($rows as $r) {
            if (isset($result[$r['status_kehadiran']])) {
                $result[$r['status_kehadiran']] = (int) $r['cnt'];
            }
        }
        return $result;
    }
}
