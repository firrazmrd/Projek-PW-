<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Form Login</h2>

<!-- Pesan Error -->
<?php if (isset($_SESSION['login_error'])): ?>
    <p style="color:red;"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
<?php endif; ?>

<form action="../controller/proses_login.php" method="POST">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<br>
<a href="register.php">Belum punya akun? Daftar di sini</a>

</body>
</html>
