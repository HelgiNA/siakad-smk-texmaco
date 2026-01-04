<?php

use App\Controllers\AbsensiController;
use App\Controllers\AuthController;
use App\Controllers\GuruController;
use App\Controllers\HomeController;
use App\Controllers\JadwalController;
use App\Controllers\KelasController;
use App\Controllers\MapelController;
use App\Controllers\NilaiController;
use App\Controllers\PlottingController;
use App\Controllers\SiswaController;
use App\Controllers\TahunAjaranController;
use App\Controllers\UserController;
use App\Controllers\ValidasiController;
use App\Controllers\ValidasiNilaiController;

use App\Core\Route;

$routes = new Route();

// =============================================================================
// 1. AUTHENTICATION & PUBLIC ROUTES
// =============================================================================
$routes->get("/login", [AuthController::class, "login"], ["guest"]);
$routes->post("/login", [AuthController::class, "submitLogin"], ["guest"]);
$routes->get("/logout", [AuthController::class, "logout"], ["auth"]);

$routes->get("/", [HomeController::class, "index"], ["auth"]);
$routes->get("/dashboard", [HomeController::class, "index"], ["auth"]);

// =============================================================================
// 2. MANAJEMEN MASTER DATA (Hanya Admin)
// =============================================================================

// MANAJEMEN USER
$routes->get(
    "/users",
    [UserController::class, "index"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/users/create",
    [UserController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/users/store",
    [UserController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/users/edit",
    [UserController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/users/update",
    [UserController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/users/delete",
    [UserController::class, "destroy"],
    ["auth", "role:Admin"]
);

// MANAJENEN SISWA
$routes->get(
    "/siswa",
    [SiswaController::class, "index"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/siswa/create",
    [SiswaController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/siswa/store",
    [SiswaController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/siswa/edit",
    [SiswaController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/siswa/update",
    [SiswaController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/siswa/delete",
    [SiswaController::class, "destroy"],
    ["auth", "role:Admin"]
);

// MANAJEMEN GURU
$routes->get("/guru", [GuruController::class, "index"], ["auth", "role:Admin"]);
$routes->get(
    "/guru/create",
    [GuruController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/guru/store",
    [GuruController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/guru/edit",
    [GuruController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/guru/update",
    [GuruController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/guru/delete",
    [GuruController::class, "destroy"],
    ["auth", "role:Admin"]
);

// MANAJEMEN TAHUN AJARAN
$routes->get(
    "/tahun-ajaran",
    [TahunAjaranController::class, "index"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/tahun-ajaran/create",
    [TahunAjaranController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/tahun-ajaran/store",
    [TahunAjaranController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/tahun-ajaran/edit",
    [TahunAjaranController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/tahun-ajaran/update",
    [TahunAjaranController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/tahun-ajaran/activate",
    [TahunAjaranController::class, "activate"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/tahun-ajaran/delete",
    [TahunAjaranController::class, "destroy"],
    ["auth", "role:Admin"]
);

// MANAJEMEN KELAS
$routes->get(
    "/kelas",
    [KelasController::class, "index"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/kelas/create",
    [KelasController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/kelas/store",
    [KelasController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/kelas/edit",
    [KelasController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/kelas/update",
    [KelasController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/kelas/delete",
    [KelasController::class, "destroy"],
    ["auth", "role:Admin"]
);

// MANAJEMEN MATA PELAJARAN
$routes->get(
    "/mapel",
    [MapelController::class, "index"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/mapel/create",
    [MapelController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/mapel/store",
    [MapelController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/mapel/edit",
    [MapelController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/mapel/update",
    [MapelController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/mapel/delete",
    [MapelController::class, "destroy"],
    ["auth", "role:Admin"]
);

// =============================================================================
// 3. AKADEMIK (Admin & Guru Terbatas)
// =============================================================================

// JADWAL PELAJARAN (Admin yang atur)
$routes->get(
    "/jadwal",
    [JadwalController::class, "index"],
    ["auth", "role:Admin"]
); // Guru boleh lihat
$routes->get(
    "/jadwal/create",
    [JadwalController::class, "create"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/jadwal/store",
    [JadwalController::class, "store"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/jadwal/edit",
    [JadwalController::class, "edit"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/jadwal/update",
    [JadwalController::class, "update"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/jadwal/delete",
    [JadwalController::class, "destroy"],
    ["auth", "role:Admin"]
);

// PLOTTING SISWA (Admin yang atur)
$routes->get(
    "/plotting",
    [PlottingController::class, "index"],
    ["auth", "role:Admin"]
);
$routes->get(
    "/plotting/manage",
    [PlottingController::class, "manage"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/plotting/add",
    [PlottingController::class, "add"],
    ["auth", "role:Admin"]
);
$routes->post(
    "/plotting/remove",
    [PlottingController::class, "remove"],
    ["auth", "role:Admin"]
);

// =============================================================================
// 4. FITUR UTAMA GURU & WALI KELAS
// =============================================================================

// --- Bagian Guru Mapel / Input ---
$routes->get(
    "/absensi",
    [AbsensiController::class, "index"],
    ["auth", "role:Guru"]
); // List History
$routes->get(
    "/absensi/create",
    [AbsensiController::class, "create"],
    ["auth", "role:Guru"]
); // Form Input
$routes->post(
    "/absensi/store",
    [AbsensiController::class, "store"],
    ["auth", "role:Guru"]
); // Proses Simpan

// --- Bagian Wali Kelas / Validasi ---
$routes->get(
    "/absensi/validasi",
    [AbsensiController::class, "validationList"],
    ["auth", "role:Guru"]
); // List Pending
$routes->get(
    "/absensi/validasi/review",
    [AbsensiController::class, "validationReview"],
    ["auth", "role:Guru"]
); // Detail sebelum approve
$routes->post(
    "/absensi/validasi/process",
    [AbsensiController::class, "validationProcess"],
    ["auth", "role:Guru"]
); // Aksi Approve/Reject

// FASE 4: INPUT NILAI AKADEMIK
// Sequence SIA-006 (V2 - Draft + Submission)
$routes->get(
    "/nilai/create",
    [NilaiController::class, "create"],
    ["auth", "role:Guru"]
);
$routes->get(
    "/nilai/input",
    [NilaiController::class, "input"],
    ["auth", "role:Guru"]
);
$routes->post(
    "/nilai/store",
    [NilaiController::class, "store"],
    ["auth", "role:Guru"]
);

// FASE 4: VALIDASI NILAI AKADEMIK (Wali Kelas)
// Sequence SIA-007
$routes->get(
    "/validasi-nilai",
    [ValidasiNilaiController::class, "index"],
    ["auth", "role:Guru"]
);
$routes->post(
    "/validasi-nilai/proses",
    [ValidasiNilaiController::class, "proses"],
    ["auth", "role:Guru"]
);

return $routes;
