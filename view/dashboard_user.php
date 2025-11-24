<?php
session_start();
require "../config/koneksi.php";
require_once __DIR__ . '/../controller/auth.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($koneksi, "SELECT name, role FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$user_name = htmlspecialchars($user['name']);
$user_role = htmlspecialchars($user['role']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="theme.css"> 
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">

    <style>
        /* ori light */
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
            z-index: 1000;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: #4b4b4b;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #19875419;
            color: #198754;
        }

        .main {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }
        
        /* dark mode */
        body.dark-mode {
            background: #121212 !important;
        }
        .main.dark-mode h1, 
        .main.dark-mode p {
            color: white !important;
        }

        .sidebar.dark-mode {
            background: #212121 !important;
            border-right: 1px solid #444 !important;
        }
        
        .sidebar.dark-mode .nav-link {
            color: #ffffff !important; 
        }

        .sidebar.dark-mode .nav-link:hover,
        .sidebar.dark-mode .nav-link.active {
            background: #19875440 !important;
            color: #4cd484 !important;
        }
        .sidebar.dark-mode .nav-link[style*="red"] {
            color: #ff6b6b !important;
        }
        .sidebar.dark-mode .bottom-box p, 
        .sidebar.dark-mode .bottom-box strong,
        .sidebar.dark-mode .bottom-box small,
        .sidebar.dark-mode .bottom-box ion-icon {
            color: white !important;
        }
        .sidebar.dark-mode .navbar-brand strong {
            color: white !important;
        }

        .card.theme-card.dark-mode {
            background: #2b2b2b !important;
            border-color: #444 !important;
            color: white !important;
            box-shadow: 0 0 7px rgba(255,255,255,0.1);
        }
        .card.theme-card.dark-mode h4,
        .card.theme-card.dark-mode p {
            color: white !important;
        }
        .card.theme-card.dark-mode a.text-success {
            color: #4cd484 !important;
        }

    </style>

</head>
<body id="body" class="light-mode"> 

    <div class="sidebar theme-card" id="sidebar"> 
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
                    <strong><?= $user_name ?></strong> <br>
                    <small>Role: <?= $user_role ?></small>
                </div>
            </div>

            <div class="mt-4">
                <a href="index.php" class="nav-link"><ion-icon name="home-outline"></ion-icon> Kembali Halaman Utama</a>
            </div>
        </div>
    </div>

    <div class="main dark-mode"> 
        <h1 class="fw-bold">User Dashboard</h1>
        <p>Selamat datang, <?= $user_name ?></p>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card p-4 shadow-sm theme-card"> 
                    <h4>Riwayat Like</h4>
                    <p>Lihat daftar artikel yang pernah kamu like.</p>
                    <a href="history_like.php" class="text-success">Lihat history like →</a>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card p-4 shadow-sm theme-card"> 
                    <h4>Komentar Saya</h4>
                    <p>Cek komentar yang pernah kamu kirim.</p>
                    <a href="history_komentar.php" class="text-success">Lihat komentar →</a>
                </div>
            </div>
        </div>
    </div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script src="theme.js"></script> 
</body>
</html>