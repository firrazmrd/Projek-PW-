<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../controller/auth.php';

session_start();
require_admin();

// Semua genre yang diizinkan (enum)
$allowed_genres = [
    'Sepak Bola','Basket','Bulu Tangkis','Tenis','Voli',
    'Renang','Atletik','Tinju','MotoGP','Lainnya'
];

// ambil semua data
$title     = trim($_POST['title'] ?? '');
$content   = trim($_POST['content'] ?? '');
$genre     = trim($_POST['genre'] ?? '');
$author_id = 1;

$errors = [];

// validasi field wajib
if ($title === '' || $content === '' || $genre === '') {
    $errors[] = "Semua field wajib diisi (judul, konten, genre).";
}

// validasi genre harus enum
if (!in_array($genre, $allowed_genres)) {
    $errors[] = "Genre tidak valid.";
}

// upload gambar
$imagePath = null;

if (!empty($_FILES['image']['name'])) {

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (!in_array($_FILES['image']['type'], $allowed)) {
        $errors[] = "Tipe gambar harus JPG, PNG, atau WEBP.";
    } else {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $fname  = 'img/' . uniqid('img_') . '.' . $ext;
        $target = __DIR__ . '/../' . $fname;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $errors[] = "Gagal mengupload file gambar.";
        } else {
            $imagePath = $fname;
        }
    }
}

// jika ada error
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../view/create.php");
    exit;
}

  function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text ?: 'n-a';
  }

// slug unik
$slug     = slugify($title);
$original = $slug;
$i = 1;

while (true) {
    $stmt = mysqli_prepare($koneksi, "SELECT id FROM articles WHERE slug = ?");
    mysqli_stmt_bind_param($stmt, "s", $slug);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 0) break;

    mysqli_stmt_close($stmt);
    $slug = $original . '-' . $i++;
}

// insert artikel
$stmt = mysqli_prepare($koneksi, "
    INSERT INTO articles (title, slug, content, image, author_id, genre, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
");

mysqli_stmt_bind_param($stmt, 'ssssss',
    $title, $slug, $content, $imagePath, $author_id, $genre
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../view/create.php?success=1");
} else {
    $_SESSION['errors'] = ["Gagal menyimpan data ke database."];
    header("Location: ../view/create.php");
}


