<?php
require_once __DIR__ . '/../config/koneksi.php';
session_start();
require_once __DIR__ . '/../controller/auth.php';
require_admin();

// pagination
$perPage = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// search
$search = trim($_GET['q'] ?? '');
$where = "";
$param = "";

if ($search !== '') {
    $where = "WHERE title LIKE ? OR content LIKE ?";
    $param = "%$search%";
}

// count total
$sqlCount = "SELECT COUNT(*) AS total FROM articles $where";
$stmt = mysqli_prepare($koneksi, $sqlCount);

if ($search !== '') {
    mysqli_stmt_bind_param($stmt, "ss", $param, $param);
}

mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$totalData = mysqli_fetch_assoc($res)['total'];
mysqli_stmt_close($stmt);

$totalPages = ceil($totalData / $perPage);

// ambil data
$sql = "SELECT * FROM articles $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare( $koneksi, $sql);

if ($search !== '') {
    mysqli_stmt_bind_param($stmt, "ssii", $param, $param, $perPage, $offset);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $perPage, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Artikel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="h3 mb-4">Kelola Artikel</h1>

    <!-- search + create -->
    <div class="d-flex justify-content-between mb-3">
        <form action="" method="GET" class="d-flex" style="gap: 8px;">
            <input type="text" name="q" class="form-control" placeholder="Cari artikel..."
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Search</button>

            <?php if ($search !== ''): ?>
                <a href="kelola.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <a href="create.php" class="btn btn-success">+ Buat Artikel</a>
    </div>

    <!-- tabel -->
    <?php if (empty($articles)): ?>
        <div class="alert alert-info">Belum ada artikel.</div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Thumbnail</th>
                            <th>Genre</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php $no = $offset + 1; ?>
                    <?php foreach ($articles as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($a['title']) ?></td>

                            <td>
                                <?php if ($a['image']): ?>
                                    <img src="../<?= $a['image'] ?>" class="img-thumbnail"
                                         style="width: 80px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($a['genre']) ?></td>

                            <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>

                            <td class="text-center">

                                <!-- EDIT -->
                                <a href="update.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <!-- DELETE -->
                                <form action="../controller/delete.php" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Yakin hapus artikel ini?');">
                                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>

                                <!-- VIEW -->
                                <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>" target="_blank"
                                   class="btn btn-sm btn-secondary">
                                    Lihat
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Pagination -->
        <nav class="mt-3">
            <ul class="pagination">

                <!-- Previous -->
                <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">Prev</a>
                </li>

                <!-- Number -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                        <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next -->
                <li class="page-item <?= ($page >= $totalPages ? 'disabled' : '') ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">Next</a>
                </li>

            </ul>
        </nav>

    <?php endif; ?>
</div>

</body>
</html>
