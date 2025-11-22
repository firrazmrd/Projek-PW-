<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
</head>
<body>

<h2>Form Registrasi</h2>

<!-- Pesan Error -->
<?php if (isset($_SESSION['register_error'])): ?>
    <p style="color:red;"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
<?php endif; ?>

<!-- Pesan Sukses -->
<?php if (isset($_SESSION['register_success'])): ?>
    <p style="color:green;"><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
<?php endif; ?>

<form action="../controller/proses_register.php" method="POST">

    <label>Nama Lengkap:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password2" required><br><br>




    <button type="submit">Daftar</button>
</form>

<br>
<a href="login.php">Sudah punya akun? Login di sini</a>

</body>
</html>
