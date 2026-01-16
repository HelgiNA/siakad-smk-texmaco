<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? "Dashboard"; ?> | SIAKAD</title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    
    <?php showFlash(); ?>

    <?php require_once __DIR__ . "/../partials/sidebar.php"; ?>

    <div class="main-wrapper">
        
        <?php require_once __DIR__ . "/../partials/navbar.php"; ?>

        <main class="content">
            <?php echo $content; ?>
        </main>

        <?php require_once __DIR__ . "/../partials/footer.php"; ?>
        
    </div>
    
    <script src="<?php echo BASE_URL; ?>/public/js/datatable.js"></script>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const body = document.body;

        if(toggleBtn){
            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    body.classList.toggle('show-sidebar');
                } else {
                    // Logic desktop collapse bisa ditambah disini
                }
            });
        }

        // Tutup sidebar saat klik overlay (Mobile)
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && body.classList.contains('show-sidebar')) {
                if (!e.target.closest('.sidebar') && !e.target.closest('#sidebarToggle')) {
                    body.classList.remove('show-sidebar');
                }
            }
        });
    </script>
</body>
</html>
