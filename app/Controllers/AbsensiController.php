<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Absensi.php";
require_once __DIR__ . "/../Models/Jadwal.php";
require_once __DIR__ . "/../Models/Siswa.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Absensi;
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
        $jadwal = Jadwal::getByGuru(
            $guru["guru_id"],
            $hariIni,
            $activeTahun["tahun_id"]
        );

        // Check submission status for each schedule
        foreach ($jadwal as &$j) {
            $existing = Absensi::checkExisting($j["jadwal_id"], date("Y-m-d"));
            $j["status_absensi"] = $existing
                ? ENUM["STATUS"][$existing["status_validasi"]]
                : ENUM["STATUS"]["Belum Input"];
            $j["absensi_id"] = $existing ? $existing["absensi_id"] : null;
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
    public function input()
    {
        $jadwal_id = $_GET["jadwal_id"] ?? null;
        if (!$jadwal_id) {
            $this->redirect("absensi");
            exit();
        }

        $jadwal = Jadwal::findWithDetails($jadwal_id);
        if (!$jadwal) {
            $this->redirect("absensi/create")->with(
                "error",
                "Jadwal tidak ditemukan"
            );
            exit();
        }

        // Cek existing
        $existing = Absensi::checkExisting($jadwal_id, date("Y-m-d"));
        // Load default siswa list
        $siswa = Siswa::getByKelas($jadwal["kelas_id"]);

        // Default values for form
        $absensiData = null;
        $detailsMap = [];
        if ($existing) {
                    if (
            $existing["status_validasi"] === "Valid" ||
            $existing["status_validasi"] === "Draft"
        ) {
            $this->redirect("absensi/create")->with(
                "info",
                "Absensi sudah diinput. Status: " . $existing["status_validasi"]
            );
            exit();
        }

        if ($existing["status_validasi"] === "Rejected") {
            // Allow edit. Load existing details to map status
            $absensiData = $existing;
            $existingDetails = Absensi::getDetails($existing["absensi_id"]);
            foreach ($existingDetails as $d) {
                $detailsMap[$d["siswa_id"]] = $d["status_kehadiran"];
            }
            // Override catatan harian default with existing
            $jadwal["catatan_harian_value"] = $existing["catatan_harian"];
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

        $this->view("akademik/absensi/input", $data);
    }

    // Store Attendance
    public function submit()
    {
        $jadwal_id = $_POST["jadwal_id"];
        $tanggal = $_POST["tanggal"]; // Usually curdate
        $catatan = $_POST["catatan_harian"] ?? "";
        $status = $_POST["status_kehadiran"] ?? []; // Array of siswa_id => status

        if (empty($status)) {
            $this->redirect("absensi/input?jadwal_id=" . $jadwal_id)->with(
                "error",
                "Tidak ada data siswa."
            );
            exit();
        }

        $detailsData = [];
        foreach ($status as $siswa_id => $st) {
            $detailsData[] = [
                "siswa_id" => $siswa_id,
                "status_kehadiran" => $st,
            ];
        }

        $headerData = [
            "jadwal_id" => $jadwal_id,
            "tanggal" => $tanggal,
            "catatan_harian" => $catatan,
        ];

        $model = new Absensi();

        // Check if updating
        $existing = Absensi::checkExisting($jadwal_id, $tanggal);

        if ($existing && $existing["status_validasi"] === "Rejected") {
            // REVISION FLOW
            $result = $model->revise(
                $existing["absensi_id"],
                $headerData,
                $detailsData
            );
        } elseif ($existing) {
            // Block other updates for now
            $this->redirect("absensi/create")->with(
                "error",
                "Data sudah ada dan tidak dalam status revisi."
            );
            exit();
        } else {
            // NEW INSERT
            $result = $model->createWithDetail($headerData, $detailsData);
        }

        if ($result["status"]) {
            $this->redirect("absensi/create")->with(
                "success",
                "Absensi berhasil disimpan."
            );
        } else {
            $this->redirect("absensi/input?jadwal_id=" . $jadwal_id)->with(
                "error",
                $result["message"]
            );
        }
    }
}
