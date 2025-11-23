<?php
require_once "../controller/proses_read.php";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="theme.css">
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

        /* Tambahkan style dasar Light Mode untuk body agar konsisten */
        body {
            background-color: #f5f6fa;
        }

        .two-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* batas 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

    </style>
</head>

<body id="body">
    <div class="container py-5">

        <button class="btn btn-secondary mb-4" onclick="history.back()">← Kembali</button>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Daftar Artikel</h2>

            <form method="GET" class="d-flex" style="gap: 10px;">
                <input type="text" name="q" class="form-control" placeholder="Cari artikel..."
                    value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-success theme-btn-success">Cari</button>
            </form>
        </div>

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
                        <div class="card shadow-sm border-0 article-list-card"> <?php if (!empty($a['image'])): ?>
                                <img src="../<?= $a['image'] ?>" class="card-img-top" style="height:180px; object-fit:cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white text-center py-5 no-image-placeholder">Tidak ada gambar</div>
                            <?php endif; ?>

                            <div class="card-body">

                                <h5 class="card-title fw-semibold"><?= htmlspecialchars($a['title']) ?></h5>

                                <p class="text-muted small mb-1 "><?= date("d M Y", strtotime($a['created_at'])) ?></p>

                                <p class="card-text two-lines">
                                    <?= substr(strip_tags($a['content']), 0, 100) ?>...
                                </p>

                                <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>"
                                    class="btn btn-success btn-sm theme-btn-success"> Baca Selengkapnya
                                </a>

                                <div class="d-flex align-items-center mt-3">

                                    <button class="like-btn d-flex align-items-center" data-article-id="<?= $a['id'] ?>"
                                        style="border:none; background:none; font-size:22px;">

                                        <ion-icon class="like-icon" name="<?= $hasLiked ? 'heart' : 'heart-outline' ?>"
                                            style="color: <?= $hasLiked ? '#e63946' : '#6c757d' ?>;"></ion-icon> <span
                                            class="fw-semibold ms-1 like-count" style="font-size:18px;">
                                            <?= $countLikes ?>
                                        </span>
                                    </button>



                                    <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>"
                                        class="d-flex align-items-center text-secondary ms-4 comment-link"
                                        style="text-decoration:none; font-size:22px;"> <ion-icon
                                            name="chatbubble-ellipses-outline"></ion-icon>
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
                <div class="alert alert-warning text-center theme-alert">Tidak ada artikel ditemukan</div> <?php endif; ?>
        </div>

        <nav class="mt-4">
            <ul class="pagination justify-content-center">

                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link theme-pagination"
                        href="?page=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">Prev</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link theme-pagination <?= $i == $page ? 'active' : '' ?>"
                            href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link theme-pagination"
                        href="?page=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">Next</a>
                </li>

            </ul>
        </nav>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Fungsi untuk menentukan warna ikon berdasarkan status like dan tema
            function getIconColor(isLiked) {
                const isDarkMode = document.body.classList.contains('dark-mode');
                if (isLiked) {
                    return "#e63946"; // Selalu merah jika disukai
                } else {
                    // Warna default ikon disesuaikan dengan tema
                    return isDarkMode ? "#aaa" : "#6c757d";
                }
            }

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
                            icon.name = data.liked ? "heart" : "heart-outline";
                            icon.style.color = getIconColor(data.liked); // Gunakan fungsi untuk warna

                            // Update jumlah like
                            countSpan.textContent = data.totalLikes;
                        });

                });

            });

        });
    </script>


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script defer src="theme.js"></script>
</body>

</html>