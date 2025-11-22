<?php
session_start(); 
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../controller/auth.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
    body {
        font-family: "Poppins";
    }

    /* NAVBAR */
    .navbar {
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }

    .navbar-brand img {
        width: 50px;
        height: 50px;
    }

    /* SWITCH */
    body,
    .navbar {
        transition: background-color 0.4s ease, color 0.4s ease;
    }

    .theme-switch {
        width: 50px;
        height: 25px;
        background: #555;
        border-radius: 30px;
        position: relative;
        cursor: pointer;
        transition: background 0.3s;
    }

    .theme-switch::after {
        content: "";
        position: absolute;
        top: 2px;
        left: 2px;
        width: 21px;
        height: 21px;

        background-color: white;
        border-radius: 50%;
        border: 2px solid white;

        background-size: 70%;
        background-repeat: no-repeat;
        background-position: center;

        background-image: url("data:image/svg+xml;utf8,<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"%230d6efd\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M21 12.79A9 9 0 0111.21 3 7 7 0 1019 14.79 9.05 9.05 0 0121 12.79z\"/></svg>");

        transition: left 0.3s ease, transform 0.3s ease;
    }

    .theme-switch.light {
        background: #ddd;
    }

    .theme-switch.light::after {
        left: 27px;
        transform: rotate(360deg);

        background-image: url("data:image/svg+xml;utf8,<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"%23fdd835\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M6.76 4.84l-1.8-1.79-1.42 1.41 1.79 1.8 1.43-1.42zM1 11h3v2H1v-2zm10-9h2v3h-2V2zm9.66 2.46l-1.41-1.41-1.8 1.79 1.42 1.42 1.79-1.8zM17 11h3v2h-3v-2zm-5 7a5 5 0 110-10 5 5 0 010 10zm7.24 1.16l1.8 1.79-1.42 1.41-1.79-1.8 1.41-1.4zM13 19h-2v3h2v-3zm-7.24-.84l1.42 1.42-1.8 1.79-1.41-1.41 1.79-1.8z\"/></svg>");
    }

    /* BODY */
    .scroll-row {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding-bottom: 10px;
    }

    .scroll-row::-webkit-scrollbar {
        height: 8px;
    }

    .scroll-row::-webkit-scrollbar-thumb {
        background: #444;
        border-radius: 4px;
    }

    .sport-card {
        min-width: 180px;
        cursor: pointer;
    }

    .sport-card img {
        width: 100%;
        height: 180px;
        border-radius: 10px;
        object-fit: cover;
    }

    .sport-card p {
        margin-top: 10px;
        font-weight: 500;
    }

    /* ================================
        DROPDOWN PROFILE (BACKGROUND SECONDARY)
        ================================ */
    .dropdown-menu.custom-dropdown {
        background-color: #e9ecef !important;
        /* abu-abu (secondary) */
        border: none !important;
        border-radius: 10px !important;
        padding: 8px 0 !important;
    }

    /* TEXT ITEM */
    .dropdown-menu.custom-dropdown .dropdown-item {
        color: #212529 !important;
        font-weight: 500 !important;
        padding: 10px 15px !important;
    }

    /* HOVER */
    .dropdown-menu.custom-dropdown .dropdown-item:hover {
        background-color: #d6d8db !important;
    }

    /* ================================
        LOGOUT BUTTON STYLE
        ================================ */
    .dropdown-menu.custom-dropdown .logout-btn {
        color: #dc3545 !important;
        /* merah */
        font-weight: 600 !important;
    }

    .dropdown-menu.custom-dropdown .logout-btn:hover {
        background-color: #f8d7da !important;
        /* merah muda */
    }
    </style>

</head>

<body class="bg-dark text-white" id="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
        <div class="container-fluid">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../img/logo.png" alt="Logo" width="35" height="35" class="me-2">
                <strong>Sportify</strong>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo02">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarTogglerDemo02">

                <div class="me-auto"></div>

                <!-- SEARCH -->
                <form class="d-flex mx-auto" style="max-width: 400px;" role="search" method="GET" action="read.php">

                    <input class="form-control me-2" type="search" name="q" placeholder="Search">

                    <button class="btn btn-success" type="submit">Search</button>
                </form>
                
                <div class="d-flex align-items-center ms-auto">

                    <!-- DARK MODE BUTTON -->
                    <div class="me-3">
                        <div id="modeSwitch" class="theme-switch"></div>
                    </div>

                    <!-- ============================ -->
                    <!--    LOGIN / LOGOUT CONTROL    -->
                    <!-- ============================ -->
                    <?php if (!isset($_SESSION['user_id'])): ?>

                    <!-- NOT LOGGED IN → Show Login Button -->
                    <a href="login.php" class="btn btn-success">Login</a>

                    <?php else: ?>

                    <!-- LOGGED IN → Show Profile Dropdown -->
                    <div class="dropdown">
                        <button class="btn d-flex align-items-center px-3 py-1 text-white border rounded-pill"
                            id="profileDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background: transparent;">

                            <!-- Avatar -->
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']) ?>&background=198754&color=fff&rounded=true&size=40"
                                class="rounded-circle me-2" width="32" height="32">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>

                            <svg width="18" height="18" fill="currentColor" class="ms-2">
                                <path d="M5 7l4 4 4-4z" />
                            </svg>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow custom-dropdown">
                            <li><a class="dropdown-item" href="dashboard_admin.php">Dashboard</a></li>

                            <li>
                                <form action="../controller/logout.php" method="POST" class="m-0">
                                    <button type="submit" class="dropdown-item logout-btn">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>


                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>


    <div id="articleCarousel" class="carousel slide mt-4" data-bs-ride="carousel">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#articleCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#articleCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#articleCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../img/foto1.jpg" class="d-block w-100" alt="Foto 1">
            </div>

            <div class="carousel-item">
                <img src="../img/foto2.jpg" class="d-block w-100" alt="Foto 2">
            </div>

            <div class="carousel-item">
                <img src="../img/foto3.jpg" class="d-block w-100" alt="Foto 3">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>


    <?php
            // Ambil artikel terbaru genre Sepak Bola
            
        // =============================
        //   DAFTAR GENRE OTOMATIS
        // =============================
        $genreList = [
            "Sepak Bola" => "Soccer",
            "Basket" => "Basketball",
            "Bulu Tangkis" => "Badminton",
            "Tenis" => "Tennis",
            "Voli" => "Volleyball",
            "Renang" => "Swimming",
            "Atletik" => "Athletics",
            "Tinju" => "Boxing",
            "MotoGP" => "MotoGP",
            "Lainnya" => "Lainnya"
        ];

        // =============================
        //   LOOP TIAP GENRE
        // =============================
        foreach ($genreList as $dbGenre => $displayName):

            $sql = "SELECT id, title, image, slug 
                    FROM articles 
                    WHERE genre = ?
                    ORDER BY created_at DESC 
                    LIMIT 10";

            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "s", $dbGenre);
            mysqli_stmt_execute($stmt);
            $resultGenre = mysqli_stmt_get_result($stmt);
        ?>
    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><?= htmlspecialchars($displayName) ?></h3>
            <a href="read.php?genre=<?= urlencode($dbGenre) ?>" class="text-secondary">Show all</a>
        </div>

        <div class="scroll-row">

            <?php if (mysqli_num_rows($resultGenre) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($resultGenre)): ?>

            <div class="sport-card" onclick="window.location.href='read_single.php?slug=<?= $row['slug'] ?>'">

                <?php if (!empty($row['image'])): ?>
                <img src="../<?= htmlspecialchars($row['image']) ?>">
                <?php else: ?>
                <img src="../img/default.jpg">
                <?php endif; ?>

                <p><?= htmlspecialchars($row['title']) ?></p>
            </div>

            <?php endwhile; ?>
            <?php else: ?>
            <p class="text-muted">Belum ada artikel <?= htmlspecialchars($displayName) ?>.</p>
            <?php endif; ?>

        </div>

    </div>

    <?php endforeach; ?>

    <script>
    const modeSwitch = document.getElementById("modeSwitch");
    const body = document.getElementById("body");
    const navbar = document.getElementById("navbar");

    let isDark = true;
    modeSwitch.classList.remove("light");
    body.classList.add("bg-dark", "text-white");
    navbar.classList.add("navbar-dark", "bg-dark");

    modeSwitch.addEventListener("click", () => {
        isDark = !isDark;

        if (isDark) {
            modeSwitch.classList.remove("light");

            body.classList.remove("bg-white", "text-dark");
            body.classList.add("bg-dark", "text-white");

            navbar.classList.remove("navbar-light", "bg-light");
            navbar.classList.add("navbar-dark", "bg-dark");

        } else {
            modeSwitch.classList.add("light");

            body.classList.remove("bg-dark", "text-white");
            body.classList.add("bg-white", "text-dark");

            navbar.classList.remove("navbar-dark", "bg-dark");
            navbar.classList.add("navbar-light", "bg-light");
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>