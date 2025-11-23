<?php
session_start();
require "../config/koneksi.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user
$user_id = $_SESSION['user_id'];
$q = mysqli_query($koneksi, "SELECT name, role FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($q);

$user_name = $user['name'];
$user_role = $user['role'];

// Ambil komentar
$sql = "SELECT comments.id, comments.content, users.name AS user_name 
        FROM comments 
        LEFT JOIN users ON comments.user_id = users.id
        ORDER BY comments.id DESC";

$result = mysqli_query($koneksi, $sql);
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Komentar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="theme.css"> 

    <style>
        /* === LIGHT MODE / BASE STYLES === */
        body {
            font-family: "Poppins", sans-serif;
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
            color: #4b4b4b;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #19875419;
            color: #198754;
        }

        .sidebar .logout {
            color: #d9534f !important;
            font-weight: 600;
        }

        .sidebar .bottom-box {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ececec;
            color: #666;
            font-size: 14px;
        }

        .main {
            margin-left: 260px;
            padding: 40px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 7px rgba(0,0,0,0.08);
        }

        table thead {
            background: #e5e5e5;
            /* Hapus border-radius: 12px agar konsisten */
        }

        table thead th {
            font-weight: 600;
            padding: 14px; /* Konsisten dengan kelola.php */
        }

        /* LIGHT MODE ZEBRA STRIPING */
        table tbody td {
            padding: 14px; /* Konsisten dengan kelola.php */
            vertical-align: middle;
            /* Background diatur oleh TR melalui TD untuk Light Mode */
        }
        
        table tbody tr:nth-child(odd) td {
            background: #f8f8f8;
        }

        table tbody tr:nth-child(even) td {
            background: #ececec;
        }

        .btn-delete {
            background: #e74c3c;
            border: none;
            padding: 5px 15px;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }

        .btn-delete:hover {
            background: #c0392b;
        }

    </style>
</head>

<body id="body">

    <div class="sidebar" id="sidebar">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../img/logo.png" width="35" class="me-2">
            <strong>Sportify</strong>
        </a>

        <br>

        <a href="dashboard_admin.php" class="nav-link">
            <ion-icon name="home-outline"></ion-icon> Dashboard
        </a>

        <a href="kelola.php" class="nav-link">
            <ion-icon name="newspaper-outline"></ion-icon> Kelola Artikel
        </a>

        <a href="kelola_komentar.php" class="nav-link active">
            <ion-icon name="chatbubble-ellipses-outline"></ion-icon> Komentar
        </a>

        <a href="../controller/logout.php" class="nav-link logout">
            <ion-icon name="log-out-outline"></ion-icon> Logout
        </a>

        <div class="bottom-box mt-4">
            <p>Signed in as</p>
            <div class="d-flex align-items-center gap-2">
                <ion-icon name="person-circle-outline" style="font-size: 30px;"></ion-icon>
                <div>
                    <strong><?= htmlspecialchars($user_name) ?></strong><br>
                    <small>Role: <?= htmlspecialchars($user_role) ?></small>
                </div>
            </div>

            <div class="mt-4">
                <a href="index.php" class="nav-link">
                    <ion-icon name="home-outline"></ion-icon> Kembali Halaman Utama
                </a>
            </div>
        </div>
    </div>

    <div class="main" id="main-content">
        <h1 class="fw-bold">Kelola Komentar</h1>

        <div class="table-container mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Isi Komentar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($comments)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">Belum ada komentar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['user_name'] ?? 'Anonim') ?></td>
                                <td><?= htmlspecialchars($c['content']) ?></td>
                                <td class="text-center">
                                    <form action="../controller/delete_comment.php" method="POST"
                                            onsubmit="return confirm('Hapus komentar ini?')" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button class="btn-delete">hapus</button>
                                    </form>
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