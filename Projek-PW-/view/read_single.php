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
    <link rel="stylesheet" href="theme.css"> <script type="module"
            src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule
            src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        /* BASE STYLES/TRANSITION */
        .comment-item { transition: .25s ease; }
        .comment-item.fade-out { opacity: 0; transform: translateX(-20px); }
        /* Light Mode body background (overrides default Bootstrap bg-light) */
        body {
            background-color: #f5f6fa;
        }
    </style>
</head>

<body id="body"> <div class="container py-5">

    <button class="btn btn-secondary mb-4" onclick="history.back()">← Kembali</button>

    <div class="card shadow-sm border-0 mb-4 article-card"> <?php if (!empty($article['image'])): ?>
            <img src="../<?= $article['image'] ?>" class="card-img-top"
                 style="max-height:400px; object-fit:cover;">
        <?php endif; ?>

        <div class="card-body article-content">
            <h1 class="fw-bold"><?= htmlspecialchars($article['title']) ?></h1>

            <p class="text-muted mb-4">
                Oleh <b><?= htmlspecialchars($article['author_name']) ?></b> •
                <small class="text-muted"><?= date("d M Y", strtotime($article['created_at'])) ?></small>
            </p>

            <div class="mt-3 article-text"><?= $article['content'] ?></div> </div>
    </div>


    <div class="mb-3">
        <button id="likeBtn" class="btn p-0 border-0 bg-transparent"
                style="font-size:28px; display:flex; align-items:center; gap:8px;">

            <ion-icon id="heartIcon"
                     name="<?= $userLiked ? 'heart-sharp' : 'heart-outline' ?>"
                     style="color: <?= $userLiked ? '#e63946' : '#444' ?>;">
            </ion-icon>

            <span id="likeCount" class="like-count-text"><?= $totalLikes ?></span> </button>
    </div>


    <h4 class="fw-bold mb-3 mt-4">Komentar</h4>

    <?php if ($user_id): ?>
        <form id="commentForm" class="mb-4">
            <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
            <input type="hidden" name="slug" value="<?= htmlspecialchars($article['slug']) ?>">

            <textarea name="content" class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>

            <button class="btn btn-primary mt-2">Kirim</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info theme-alert"> <a href="login.php"><b>Login</b></a> untuk menulis komentar.
        </div>
    <?php endif; ?>


    <div id="commentList">
        <?php if (empty($comments)): ?>
            <p class="text-muted">Belum ada komentar.</p>
        <?php else: ?>
            <?php foreach ($comments as $c): ?>
                <div class="card mb-3 shadow-sm comment-item comment-card" id="comment-<?= $c['id'] ?>"> <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <div>
                                <b><?= htmlspecialchars($c['commenter_name']) ?></b>
                                <small class="text-muted comment-date-muted">• <?= date("d M Y H:i", strtotime($c['created_at'])) ?></small>
                            </div>

                            <?php if ($user_id == $c['user_id']): ?>
                                <button class="btn btn-sm btn-danger delete-comment"
                                        data-id="<?= $c['id'] ?>">Hapus</button>
                            <?php endif; ?>
                        </div>

                        <p class="mt-2 comment-content-text"><?= nl2br(htmlspecialchars($c['content'])) ?></p> </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>


<script>
// ... (Skrip AJAX yang ada tetap sama)
// ==================== LIKE AJAX ====================
document.getElementById("likeBtn").addEventListener("click", function () {

    fetch("../controller/proses_like.php", {
        method: "POST",
        body: new URLSearchParams({
            article_id: "<?= $article['id'] ?>"
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) return;

        const icon  = document.getElementById("heartIcon");
        const count = document.getElementById("likeCount");

        // Logic warna ikon berdasarkan status like
        let iconColor = data.liked ? "#e63946" : document.body.classList.contains('dark-mode') ? "#aaa" : "#444";
        
        icon.name = data.liked ? "heart-sharp" : "heart-outline";
        icon.style.color = iconColor;
        count.textContent = data.totalLikes;
    });
});


// ==================== KOMENTAR - ADD AJAX ====================
document.getElementById("commentForm")?.addEventListener("submit", function (e) {
    e.preventDefault();

    fetch("../controller/proses_coment.php", {
        method: "POST",
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {

        if (data.error) return alert(data.error);

        // Tambahkan komentar baru ke halaman tanpa refresh
        let html = `
            <div class="card mb-3 shadow-sm comment-item comment-card" id="comment-${data.id}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <b>${data.name}</b>
                            <small class="text-muted comment-date-muted">• baru saja</small>
                        </div>

                        <button class="btn btn-sm btn-danger delete-comment" data-id="${data.id}">
                            Hapus
                        </button>
                    </div>
                    <p class="mt-2 comment-content-text">${data.content}</p>
                </div>
            </div>`;

        document.getElementById("commentList").insertAdjacentHTML("afterbegin", html);

        this.reset(); // reset form
    });
});


// ==================== KOMENTAR - DELETE AJAX ====================
document.addEventListener("click", function (e) {
    if (!e.target.classList.contains("delete-comment")) return;

    let id = e.target.dataset.id;
    if (!confirm("Hapus komentar ini?")) return;

    fetch("../controller/proses_coment.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "delete",
            delete_id: id,
            slug: "<?= $article['slug'] ?>"
        })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) return;

        let item = document.getElementById("comment-" + id);
        item.classList.add("fade-out");

        setTimeout(() => item.remove(), 250);
    });
});
</script>
<script defer src="theme.js"></script> </body>
</html>