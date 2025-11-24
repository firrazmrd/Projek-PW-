<?php
session_start();
require_once __DIR__ . '/../controller/auth.php';
require_admin();
require_once __DIR__ . '/../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: kelola.php");
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM articles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$article) {
    header("Location: kelola.php");
    exit;
}

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Artikel</title>

<style>
    body { 
        font-family: "Poppins"; 
        background-color: #f5f6fa;
    }
</style>

<link rel="stylesheet" href="theme.css"> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">

</head>
<body id="body"> <div class="container py-5">

    <a href="kelola.php" class="btn btn-secondary mb-4 theme-btn-secondary">← Kembali</a> <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="h3 mb-4 fw-bold">Edit Artikel</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger theme-alert"> <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm theme-card"> <div class="card-body">

                    <form action="../controller/proses_update.php" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?= $article['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul</label>
                            <input type="text" name="title" class="form-control"
                                       value="<?= htmlspecialchars($article['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="genre" class="form-select" required>
                                <?php
                                $genres = [
                                    'Sepak Bola','Basket','Bulu Tangkis','Tenis','Voli',
                                    'Renang','Atletik','Tinju','MotoGP','Lainnya'
                                ];
                                foreach ($genres as $g):
                                ?>
                                    <option value="<?= $g ?>" <?= ($article['genre'] == $g ? "selected" : "") ?>>
                                        <?= $g ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Isi Artikel</label>
                            <textarea id="editor" name="content" class="form-control" rows="10">
                                <?= htmlspecialchars($article['content']) ?>
                            </textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar Saat Ini</label><br>

                            <?php if ($article['image']): ?>
                                <img src="../<?= $article['image'] ?>" class="img-thumbnail mb-2 theme-img-thumbnail"
                                     style="width: 200px;"> <?php else: ?>
                                <p class="text-muted">Tidak ada gambar</p>
                            <?php endif; ?>

                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-success px-4 theme-btn-success"> Simpan Perubahan
                            </button>

                            <a href="kelola.php" class="btn btn-secondary theme-btn-secondary"> Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/2cavu4bix6n6mqx2dtna24bn9uabud3w2sdzqkeiw6wiszzi/tinymce/6/tinymce.min.js"></script>
<script>
let tinymceConfig = {
    selector: '#editor',
    height: 400,
    plugins: 'image link media table lists code',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
    menubar: false,
};

/* manggil tiny ace */
const isDarkMode = document.body.classList.contains('dark-mode');

tinymceConfig.skin = 'oxide';
tinymceConfig.content_css = 'default';

tinymce.init(tinymceConfig);
</script>

<script defer src="theme.js"></script>
</body>
</html>