<?php ob_start(); ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi Kesalahan!</strong> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php
// Render konten berdasarkan role
switch ($role) {
    case 'Admin':
    case 'Kepsek':
        include 'dashboard/admin.php';
        break;
    case 'Guru':
        include 'dashboard/guru.php';
        break;
    case 'Siswa':
        include 'dashboard/siswa.php';
        break;
    default:
        echo '<div class="alert alert-warning">Role tidak dikenal</div>';
}
?>

<?php
    $content = ob_get_clean();
    // Pastikan path ke layout main benar
    require_once __DIR__ . '/layouts/main.php';
?>