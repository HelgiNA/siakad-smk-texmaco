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

            public function with($key, $message)
            {
                $this->flash = ["type" => $key, "message" => $message];
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
