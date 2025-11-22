<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

// hanya POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../view/read.php'));
    exit;
}

// user harus login
if (empty($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$article_id = (int)($_POST['article_id'] ?? 0);

if ($article_id <= 0) {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../view/read.php'));
    exit;
}

// cek apakah user sudah like
$stmt = mysqli_prepare($koneksi, 
    "SELECT id FROM likes WHERE article_id = ? AND user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// UNLIKE
if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);

    $del = mysqli_prepare($koneksi, 
        "DELETE FROM likes WHERE id = ?");
    mysqli_stmt_bind_param($del, "i", $row['id']);
    mysqli_stmt_execute($del);
    mysqli_stmt_close($del);
}
// LIKE
else {
    $ins = mysqli_prepare($koneksi,
        "INSERT INTO likes (article_id, user_id, created_at)
         VALUES (?, ?, NOW())"
    );
    mysqli_stmt_bind_param($ins, "ii", $article_id, $user_id);
    mysqli_stmt_execute($ins);
    mysqli_stmt_close($ins);
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../view/read.php'));
exit;
