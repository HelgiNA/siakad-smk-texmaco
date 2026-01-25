<?php

// =========================================================================
// 1. KONFIGURASI DATABASE
// =========================================================================
define("DB_HOST", "localhost");
define("DB_PORT", "3306");
define("DB_NAME", "db_siakad_texmaco");
define("DB_USERNAME", "root"); // Wasmer pakai DB_USERNAME
define("DB_PASSWORD", "");

// =========================================================================
// 4. KONFIGURASI APLIKASI
// =========================================================================
define("APP_NAME", "Siakad SMK Texmaco Subang");
define("APP_DESCRIPTION", "Sistem Informasi Akademik SMK Texmaco Subang");

// Base URL Dinamis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
define("BASE_URL", $protocol . "://" . $domain);
define("BASE_PATH", __DIR__ . "/..");

// =========================================================================
// 5. ENUMERASI DATA
// =========================================================================
define("ENUM", [
    "STATUS" => [
        "Belum Input" => "Belum Input",
        "Valid" => "Valid",
        "Pending" => "Pending",
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

// =========================================================================
// 6. DEBUGGING (dd)
// =========================================================================
function dd($data)
{
    $backtrace = debug_backtrace();
    $caller = array_shift($backtrace);
    $file = $caller['file'] ?? 'unknown';
    $line = $caller['line'] ?? 'unknown';

    echo "<style>
        .dd-wrapper { background-color: #1e1e1e; color: #d4d4d4; font-family: monospace; padding: 15px; z-index:99999; position:relative; word-wrap: break-word; }
        .dd-header { border-bottom: 1px solid #333; margin-bottom: 10px; color: #888; font-size: 12px; }
        details { margin-left: 15px; }
        summary { cursor: pointer; outline: none; }
        .dd-key { color: #9cdcfe; }
        .dd-str { color: #ce9178; }
        .dd-num { color: #b5cea8; }
    </style>";

    $render = function ($item, $key = null) use (&$render) {
        $keyHtml = $key !== null ? "<span class='dd-key'>\"{$key}\"</span> => " : "";
        if (is_array($item) || is_object($item)) {
            $arrayItem = (array) $item;
            $count = count($arrayItem);
            $type = is_object($item) ? get_class($item) : "Array";
            echo "<details open><summary>{$keyHtml}<span style='color:#4ec9b0'>{$type}</span> [{$count}]</summary>";
            foreach ($arrayItem as $k => $v) {
                // Bersihkan null bytes pada properti private/protected
                $cleanKey = str_replace(["\0*\0", "\0"], "", $k);
                echo "<div style='margin-left:15px'>";
                $render($v, $cleanKey);
                echo "</div>";
            }
            echo "</details>";
        } else {
            $val = is_string($item) ? "<span class='dd-str'>\"" . htmlspecialchars($item) . "\"</span>" : "<span class='dd-num'>$item</span>";
            echo "<div>{$keyHtml}{$val}</div>";
        }
    };

    echo "<div class='dd-wrapper'><div class='dd-header'>Called in <strong>{$file}</strong>:<strong>{$line}</strong></div>";
    $render($data);
    echo "</div>";
    die();
}