<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIAKAD Texmaco</title>

    <link rel="stylesheet" href="<?php echo BASE_URL ?>/public/css/style.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="login-page">
    
    <?php echo $content; ?>

    <script>
        // Script sederhana untuk menutup alert secara manual
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-close')) {
                e.target.parentElement.style.display = 'none';
            }
        });
    </script>
</body>
</html>
