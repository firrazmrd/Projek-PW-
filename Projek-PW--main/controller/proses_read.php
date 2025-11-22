<?php
require_once __DIR__ . '/../config/koneksi.php';
session_start();

/*
 |----------------------------------------------
 | 1. PAGINATION
 |----------------------------------------------
*/
$perPage = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

/*
 |----------------------------------------------
 | 2. SEARCH
 |----------------------------------------------
*/
$search = trim($_GET['q'] ?? '');
$where = "";
$paramLike = "";

if ($search !== "") {
    $where = "WHERE title LIKE ? OR content LIKE ?";
    $paramLike = "%$search%";
}

/*
 |----------------------------------------------
 | 3. HITUNG TOTAL ARTIKEL
 |----------------------------------------------
*/
$sqlCount = "SELECT COUNT(*) AS total FROM articles $where";
$stmt = mysqli_prepare($koneksi, $sqlCount);

if ($search !== "") {
    mysqli_stmt_bind_param($stmt, "ss", $paramLike, $paramLike);
}

mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$totalItems = mysqli_fetch_assoc($res)['total'];
mysqli_stmt_close($stmt);

$totalPages = ceil($totalItems / $perPage);

/*
 |----------------------------------------------
 | 4. AMBIL ARTIKEL (LIMIT + SEARCH)
 |----------------------------------------------
*/
$sql = "SELECT * FROM articles $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($koneksi, $sql);

if ($search !== "") {
    mysqli_stmt_bind_param($stmt, "ssii", $paramLike, $paramLike, $perPage, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $perPage, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

/*
 |----------------------------------------------
 | 5. PREPARE LIKE & COMMENT COUNTERS
 |----------------------------------------------
*/
$likesCount     = [];   // total like untuk setiap artikel
$commentsCount  = [];   // total komen untuk setiap artikel
$userLiked      = [];   // artikel apa saja yang user telah like

// Ambil ID semua artikel yang tampil
$articleIds = array_column($articles, 'id');

if (!empty($articleIds)) {

    $idList = implode(",", array_map('intval', $articleIds));

    /*
    |----------------------------------------------
    | 5A. HITUNG TOTAL LIKE PER ARTIKEL
    |----------------------------------------------
    */
    $sqlLike = "SELECT article_id, COUNT(*) AS total
                FROM likes
                WHERE article_id IN ($idList)
                GROUP BY article_id";

    $resLike = mysqli_query($koneksi, $sqlLike);

    while ($row = mysqli_fetch_assoc($resLike)) {
        $likesCount[$row['article_id']] = (int)$row['total'];
    }

    /*
    |----------------------------------------------
    | 5B. HITUNG TOTAL KOMENTAR PER ARTIKEL
    |----------------------------------------------
    */
    $sqlCom = "SELECT article_id, COUNT(*) AS total
               FROM comments
               WHERE article_id IN ($idList)
               GROUP BY article_id";

    $resCom = mysqli_query($koneksi, $sqlCom);

    while ($row = mysqli_fetch_assoc($resCom)) {
        $commentsCount[$row['article_id']] = (int)$row['total'];
    }

    /*
    |----------------------------------------------
    | 5C. CEK ARTIKEL MANA SAJA YANG SUDAH DI-LIKE USER
    |----------------------------------------------
    */
    if (!empty($_SESSION['user_id'])) {

        $uid = (int)$_SESSION['user_id'];

        $sqlUserLike = "SELECT article_id FROM likes 
                        WHERE user_id = $uid AND article_id IN ($idList)";

        $resUL = mysqli_query($koneksi, $sqlUserLike);

        while ($row = mysqli_fetch_assoc($resUL)) {
            $userLiked[$row['article_id']] = true;
        }
    }
}

?>
