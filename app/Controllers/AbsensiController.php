<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Absensi.php";
require_once __DIR__ . "/../Models/Jadwal.php";
require_once __DIR__ . "/../Models/Kelas.php";
require_once __DIR__ . "/../Models/Siswa.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Model;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class AbsensiController extends Controller
{
    // Show today's schedule for the teacher
    public function index()
    {
        // 1. Determine active Academic Year
        $activeTahun = TahunAjaran::getActive();

        if (!$activeTahun) {
            $this->redirect("dashboard")->with(
                "error",
                "Belum ada Tahun Ajaran aktif."
            );
            exit();
        }

        // 2. Identify the Guru
        // Assuming user_id is in session. detailed role check needed.
        // For now, assume user_id is linked to a Guru record
        $guru = Guru::where("user_id", $_SESSION["user_id"])->first();

        if (!$guru) {
            // Handle error: User is not linked to a Guru record
            $this->redirect("dashboard")->with(
                "error",
                "Data Guru tidak ditemukan."
            );
            exit();
        }

        // 3. Determine Day
        $todayEnglish = date("l");
        $hariIni = ENUM["HARI"][$todayEnglish] ?? "Minggu";

        // 4. Fetch Schedules
        $jadwal = Jadwal::getJadwalGuru(
            $guru["guru_id"],
            $activeTahun["tahun_id"],
            $hariIni
        );

        // Check submission status for each schedule
        foreach ($jadwal as &$j) {
            $absensi = Absensi::checkExisting($j["jadwal_id"], date("Y-m-d"));
            $j["status_absensi"] = $absensi
                ? ENUM["STATUS"][$absensi["status_validasi"]]
                : ENUM["STATUS"]["Belum Input"];
            $j["absensi_id"] = $absensi ? $absensi["absensi_id"] : null;
        }

        if (!$jadwal) {
            setAlert("info", "Tidak ada jadwal mengajar untuk Anda hari ini.");
        }

        $data = [
            "title" => "Input Absensi - " . $hariIni . ", " . date("d M Y"),
            "jadwal" => $jadwal,
            "hari" => $hariIni,
            "tanggal" => date("Y-m-d"),
        ];

        $this->view("akademik/absensi/index", $data);
    }

    // Show Input Form
    public function create()
    {
        $jadwal_id = $_GET["jadwal_id"] ?? null;
        if (!$jadwal_id) {
            $this->redirect("absensi")->with(
                "info",
                "Anda tidak memiliki jadwal mengajar di tahun ajaran ini."
            );
            exit();
        }

        $jadwal = Jadwal::findWithDetails($jadwal_id);
        if (!$jadwal) {
            $this->redirect("absensi")->with("error", "Jadwal tidak ditemukan");
            exit();
        }

        // Cek existing
        $absensi = Absensi::checkExisting($jadwal_id, date("Y-m-d"));
        // Load default siswa list
        $siswa = Siswa::getByKelas($jadwal["kelas_id"]);

        // Default values for form
        $absensiData = null;
        $detailsMap = [];
        if ($absensi) {
            if (
                $absensi["status_validasi"] === "Valid" ||
                $absensi["status_validasi"] === "Pending"
            ) {
                $this->redirect("absensi")->with(
                    "info",
                    "Absensi sudah diinput. Status: " .
                        $absensi["status_validasi"]
                );
                exit();
            }

            if ($absensi["status_validasi"] === "Rejected") {
                // Allow edit. Load existing details to map status
                $absensiData = $absensi;
                $absensiDetails = Absensi::getDetails($absensi["absensi_id"]);
                foreach ($absensiDetails as $d) {
                    $detailsMap[$d["siswa_id"]] = $d["status_kehadiran"];
                }
                // Override catatan harian default with existing
                $jadwal["catatan_harian_value"] = $absensi["catatan_harian"];
            }
        }

        $data = [
            "title" =>
                "Form Absensi: " .
                $jadwal["nama_kelas"] .
                " - " .
                $jadwal["nama_mapel"],
            "jadwal" => $jadwal,
            "siswa" => $siswa,
            "tanggal" => date("Y-m-d"),
            "absensi" => $absensiData, // contains alasan_penolakan, status_validasi
            "savedDetails" => $detailsMap,
        ];

        $this->view("akademik/absensi/create", $data);
    }

    // Store Attendance
    public function store()
    {
        $jadwal_id = $_POST["jadwal_id"];
        $tanggal = $_POST["tanggal"]; // Usually curdate
        $catatan = $_POST["catatan_harian"] ?? null;
        $status = $_POST["status_kehadiran"] ?? []; // Array of siswa_id => status

        if (empty($status)) {
            $this->redirect("absensi/create?jadwal_id=" . $jadwal_id)->with(
                "error",
                "Tidak ada data siswa."
            );
            exit();
        }

        $headerData = [
            "jadwal_id" => $jadwal_id,
            "tanggal" => $tanggal,
            "catatan_harian" => $catatan,
        ];

        $dataAbsensiMany = [];
        foreach ($status as $siswa_id => $st) {
            $dataAbsensiMany[] = [
                "siswa_id" => $siswa_id,
                "status_kehadiran" => $st,
            ];
        }

        $model = new Absensi();

        // Check if updating
        $absensi = Absensi::checkExisting($jadwal_id, $tanggal);

        if ($absensi && $absensi["status_validasi"] === "Valid") {
            // Block other updates for now
            $this->redirect("absensi/create")->with(
                "error",
                "Data sudah ada dan tidak dalam status revisi."
            );
            exit();
        } elseif ($absensi && $absensi["status_validasi"] === "Rejected") {
            // Regisi Flow
            $resultAbsensi = Absensi::submit(
                $headerData,
                $dataAbsensiMany,
                true,
                $absensi["absensi_id"]
            );
        } else {
            $resultAbsensi = Absensi::submit($headerData, $dataAbsensiMany);
        }

        if ($resultAbsensi["status"]) {
            $this->redirect("absensi/create?jadwal_id=" . $jadwal_id)->with(
                "error",
                $resultAbsensi["message"]
            );
        }

        $this->redirect("absensi")->with(
            "success",
            "Absensi berhasil disimpan."
        );
    }

    // Dashboard for Validation
    public function validationList()
    {
        $guru = Guru::findByUserId($_SESSION["user_id"]);
        if (!$guru) {
            $this->redirect("dashboard")->with(
                "error",
                "Data Guru tidak ditemukan."
            );
            exit();
        }

        // 2. Find Managed Class
        $kelas = Kelas::getByWaliKelas($guru["guru_id"]);
        if (!$kelas) {
            // Not a wali kelas
            $this->view("akademik/absensi/validationList", [
                "title" => "Validasi Absensi",
                "isWaliKelas" => false,
                "pending" => [],
            ])->with(
                "warning",
                "Anda tidak terdaftar sebagai Wali Kelas untuk kelas manapun."
            );
            return;
        }

        // 3. Get Pending Absensi
        $pending = Absensi::getPendingByKelas($kelas["kelas_id"]);

        if (empty($pending)) {
            setFlash(
                "info",
                "Tidak ada pengajuan absensi yang perlu divalidasi saat ini."
            );
        }

        $data = [
            "title" => "Validasi Absensi - Kelas " . $kelas["nama_kelas"],
            "isWaliKelas" => true,
            "kelas" => $kelas,
            "pending" => $pending,
        ];

        $this->view("akademik/absensi/validationList", $data);
    }

    // Show Details (and Approve/Reject form)
    public function validationReview()
    {
        $absensi_id = $_GET["absensi_id"] ?? null;
        if (!$absensi_id) {
            $this->redirect("absensi/validasi");
            exit();
        }

        $absensi = Absensi::findWithDetails($absensi_id);
        if (!$absensi) {
            $this->redirect("absensi/validasi")->with(
                "error",
                "Data absensi tidak ditemukan."
            );
            exit();
        }

        $details = Absensi::getDetails($absensi_id);

        $data = [
            "title" => "Detail Validasi Absensi",
            "absensi" => $absensi,
            "details" => $details,
        ];

        $this->view("akademik/absensi/validationReview", $data);
    }

    // Process Approval
    public function validationProcess()
    {
        $absensi_id = $_POST["absensi_id"];
        $action = $_POST["action"];

        // 1. Ambil Data Absensi & Jadwal untuk tahu ini Kelas berapa
        $absensi = Absensi::findWithDetails($absensi_id);
        if (!$absensi) {
            /* Error handling */
        }

        // 2. Cek Otoritas: Apakah user login adalah Wali Kelas dari kelas ini?
        $guru = Guru::findByUserId($_SESSION["user_id"]);
        $kelas = Kelas::getByWaliKelas($guru["guru_id"]);

        if (!$kelas || $kelas["kelas_id"] != $absensi["kelas_id"]) {
            $this->redirect("absensi/validasi")->with(
                "error",
                "Anda tidak berhak memvalidasi kelas ini."
            );
            exit();
        }

        $status = $action === "approve" ? "Valid" : "Rejected";

        $result = Absensi::update($absensi_id, [
            "status_validasi" => $status,
            "alasan_penolakan" =>
                $action === "reject" ? $_POST["alasan_penolakan"] : null,
        ]);

        if ($result["status"]) {
            $this->redirect("absensi/validasi")->with(
                "success",
                "Status absensi berhasil diperbarui menjadi " . $status
            );
        } else {
            $this->redirect("absensi/validasi/review")->with(
                "error",
                "Gagal memperbarui status: " . $result["message"]
            );
        }
    }

    /**
     * Menghitung persentase kehadiran seluruh sekolah (Global)
     * Digunakan untuk Dashboard Kepsek
     */
    public static function getGlobalAttendancePercentage()
    {
        $instance = new static();
        
        // Hitung Total Data Absensi & Total Hadir
        $query = "SELECT 
                    COUNT(*) as total_record,
                    SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as total_hadir
                  FROM detail_absensi";

        $stmt = $instance->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = $result['total_record'] ?? 0;
        $hadir = $result['total_hadir'] ?? 0;

        if ($total == 0) return 0;

        // Hitung Persentase (Bulatkan ke 1 desimal)
        return round(($hadir / $total) * 100, 1);
    }

    /**
     * Mendapatkan daftar siswa dengan jumlah Alpa tertinggi
     * Digunakan untuk Early Warning System di Dashboard Kepsek
     */
    public static function getSiswaBermasalah($limit = 5)
    {
        $instance = new static();

        $query = "SELECT 
                    s.nama_lengkap as nama,
                    k.nama_kelas as kelas,
                    COUNT(da.detail_id) as alpa
                  FROM detail_absensi da
                  JOIN siswa s ON da.siswa_id = s.siswa_id
                  JOIN kelas k ON s.kelas_id = k.kelas_id
                  WHERE da.status_kehadiran = 'Alpa'
                  GROUP BY s.siswa_id
                  HAVING alpa > 0
                  ORDER BY alpa DESC
                  LIMIT :limit";

        $stmt = $instance->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ... method countPendingByKelas (jika belum ada) ...
    public static function countPendingByKelas($kelas_id)
    {
        $instance = new static();
        $query = "SELECT COUNT(*) FROM absensi a 
                  JOIN jadwal_pelajaran j ON a.jadwal_id = j.jadwal_id
                  WHERE j.kelas_id = :kelas_id AND a.status_validasi = 'Pending'";
        
        $stmt = $instance->conn->prepare($query);
        $stmt->execute([':kelas_id' => $kelas_id]);
        return $stmt->fetchColumn();
    }

}
