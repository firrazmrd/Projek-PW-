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

  <a href="read.php" class="btn btn-secondary mb-4">← Kembali</a>

  <!-- ARTIKEL -->
  <div class="card shadow-sm border-0 mb-4">
    <?php if ($article['image']): ?>
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

  <!-- KOMENTAR -->
  <h4 class="fw-bold mb-3">Komentar (<?= count($comments) ?>)</h4>

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
