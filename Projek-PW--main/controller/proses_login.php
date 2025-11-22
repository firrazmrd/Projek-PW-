<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/login.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi field kosong 
if ($email === '' || $password === '') {
    $_SESSION['login_error'] = "Email & password wajib diisi!";
    header("Location: ../view/login.php");
    exit;
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = "Format email tidak valid!";
    header("Location: ../view/login.php");
    exit;
}

// Ambil user berdasarkan email 
$stmt = mysqli_prepare($koneksi, "
    SELECT id, name, email, password_hash, role 
    FROM users 
    WHERE email = ?
");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

// Ambil hasil
mysqli_stmt_bind_result($stmt, $id, $name, $db_email, $password_hash, $role);
$found = mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

//Jika email tidak ditemukan 
if (!$found) {
    $_SESSION['login_error'] = "Email atau password salah!";
    header("Location: ../view/login.php");
    exit;
}

// Verifikasi password
if (!password_verify($password, $password_hash)) {
    $_SESSION['login_error'] = "Email atau password salah!";
    header("Location: ../view/login.php");
    exit;
}

// Login sukses 
session_regenerate_id(true);
$_SESSION['user_id']    = $id;
$_SESSION['user_name']  = $name;
$_SESSION['user_email'] = $db_email;
$_SESSION['user_role']  = $role;

// Redirect berdasarkan role
if ($role === 'admin') {
    header("Location: ../view/admin/admin_dashboard.php");
    exit;
} else {
    header("Location: ../view/index.php");
    exit;
}
?>
