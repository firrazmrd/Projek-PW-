<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <style>

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: url(backround.png) no-repeat;
        background-size: cover;
        background-position: center;
    }

    /* wrapper buat login */
    .wrapper {
        position: relative;
        width: 400px;
        background: transparent;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        min-height: 400px;
        transition: .5s ease;
    }

    .wrapper.active {
        min-height: 620px;
    }

    /* btn x */
    .icon-close {
        position: absolute;
        top: 0;
        right: 0;
        width: 45px;
        height: 45px;
        font-size: 2em;
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom-left-radius: 20px;
        cursor: pointer;
        z-index: 10;
        text-decoration: none;
    }

    .icon-close ion-icon {
        color: #000;
    }

    /* form regis sm log*/
    .form-box {
        width: 100%;
        padding: 40px;
    }

    .form-box.login {
        width: 100%;
        transition: 0.5s;
    }

    .wrapper.active .form-box.login {
        transform: translateX(-400px);
    }

    .form-box.register {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        transform: translateX(400px);
        transition: 0.5s;
    }

    .wrapper.active .form-box.register {
        transform: translateX(0);
    }

    .form-box h2 {
        font-size: 2em;
        color: #000;
        text-align: center;
    }

    /* input fomr */
    .input-box {
        position: relative;
        width: 100%;
        height: 50px;
        border-bottom: 2px solid #000;
        margin: 30px 0;
    }

    .input-box label {
        position: absolute;
        top: 50%;
        left: 5px;
        transform: translateY(-50%);
        color: #000;
        transition: .4s;
        pointer-events: none;
        font-weight: 500;
    }

    .input-box input {
        width: 100%;
        height: 100%;
        background: transparent;
        border: none;
        outline: none;
        color: #000;
        padding: 0 35px 0 5px;
        font-size: 1em;
    }

    .input-box input:focus~label,
    .input-box input:not(:placeholder-shown)~label {
        top: -6px;
        font-size: 0.85em;
    }

    .input-box .icon {
        position: absolute;
        right: 8px;
        font-size: 1.3em;
        color: #000;
        line-height: 55px;
    }

    /* cekbox */
    .remember-forgot {
        display: flex;
        justify-content: space-between;
        color: #000;
        font-size: .9em;
        margin: -15px 0 15px;
    }

    .remember-forgot a {
        color: #000;
        text-decoration: none;
    }

    .login-register {
        text-align: center;
        margin: 25px 0 10px;
        color: #000;
    }

    /* bnt lgn */
    .btn {
        width: 100%;
        height: 45px;
        background: #000;
        border-radius: 6px;
        border: none;
        color: #fff;
        cursor: pointer;
        font-size: 1em;
        transition: 0.3s;
    }

    /* alert */
    .alert-box {
        width: 100%;
        padding: 10px 15px;
        border-radius: 6px;
        margin-top: 10px;
        font-size: 0.9em;
        opacity: 0;
        transform: translateY(-5px);
        animation: fadeIn 0.4s forwards;
    }

    .alert-error {
        background: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }

    .alert-success {
        background: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* darkmode */
    .dark-mode body {
        background-color: #111 !important;
        color: white !important;
    }

    .dark-mode .wrapper {
        border-color: rgba(255, 255, 255, 0.5) !important;
    }

    .dark-mode .form-box h2,
    .dark-mode .input-box label,
    .dark-mode .remember-forgot,
    .dark-mode .login-register {
        color: white !important;
    }

    .dark-mode .input-box {
        border-bottom-color: white !important;
    }

    .dark-mode .input-box input {
        color: white !important;
    }

    .dark-mode .input-box .icon {
        color: white !important;
    }

    .dark-mode .btn {
        background: white !important;
        color: black !important;
    }

    .dark-mode .icon-close ion-icon {
        color: white !important;
    }

    /* light mode */
    .light-mode body {
        background-color: white !important;
        color: black !important;
    }

    .light-mode .wrapper {
        border-color: rgba(0, 0, 0, 0.5) !important;
    }

    .light-mode .form-box h2,
    .light-mode .input-box label,
    .light-mode .remember-forgot,
    .light-mode .login-register {
        color: black !important;
    }

    .light-mode .input-box {
        border-bottom-color: black !important;
    }

    .light-mode .input-box input {
        color: black !important;
    }

    .light-mode .input-box .icon {
        color: black !important;
    }

    .light-mode .btn {
        background: black !important;
        color: white !important;
    }

    .light-mode .icon-close ion-icon {
        color: black !important;
    }
/* biar regis sm log ttp biru */
    .auth-link {
        color: #3498db !important;
        font-weight: 600;
    }

    .auth-link:hover {
        color: #5dade2 !important;
    }

    .dark-mode .login-register a:not(.auth-link) {
        color: white !important;
    }

    .light-mode .login-register a:not(.auth-link) {
        color: black !important;
    }
    </style>


</head>

<body>

    <?php
    $openRegister = false;
    if (isset($_SESSION['register_error']) || isset($_SESSION['register_success'])) {
    $openRegister = true;
    }?>

    <div class="wrapper <?php if ($openRegister) echo 'active'; ?>">
        <a href="index.php" class="icon-close">
            <ion-icon name="close-circle"></ion-icon>
        </a>

        <div class="form-box login">
            <h2>Login</h2>

            <?php if (isset($_SESSION['login_error'])): ?>
            <div id="loginAlert" class="alert-box alert-error">
                <?= $_SESSION['login_error']; ?>
            </div>
            <?php unset($_SESSION['login_error']); endif; ?>

            <form action="../controller/proses_login.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" name="email" required placeholder=" ">
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" required placeholder=" ">
                    <label>Password</label>
                </div>

                <button type="submit" class="btn">Login</button>

                <div class="login-register">
                    <p>Don't have an account? <a href="#" class="register-link auth-link">Register</a></p>
                </div>

            </form>
        </div>
        <div class="form-box register">
            <h2>Registration</h2>

            <?php if (isset($_SESSION['register_error'])): ?>
            <div id="registerAlert" class="alert-box alert-error">
                <?= $_SESSION['register_error']; ?>
            </div>
            <?php unset($_SESSION['register_error']); endif; ?>

            <?php if (isset($_SESSION['register_success'])): ?>
            <div id="registerAlertSuccess" class="alert-box alert-success">
                <?= $_SESSION['register_success']; ?>
            </div>
            <?php unset($_SESSION['register_success']); endif; ?>

            <form action="../controller/proses_register.php" method="POST">
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" name="name" required placeholder=" ">
                    <label>Nama</label>
                </div>


                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" name="email" required placeholder=" ">
                    <label>Email</label>
                </div>


                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" required placeholder=" ">
                    <label>Password</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password2" required placeholder=" ">
                    <label>Konfirmasi Password</label>
                </div>

                <div class="remember-forgot">
                    <label><input type="checkbox" required> I agree to the terms & conditions</label>
                </div>
                <button type="submit" class="btn">Register</button>
                <div class="login-register">
                    <p>Already have an account? <a href="#" class="login-link auth-link">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
    const wrapper = document.querySelector('.wrapper');
    const registerLink = document.querySelector('.register-link');
    const loginLink = document.querySelector('.login-link');
    const iconClose = document.querySelector('.icon-close');

    registerLink.addEventListener('click', () => {
        wrapper.classList.add('active');
    });

    loginLink.addEventListener('click', () => {
        wrapper.classList.remove('active');
    });
    </script>

    <script>
    let mode = localStorage.getItem("themeMode");
    let savedMode = localStorage.getItem("themeMode");
    const body = document.body;

    if (savedMode === "light") {
        document.documentElement.classList.add("light-mode");

    } else {
        document.documentElement.classList.add("dark-mode");
    }
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


</body>

</html>