<?php
include 'function.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT namalengkap FROM login WHERE email = ? AND token_verifikasi = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $namalengkap = $data['namalengkap'];
        $update = $conn->prepare("UPDATE login SET status_verifikasi = 1, token_verifikasi = NULL WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();
        // echo "Akun berhasil diverifikasi. <a href='login.php'>Login sekarang</a>";
        $alert = "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Akun berhasil diverifikasi',
                    text: 'Anda sekarang dapat masuk.'
                }).then(() => {
                window.location.href = 'login.php';
                });
            });
        </script>";
    } else {
        // echo "Link tidak valid atau sudah digunakan.";
        $alert = "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Gagal',
                    text: 'Link tidak valid atau sudah digunakan.'
                }).then(() => {
                window.location.href = 'resend-verification.php';
                });
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Parma</title>
    <link rel="shortcut icon" href="/public/assets/svgs/logo-mark.svg" type="image/x-icon">
    <link rel="stylesheet" href="/public/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <?= $alert ?? '' ?>

</body>
</html>