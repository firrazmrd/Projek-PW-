<?php
require_once __DIR__ . '/../config/koneksi.php';

$slug = $_GET['slug'] ?? '';

if (!$slug) {
    die("Slug tidak valid");
}

/* ==========================
   AMBIL ARTIKEL + NAMA AUTHOR
========================== */

$sql = "
    SELECT a.*, u.name AS author_name 
    FROM articles a
    LEFT JOIN users u ON u.id = a.author_id
    WHERE a.slug = ?
    LIMIT 1
";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$article) {
    die("Artikel tidak ditemukan.");
}

$article_id = (int)$article['id'];

/* ==========================
   AMBIL SEMUA KOMENTAR ARTIKEL
========================== */

$sqlKomentar = "
    SELECT c.*, u.name AS commenter_name
    FROM comments c
    JOIN users u ON u.id = c.user_id
    WHERE c.article_id = ?
    ORDER BY c.created_at DESC
";
$stmtC = mysqli_prepare($koneksi, $sqlKomentar);
mysqli_stmt_bind_param($stmtC, "i", $article_id);
mysqli_stmt_execute($stmtC);
$comments = mysqli_stmt_get_result($stmtC)->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($stmtC);

/* ==========================
   JUMLAH LIKE
========================== */

$sqlLike = "SELECT COUNT(*) AS total_like FROM likes WHERE article_id = ?";
$stmtL = mysqli_prepare($koneksi, $sqlLike);
mysqli_stmt_bind_param($stmtL, "i", $article_id);
mysqli_stmt_execute($stmtL);
$total_likes = mysqli_stmt_get_result($stmtL)->fetch_assoc()['total_like'] ?? 0;
mysqli_stmt_close($stmtL);

/* ==========================
   CEK APAKAH USER SUDAH LIKE
========================== */

session_start();
$user_id = $_SESSION['user_id'] ?? null;
$hasLiked = false;

if ($user_id) {
    $sqlCheck = "SELECT id FROM likes WHERE user_id = ? AND article_id = ? LIMIT 1";
    $stmtChk = mysqli_prepare($koneksi, $sqlCheck);
    mysqli_stmt_bind_param($stmtChk, "ii", $user_id, $article_id);
    mysqli_stmt_execute($stmtChk);
    $resChk = mysqli_stmt_get_result($stmtChk);
    $hasLiked = ($resChk->num_rows > 0);
    mysqli_stmt_close($stmtChk);
}
