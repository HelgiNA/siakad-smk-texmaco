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
    // 1. Ambil info lokasi pemanggil (File & Line)
    $backtrace = debug_backtrace();
    $caller = array_shift($backtrace);
    $file = $caller['file'] ?? 'unknown';
    $line = $caller['line'] ?? 'unknown';

    // 2. CSS Styling (Dark Mode + Tree View)
    echo "<style>
        .dd-wrapper {
            background-color: #1e1e1e;
            color: #d4d4d4;
            font-family: 'Fira Code', 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            padding: 15px;
            margin: 0;
            border-left: 5px solid #bd93f9; /* Ungu Dracula */
            position: relative;
            z-index: 99999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            line-height: 1.6;
            text-align: left;
        }
        .dd-header {
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            margin-bottom: 10px;
            font-size: 12px;
            color: #888;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dd-header strong { color: #569cd6; }
        .dd-actions button {
            background: #333;
            border: none;
            color: #ccc;
            font-size: 10px;
            padding: 2px 8px;
            cursor: pointer;
            border-radius: 3px;
            margin-left: 5px;
        }
        .dd-actions button:hover { background: #444; color: #fff; }

        /* Tree View Styles */
        details { margin-left: 15px; }
        summary {
            cursor: pointer;
            list-style: none; /* Hilangkan panah default browser */
            outline: none;
        }
        /* Custom Arrow */
        summary::-webkit-details-marker { display: none; }
        summary::before {
            content: '▶';
            font-size: 9px;
            display: inline-block;
            margin-right: 5px;
            color: #666;
            transition: transform 0.2s;
        }
        details[open] > summary::before { transform: rotate(90deg); }
        
        /* Syntax Colors */
        .dd-key { color: #9cdcfe; margin-right: 5px; }
        .dd-arrow { color: #569cd6; margin-right: 5px; }
        .dd-type { color: #4ec9b0; font-size: 11px; font-style: italic; opacity: 0.7; }
        .dd-str { color: #ce9178; }
        .dd-num { color: #b5cea8; }
        .dd-bool { color: #569cd6; font-weight: bold; }
        .dd-null { color: #888; font-style: italic; }
        .dd-preview { color: #888; font-size: 11px; margin-left: 5px; }
    </style>";

    // 3. JavaScript untuk Expand/Collapse All
    echo "<script>
        function ddExpandAll() { document.querySelectorAll('.dd-wrapper details').forEach(e => e.open = true); }
        function ddCollapseAll() { document.querySelectorAll('.dd-wrapper details').forEach(e => e.open = false); }
    </script>";

    // 4. Helper Function untuk Rekursi
    // Menggunakan closure agar tidak mengotori global namespace
    $render = function ($item, $key = null, $level = 0) use (&$render) {
        $indent = ''; // Indentasi visual ditangani oleh margin-left CSS <details>
        
        // Render Key jika ada
        $keyHtml = $key !== null ? "<span class='dd-key'>\"{$key}\"</span><span class='dd-arrow'>=></span>" : "";

        if (is_array($item) || is_object($item)) {
            $count = count((array)$item);
            $type = is_object($item) ? get_class($item) . " Object" : "Array";
            $preview = " <span class='dd-preview'>[ {$count} items ]</span>";

            // Level 0 (Root) terbuka default, sisanya tertutup
            $openState = ($level === 0) ? 'open' : '';

            echo "<details $openState>
                    <summary>{$keyHtml}<span class='dd-type'>{$type}</span>{$preview}</summary>";
            
            foreach ($item as $k => $v) {
                $render($v, $k, $level + 1);
            }
            
            echo "</details>";
        } else {
            // Render Scalar Values (String, Int, Boolean, Null)
            $valHtml = '';
            if (is_string($item)) {
                $valHtml = "<span class='dd-str'>\"" . htmlspecialchars($item) . "\"</span>";
            } elseif (is_int($item) || is_float($item)) {
                $valHtml = "<span class='dd-num'>{$item}</span>";
            } elseif (is_bool($item)) {
                $valHtml = "<span class='dd-bool'>" . ($item ? 'true' : 'false') . "</span>";
            } elseif (is_null($item)) {
                $valHtml = "<span class='dd-null'>null</span>";
            } else {
                $valHtml = htmlspecialchars((string)$item);
            }

            echo "<div style='margin-left: 18px;'>{$keyHtml}{$valHtml}</div>";
        }
    };

    // 5. Output Utama
    echo "<div class='dd-wrapper'>";
    
    // Header Info
    echo "<div class='dd-header'>
            <div>Called in <strong>{$file}</strong> : <strong>{$line}</strong></div>
            <div class='dd-actions'>
                <button onclick='ddExpandAll()'>Expand All</button>
                <button onclick='ddCollapseAll()'>Collapse All</button>
            </div>
          </div>";
    
    // Mulai Render
    $render($data);
    
    echo "</div>";
    die();
}