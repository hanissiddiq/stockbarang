<?php
require 'function.php';
session_start();

// $cek = $_SESSION['login'];
// echo $cek;
// Cek apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
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
                <h1 class="mt-4 text-center">STOK BARANG</h1>
                <div class="card mb-4">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                            TAMBAH BARANG
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Kode Barang</th>
                                    <th>Deskripsi</th>
                                    <th>Stok Awal</th>
                                    <th>Sisa Barang</th>
                                    <th>Satuan</th>
                                    <th>Harga Barang</th>
                                    <th>Harga Total</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                    $i = 1;
                    $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM stock");

                    while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                        $idbarang = $data['idbarang'];
                        $namabarang = $data['namabarang'];
                        $deskripsi = $data['deskripsi'];
                        $stokawal = intval($data['stok_awal']);
                        $satuan = $data['satuan'];
                        $hargabarang = intval($data['hargabarang']);
                        $status = $data['status_barang'] ?? '';

                        // Tambahkan label "(barang baru)" jika statusnya 'baru'
                        if ($status === 'baru') {
                            $namabarang .= " (barang baru)";
                        }

                        // Barang masuk (tidak menghitung keterangan 'stok_awal')
                        $qmasuk = mysqli_query($conn, "
                            SELECT SUM(quantity) as total_masuk 
                            FROM masuk 
                            WHERE idbarang='$idbarang' AND (keterangan IS NULL OR keterangan != 'stok_awal')
                        ");
                        $datamasuk = mysqli_fetch_assoc($qmasuk);
                        $barangmasuk = intval($datamasuk['total_masuk'] ?? 0);

                        // Barang keluar
                        $qkeluar = mysqli_query($conn, "
                            SELECT SUM(quantity) as total_keluar 
                            FROM keluar 
                            WHERE idbarang='$idbarang'
                        ");
                        $datakeluar = mysqli_fetch_assoc($qkeluar);
                        $barangkeluar = intval($datakeluar['total_keluar'] ?? 0);

                        // Hitung stok tersedia
                        $stok_awal_riil = $stokawal;
                        $stock = $stok_awal_riil + $barangmasuk - $barangkeluar;

                        // Format rupiah
                        $hargabarang_rupiah = "Rp " . number_format($hargabarang, 0, ',', '.');
                        $hargatotal = "Rp " . number_format($hargabarang * $stock, 0, ',', '.');
                    ?>

                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($namabarang); ?></td>
                        <td><?= $idbarang; ?></td>
                        <td><?= htmlspecialchars($deskripsi); ?></td>
                        <td><?= $stok_awal_riil; ?></td>
                        <td><?= $stock; ?></td>
                        <td><?= $satuan; ?></td>
                        <td><?= $hargabarang_rupiah; ?></td>
                        <td><?= $hargatotal; ?></td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $idbarang; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $idbarang; ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>

 


                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit<?= $idbarang; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="post">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="idbarang" value="<?= $idbarang; ?>">
                                                        <input type="text" name="namabarang" value="<?= $namabarang; ?>" class="form-control" required><br>
                                                        <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control" required><br>
                                                        <input type="number" name="stokawal" value="<?= $stokawal; ?>" class="form-control" required>
                                                        
                                                        <label for="satuan"></label>
                                                            <select name="satuan" id="satuan" class="form-select" required>
                                                                <option value="" disabled selected>Satuan</option>
                                                                <option value="kardus">Kardus</option>
                                                                <option value="box">Box</option>
                                                                <option value="pcs">Pcs</option>
                                                                <option value="lusin">Lusin</option>
                                                                <option value="rim">Rim</option>
                                                            </select>
                                                            <br>
                                                        <input type="number" name="hargabarang" value="<?= $hargabarang; ?>" class="form-control" required><br>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" name="updatebarang">Simpan</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus -->
                                        <div class="modal fade" id="delete<?= $idbarang; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="post">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Barang</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus barang <strong><?= $namabarang; ?></strong>?
                                                            <input type="hidden" name="idbarang" value="<?= $idbarang; ?>">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Toko Barokah The Best</div>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header">
        <h4 class="modal-title">Tambah Barang</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">
        <form method="post" autocomplete="off">
          
          <!-- Nama Barang -->
          <div class="mb-3">
            <label for="namabarang" class="form-label"></label>
            <input type="text" name="namabarang" id="namabarang" class="form-control" placeholder="Nama Barang" required>
          </div>

          <!-- Deskripsi -->
          <div class="mb-3">
            <label for="deskripsi" class="form-label"></label>
            <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Deskripsi" required>
          </div>

          <!-- Stok Awal -->
          <div class="mb-3">
            <label for="stokawal" class="form-label"></label>
            <input type="number" name="stokawal" id="stokawal" class="form-control" placeholder="Stok Awal" required min="0">
          </div>

          <!-- Satuan -->
          <div class="mb-3">
            <label for="satuan" class="form-label"></label>
            <select name="satuan" id="satuan" class="form-select" placeholder="Satuan"required>
              <option value="" disabled selected>Pilih satuan</option>
              <option value="pcs">Pcs</option>
              <option value="box">Box</option>
              <option value="kardus">Kardus</option>
              <option value="lusin">Lusin</option>
              <option value="rim">Rim</option>
            </select>
          </div>

          <!-- Harga Barang -->
          <div class="mb-3">
            <label for="hargabarang" class="form-label"></label>
            <input type="number" name="hargabarang" id="hargabarang" class="form-control" placeholder="Harga Barang" required min="0">
          </div>

          <!-- Tombol Submit -->
          <div class="d-grid">
            <button type="submit" class="btn btn-primary" name="addnewbarang">Simpan Barang</button>
          </div>
        </form>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
