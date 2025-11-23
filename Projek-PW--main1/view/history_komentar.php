<?php
session_start();
require "../config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$q = mysqli_query($koneksi, "SELECT name, role FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($q);

$user_name = $user['name'];
$user_role = $user['role'];

// ambil komentar user
$sql = "SELECT comments.content, comments.created_at, articles.title, articles.slug
        FROM comments 
        JOIN articles ON comments.article_id = articles.id
        WHERE comments.user_id = $user_id
        ORDER BY comments.id DESC";

$res = mysqli_query($koneksi, $sql);
$comments = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>History Komentar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    body {
        font-family: "Poppins";
        background: #f5f6fa;
    }
    .sidebar {
        width: 260px;
        height: 100vh;
        background: white;
        border-right: 1px solid #e3e3e3;
        position: fixed;
        left: 0;
        top: 0;
        padding: 25px;
    }
    .sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        border-radius: 10px;
        font-weight: 500;
        color: #4b4b4b;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background: #19875419;
        color: #198754;
    }

    .main {
        margin-left: 260px;
        padding: 40px;
    }
</style>
</head>

<body>

<div class="sidebar">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../img/logo.png" width="35" class="me-2">
            <strong>Sportify</strong>
        </a>
        <br>

        <a href="dashboard_user.php" class="nav-link active">
            <ion-icon name="home-outline"></ion-icon> Dashboard
        </a>

        <a href="history_like.php" class="nav-link">
            <ion-icon name="heart-outline"></ion-icon> History Like
        </a>

        <a href="history_komentar.php" class="nav-link">
            <ion-icon name="chatbubble-outline"></ion-icon> Komentar Anda
        </a>

        <a href="../controller/logout.php" class="nav-link" style="color:red;">
            <ion-icon name="log-out-outline"></ion-icon> Logout
        </a>

        <div class="bottom-box mt-4">
            <p>Signed in as</p>
            <div class="d-flex align-items-center gap-2">
                <ion-icon name="person-circle-outline" style="font-size: 30px;"></ion-icon>
                <div>
                    <strong><?= htmlspecialchars($user_name) ?></strong> <br>
                    <small>Role: <?= htmlspecialchars($user_role) ?></small>
                </div>
            </div>

            <div class="mt-4">
                <a href="index.php" class="nav-link"><ion-icon name="home-outline"></ion-icon> Kembali Halaman Utama</a>
            </div>
        </div>
    </div>

<div class="main">

    <h1 class="fw-bold mb-4">History Komentar</h1>

    <div class="card p-4 shadow-sm rounded-4">

        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Judul Artikel</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($comments)): ?>
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Belum ada komentar.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['title']) ?></td>
                    <td><?= htmlspecialchars($c['content']) ?></td>
                    <td><?= date("d M Y", strtotime($c['created_at'])) ?></td>
                    <td>
                        <a href="read_single.php?slug=<?= $c['slug'] ?>" class="btn btn-sm btn-secondary">Lihat</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>

        </table>

    </div>

</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

</body>
</html>
