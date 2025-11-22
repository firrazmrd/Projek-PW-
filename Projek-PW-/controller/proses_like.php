<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

// Hanya boleh lewat POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/index.php');
    exit;
}

// Wajib login
if (empty($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit;
}

$user_id    = (int) $_SESSION['user_id'];
$article_id = (int) ($_POST['article_id'] ?? 0);

// Jika article_id tidak valid, balik ke halaman sebelumnya
if ($article_id <= 0) {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../view/index.php'));
    exit;
}

// Cek apakah user sudah pernah like artikel ini
$sql = "SELECT id FROM likes WHERE article_id = ? AND user_id = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika sudah like → UNLIKE (hapus baris)
if ($row) {
    $del = mysqli_prepare($koneksi, "DELETE FROM likes WHERE id = ?");
    mysqli_stmt_bind_param($del, "i", $row['id']);
    mysqli_stmt_execute($del);
    mysqli_stmt_close($del);
}
// Jika belum like → LIKE (insert baris baru)
else {
    $ins = mysqli_prepare(
        $koneksi,
        "INSERT INTO likes (article_id, user_id, created_at) VALUES (?, ?, NOW())"
    );
    mysqli_stmt_bind_param($ins, "ii", $article_id, $user_id);
    mysqli_stmt_execute($ins);
    mysqli_stmt_close($ins);
}

// Balik ke halaman sebelumnya (read.php atau read_single.php)
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../view/index.php'));
exit;
