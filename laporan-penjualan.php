<?php
require 'function.php';
// session_start();

// $cek = $_SESSION['login'];
// echo $cek;
// Cek apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>STOK BARANG</title>
    <link href="style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- //style Perbaikan -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffdd59, #ff7f50);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            overflow: hidden;
        }

        .container {
            max-width: 500px;
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeIn 1s ease-in-out;
        }

        img {
            width: 150px;
            animation: bounce 2s infinite;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #ff5722;
        }

        p {
            font-size: 1.1rem;
            color: #555;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand-sm ">
        <!-- Logo di kiri -->
        <a class="navbar-brand fw-bold" href="index.php">TOKO BAROKAH</a>

        <!-- Sidebar toggle -->
        <button class="btn btn-link btn-sm text-white me-auto" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Nama pengguna dan logout button di kanan -->
        <div class="d-flex align-items-center ms-auto">
            <!-- Nama pengguna -->
            <span class="text-white me-3 d-flex align-items-center">
                <i class="fas fa-user-circle me-2"></i>
                <?php echo isset($_SESSION['namalengkap']) ? $_SESSION['namalengkap'] : 'Pengguna'; ?>
            </span>
            <!-- Tombol Logout -->
            <a href="logout.php" class="btn btn-outline-light rounded-pill px-3">
                <i class="fas fa-door-open me-1"></i> Logout
            </a>
        </div>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-in-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Stok Barang
                        </a>
                        <a class="nav-link" href="stock-bulanan.php">
                            <div class="sb-nav-link-icon"><i class="fa-notdog fa-solid fa-file"></i></div>
                            Stok Bulanan
                        </a>
                        <a class="nav-link" href="laporan-penjualan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Laporan Penjualan
                        </a>
                        <a class="nav-link" href="akun.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Daftar Karyawan
                        </a>
                        <a class="nav-link" href="laporan.php">
                            <div class="sb-nav-link-icon"><i class="fa fa-file-text"></i></div>
                            Laporan
                        </a>

                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="container">
                        <img src="https://cdn-icons-png.flaticon.com/512/679/679720.png"
                            style="width: 300px; height: 300px;" alt="Maintenance Icon">
                        <h1>Website Sedang Dalam Perbaikan</h1>
                        <p>Kami sedang melakukan pemeliharaan untuk memberikan layanan terbaik.
                            Silakan kembali lagi beberapa saat lagi.</p>

                    </div>

                </div>
            </main>
            <!-- <footer class="py-auto bg-light mt-auto"> -->
            <footer class="bg-light py-3 w-100">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Toko Barokah The Best</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>





    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
