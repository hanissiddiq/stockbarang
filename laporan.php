<?php
include "function.php";
$koneksi = mysqli_connect("localhost", "root", "", "stockbarang");

// Ambil filter
$tanggal = $_GET['tanggal'] ?? '';
$nama = $_GET['nama'] ?? '';
$lihatSemua = isset($_GET['all']) && $_GET['all'] === '1';

// Filter
$filterTanggalMasuk = $filterTanggalKeluar = '';
if (!$lihatSemua && !empty($tanggal)) {
    $tanggalEscaped = mysqli_real_escape_string($koneksi, $tanggal);
    // $filterTanggalMasuk = "AND DATE(m.tanggal) = '$tanggalEscaped'";
    $filterTanggalMasuk = "AND DATE(tanggal) = '$tanggalEscaped'";
    // $filterTanggalKeluar = "AND DATE(k.tanggal) = '$tanggalEscaped'";
    $filterTanggalKeluar = "AND DATE(tanggal) = '$tanggalEscaped'"; 
}
$filterNama = (!$lihatSemua && !empty($nama)) ? "WHERE s.namabarang LIKE '%" . mysqli_real_escape_string($koneksi, $nama) . "%'" : '';

// Query laporan
$query = "
    SELECT 
        s.idbarang,
        s.namabarang,
        s.deskripsi,
        s.hargabarang,
        s.stok_awal,
        IFNULL(m.total_masuk, 0) AS total_masuk,
        IFNULL(k.total_keluar, 0) AS total_keluar
    FROM stock s
    LEFT JOIN (
        SELECT idbarang, SUM(Quantity) AS total_masuk
        FROM masuk
        WHERE (keterangan IS NULL OR keterangan != 'stok_awal') $filterTanggalMasuk
        GROUP BY idbarang
    ) m ON s.idbarang = m.idbarang
    LEFT JOIN (
        SELECT idbarang, SUM(Quantity) AS total_keluar
        FROM keluar
        WHERE 1=1 $filterTanggalKeluar
        GROUP BY idbarang
    ) k ON s.idbarang = k.idbarang
    $filterNama
    ORDER BY s.namabarang
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Persediaan Barang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 30px;
            color: #333;
        }
        h2 { text-align: center; margin-bottom: 20px; color: #003366; }
        form {
            margin-bottom: 20px;
            background-color: #e0f0f5;
            padding: 15px;
            border-radius: 8px;
        }
        label { font-weight: bold; margin-right: 10px; }
        input[type="text"], input[type="date"] {
            padding: 5px; margin-right: 10px;
        }
        button {
            padding: 8px 15px;
            background-color: #117a8b;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #0d5d64; }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th { background-color: #77B0AA; color: #000; }
        tr:nth-child(even) { background-color: #f0f7f7; }
    </style>
</head>
<body>

<h2>Laporan Persediaan Barang</h2>

<form method="GET">
    <label for="tanggal">Tanggal:</label>
    <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($tanggal) ?>" <?= $lihatSemua ? 'disabled' : '' ?>>

    <label for="nama">Nama Barang:</label>
    <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($nama) ?>" <?= $lihatSemua ? 'disabled' : '' ?>>

    <label><input type="checkbox" name="all" value="1" onchange="this.form.submit()" <?= $lihatSemua ? 'checked' : '' ?>> Tampilkan Semua</label>

    <button type="submit">Cari</button>
    <a href="laporan.php"><button type="button">Reset</button></a>
    <a href="exportpdf.php?tanggal=<?= urlencode($tanggal) ?>&nama=<?= urlencode($nama) ?>&all=<?= $lihatSemua ? '1' : '0' ?>" target="_blank">
        <button type="button">Export PDF</button>
    </a>
    <a href="index.php"><button type="button">Exit</button></a>
</form>

<table>
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Deskripsi</th>
            <th>Harga Barang</th>
            <th>Stok Awal</th>
            <th>Barang Masuk</th>
            <th>Barang Keluar</th>
            <th>Stok Tersedia</th>
            <th>Total Nilai</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grand_total_nilai = 0;
        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
                $stok_awal = (int)$row['stok_awal'];
                $masuk = (int)$row['total_masuk'];
                $keluar = (int)$row['total_keluar'];
                $harga = (int)$row['hargabarang'];

                $stok_tersedia = $stok_awal + $masuk - $keluar;
                $total_nilai = $stok_tersedia * $harga;
                $grand_total_nilai += $total_nilai;
        ?>
        <tr>
            <td><?= htmlspecialchars($row['namabarang']) ?></td>
            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
            <td><?= "Rp " . number_format($harga, 0, ',', '.') ?></td>
            <td><?= $stok_awal ?></td>
            <td><?= $masuk ?></td>
            <td><?= $keluar ?></td>
            <td><?= $stok_tersedia ?></td>
            <td><?= "Rp " . number_format($total_nilai, 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="7" style="text-align:right;"><strong>Total Nilai Keseluruhan:</strong></td>
            <td><strong><?= "Rp " . number_format($grand_total_nilai, 0, ',', '.') ?></strong></td>
        </tr>
        <?php else: ?>
        <tr><td colspan="8">Tidak ada data ditemukan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
