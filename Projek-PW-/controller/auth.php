<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// WAJIB LOGIN
function require_login() {
  if (!is_logged_in()) {
    header('Location: ../view/login.php');
    exit;
  }
}

// HANYA ADMIN
function require_admin() {
  require_login();

  if (current_user_role() !== 'admin') {
    // JANGAN 403! ARAHKAN KE DASHBOARD USER
    header("Location: ../view/dashboard_user.php");
    exit;
  }
}
