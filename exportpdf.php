<?php
require_once('tcpdf/tcpdf.php');
$koneksi = mysqli_connect("localhost", "root", "", "stockbarang");

// Ambil parameter filter dari URL
$tanggal = $_GET['tanggal'] ?? '';
$nama = $_GET['nama'] ?? '';
$lihatSemua = isset($_GET['all']) && $_GET['all'] === '1';

// Siapkan filter untuk query
$filterTanggalMasuk = $filterTanggalKeluar = '';
if (!$lihatSemua && !empty($tanggal)) {
    $tanggalEscaped = mysqli_real_escape_string($koneksi, $tanggal);
    $filterTanggalMasuk = "AND DATE(masuk.tanggal) = '$tanggalEscaped'";
    $filterTanggalKeluar = "AND DATE(keluar.tanggal) = '$tanggalEscaped'";
}

$filterNama = (!$lihatSemua && !empty($nama)) ? "WHERE s.namabarang LIKE '%" . mysqli_real_escape_string($koneksi, $nama) . "%'" : '';

// Query gabungan dengan filter dan pengecualian stok_awal
$query = "
    SELECT 
        s.namabarang,
        s.status_barang,
        s.deskripsi,
        s.hargabarang,
        s.stok_awal,
        s.stock AS stok_tersedia,
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

// Inisialisasi PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Toko Barokah');
$pdf->SetTitle('Laporan Gabungan Barang');
$pdf->SetHeaderData('', 0, 'Toko Barokah', 'Laporan Gabungan Stok Awal, Masuk & Keluar Barang');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 10));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 8));
$pdf->SetMargins(15, 20, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// Tampilkan informasi filter
$filterInfo = '<p><b>Filter:</b> ';
$filterInfo .= !empty($tanggal) ? 'Tanggal: ' . htmlspecialchars($tanggal) . '; ' : '';
$filterInfo .= !empty($nama) ? 'Nama Barang: ' . htmlspecialchars($nama) . '; ' : '';
$filterInfo .= $lihatSemua ? 'Menampilkan Semua Data' : ' ';
$filterInfo .= '</p>';

// Mulai isi HTML
$html = '
<h2 style="text-align:center;">Laporan Gabungan Barang</h2>' . $filterInfo . '
<table border="1" cellpadding="5">
    <thead>
        <tr style="background-color:#f0f0f0;">
            <th><b>Nama Barang</b></th>
            <th><b>Deskripsi</b></th>
            <th><b>Harga</b></th>
            <th><b>Stok Awal</b></th>
            <th><b>Total Masuk</b></th>
            <th><b>Total Keluar</b></th>
            <th><b>Stok Tersedia</b></th>
            <th><b>Total Nilai</b></th>
        </tr>
    </thead>
    <tbody>
';

$totalSeluruhNilai = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $nama_tanpa_label = preg_replace('/\s*\(barang baru\)$/i', '', $row['namabarang']);
    $harga_rupiah = "Rp " . number_format($row['hargabarang'], 0, ',', '.');
    $total_nilai = $row['hargabarang'] * $row['stok_tersedia'];
    $total_nilai_rupiah = "Rp " . number_format($total_nilai, 0, ',', '.');
    $totalSeluruhNilai += $total_nilai;

    $html .= '
        <tr>
            <td>' . htmlspecialchars($nama_tanpa_label) . '</td>
            <td>' . htmlspecialchars($row['deskripsi']) . '</td>
            <td>' . $harga_rupiah . '</td>
            <td>' . htmlspecialchars($row['stok_awal']) . '</td>
            <td>' . htmlspecialchars($row['total_masuk']) . '</td>
            <td>' . htmlspecialchars($row['total_keluar']) . '</td>
            <td>' . htmlspecialchars($row['stok_tersedia']) . '</td>
            <td>' . $total_nilai_rupiah . '</td>
        </tr>
    ';
}


$html .= '
    <tr style="background-color:#f0f0f0;">
        <td colspan="7" style="text-align:right;"><b>Total Nilai Keseluruhan:</b></td>
        <td><b>Rp ' . number_format($totalSeluruhNilai, 0, ',', '.') . '</b></td>
    </tr>
';

$html .= '</tbody></table>';

// Output ke PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('laporan_barang.pdf', 'I');
?>
