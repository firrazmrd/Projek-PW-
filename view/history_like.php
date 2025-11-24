<?php
session_start();
require "../config/koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$q = mysqli_query($koneksi, "SELECT name, role FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($q);

$user_name = $user['name'];
$user_role = $user['role'];

$sql = "SELECT likes.id, articles.title, articles.genre, articles.image, articles.created_at, articles.slug
        FROM likes
        JOIN articles ON likes.article_id = articles.id
        WHERE likes.user_id = $user_id
        ORDER BY likes.id DESC";

$result = mysqli_query($koneksi, $sql);
$likes = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>History Like</title>

<link rel="stylesheet" href="theme.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
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
    .sidebar.dark-mode .nav-link[style*="red"] {
            color: #ff6b6b !important;
        }
</style>

</head>
<body>

<div class="sidebar theme-card" id="sidebar">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../img/logo.png" width="35" class="me-2">
            <strong>Sportify</strong>
        </a>
        <br>

        <a href="dashboard_user.php" class="nav-link">
            <ion-icon name="home-outline"></ion-icon> Dashboard
        </a>

        <a href="history_like.php" class="nav-link active">
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

    <h1 class="fw-bold mb-4">History Like</h1>

    <div class="card p-4 shadow-sm rounded-4">

        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Thumbnail</th>
                    <th>Genre</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($likes)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada history Like.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($likes as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>

                        <td>
                            <img src="../<?= $item['image'] ?>" class="img-thumbnail"
                                style="width: 80px; height: 50px; object-fit: cover;">
                        </td>

                        <td><?= htmlspecialchars($item['genre']) ?></td>

                        <td><?= date("d M Y", strtotime($item['created_at'])) ?></td>

                        <td>
                            <a href="read_single.php?slug=<?= $item['slug'] ?>" class="btn btn-sm btn-secondary">Lihat</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

<script defer src="theme.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>
