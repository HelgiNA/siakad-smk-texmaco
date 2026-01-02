<?php

define("DB_HOST", "127.0.0.1");
define("DB_NAME", "db_siakad_texmaco");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

define("APP_NAME", "Siakad SMK Texmaco Subang");
define("APP_DESCRIPTION", "Sistem Informasi Akademik SMK Texmaco Subang");
define("BASE_URL", "http://localhost:8080");
define("BASE_PATH", __DIR__ . "/..");

define("ENUM", [
    "STATUS" => [
        "Belum Input" => "Belum Input",
        "Valid" => "Valid",
        "Draft" => "Draft",
        "Rejected" => "Rejected",
    ],
    "STATUS_KEHADIRAN" => [
        "Hadir" => "Hadir",
        "Izin" => "Izin",
        "Sakit" => "Sakit",
        "Alpa" => "Alpa",
    ],
    "HARI" => [
        "Sunday" => "Minggu",
        "Monday" => "Senin",
        "Tuesday" => "Selasa",
        "Wednesday" => "Rabu",
        "Thursday" => "Kamis",
        "Friday" => "Jumat",
        "Saturday" => "Sabtu",
    ],
]);

define("ADMIN_USERNAME", "admin");
define("ADMIN_PASSWORD", "admin");

define("SISWA_USERNAME", "siswa");
define("SISWA_PASSWORD", "siswa");

define("GURU_USERNAME", "guru");
define("GURU_PASSWORD", "guru");

function dd($data)
{
    echo "<pre>";
    die(var_dump($data));
    echo "</pre>";
}
