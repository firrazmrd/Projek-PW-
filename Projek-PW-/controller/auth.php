<?php
// app/Auth.php
// Simple auth helper for page protection.
// Usage: include __DIR__ . '/../app/Auth.php'; require_login(); or require_admin();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// load helper functions (assume functions.php berada di folder yang sama: app/)
// if (!function_exists('e')) {
//     // safe include: require_once __DIR__ . '/functions.php';
//     // jika functions.php ada di app/functions.php
//     require_once __DIR__ . '/functions.php';
// }

function is_logged_in() {
  return isset($_SESSION['user_id']);
}

function current_user_id() {
  return $_SESSION['user_id'] ?? null;
}

function current_user_role() {
  return $_SESSION['user_role'] ?? null;
}

function current_user_name() {
  return $_SESSION['user_name'] ?? null;
}

// ini kalau diminta login
// ini syarat login biasa atau user
function require_login() {
  if (!is_logged_in()) {
    header('Location: /Pert3-web-blog/public/login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
  }
}

// hanya admin yang bisa masuk disni
// ini syarat login admin
function require_admin() {
  // wajib login
  require_login();
  // kalau misal kita bukan admin
  if (current_user_role() !== 'admin') {
    // simple forbidden page
    header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
    echo "<h1>403 - Akses ditolak</h1><p>Halaman ini hanya untuk admin.</p>";
    exit;
  }
}

//  buat mengatur login admin dan user
//  buat mengatur proses login ataupun register
//  middleware / helper auth
//  Digunakan di halaman yang butuh proteksi.