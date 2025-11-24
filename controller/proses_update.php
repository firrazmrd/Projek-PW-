<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../controller/auth.php';
session_start();
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../view/kelola.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$genre = trim($_POST['genre'] ?? '');
$errors = [];

// Validasi sederhana
if ($id <= 0 || $title === '' || $content === '' || $genre === '') {
    $errors[] = "Semua field wajib diisi!";
}

// Ambil data lama
$stmt = mysqli_prepare($koneksi, "SELECT * FROM articles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$old = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$old) {
    $errors[] = "Artikel tidak ditemukan!";
}

// Jika ada error → redirect
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../view/edit.php?id=$id");
    exit;
}

// Upload gambar (opsional)
$imagePath = $old['image'];

if (!empty($_FILES['image']['name'])) {
    $allowed = ['image/jpeg','image/png','image/webp'];

    if (!in_array($_FILES['image']['type'], $allowed)) {
        $errors[] = "Tipe gambar tidak valid!";
    } else {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname = 'img/' . uniqid('img_') . '.' . $ext;
        $target = __DIR__ . '/../' . $fname;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // hapus gambar lama
            if (!empty($old['image']) && file_exists(__DIR__ . '/../' . $old['image'])) {
                unlink(__DIR__ . '/../' . $old['image']);
            }
            $imagePath = $fname;
        } else {
            $errors[] = "Gagal upload gambar!";
        }
    }
}

// Jika upload error
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../view/edit.php?id=$id");
    exit;
}

// Update data
$stmt = mysqli_prepare($koneksi, "
    UPDATE articles 
    SET title = ?, content = ?, genre = ?, image = ?, updated_at = NOW()
    WHERE id = ?
");
mysqli_stmt_bind_param($stmt, "ssssi", $title, $content, $genre, $imagePath, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: ../view/kelola.php?updated=1");
exit;
