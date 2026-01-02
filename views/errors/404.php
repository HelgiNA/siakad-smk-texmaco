<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: #f4f6f8;
            color: #2c3e50;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .ghost-icon {
            width: 100px;
            height: 100px;
            color: #3498db; /* Biru */
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        h1 {
            font-size: 4rem;
            font-weight: 800;
            color: #34495e;
            margin: 0;
            line-height: 1;
        }
        h3 {
            font-size: 1.25rem;
            color: #7f8c8d;
            margin-top: 10px;
            font-weight: 500;
        }
        p {
            margin: 20px 0 30px;
            color: #95a5a6;
        }
        .btn {
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border-radius: 50px; /* Tombol bulat */
            font-weight: 600;
            transition: transform 0.2s, background-color 0.2s;
            display: inline-block;
        }
        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        /* Animasi Melayang */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <svg class="ghost-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 22l3-3 3 3V10a6 6 0 0 0-12 0v12l3-3z"></path>
            <circle cx="10" cy="13" r="1.5" fill="currentColor"></circle>
            <circle cx="14" cy="13" r="1.5" fill="currentColor"></circle>
        </svg>

        <h1>404</h1>
        <h3>Oops! Halaman Hilang</h3>
        <p>Halaman yang Anda cari mungkin telah dihapus, <br>namanya diganti, atau sedang tidak tersedia sementara.</p>
        
        <a href="/" class="btn">Pulang ke Beranda</a>
    </div>
</body>
</html>