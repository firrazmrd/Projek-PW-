<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

require_once __DIR__ . '/../controller/auth.php';
require_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Buat Artikel</title>

    <style>
        body {
            font-family: "Poppins";
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="theme.css"> 

</head>
<body id="body" class="light-mode"> 

<div class="container py-5">

    <a href="kelola.php" class="btn btn-secondary mb-4 theme-btn-secondary">← Kembali</a>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h1 class="h3 mb-4 fw-bold">Buat Artikel</h1>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger theme-alert"> 
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success theme-alert"> Artikel berhasil disimpan!
            </div>
            <?php endif; ?>

            <div class="card shadow-sm theme-card">
                <div class="card-body">

                    <form action="../controller/proses_create.php" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="genre" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Sepak Bola">Sepak Bola</option>
                                <option value="Basket">Basket</option>
                                <option value="Bulu Tangkis">Bulu Tangkis</option>
                                <option value="Tenis">Tenis</option>
                                <option value="Voli">Voli</option>
                                <option value="Renang">Renang</option>
                                <option value="Atletik">Atletik</option>
                                <option value="Tinju">Tinju</option>
                                <option value="MotoGP">MotoGP</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Isi Artikel</label>
                            <textarea id="editor" name="content" class="form-control" rows="10"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar Utama</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4 theme-btn-success">Simpan</button>

                            <a href="kelola.php" class="btn btn-secondary theme-btn-secondary">Batal</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/2cavu4bix6n6mqx2dtna24bn9uabud3w2sdzqkeiw6wiszzi/tinymce/6/tinymce.min.js"></script>

<script>
/* manggil tiny */ 
let tinymceConfig = {
    selector: '#editor',
    height: 400,
    plugins: 'image link media table lists code',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
    menubar: false,
};

    tinymceConfig.skin = 'oxide';
    tinymceConfig.content_css = 'default';

tinymce.init(tinymceConfig);
</script>
<script defer src="theme.js"></script> 

</body>
</html>