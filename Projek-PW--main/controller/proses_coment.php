<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

// hanya boleh POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../view/read.php");
    exit;
}

// wajib login
if (empty($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? 'create';

/* ==========================
   HAPUS KOMENTAR
========================== */
if ($action === 'delete') {

    $delete_id = (int)($_POST['delete_id'] ?? 0);

    // Ambil slug
    $stmtSlug = mysqli_prepare($koneksi, "
        SELECT a.slug 
        FROM comments c 
        JOIN articles a ON a.id = c.article_id
        WHERE c.id = ?
    ");
    mysqli_stmt_bind_param($stmtSlug, "i", $delete_id);
    mysqli_stmt_execute($stmtSlug);
    $slugRow = mysqli_stmt_get_result($stmtSlug)->fetch_assoc();
    mysqli_stmt_close($stmtSlug);

    $slug = $slugRow['slug'] ?? '';

    if ($delete_id > 0) {
        // cek apakah komentar milik user
        $stmtCheck = mysqli_prepare($koneksi, "SELECT user_id FROM comments WHERE id = ?");
        mysqli_stmt_bind_param($stmtCheck, "i", $delete_id);
        mysqli_stmt_execute($stmtCheck);
        $owner = mysqli_stmt_get_result($stmtCheck)->fetch_assoc();
        mysqli_stmt_close($stmtCheck);

        if ($owner && $owner['user_id'] == $user_id) {
            // hapus komentar
            $stmt = mysqli_prepare($koneksi, "DELETE FROM comments WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $delete_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: ../view/read_single.php?slug=" . urlencode($slug));
    exit;
}

/* ==========================
   TAMBAH KOMENTAR
========================== */
$article_id = (int)($_POST['article_id'] ?? 0);
$content = trim($_POST['content'] ?? '');
$slug = $_POST['slug'] ?? '';

if ($article_id <= 0 || $content === '') {
    header("Location: ../view/read.php");
    exit;
}

// limit panjang komentar
if (mb_strlen($content) > 2000) {
    $content = mb_substr($content, 0, 2000);
}

// jika slug tidak ada → ambil dari database
if ($slug === '') {
    $stmtSlug = mysqli_prepare($koneksi, "SELECT slug FROM articles WHERE id = ?");
    mysqli_stmt_bind_param($stmtSlug, "i", $article_id);
    mysqli_stmt_execute($stmtSlug);
    $row = mysqli_stmt_get_result($stmtSlug)->fetch_assoc();
    mysqli_stmt_close($stmtSlug);

    $slug = $row['slug'] ?? '';
}

// insert komentar
$stmt = mysqli_prepare($koneksi, "
    INSERT INTO comments (article_id, user_id, content, created_at, is_approved)
    VALUES (?, ?, ?, NOW(), 1)
");
mysqli_stmt_bind_param($stmt, "iis", $article_id, $user_id, $content);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// redirect kembali ke artikel
header("Location: ../view/read_single.php?slug=" . urlencode($slug));
exit;
