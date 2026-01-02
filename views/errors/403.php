<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Courier New', Courier, monospace; /* Font gaya terminal */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .container {
            max-width: 500px;
            padding: 20px;
        }
        .lock-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            color: #ff4757; /* Merah Neon */
            animation: shake 2s infinite;
        }
        h1 {
            font-size: 5rem;
            color: #ff4757;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(255, 71, 87, 0.5);
        }
        h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        p {
            color: #888;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            text-decoration: none;
            color: #121212;
            background-color: #ff4757;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            transition: 0.3s;
            display: inline-block;
        }
        .btn:hover {
            background-color: #ff6b81;
            box-shadow: 0 0 15px rgba(255, 71, 87, 0.6);
        }

        /* Animasi gembok bergetar sedikit */
        @keyframes shake {
            0% { transform: rotate(0deg); }
            20% { transform: rotate(-5deg); }
            40% { transform: rotate(5deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <svg class="lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>
        
        <h1>403</h1>
        <h2>Akses Dilarang</h2>
        <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>Silakan hubungi administrator jika ini adalah kesalahan.</p>
        
        <a href="/" class="btn">KEMBALI KE HOME</a>
    </div>
</body>
</html>