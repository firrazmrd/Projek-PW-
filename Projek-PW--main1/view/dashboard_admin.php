<?php
session_start();

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../controller/auth.php';

require_admin();

require "../config/koneksi.php";  // sesuaikan path bila perlu

// ===== CEK LOGIN =====
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ===== AMBIL DATA USER DARI DATABASE =====
$user_id = $_SESSION['user_id'];
$query = mysqli_query($koneksi, "SELECT name, role FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);

$user_name = $data['name'];
$user_role = $data['role']; // admin / user
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

        .sidebar .brand {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: #4b4b4b;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #19875419;
            color: #198754;
        }

        .sidebar .bottom-box {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ececec;
            color: #666;
            font-size: 14px;
        }

        .sidebar .logout {
            color: #d9534f !important;
            font-weight: 600;
        }

        .main {
            margin-left: 260px;
            padding: 40px;
        }

        .main h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .main p {
            color: #666;
        }

        .card-custom {
            padding: 25px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 0 7px rgba(0,0,0,0.08);
            border: none;
            transition: .2s;
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .card-custom h4 {
            font-size: 20px;
            font-weight: 600;
        }

        .card-custom a {
            color: #198754;
            font-weight: 500;
            text-decoration: none;
        }

        .card-custom a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

    <div class="sidebar">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../img/logo.png" alt="Logo" width="35" height="35" class="me-2">
            <strong>Sportify</strong>
        </a>
        <br>

        <a href="dashboard_admin.php" class="nav-link active"><ion-icon name="home-outline"></ion-icon> Dashboard</a>
        <a href="kelola.php" class="nav-link"><ion-icon name="newspaper-outline"></ion-icon> Kelola Artikel</a>
        <a href="kelola_komentar.php" class="nav-link"><ion-icon name="chatbubble-ellipses-outline"></ion-icon> Komentar</a>
        <a href="../controller/logout.php" class="nav-link logout"><ion-icon name="log-out-outline"></ion-icon> Logout</a>

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
        <h1>Admin Dashboard</h1>
        <p>Selamat datang, <?= htmlspecialchars($user_name) ?> — role: <?= htmlspecialchars($user_role) ?></p>

        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <div class="card-custom">
                    <h4>Kelola Artikel</h4>
                    <p>Tambah / edit / hapus artikel.</p>
                    <a href="kelola.php">Buka manajemen artikel →</a>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card-custom">
                    <h4>Kelola Komentar</h4>
                    <p>Review pesan atau komentar dari pengunjung.</p>
                    <a href="kelola_komentar.php">Lihat komentar →</a>
                </div>
            </div>
        </div>

    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>