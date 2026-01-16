<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Nilai.php";
require_once __DIR__ . "/../Models/DetailNilai.php";
require_once __DIR__ . "/../Models/Jadwal.php";
require_once __DIR__ . "/../Models/Kelas.php";
require_once __DIR__ . "/../Models/Siswa.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/../Models/Mapel.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Model;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Mapel;

class NilaiController extends Controller
{
    /**
     * HALAMAN UTAMA GURU:
     * Menampilkan daftar Kelas & Mapel yang diajar untuk dipilih.
     */
    public function index()
    {
        // 1. Cek Tahun Ajaran Aktif
        $activeTahun = TahunAjaran::getActive();
        if (!$activeTahun) {
            $this->redirect("dashboard")->with(
                "error",
                "Belum ada Tahun Ajaran aktif."
            );
            exit();
        }

        // 2. Identifikasi Guru yang Login
        $guru = Guru::where("user_id", $_SESSION["user_id"])->first();
        if (!$guru) {
            $this->redirect("dashboard")->with(
                "error",
                "Data Guru tidak ditemukan."
            );
            exit();
        }

        // 3. Ambil Daftar Kelas & Mapel yang diajar (DISTINCT)
        // Kita pakai fungsi yang sudah kita perbaiki di Jadwal.php sebelumnya
        $listMengajar = Jadwal::getListKelasMapelGuru(
            $guru["guru_id"],
            $activeTahun["tahun_id"]
        );

        // 4. Cek Status Input Nilai untuk setiap item
        foreach ($listMengajar as &$item) {
            $existing = Nilai::checkExisting(
                $item["kelas_id"],
                $item["mapel_id"],
                $activeTahun["tahun_id"]
            );

            if ($existing) {
                $item["status_nilai"] = $existing["status_validasi"]; // Draft/Submitted/Final
                $item["nilai_id"] = $existing["nilai_id"];
            } else {
                $item["status_nilai"] = "Belum Input";
                $item["nilai_id"] = null;
            }
        }

        $data = [
            "title" => "Input Nilai Siswa",
            "listMengajar" => $listMengajar,
            "tahun" => $activeTahun,
        ];

        $this->view("akademik/nilai/index", $data);
    }

    /**
     * HALAMAN FORM INPUT:
     * Menampilkan tabel input nilai untuk satu kelas.
     */
    public function create()
    {
        // Validasi Parameter URL
        $kelas_id = $_GET["kelas_id"] ?? null;
        $mapel_id = $_GET["mapel_id"] ?? null;

        if (!$kelas_id || !$mapel_id) {
            $this->redirect("nilai")->with("error", "Parameter tidak lengkap.");
            exit();
        }

        $activeTahun = TahunAjaran::getActive();

        // Ambil Info Header (Nama Kelas & Mapel)
        $kelas = Kelas::find($kelas_id);
        $mapel = Mapel::find($mapel_id); // Asumsi ada Model Mapel

        // Cek apakah Data Nilai sudah pernah diinput?
        $existingHeader = Nilai::checkExisting(
            $kelas_id,
            $mapel_id,
            $activeTahun["tahun_id"]
        );

        // Default Data
        $siswaList = Siswa::getByKelas($kelas_id);
        $savedDetails = []; // Untuk menampung nilai yang sudah tersimpan (jika ada)
        $catatanRevisi = null;

        // LOGIKA EDIT / REVISI
        if ($existingHeader) {
            // Jika sudah FINAL, blokir edit
            if ($existingHeader["status_validasi"] === "Final") {
                $this->redirect("nilai")->with(
                    "info",
                    "Nilai sudah Final dan tidak dapat diubah."
                );
                exit();
            }

            // Jika Draft/Revisi, ambil detailnya untuk diisi ke form
            $details = Nilai::getDetails($existingHeader["nilai_id"]);
            foreach ($details as $d) {
                $savedDetails[$d["siswa_id"]] = [
                    "tugas" => $d["nilai_tugas"],
                    "uts" => $d["nilai_uts"],
                    "uas" => $d["nilai_uas"],
                    "akhir" => $d["nilai_akhir"],
                ];
            }
            $catatanRevisi = $existingHeader["catatan_revisi"];
        }

        $data = [
            "title" =>
                "Form Nilai: " .
                $kelas["nama_kelas"] .
                " - " .
                $mapel["nama_mapel"],
            "kelas" => $kelas,
            "mapel" => $mapel,
            "tahun" => $activeTahun,
            "siswa" => $siswaList,
            "existing" => $existingHeader, // null jika belum ada
            "savedDetails" => $savedDetails,
            "catatanRevisi" => $catatanRevisi,
        ];

        $this->view("akademik/nilai/create", $data);
    }

    /**
     * PROSES SIMPAN (STORE):
     * Menghitung nilai akhir dan menyimpan ke DB.
     */
    public function store()
    {
        $activeTahun = TahunAjaran::getActive();
        $guru = Guru::where("user_id", $_SESSION["user_id"])->first();

        // Ambil Input
        $kelas_id = $_POST["kelas_id"];
        $mapel_id = $_POST["mapel_id"];
        $inputNilai = $_POST["nilai"] ?? []; // Array [siswa_id => [tugas, uts, uas]]

        if (empty($inputNilai)) {
            $this->redirect("nilai")->with(
                "error",
                "Tidak ada data nilai yang dikirim."
            );
            exit();
        }

        // Siapkan Data Header
        $headerData = [
            "tahun_id" => $activeTahun["tahun_id"],
            "kelas_id" => $kelas_id,
            "mapel_id" => $mapel_id,
            "guru_id" => $guru["guru_id"],
        ];

        // Siapkan Data Detail (Looping & Hitung)
        $dataNilaiMany = [];
        foreach ($inputNilai as $siswa_id => $skor) {
            $tugas = floatval($skor["tugas"]);
            $uts = floatval($skor["uts"]);
            $uas = floatval($skor["uas"]);

            // Rumus: Tugas 30%, UTS 30%, UAS 40%
            $akhir = $tugas * 0.3 + $uts * 0.3 + $uas * 0.4;

            $dataNilaiMany[] = [
                "siswa_id" => $siswa_id,
                "nilai_tugas" => $tugas,
                "nilai_uts" => $uts,
                "nilai_uas" => $uas,
                "nilai_akhir" => round($akhir, 2), // Bulatkan 2 desimal
            ];
        }

        // Cek Update atau Insert Baru
        $existing = Nilai::checkExisting(
            $kelas_id,
            $mapel_id,
            $activeTahun["tahun_id"]
        );

        $revise = false;
        $nilaiId = null;

        if ($existing) {
            // Update mode
            $revise = true;
            $nilaiId = $existing["nilai_id"];
        }

        // Panggil Model (Transactional)
        $result = Nilai::submit($headerData, $dataNilaiMany, $revise, $nilaiId);

        if ($result["status"]) {
            $this->redirect("nilai")->with(
                "success",
                "Nilai berhasil disimpan."
            );
        } else {
            // Redirect back with error
            $url = "nilai/create?kelas_id=$kelas_id&mapel_id=$mapel_id";
            $this->redirect($url)->with("error", $result["message"]);
        }
    }

    /**
     * DASHBOARD WALI KELAS:
     * Melihat daftar nilai yang perlu divalidasi.
     */
    public function validationList()
    {
        $guru = Guru::findByUserId($_SESSION["user_id"]);

        // 1. Cek apakah dia Wali Kelas?
        $kelasBinaan = Kelas::getByWaliKelas($guru["guru_id"]);

        if (!$kelasBinaan) {
            // Jika bukan wali kelas, tampilkan view kosong/info
            $this->view("akademik/nilai/validationList", [
                "title" => "Validasi Nilai",
                "isWaliKelas" => false,
                "pending" => [],
            ]);
            return;
        }

        // 2. Ambil Nilai yang statusnya 'Draft' milik kelas binaannya
        $pending = Nilai::getPendingByKelas($kelasBinaan["kelas_id"]);

        $data = [
            "title" => "Validasi Nilai - Kelas " . $kelasBinaan["nama_kelas"],
            "isWaliKelas" => true,
            "kelas" => $kelasBinaan,
            "pending" => $pending,
        ];

        $this->view("akademik/nilai/validationList", $data);
    }

    /**
     * DETAIL VALIDASI:
     * Melihat rincian nilai siswa sebelum di-approve.
     */
    public function validationReview()
    {
        $nilai_id = $_GET["nilai_id"] ?? null;
        if (!$nilai_id) {
            $this->redirect("nilai/validasi");
            exit();
        }

        // Ambil Header & Detail
        $header = Nilai::findWithInfo($nilai_id);
        $details = Nilai::getDetails($nilai_id);

        if (!$header) {
            $this->redirect("nilai/validasi")->with(
                "error",
                "Data tidak ditemukan."
            );
            exit();
        }

        $data = [
            "title" => "Review Nilai: " . $header["nama_mapel"],
            "header" => $header,
            "details" => $details,
        ];

        $this->view("akademik/nilai/validationReview", $data);
    }

    /**
     * PROSES APPROVE / REJECT
     */
    public function validationProcess()
    {
        $nilai_id = $_POST["nilai_id"];
        $action = $_POST["action"]; // 'approve' or 'reject'
        $alasan = $_POST["catatan_revisi"] ?? null;

        // 1. Validasi Keamanan (Apakah benar Wali Kelas dari kelas ini?)
        $nilai = Nilai::find($nilai_id);
        $guru = Guru::findByUserId($_SESSION["user_id"]);
        $kelasBinaan = Kelas::getByWaliKelas($guru["guru_id"]);

        if (!$kelasBinaan || $kelasBinaan["kelas_id"] != $nilai["kelas_id"]) {
            $this->redirect("nilai/validasi")->with(
                "error",
                "Akses ditolak. Anda bukan Wali Kelas ini."
            );
            exit();
        }

        // 2. Tentukan Status Baru
        // Jika Approve -> Final
        // Jika Reject -> Draft (Tapi dikasih catatan revisi)
        // Catatan: Anda bisa pakai status 'Rejected' di enum jika mau lebih eksplisit.
        // Sesuai diskusi Absensi, kita pakai logika: Reject = Balik ke Draft + Note.

        $statusBaru = $action === "approve" ? "Final" : "Draft"; // Atau 'Rejected' jika enum mendukung

        $updateData = [
            "status_validasi" => $statusBaru,
            "catatan_revisi" => $action === "reject" ? $alasan : null,
        ];

        $result = Nilai::update($nilai_id, $updateData);

        if ($result["status"]) {
            $msg =
                $action === "approve"
                    ? "Nilai berhasil divalidasi (Final)."
                    : "Nilai dikembalikan ke Guru Mapel (Revisi).";
            $this->redirect("nilai/validasi")->with("success", $msg);
        } else {
            $this->redirect("nilai/validasi")->with(
                "error",
                "Gagal memproses validasi."
            );
        }
    }
}
