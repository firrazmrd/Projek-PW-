<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../view/kelola_komentar.php");
    exit;
}

// Cek login admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit;
}

// Ambil ID komentar
$comment_id = (int)($_POST['id'] ?? 0);

if ($comment_id <= 0) {
    header("Location: ../view/kelola_komentar.php");
    exit;
}

// Hapus komentar
$sql = "DELETE FROM comments WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $comment_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Kembali ke halaman komentar admin
header("Location: ../view/kelola_komentar.php");
exit;