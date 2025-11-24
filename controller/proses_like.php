<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

// Output JSON
header("Content-Type: application/json");

// Harus login
if (empty($_SESSION['user_id'])) {
    echo json_encode(["error" => "not_logged_in"]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$article_id = (int)($_POST['article_id'] ?? 0);

if ($article_id <= 0) {
    echo json_encode(["error" => "invalid_article"]);
    exit;
}

// Cek apakah user sudah like
$sql = "SELECT id FROM likes WHERE article_id = ? AND user_id = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika sudah like → UNLIKE
if ($row) {
    $del = mysqli_prepare($koneksi, "DELETE FROM likes WHERE id = ?");
    mysqli_stmt_bind_param($del, "i", $row['id']);
    mysqli_stmt_execute($del);
    mysqli_stmt_close($del);
    $liked = false;
}
// Jika belum like → LIKE
else {
    $ins = mysqli_prepare(
        $koneksi,
        "INSERT INTO likes (article_id, user_id, created_at) VALUES (?, ?, NOW())"
    );
    mysqli_stmt_bind_param($ins, "ii", $article_id, $user_id);
    mysqli_stmt_execute($ins);
    mysqli_stmt_close($ins);
    $liked = true;
}

// Hitung total like update
$q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM likes WHERE article_id = $article_id");
$total = mysqli_fetch_assoc($q)['total'];

echo json_encode([
    "liked" => $liked,
    "totalLikes" => $total
]);
exit;
