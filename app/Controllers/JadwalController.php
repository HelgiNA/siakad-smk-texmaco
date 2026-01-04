<?php
namespace App\Controllers;

require_once __DIR__ . "/../Models/Jadwal.php";
require_once __DIR__ . "/../Models/Kelas.php";
require_once __DIR__ . "/../Models/Mapel.php";
require_once __DIR__ . "/../Models/Guru.php";
require_once __DIR__ . "/../Models/TahunAjaran.php";
require_once __DIR__ . "/Controller.php";

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;

class JadwalController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::getActive();
        $jadwal = [];

        if ($tahunAktif) {
            $jadwal = Jadwal::getAllByTahun($tahunAktif["tahun_id"]);
        }

        $data = [
            "title" => "Jadwal Pelajaran",
            "tahun_aktif" => $tahunAktif,
            "jadwal" => $jadwal,
        ];
        $this->view("akademik/jadwal/index", $data);
    }

    public function create()
    {
        $tahunAktif = TahunAjaran::getActive();
        if (!$tahunAktif) {
            $this->redirect("jadwal")->with(
                "error",
                "Tidak ada Tahun Ajaran Aktif!"
            );
            exit();
        }

        $data = [
            "title" => "Tambah Jadwal",
            "tahun_aktif" => $tahunAktif,
            "hari" => ENUM["HARI"],
            "kelas" => Kelas::getAll(),
            "mapel" => Mapel::getAll(),
            "gurus" => Guru::getAll(),
        ];
        $this->view("akademik/jadwal/create", $data);
    }

    public function store()
    {
        $tahunAktif = TahunAjaran::getActive();
        if (!$tahunAktif) {
            $this->redirect("jadwal")->with(
                "error",
                "Tahun Ajaran Aktif tidak ditemukan!"
            );
            exit();
        }

        $data = [
            "tahun_id" => $tahunAktif["tahun_id"],
            "kelas_id" => $_POST["kelas_id"],
            "mapel_id" => $_POST["mapel_id"],
            "guru_id" => $_POST["guru_id"],
            "hari" => $_POST["hari"],
            "jam_mulai" => $_POST["jam_mulai"],
            "jam_selesai" => $_POST["jam_selesai"],
        ];

        // Validasi Simple
        if (in_array("", $data)) {
            $this->redirect("jadwal/create")->with(
                "error",
                "Semua kolom wajib diisi!"
            );
            exit();
        }

        // Validasi Jam
        if ($data["jam_mulai"] >= $data["jam_selesai"]) {
            $this->redirect("jadwal/create")->with(
                "error",
                "Jam selesai harus lebih besar dari jam mulai"
            );
            exit();
        }

        $result = Jadwal::create($data);

        if ($result["status"]) {
            $this->redirect("jadwal")->with(
                "success",
                "Jadwal berhasil disimpan!"
            );
        } else {
            $this->redirect("jadwal/create")->with(
                "error",
                "Gagal menyimpan: " . $result["error"]
            );
        }
    }

    public function edit()
    {
        $id = $_GET["id"] ?? null;
        if (!$id) {
            $this->redirect("jadwal")->with("error", "ID tidak valid");
            exit();
        }

        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            $this->redirect("jadwal")->with("error", "Data tidak ditemukan");
            exit();
        }

        $data = [
            "title" => "Edit Jadwal",
            "jadwal" => $jadwal,
            "hari" => ENUM["HARI"],
            "kelas" => Kelas::getAll(),
            "mapel" => Mapel::getAll(),
            "gurus" => Guru::getAll(),
        ];
        $this->view("akademik/jadwal/edit", $data);
    }

    public function update()
    {
        $id = $_POST["jadwal_id"] ?? null;
        if (!$id) {
            $this->redirect("jadwal")->with("error", "ID tidak valid");
            exit();
        }

        $data = [
            "kelas_id" => $_POST["kelas_id"],
            "mapel_id" => $_POST["mapel_id"],
            "guru_id" => $_POST["guru_id"],
            "hari" => $_POST["hari"],
            "jam_mulai" => $_POST["jam_mulai"],
            "jam_selesai" => $_POST["jam_selesai"],
        ];

        if (in_array("", $data)) {
            $this->redirect("jadwal/edit?id=" . $id)->with(
                "error",
                "Semua kolom wajib diisi"
            );
            exit();
        }

        if ($data["jam_mulai"] >= $data["jam_selesai"]) {
            $this->redirect("jadwal/edit?id=" . $id)->with(
                "error",
                "Jam selesai harus lebih besar dari jam mulai"
            );
            exit();
        }

        $result = Jadwal::update($id, $data);

        if ($result["status"]) {
            $this->redirect("jadwal")->with(
                "success",
                "Jadwal berhasil diperbarui!"
            );
        } else {
            $this->redirect("jadwal/edit?id=" . $id)->with(
                "error",
                "Gagal update: " . $result["error"]
            );
        }
    }

    public function destroy()
    {
        $id = $_GET["id"] ?? null;
        if (!$id) {
            $this->redirect("jadwal")->with("error", "ID tidak valid");
            exit();
        }

        $result = Jadwal::delete($id);

        if ($result["status"]) {
            $this->redirect("jadwal")->with(
                "success",
                "Jadwal berhasil dihapus"
            );
        } else {
            $this->redirect("jadwal")->with(
                "error",
                "Gagal hapus: " . $result["error"]
            );
        }
    }
}
