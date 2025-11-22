<?php 
require_once "../controller/proses_read.php"; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <!-- HEADER + SEARCH -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Artikel</h2>

        <form method="GET" class="d-flex" style="gap: 10px;">
            <input type="text" name="q" class="form-control" placeholder="Cari artikel..."
                   value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary">Cari</button>
        </form>
    </div>

    <!-- ARTIKEL LIST -->
    <div class="row g-4">
        <?php if (count($articles) > 0): ?>
            
            <?php foreach ($articles as $a): ?>
                <?php
                    $aid           = $a['id'];
                    $countLikes    = $likesCount[$aid]    ?? 0;
                    $countComments = $commentsCount[$aid] ?? 0;
                    $hasLiked      = !empty($userLiked[$aid]);
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
                        <h5 class="card-title"><?= htmlspecialchars($a['title']) ?></h5>

                        <!-- TANGGAL -->
                        <p class="text-muted small mb-1"><?= date("d M Y", strtotime($a['created_at'])) ?></p>

                        <!-- EXCERPT -->
                        <p class="card-text">
                            <?= substr(strip_tags($a['content']), 0, 100) ?>...
                        </p>

                        <!-- BACA LENGKAP -->
                        <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            Baca Selengkapnya
                        </a>

                        <!-- LIKE & COMMENT -->
                        <div class="d-flex align-items-center mt-2">

                            <!-- LIKE BUTTON -->
                            <form method="post" action="../controller/proses_like.php" class="d-inline">
                                <input type="hidden" name="article_id" value="<?= $aid ?>">

                                <button type="submit" 
                                        class="btn btn-link p-0 text-decoration-none 
                                               <?= $hasLiked ? 'text-danger' : 'text-secondary' ?>">

                                    <?php if ($hasLiked): ?>
                                        <!-- filled heart -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                             fill="currentColor" class="bi bi-heart-fill me-1">
                                            <path fill-rule="evenodd"
                                                d="M8 1.314C12.438-3.248 23.534 4.735 8 15
                                                -7.534 4.736 3.562-3.248 8 1.314z" />
                                        </svg>
                                    <?php else: ?>
                                        <!-- outline heart -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                             fill="currentColor" class="bi bi-heart me-1">
                                            <path
                                                d="m8 2.748-.717-.737C5.6.281 2.514.878 
                                                  1.4 3.053c-.523 1.023-.641 2.5.314 
                                                  4.385.92 1.815 2.834 3.989 6.286 
                                                  6.357 3.452-2.368 5.365-4.542 
                                                  6.286-6.357.955-1.886.838-3.362.314
                                                  -4.385C13.486.878 10.4.28 
                                                  8.717 2.01L8 2.748zM8 15C-7.333 
                                                  4.868 3.279-3.04 7.824 1.143z" />
                                        </svg>
                                    <?php endif; ?>

                                    <span class="fw-semibold"><?= $countLikes ?></span>
                                </button>
                            </form>

                            <!-- COMMENT ICON -->
                            <a href="read_single.php?slug=<?= urlencode($a['slug']) ?>" 
                               class="btn btn-link p-0 text-decoration-none text-secondary ms-4">

                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                     fill="currentColor" class="bi bi-chat-dots me-1">
                                    <path d="M2.678 11.894a1 1 0 0 1 .287.801A.5.5 
                                            0 0 0 3.46 13h5.982c.4 0 .778-.158 
                                            1.06-.44l3.83-3.83a2 2 0 0 0 .588-1.414V4a2 
                                            2 0 0 0-2-2H4a2 2 0 0 0-2 2v5.586c0 
                                            .53.21 1.039.586 1.414l.092.092z" />
                                    <path d="M5 6.5a1 1 0 1 1-2 0 1 1 
                                            0 0 1 2 0zm3 0a1 1 0 1 1-2 
                                            0 1 1 0 0 1 2 0zm3 0a1 1 
                                            0 1 1-2 0 1 1 0 0 1 2 0z" />
                                </svg>

                                <span class="fw-semibold"><?= $countComments ?></span>
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
                <a class="page-link" href="?page=<?= $page - 1 ?>&q=<?= urlencode($search) ?>">Prev</a>
            </li>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>&q=<?= urlencode($search) ?>">Next</a>
            </li>

        </ul>
    </nav>

</div>

</body>
</html>
