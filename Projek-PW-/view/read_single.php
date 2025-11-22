<?php
require_once "../controller/proses_read_single.php";
if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <a href="index.php" class="btn btn-secondary mb-4">← Kembali</a>

    <!-- ARTIKEL -->
    <div class="card shadow-sm border-0 mb-4">
        <?php if (!empty($article['image'])): ?>
            <img src="../<?= $article['image'] ?>" class="card-img-top" style="max-height:400px; object-fit:cover;">
        <?php endif; ?>

        <div class="card-body">
            <h1 class="fw-bold"><?= htmlspecialchars($article['title']) ?></h1>

            <p class="text-muted mb-4">
                Oleh <b><?= htmlspecialchars($article['author_name']) ?></b> •
                <?= date("d M Y", strtotime($article['created_at'])) ?>
            </p>

            <div class="mt-3">
                <?= $article['content'] ?>
            </div>
        </div>
    </div>

    <!-- LIKE BUTTON -->
    <form method="post" action="../controller/proses_like.php" class="mt-3">
        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">

        <button type="submit"
            class="btn btn-link p-0 text-decoration-none <?= $userLiked ? 'text-danger' : 'text-secondary' ?>"
            style="font-size: 20px;">

            <?php if ($userLiked): ?>
                <!-- filled heart -->
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                     class="bi bi-heart-fill me-1">
                    <path fill-rule="evenodd"
                          d="M8 1.314C12.438-3.248 23.534 4.735 8 15
                             -7.534 4.736 3.562-3.248 8 1.314z" />
                </svg>
            <?php else: ?>
                <!-- outline heart -->
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                     class="bi bi-heart me-1">
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

            <span class="fw-semibold"><?= $totalLikes ?></span>
        </button>
    </form>

    <!-- KOMENTAR -->
    <h4 class="fw-bold mb-3 mt-4">Komentar (<?= count($comments) ?>)</h4>

    <!-- FORM KOMENTAR -->
    <?php if ($user_id): ?>
        <form action="../controller/proses_coment.php" method="POST" class="mb-4">
            <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
            <input type="hidden" name="slug" value="<?= htmlspecialchars($article['slug']) ?>">

            <textarea name="content" class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>

            <small class="text-muted">Jaga etika saat berkomentar ya 😊.</small>

            <button class="btn btn-primary mt-2">Kirim</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info">
            <a href="login.php"><b>Login</b></a> untuk menulis komentar.
        </div>
    <?php endif; ?>

    <!-- LIST KOMENTAR -->
    <?php if (count($comments) === 0): ?>
        <p class="text-muted">Belum ada komentar. Jadilah yang pertama!</p>
    <?php else: ?>
        <?php foreach ($comments as $c): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between">
                        <div>
                            <b><?= htmlspecialchars($c['commenter_name']) ?></b>
                            <small class="text-muted">• <?= date("d M Y H:i", strtotime($c['created_at'])) ?></small>
                        </div>

                        <?php if ($user_id == $c['user_id']): ?>
                            <form action="../controller/proses_coment.php" method="POST"
                                  onsubmit="return confirm('Hapus komentar ini?');">
                                <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
                                <input type="hidden" name="slug" value="<?= htmlspecialchars($article['slug']) ?>">
                                <button name="action" value="delete" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <p class="mt-2"><?= nl2br(htmlspecialchars($c['content'])) ?></p>

                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
