<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../controller/auth.php';

session_start();
require_admin();

// hanya boleh akses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/kelola.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    header('Location: ../view/kelola.php');
    exit;
}

// Ambil nama gambar dari database
$stmt = mysqli_prepare($koneksi, "SELECT image FROM articles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $img);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Hapus row
$stmt = mysqli_prepare($koneksi, "DELETE FROM articles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Hapus file gambar jika ada
if ($ok && $img) {
    // path file → Projek-PW/img/nama_filenya
    $path = __DIR__ . '/../' . $img;

    if (file_exists($path)) {
        unlink($path);
    }
}

// balik ke halaman kelola
header('Location: ../view/kelola.php');
exit;
