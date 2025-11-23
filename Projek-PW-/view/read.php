<?php
require_once "../controller/proses_read.php";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <style>
        .btn-icon {
            padding: 0;
            border: none;
            background: transparent;
        }

        .icon-liked {
            color: #e63946 !important;
        }

        .icon-default {
            color: #6c757d !important;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">

        <!-- TOMBOL KEMBALI -->
        <button class="btn btn-success mb-4" onclick="history.back()">← Kembali</button>

        <!-- HEADER + SEARCH -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Daftar Artikel</h2>

            <form method="GET" class="d-flex" style="gap: 10px;">
                <input type="text" name="q" class="form-control" placeholder="Cari artikel..."
                    value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-success">Cari</button>
            </form>
        </div>

        <!-- ARTIKEL LIST -->
        <div class="row g-4">
            <?php if (count($articles) > 0): ?>

                <?php foreach ($articles as $a): ?>
                    <?php
                    $aid = $a['id'];
                    $countLikes = $likesCount[$aid] ?? 0;
                    $countComments = $commentsCount[$aid] ?? 0;
                    $hasLiked = !empty($userLiked[$aid]);
                    ?>

                    <div class="col-sm-6 col-lg-4">
                        <div class="card shadow-sm border-0">

                            <!-- GAMBAR -->
                            <?php if (!empty($a['image'])): ?>
                                <img src="../<?= $a['image'] ?>" class="card-img-top" style="height:180px; object-fit:cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white text-center py-5">Tidak ada gambar</div>
                            <?php endif; ?>

                            <div class="card-body">

                                <!-- JUDUL -->
                                <h5 class="card-title fw-semibold"><?= htmlspecialchars($a['title']) ?></h5>

                                <!-- TANGGAL -->
                                <p class="text-muted small mb-1"><?= date("d M Y", strtotime($a['created_at'])) ?></p>

                                <!-- EXCERPT -->
                                <p class="card-text">
                                    <?= substr(strip_tags($a['content']), 0, 100) ?>...
                                </p>

                                <!-- BACA LENGKAP -->
                                <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>" class="btn btn-success btn-sm">
                                    Baca Selengkapnya
                                </a>

                                <!-- LIKE & COMMENT -->
                                <div class="d-flex align-items-center mt-3">

                                    <!-- ICON LIKE (menuju read_single) -->
                                    <button class="like-btn d-flex align-items-center" data-article-id="<?= $a['id'] ?>"
                                        style="border:none; background:none; font-size:22px; color:<?= $hasLiked ? 'red' : 'gray' ?>">

                                        <ion-icon class="like-icon"
                                            name="<?= $hasLiked ? 'heart' : 'heart-outline' ?>"></ion-icon>

                                        <span class="fw-semibold ms-1 like-count" style="font-size:18px;">
                                            <?= $countLikes ?>
                                        </span>
                                    </button>



                                    <!-- ICON KOMENTAR -->
                                    <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>"
                                        class="d-flex align-items-center text-secondary ms-4"
                                        style="text-decoration:none; font-size:22px;">

                                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                                        <span class="fw-semibold ms-1" style="font-size:18px;">
                                            <?= $countComments ?>
                                        </span>
                                    </a>

                                </div>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>
                <div class="alert alert-warning text-center">Tidak ada artikel ditemukan</div>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <nav class="mt-4">
            <ul class="pagination justify-content-center">

                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link btn-success text-white"
                        href="?page=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">Prev</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link btn-success text-white"
                            href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link btn-success text-white"
                        href="?page=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">Next</a>
                </li>

            </ul>
        </nav>

    </div>

    <!-- YANG DI UBAH TADI PAGI -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            document.querySelectorAll(".like-btn").forEach(btn => {

                btn.addEventListener("click", function () {

                    let articleId = this.dataset.articleId;
                    let icon = this.querySelector(".like-icon");
                    let countSpan = this.querySelector(".like-count");

                    fetch("../controller/proses_like.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "article_id=" + articleId
                    })
                        .then(res => res.json())
                        .then(data => {

                            if (data.error === "not_logged_in") {
                                window.location.href = "login.php";
                                return;
                            }

                            // Update icon
                            if (data.liked) {
                                icon.name = "heart";
                                icon.style.color = "#e63946";
                            } else {
                                icon.name = "heart-outline";
                                icon.style.color = "#6c757d";
                            }

                            // Update jumlah like
                            countSpan.textContent = data.totalLikes;
                        });

                });

            });

        });
    </script>



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="theme.js"></script>
</body>

</html>