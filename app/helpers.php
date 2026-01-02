<?php
// app/helpers.php

if (!function_exists("redirect")) {
    function redirect($path)
    {
        return new class ($path) {
            private $path;
            private $flash = [];

            public function __construct($path)
            {
                // Pastikan path diawali slash '/' agar konsisten
                $this->path = "/" . ltrim($path, "/");
            }

            // Di dalam class anonim helper redirect()
            public function with($type, $message, $title = null)
            {
                // Jika title kosong, buat default berdasarkan tipe
                if ($title === null) {
                    $title = ucfirst($type) . "!";
                }

                $this->flash = [
                    "type" => $type,
                    "title" => $title,
                    "message" => $message,
                ];
                return $this;
            }

            public function __destruct()
            {
                if (!empty($this->flash)) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION["flash"] = $this->flash;
                }

                // Hapus slash akhir di BASE_URL agar tidak double (misal: http://localhost/)
                $baseUrl = rtrim(BASE_URL, "/");
                $fullUrl = $baseUrl . $this->path;

                // Debugging: Jika error berlanjut, uncomment baris ini untuk lihat mau redirect kemana
                // die("Redirecting to: " . $fullUrl);

                header("Location: " . $fullUrl);
                exit();
            }
        };
    }
}

if (!function_exists("abort")) {
    /**
     * Menampilkan halaman error HTTP dan menghentikan script.
     * @param int $code Kode HTTP (403, 404, 500)
     */
    function abort($code = 404)
    {
        // 1. Set HTTP Response Code agar browser tahu ini error
        http_response_code($code);

        // 2. Cari file view error yang sesuai
        // Pastikan BASE_PATH sudah didefinisikan di config.php
        $path = BASE_PATH . "/views/errors/{$code}.php";

        // 3. Jika file ada, tampilkan. Jika tidak, tampilkan pesan default.
        if (file_exists($path)) {
            require_once $path;
        } else {
            // Fallback sederhana jika file view belum dibuat
            echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'>";
            echo "<h1>Error {$code}</h1>";
            echo "<p>Maaf, halaman error kustom belum tersedia untuk kode ini.</p>";
            echo "</div>";
        }

        // 4. Matikan proses PHP seketika (penting!)
        exit();
    }
}

if (!function_exists("setFlash")) {
    /**
     * Set pesan flash session.
     * @param string $type success, info, warning, error, question
     * @param string $title Judul flash (tebal)
     * @param string $message Isi pesan
     */
    function setFlash($type, $message, $title = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["flash"] = [
            "type" => $type,
            "title" => $title,
            "message" => $message,
        ];
    }
}

if (!function_exists("showFlash")) {
    /**
     * Menampilkan komponen flash secara manual di View.
     * Fungsi ini akan memuat file views/components/flash.php
     */
    function showFlash()
    {
        // Pastikan session aktif karena kita butuh akses $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cek apakah ada flash data?
        if (isset($_SESSION["flash"])) {
            // Panggil file view flash yang sudah kita buat sebelumnya
            $path = BASE_PATH . "/views/components/flash.php";

            if (file_exists($path)) {
                require $path;
            }
        }
    }
}

if (!function_exists("setAlert")) {
    /**
     * Set pesan alert session.
     * @param string $type success, info, warning, error, question
     * @param string $title Judul alert (tebal)
     * @param string $message Isi pesan
     */
    function setAlert($type, $message, $title = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["alert"] = [
            "type" => $type,
            "title" => $title,
            "message" => $message,
        ];
    }
}

if (!function_exists("showAlert")) {
    /**
     * Menampilkan komponen alert secara manual di View.
     * Fungsi ini akan memuat file views/components/alert.php
     */
    function showAlert()
    {
        // Pastikan session aktif karena kita butuh akses $_SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Cek apakah ada flash data?
        if (isset($_SESSION["alert"])) {
            // Panggil file view alert yang sudah kita buat sebelumnya
            $path = BASE_PATH . "/views/components/alert.php";

            if (file_exists($path)) {
                require $path;
            }
        }
    }
}
