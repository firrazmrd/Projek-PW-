<?php
session_start();
require "../config/koneksi.php";

// **PENTING:** Membiarkan ob_start() di sini membantu mencegah karakter kosong**
ob_start();
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

// Pagination
$perPage = 5;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Search
$search = trim($_GET['q'] ?? '');
$where = "";
$param = "";

if ($search !== "") {
    $where = "WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
}

// Total data
$sqlCount = "SELECT COUNT(*) as total FROM articles $where";
$resCount = mysqli_query($koneksi, $sqlCount);
$totalData = mysqli_fetch_assoc($resCount)['total'];

$totalPages = ceil($totalData / $perPage);

// Data utama
$sql = "SELECT * FROM articles $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$res = mysqli_query($koneksi, $sql);
$articles = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Artikel</title>

    <link rel="stylesheet" href="theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* === HANYA LIGHT MODE / BASE STYLES DI SINI === */
        body {
            font-family: "Poppins", sans-serif;
            background: #f5f6fa;
        }

        /* SIDEBAR */
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

        /* MAIN AREA */
        .main {
            margin-left: 260px;
            padding: 40px;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 7px rgba(0, 0, 0, 0.07);
        }

        table thead {
            background: #e5e5e5;
        }

        thead th {
            padding: 14px;
            font-weight: 600;
        }

        tbody td {
            padding: 14px;
            vertical-align: middle;
        }

        /* BUTTONS */
        .btn-edit {
            background: #f1c40f;
            color: black;
            border: none;
            padding: 5px 15px;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-view {
            background: #7f8c8d;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-create {
            background: #0d6efd;
            padding: 10px 20px;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
        }

        .table-title {
            max-width: 300px;
            /* sesuaikan lebar kolom */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            /* muncul ... */
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

        <a href="kelola.php" class="nav-link active">
            <ion-icon name="newspaper-outline"></ion-icon> Kelola Artikel
        </a>

        <a href="kelola_komentar.php" class="nav-link">
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
        <h1 class="fw-bold">Kelola Artikel</h1>

        <div class="d-flex justify-content-between mt-4 mb-3">
            <form method="GET" class="d-flex" style="gap:10px;">
                <input type="text" name="q" class="form-control" placeholder="Cari artikel..."
                    value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-success">Search</button>
            </form>

            <a href="create.php" class="btn-create">+ Buat Artikel</a>
        </div>

        <div class="table-container mt-3" id="article-table-container">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Thumbnail</th>
                        <th>Genre</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">Belum ada artikel.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = $offset + 1; ?>
                        <?php foreach ($articles as $a): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="table-title"><?= htmlspecialchars($a['title']) ?></td>

                                <td>
                                    <?php if ($a['image']): ?>
                                        <img src="../<?= $a['image'] ?>" width="90" height="60"
                                            style="border-radius:8px;object-fit:cover;">
                                    <?php else: ?>
                                        <span class="text-muted">No Image</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($a['genre']) ?></td>
                                <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>

                                <td>
                                    <a href="update.php?id=<?= $a['id'] ?>" class="btn-edit">Edit</a>

                                    <form action="../controller/delete.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Yakin hapus artikel ini?')">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <button class="btn-delete">Hapus</button>
                                    </form>

                                    <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>" class="btn-view"
                                        target="">Lihat</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center gap-2">
            <a class="btn btn-outline-secondary <?= ($page <= 1) ? 'disabled' : '' ?>"
                href="?page=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">Prev</a>

            <button class="btn btn-primary"><?= $page ?></button>

            <a class="btn btn-outline-secondary <?= ($page >= $totalPages) ? 'disabled' : '' ?>"
                href="?page=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">Next</a>
        </div>

    </div>

    <script defer src="theme.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>
<?php
ob_end_flush();
?>