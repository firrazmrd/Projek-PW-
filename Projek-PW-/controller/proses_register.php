<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Pastikan request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/register.php');
    exit;
}

$nama  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';
$pass2 = $_POST['password2'] ?? '';

// --- Validasi field kosong ---
if ($nama === '' || $email === '' || $pass === '' || $pass2 === '') {
    $_SESSION['register_error'] = "Semua field wajib diisi!";
    header("Location: ../view/register.php");
    exit;
}

// --- Validasi email ---
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = "Email tidak valid!";
    header("Location: ../view/register.php");
    exit;
}

//Validasi panjang password minimal 3 karakter
if (strlen($pass) < 3) {
    $_SESSION['register_error'] = "Password minimal 3 karakter!";
    header("Location: ../view/register.php");
    exit;
}

// Validasi konfirmasi password 
if ($pass !== $pass2) {
    $_SESSION['register_error'] = "Konfirmasi password tidak cocok!";
    header("Location: ../view/register.php");
    exit;
}

// Cek apakah email sudah terdaftar
$stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    $_SESSION['register_error'] = "Email sudah digunakan!";
    header("Location: ../view/register.php");
    exit;
}
mysqli_stmt_close($stmt);

// Hash password
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Simpan user baru
$stmt = mysqli_prepare($koneksi,
    "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'user')"
);

if (!$stmt) {
    $_SESSION['register_error'] = "Terjadi kesalahan server!";
    header("Location: ../view/register.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $hashed_password);
$ok = mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($koneksi);

// --- Redirect ---
if ($ok) {
    $_SESSION['register_success'] = "Register berhasil! Silakan login.";
    header("Location: ../view/login.php");
} else {
    $_SESSION['register_error'] = "Gagal membuat akun!";
    header("Location: ../view/register.php");
}
exit;
?>
