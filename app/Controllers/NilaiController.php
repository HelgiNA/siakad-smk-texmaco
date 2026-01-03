<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Nilai.php";
require_once __DIR__ . "/../Models/Jadwal.php";
require_once __DIR__ . "/../Models/Siswa.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/../Models/Mapel.php";
require_once __DIR__ . "/../Models/Kelas.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;

class NilaiController extends Controller
{
    /**
     * FASE 1: Menampilkan pilihan Kelas & Mapel yang diajar Guru
     * 
     * Guru login → klik "Input Nilai" → melihat dropdown kelas yang diajarnya
     * 
     * Query: Cari semua jadwal pelajaran guru ini di tahun ajaran aktif
     * Ambil unique kombinasi: Kelas + Mapel
     */
    public function create()
    {
        // 1. Tentukan Tahun Ajaran Aktif
        $activeTahun = TahunAjaran::getActive();
        if (!$activeTahun) {
            setAlert("error", "Belum ada Tahun Ajaran aktif.");
            return $this->redirect("dashboard");
        }

        // 2. Identifikasi Guru
        $guru = Guru::where("user_id", "=", $_SESSION["user_id"])->first();
        if (!$guru) {
            setAlert("error", "Data Guru tidak ditemukan.");
            return $this->redirect("dashboard");
        }

        // 3. Query Jadwal Guru di Tahun Aktif
        // Ambil semua jadwal pelajaran guru ini dengan detail kelas & mapel
        $jadwal = Jadwal::getByGuruWithDetails($guru["guru_id"], $activeTahun["tahun_id"]);

        // 4. Jika tidak ada jadwal, tampilkan pesan
        if (!$jadwal) {
            setAlert("info", "Anda tidak memiliki jadwal mengajar di tahun ajaran ini.");
            return $this->redirect("dashboard");
        }

        // 5. Buat array unique (kelas + mapel) untuk dropdown
        $kelasMapelList = [];
        foreach ($jadwal as $j) {
            $key = $j["kelas_id"] . "_" . $j["mapel_id"];
            if (!isset($kelasMapelList[$key])) {
                $kelasMapelList[$key] = [
                    "jadwal_id"  => $j["jadwal_id"],
                    "kelas_id"   => $j["kelas_id"],
                    "mapel_id"   => $j["mapel_id"],
                    "nama_kelas" => $j["nama_kelas"],
                    "nama_mapel" => $j["nama_mapel"]
                ];
            }
        }

        $data = [
            "title"           => "Pilih Kelas & Mapel untuk Input Nilai",
            "kelasMapelList"  => array_values($kelasMapelList), // reset index
            "activeTahun"     => $activeTahun
        ];

        $this->view("akademik/nilai/create", $data);
    }

    /**
     * FASE 2: Menampilkan Form Matriks Nilai
     * 
     * Guru memilih kelas & mapel → sistem tampilkan tabel siswa dengan input nilai
     * 
     * Query:
     * 1. Ambil daftar siswa di kelas
     * 2. Ambil nilai yang sudah pernah disimpan sebelumnya (jika ada)
     */
    public function input()
    {
        // Ambil parameter dari query string
        $kelas_id = $_GET["kelas_id"] ?? null;
        $mapel_id = $_GET["mapel_id"] ?? null;

        if (!$kelas_id || !$mapel_id) {
            setAlert("error", "Parameter Kelas atau Mapel tidak valid.");
            return $this->redirect("nilai/create");
        }

        // 1. Tentukan Tahun Ajaran Aktif
        $activeTahun = TahunAjaran::getActive();
        if (!$activeTahun) {
            setAlert("error", "Belum ada Tahun Ajaran aktif.");
            return $this->redirect("dashboard");
        }

        // 2. Identifikasi Guru (untuk verifikasi kepemilikan)
        $guru = Guru::where("user_id", "=", $_SESSION["user_id"])->first();
        if (!$guru) {
            setAlert("error", "Data Guru tidak ditemukan.");
            return $this->redirect("dashboard");
        }

        // 3. Verifikasi guru mengajar mapel di kelas ini (dari jadwal)
        $jadwal = Jadwal::where("guru_id", "=", $guru["guru_id"])
                         ->where("kelas_id", "=", $kelas_id)
                         ->where("mapel_id", "=", $mapel_id)
                         ->where("tahun_id", "=", $activeTahun["tahun_id"])
                         ->first();

        if (!$jadwal) {
            setAlert("error", "Anda tidak mengajar mapel ini di kelas tersebut.");
            return $this->redirect("nilai/create");
        }

        // 4. Query Paralel: Ambil Siswa & Nilai yang sudah ada
        // Query 1: Daftar siswa di kelas
        $siswa = Siswa::getByKelas($kelas_id);

        // Query 2: Nilai yang sudah pernah disimpan
        $nilaiExisting = Nilai::getByKelasMapel($kelas_id, $mapel_id, $activeTahun["tahun_id"]);

        // 5. Map nilai existing ke array keyed by siswa_id untuk mudah diakses di view
        $nilaiMap = [];
        foreach ($nilaiExisting as $n) {
            $nilaiMap[$n["siswa_id"]] = $n;
        }

        // 6. Ambil detail kelas & mapel untuk ditampilkan
        $kelas = Kelas::find($kelas_id);
        $mapel = Mapel::find($mapel_id);

        if (!$kelas || !$mapel) {
            setAlert("error", "Data Kelas atau Mapel tidak ditemukan.");
            return $this->redirect("nilai/create");
        }

        if (!$siswa) {
            setAlert("info", "Tidak ada siswa di kelas ini.");
            // Tetap tampilkan form kosong agar guru bisa menyimpan nilai 0
        }

        $data = [
            "title"        => "Input Nilai - " . $kelas["nama_kelas"] . " | " . $mapel["nama_mapel"],
            "siswa"        => $siswa ?? [],
            "nilaiMap"     => $nilaiMap,
            "kelas_id"     => $kelas_id,
            "mapel_id"     => $mapel_id,
            "tahun_id"     => $activeTahun["tahun_id"],
            "nama_kelas"   => $kelas["nama_kelas"],
            "nama_mapel"   => $mapel["nama_mapel"],
            "activeTahun"  => $activeTahun
        ];

        $this->view("akademik/nilai/input", $data);
    }

    /**
     * FASE 3 & 4: Validasi, Kalkulasi & Simpan (UPSERT)
     * 
     * Guru submit form → server:
     * 1. Validasi range nilai (0-100)
     * 2. Kalkulasi Nilai Akhir = (Tugas*20%) + (UTS*30%) + (UAS*50%)
     * 3. UPSERT ke database (update jika ada, insert jika baru)
     * 4. Set status_validasi = 'Draft' (belum finalisasi)
     */
    public function store()
    {
        // Ambil POST data
        $kelas_id = $_POST["kelas_id"] ?? null;
        $mapel_id = $_POST["mapel_id"] ?? null;
        $tahun_id = $_POST["tahun_id"] ?? null;

        // Ambil array nilai per siswa dari form
        // Format: nilai[siswa_id] = ['tugas' => x, 'uts' => y, 'uas' => z]
        $nilaiData = $_POST["nilai"] ?? [];

        if (!$kelas_id || !$mapel_id || !$tahun_id) {
            setAlert("error", "Data Kelas, Mapel, atau Tahun tidak valid.");
            return $this->redirect("nilai/create");
        }

        if (empty($nilaiData)) {
            setAlert("error", "Tidak ada data nilai yang dikirim.");
            return $this->redirect("nilai/input?kelas_id=$kelas_id&mapel_id=$mapel_id");
        }

        // 1. Verifikasi guru mengajar mapel ini (keamanan)
        $guru = Guru::where("user_id", "=", $_SESSION["user_id"])->first();
        $jadwal = Jadwal::where("guru_id", "=", $guru["guru_id"])
                         ->where("kelas_id", "=", $kelas_id)
                         ->where("mapel_id", "=", $mapel_id)
                         ->where("tahun_id", "=", $tahun_id)
                         ->first();

        if (!$jadwal) {
            setAlert("error", "Anda tidak berhak mengisi nilai untuk mapel ini.");
            return $this->redirect("nilai/create");
        }

        // 1.5. SECURITY: Cek apakah ada nilai yang sudah dikunci (Final)
        // Jika ada, guru tidak boleh edit lagi
        foreach ($nilaiData as $siswa_id => $nilai) {
            if (Nilai::isLocked($siswa_id, $mapel_id, $tahun_id)) {
                setAlert("error", "Nilai sudah difinalisasi oleh Wali Kelas. Anda tidak bisa mengubahnya lagi. Hubungi Wali Kelas untuk revisi.");
                return $this->redirect("nilai/create");
            }
        }

        // 2. Persiapkan data untuk batch save
        $dataBatch = [];
        foreach ($nilaiData as $siswa_id => $nilai) {
            // Sanitasi input
            $tugas = isset($nilai['tugas']) ? (float) $nilai['tugas'] : 0;
            $uts   = isset($nilai['uts']) ? (float) $nilai['uts'] : 0;
            $uas   = isset($nilai['uas']) ? (float) $nilai['uas'] : 0;

            // Validasi range (0-100)
            if ($tugas < 0 || $tugas > 100) {
                setAlert("error", "Nilai Tugas harus 0-100. Cek siswa ID: $siswa_id");
                return $this->redirect("nilai/input?kelas_id=$kelas_id&mapel_id=$mapel_id");
            }
            if ($uts < 0 || $uts > 100) {
                setAlert("error", "Nilai UTS harus 0-100. Cek siswa ID: $siswa_id");
                return $this->redirect("nilai/input?kelas_id=$kelas_id&mapel_id=$mapel_id");
            }
            if ($uas < 0 || $uas > 100) {
                setAlert("error", "Nilai UAS harus 0-100. Cek siswa ID: $siswa_id");
                return $this->redirect("nilai/input?kelas_id=$kelas_id&mapel_id=$mapel_id");
            }

            $dataBatch[] = [
                'siswa_id' => (int) $siswa_id,
                'mapel_id' => (int) $mapel_id,
                'tahun_id' => (int) $tahun_id,
                'tugas'    => $tugas,
                'uts'      => $uts,
                'uas'      => $uas
            ];
        }

        // 3. Simpan batch ke database (dengan UPSERT)
        $result = Nilai::saveBatch($dataBatch);

        if ($result['status']) {
            setAlert("success", "Nilai berhasil disimpan dengan status Draft. Anda masih bisa mengubahnya nanti.");
            return $this->redirect("nilai/create");
        } else {
            setAlert("error", $result['message']);
            return $this->redirect("nilai/input?kelas_id=$kelas_id&mapel_id=$mapel_id");
        }
    }

    /**
     * Helper: Validasi apakah nilai dalam range yang valid
     */
    private function validateNilai($nilai)
    {
        return is_numeric($nilai) && $nilai >= 0 && $nilai <= 100;
    }
}
