<?php
require 'function.php';

if (isset($_POST['Login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $posisi = $_POST['posisi'];

    $stmt = $conn->prepare('SELECT * FROM login WHERE email = ? AND posisi = ?');
    $stmt->bind_param('ss', $email, $posisi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ((int) $user['status_verifikasi'] !== 1) {
            // echo "<script>alert('Akun belum diverifikasi! Silakan cek email Anda.'); window.location='login.php';</script>";
            echo "
          <script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>
          <script>
                        document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Login',
                    text: 'Akun belum diverifikasi! Silakan cek email Anda.'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
            </script>";
            exit();
        }

        // if ($password === $user['password']) {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['log'] = 'True';
            $_SESSION['posisi'] = $user['posisi'];
            $_SESSION['namalengkap'] = $user['namalengkap'];

            // Tambahkan efek transisi sebelum redirect
            echo "<script>
                    document.body.classList.add('fade-out');
                    setTimeout(function() {
                        window.location.href = '" .
                ($user['posisi'] === 'pemilik' ? 'index.php' : 'operator.php') .
                "';
                    }, 500);
                  </script>";
            exit();
        } else {
            // echo "<script>alert('Password salah!'); window.location='login.php';</script>";
          echo "
          <script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>
          <script>
                        document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Password salah!'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
            </script>";

            exit();
        }
    } else {
        // echo "<script>alert('Email/posisi tidak ditemukan!'); window.location='login.php';</script>";
        echo "
        <script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>
          <script>
                        document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Email/posisi tidak ditemukan!'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
            </script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Toko Barokah</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: url('9bbbb.JPG');
            /* Ganti sesuai path gambar */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: opacity 0.5s ease-in-out;
        }

        .fade-out {
            opacity: 0;
        }

        .welcome-text {
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
            margin-top: 20px;
            color: #ffffff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            display: block;
        }

        .card {
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            /* transparan putih */
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: rgb(151, 11, 11);
        }

        .card-header h3 {
            color: #ffffff !important;
            /* Paksa warna putih */
        }


        .form-floating {
            position: relative;
            margin-bottom: 20px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .form-control::placeholder,
        .form-floating label,
        .form-check-label {
            color: #e0e0e0;
        }

        .form-check-input {
            background-color: transparent;
            border: 1px solid #ccc;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #ccc;
        }

        button.btn-primary {
            background-color: rgba(0, 123, 255, 0.7);
            border: none;
            transition: background-color 0.3s ease;
        }

        button.btn-primary:hover {
            background-color: rgba(0, 123, 255, 1);
        }
    </style>


</head>

<body>
    <div class="welcome-text">Selamat Datang di Website Informasi Persediaan Barang <br><span
            style="font-size: 1.5rem;">Toko Barokah</span></div>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-4">
                                <div class="card-header  text-white">
                                    <h3 class="text-center">
                                        <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="email" id="inputEmail" type="email"
                                                placeholder="name@example.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="inputPassword"
                                                type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                                <i class="fa fa-eye" id="toggleIcon" onclick="togglePassword()"></i>

                                            </button>
                                        </div>
                                        <!-- <div class="form-floating mb-3">
                                            <input class="form-control" name="posisi" id="inputPosisi" type="text" placeholder="Posisi" required />
                                            <label for="inputPosisi">Posisi</label>
                                        </div> -->
                                        <div class="form-floating mb-3">
                                            <select class="form-control" name="posisi" id="inputPosisi" required>
                                                <option value="" disabled selected>Pilih Posisi</option>
                                                <option value="pemilik">Pemilik</option>
                                                <option value="karyawan">Karyawan</option>
                                            </select>
                                            <label for="inputPosisi">Posisi</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword"
                                                type="checkbox" />
                                            <label class="form-check-label" for="inputRememberPassword">Remember
                                                Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4">
                                            <button class="btn btn-success w-50 me-5" name="Login">Login</button>
                                            <button class="btn btn-primary w-50"
                                                onclick="window.location.href='daftar.php'; return false;">Daftar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("inputPassword");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
</body>

</html>
