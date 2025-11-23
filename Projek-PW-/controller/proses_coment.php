<?php
require_once "../config/koneksi.php";

if (session_status() === PHP_SESSION_NONE) session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo json_encode(["error" => "Harus login"]);
    exit;
}

$action = $_POST['action'] ?? "add";


//DELETE COMMENT 
if ($action === "delete") {

    $id = (int) $_POST['delete_id'];

    mysqli_query($koneksi, "DELETE FROM comments WHERE id = $id AND user_id = $user_id");

    echo json_encode(["success" => true]);
    exit;
}



//  ADD COMMENT 
$article_id = (int) $_POST['article_id'];
$content    = trim($_POST['content']);

if ($content === "") {
    echo json_encode(["error" => "Komentar tidak boleh kosong"]);
    exit;
}

$stmt = mysqli_prepare($koneksi,
    "INSERT INTO comments (article_id, user_id, content, created_at)
     VALUES (?, ?, ?, NOW())"
);
mysqli_stmt_bind_param($stmt, "iis", $article_id, $user_id, $content);
mysqli_stmt_execute($stmt);

$new_id = mysqli_insert_id($koneksi);

// ambil nama user
$q = mysqli_query($koneksi, "SELECT name FROM users WHERE id = $user_id");
$name = mysqli_fetch_assoc($q)['name'];

echo json_encode([
    "success" => true,
    "id"      => $new_id,
    "name"    => $name,
    "content" => nl2br(htmlspecialchars($content))
]);
exit;
