<?php
require 'function.php';
require 'vendor/autoload.php'; // pastikan composer autoload dimuat

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['daftarakun'])) {
    $namalengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $alamat = trim($_POST['alamat']); 

    // Sanitasi nomor HP: hanya angka yang diizinkan
    $no_hp = trim($_POST['no_hp']);
    $no_hp = preg_replace('/[^0-9]/', '', $no_hp); 

    // Posisi langsung ditentukan sebagai "karyawan"
    $posisi = "karyawan";

    // Cek email sudah terdaftar
    $cek = $conn->prepare("SELECT iduser FROM login WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();
    if ($cek->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar.'); window.location='akun.php';</script>";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate token verifikasi
    $token = bin2hex(random_bytes(16));

    // Masukkan data user ke database
    $stmt = $conn->prepare("INSERT INTO login (namalengkap, email, password, alamat, no_hp, posisi, token_verifikasi, status_verifikasi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssssss", $namalengkap, $email, $hashedPassword, $alamat, $no_hp, $posisi, $token);

    if ($stmt->execute()) {
        // Kirim Email Verifikasi
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'riskazahara43@gmail.com'; // Ganti dengan email kamu
            $mail->Password = 'xoqdxoeafpbnhkem'; // Ganti dengan App Password dari Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('riskazahara43@gmail.com', 'Toko Barokah');
            $mail->addAddress($email, $namalengkap);
            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Email Anda - Toko Barokah';
            $mail->Body = "
                <p>Halo <strong>$namalengkap</strong>,</p>
                <p>Terima kasih telah mendaftar. Silakan klik link berikut untuk verifikasi akun Anda:</p>
                <p><a href='http://stockbarang.test/verify.php?email=$email&token=$token'>Verifikasi Sekarang</a></p>
                <p>Jika Anda tidak merasa melakukan pendaftaran, silakan abaikan email ini.</p>";

            $mail->send();
            // echo "<script>alert('Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.'); window.location='login.php';</script>";
             $alert = "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi berhasil!',
                        text: 'Silakan cek email Anda untuk melakukan verifikasi.'
                    });
                });
            </script>";
        } catch (Exception $e) {
            echo "Gagal mengirim email verifikasi: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Gagal menyimpan ke database: ' . $stmt->error;
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>DAFTAR AKUN KARYAWAN</title>
    <!-- <link href="stylee.css" rel="stylesheet" /> -->
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="">
<style>
    body {
    font-family: Arial, sans-serif;
    background-image: url('9bbbb.jpg');
    background-size: cover;
    background-position: center;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    font-size: 1.8rem;
    font-weight: bold;
    margin-top: 40px;
    color: #ffffff;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
}

form {
    background: rgba(255, 255, 255, 0.15); /* blur translucent */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    margin: 40px auto;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.form-label {
    font-weight: bold;
    color: #ffffff;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.form-control,
.form-select {
    /* background-color: rgba(255, 255, 255, 0.8); */
    background: rgba(255, 255, 255, 0.15); /* blur translucent */
    backdrop-filter: blur(10px);
    border-radius: 8px;
    border: 1px solid #205781;
    padding: 12px;
    font-size: 16px;
    margin-bottom: 15px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 12px;
    font-size: 16px;
    width: 100%;
    border-radius: 8px;
    color: #fff;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: blur(0px);
}

@media (max-width: 600px) {
    form {
        width: 90%;
        padding: 20px;
    }
}

</style>
</head>
<body class="container mt-5">
    <h2 class="text-center">Form Pendaftaran Karyawan</h2>
<form method="POST" action="" enctype="multipart/form-data" class="p-4 border rounded">
    <div class="mb-3">
        <label class="form-label">Nama Lengkap:</label>
        <input type="text" name="nama_lengkap" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    
    <div class="mb-3">
    <label class="form-label">Password:</label>
    <div style="position: relative;">
        <input type="password" name="password" id="password" class="form-control" required>
        <span onclick="togglePassword()" 
              style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
            <i class="fa fa-eye" id="toggleIcon" onclick="togglePassword()"></i>

        </span>
    </div>
</div>

</div>


    <div class="mb-3">
        <label class="form-label">Alamat:</label>
        <input type="text" name="alamat" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">No. HP:</label>
        <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 082112345678" required>
    </div>

    <!-- Posisi langsung otomatis sebagai karyawan -->
    <input type="hidden" name="posisi" value="karyawan">

    <button type="submit" class="btn btn-primary" name="daftarakun">Daftar</button>
</form>
<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const type = passwordInput.getAttribute("type");

    if (type === "password") {
        passwordInput.setAttribute("type", "text");
    } else {
        passwordInput.setAttribute("type", "password");
    }
}
</script>
<?= $alert ?? '' ?>

</body>
</html>
