<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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

        .wrapper {
        position: relative;
        width: 400px;
        background: transparent;
        border: 2px solid rgba(255,255,255,0.5);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
        overflow: hidden;
        min-height: 400px;     
        transition: .5s ease;
        }

        .wrapper.active-popup {
            transform: scale(1);
        }

        .wrapper.active {
        min-height: 600px;    
        }

        .icon-close {
            position: absolute;
            top: 0;
            right: 0;
            width: 45px;
            height: 45px;
            font-size: 2em;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom-left-radius: 20px;
            cursor: pointer;
            z-index: 10;
        }


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
            color: #000000ff;
            text-align: center;
        }

        .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            border-bottom: 2px solid #000000;
            margin: 30px 0;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            color: #000000;
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
            color: #000000;
            padding: 0 35px 0 5px;
            font-size: 1em;
        }

        .input-box input:focus ~ label,
        .input-box input:not(:placeholder-shown) ~ label {
            top: -6px;
            font-size: 0.85em;
        }

        .input-box .icon {
            position: absolute;
            right: 8px;
            font-size: 1.3em;
            color: #000000;
            line-height: 55px;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            color: #000000ff;
            font-size: .9em;
            margin: -15px 0 15px;
        }

        .remember-forgot a {
            color: #000000ff;
            text-decoration: none;
        }

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

        .login-register {
            text-align: center;
            margin: 25px 0 10px;
            color: #000000ff;
        }

        .login-register p a {
            color: #3498db;
            text-decoration: none;
        }


    </style>
</head>

<body>

    <?php if (isset($_SESSION['login_error'])): ?>
    <p style="color:red;"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
    <?php endif; ?>

    <!-- Pesan Error -->
    <?php if (isset($_SESSION['register_error'])): ?>
        <p style="color:red;"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
    <?php endif; ?>

    <!-- Pesan Sukses -->
    <?php if (isset($_SESSION['register_success'])): ?>
        <p style="color:green;"><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
    <?php endif; ?>
    
    <div class="wrapper">
        <span class="icon-close"><ion-icon name="close-circle"></ion-icon></span>
        <div class="form-box login">
            <h2>Login</h2>
    <form action="../controller/proses_login.php" method="POST">
            <div class="input-box">
                <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required placeholder=" ">
        <label>Email</label>
        </div>

    <div class="input-box">
        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
        <input type="password" name="password" required placeholder=" ">
        <label>Password</label>
    </div>

    <div class="remember-forgot">
        <label><input type="checkbox"> Remember me</label>
    </div>

        <button type="submit" class="btn">Login</button>

    <div class="login-register">
        <p>Don't have an account? <a href="#" class="register-link">Register</a></p>
    </div>

    </form>
    </div>
        <div class="form-box register">
            <h2>Registration</h2>
            <form action="../controller/proses_register.php" method="POST">
            <div class="input-box">
                <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="name" required placeholder=" ">
                    <label>Nama</label>
            </div>


            <div class="input-box">
                <span class="icon"><ion-icon name="mail"></ion-icon></span>
                <input type="email" name="email" required placeholder=" ">
                <label>Email</label>
            </div>


            <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="password" required placeholder=" ">
                <label>Password</label>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                <input type="password" name="password2" required placeholder=" ">
                <label>Konfirmasi Password</label>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" required> I agree to the terms & conditions</label>
            </div>
            <button type="submit" class="btn">Register</button>
            <div class="login-register">
                <p>Already have an account? <a href="#" class="login-link">Login</a></p>
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

        iconClose.addEventListener('click', () => {
            wrapper.classList.remove('active-popup');
});


    </script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


</body>
</html>