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
 | 2. FILTER GENRE + SEARCH
 |----------------------------------------------
*/
$search = trim($_GET['q'] ?? '');
$genre  = trim($_GET['genre'] ?? '');

$where = "WHERE 1";
$params = [];
$types  = "";

/* Filter genre */
if ($genre !== "") {
    $where .= " AND genre = ?";
    $params[] = $genre;
    $types   .= "s";
}

/* Filter search */
if ($search !== "") {
    $where .= " AND (title LIKE ? OR content LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $types   .= "ss";
}

/*
 |----------------------------------------------
 | 3. HITUNG TOTAL ARTIKEL
 |----------------------------------------------
*/
$sqlCount = "SELECT COUNT(*) AS total FROM articles $where";
$stmt = mysqli_prepare($koneksi, $sqlCount);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);

$res = mysqli_stmt_get_result($stmt);
$totalItems = mysqli_fetch_assoc($res)['total'];
mysqli_stmt_close($stmt);

$totalPages = ceil($totalItems / $perPage);

/*
 |----------------------------------------------
 | 4. AMBIL ARTIKEL SESUAI FILTER
 |----------------------------------------------
*/
$sql = "SELECT * FROM articles $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($koneksi, $sql);

// Tambahkan limit & offset
$typesWithLimit = $types . "ii";
$paramsWithLimit = array_merge($params, [$perPage, $offset]);

mysqli_stmt_bind_param($stmt, $typesWithLimit, ...$paramsWithLimit);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

/*
 |----------------------------------------------
 | 5. HITUNG LIKE, KOMENTAR, CEK USER LIKE
 |----------------------------------------------
*/
$likesCount     = [];
$commentsCount  = [];
$userLiked      = [];

$articleIds = array_column($articles, 'id');

if (!empty($articleIds)) {

    $idList = implode(",", array_map('intval', $articleIds));

    /* LIKE COUNT */
    $sqlLike = "SELECT article_id, COUNT(*) AS total FROM likes 
                WHERE article_id IN ($idList)
                GROUP BY article_id";
    $resLike = mysqli_query($koneksi, $sqlLike);
    while ($row = mysqli_fetch_assoc($resLike)) {
        $likesCount[$row['article_id']] = (int)$row['total'];
    }

    /* COMMENT COUNT */
    $sqlCom = "SELECT article_id, COUNT(*) AS total FROM comments 
               WHERE article_id IN ($idList)
               GROUP BY article_id";
    $resCom = mysqli_query($koneksi, $sqlCom);
    while ($row = mysqli_fetch_assoc($resCom)) {
        $commentsCount[$row['article_id']] = (int)$row['total'];
    }

    /* CHECK USER LIKE */
    if (isset($_SESSION['user_id'])) {
        $uid = (int)$_SESSION['user_id'];

        $sqlUL = "SELECT article_id FROM likes 
                  WHERE user_id = $uid AND article_id IN ($idList)";
        $resUL = mysqli_query($koneksi, $sqlUL);

        while ($row = mysqli_fetch_assoc($resUL)) {
            $userLiked[$row['article_id']] = true;
        }
    }
}

?>