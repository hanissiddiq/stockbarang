<?php
$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

$pesan = '';
$berhasil = false;

$conn = new mysqli('localhost', 'root', '', 'stockbarang');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM login WHERE email = ? AND token_verifikasi = ? AND status_verifikasi = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // update status
    $update = $conn->prepare("UPDATE login SET status_verifikasi = 1 WHERE email = ?");
    $update->bind_param("s", $email);
    if ($update->execute()) {
        $pesan = "Email Anda berhasil diverifikasi! Silakan login.";
        $berhasil = true;
    } else {
        $pesan = "Terjadi kesalahan saat memperbarui data. Silakan coba lagi.";
    }
    $update->close();
} else {
    $pesan = "Link verifikasi tidak valid atau sudah digunakan.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email - Toko Barokah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('9bbbb.JPG'); /* Ganti sesuai gambar kamu */
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: #fff;
            text-align: center;
            padding: 30px 20px;
            max-width: 400px;
            width: 100%;
        }

        .card h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .success {
            color: #4CAF50;
        }

        .error {
            color: #FF5252;
        }

        .btn {
            margin-top: 20px;
        }

        .logo {
            width: 60px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-container">
            <img src="check.png" alt="Logo" class="logo"> <!-- Ganti jika ada logo -->
        </div>
        <h2>Verifikasi Email</h2>
        <p class="<?= $berhasil ? 'success' : 'error' ?>">
            <?= $pesan ?>
        </p>
        <?php if ($berhasil): ?>
            <a href="login.php" class="btn btn-success">Login Sekarang</a>
        <?php else: ?>
            <a href="index.php" class="btn btn-outline-light">Kembali ke Beranda</a>
        <?php endif; ?>
    </div>
</body>
</html>
