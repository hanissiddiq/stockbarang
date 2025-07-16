<?php
require 'function.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['resend'])) {
    $email = trim($_POST['email']);

    // Cek apakah email terdaftar & belum diverifikasi
    $stmt = $conn->prepare("SELECT namalengkap FROM login WHERE email = ? AND status_verifikasi = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();
        $namalengkap = $data['namalengkap'];

        // Buat token baru
        $token = bin2hex(random_bytes(16));
        $update = $conn->prepare("UPDATE login SET token_verifikasi = ? WHERE email = ?");
        $update->bind_param("ss", $token, $email);
        $update->execute();

        // Kirim ulang email verifikasi
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'riskazahara43@gmail.com';
            $mail->Password = 'xoqdxoeafpbnhkem'; // ganti dengan password aplikasi
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // $ip = '192.168.29.238';
            $mail->setFrom('riskazahara43@gmail.com');
            $mail->addAddress($email, $namalengkap);
            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Ulang Email Anda';
            $mail->Body = "Hai <b>$namalengkap</b>,<br><br>Berikut adalah link baru untuk verifikasi akun Anda:<br>
            <a href='http://localhost/stockbarang/verifikasi.php?email=$email&token=$token'>Verifikasi Sekarang</a>";

            $mail->send();
            echo "<script>alert('Email verifikasi ulang telah dikirim.'); window.location='login.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Gagal mengirim email: {$mail->ErrorInfo}'); window.location='login.php';</script>";
        }

    } else {
        echo "<script>alert('Email tidak ditemukan atau sudah diverifikasi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kirim Ulang Verifikasi</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body class="container mt-5">
    <h2 class="text-center">Kirim Ulang Email Verifikasi</h2>
    <form method="POST" action="" class="p-4 border rounded">
        <div class="mb-3">
            <label class="form-label">Masukkan Email Anda:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning" name="resend">Kirim Ulang</button>
    </form>
</body>
</html>
