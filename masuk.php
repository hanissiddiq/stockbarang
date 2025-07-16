<?php
require 'function.php';
require 'cek.php';


// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "stockbarang");

if (isset($_POST['barangbaru'])) {
    $namabarang_input = htmlspecialchars($_POST['namabarang']);
    $namabarang = $namabarang_input . " (barang baru)";
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $stokawal = intval($_POST['stokawal']);
    $satuan = $_POST['satuan'];
    $hargabarang = intval($_POST['hargabarang']);
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d H:i:s');

    // Masukkan hanya ke tabel stock
    $insertStock = mysqli_query($conn, "INSERT INTO stock 
        (namabarang, deskripsi, satuan, stok_awal, stock, hargabarang) 
        VALUES 
        ('$namabarang', '$deskripsi', '$satuan', '$stokawal', '$stokawal', '$hargabarang')");

    if ($insertStock) {
        // Jangan simpan ke tabel 'masuk', cukup simpan di stock
        echo "<script>alert('Barang baru berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan barang baru!');</script>";
    }
}


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>BARANG MASUK - TOKO BAROKAH</title>
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
                <h1 class="mt-4 text-center">BARANG MASUK</h1>
                <div class="card mb-4">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">TAMBAH BARANG MASUK</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#barangbaru">TAMBAH BARANG BARU</button>
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <!-- <th>Id Masuk</th> -->
                                    <th>Kode Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $ambilsemuadatastock = mysqli_query($conn,"SELECT m.*, s.namabarang, s.satuan FROM masuk m JOIN stock s ON s.idbarang = m.idbarang");
                            while($data = mysqli_fetch_array($ambilsemuadatastock)){
                                $tanggal = date("Y-m-d\TH:i", strtotime($data['tanggal']));
                                $namabarang = $data['namabarang'];
                                $idmasuk = $data['idmasuk'];
                                $idbarang = $data['idbarang'];
                                $quantity = $data['Quantity'];
                                $satuan = $data['satuan'];
                                $keterangan = $data['keterangan'];
                            ?>
                            <tr>
                                <td><?= $data['tanggal']; ?></td>
                                <td><?= $namabarang; ?></td>
                                
                                <td><?= $idbarang; ?></td>
                                <td><?= $quantity; ?></td>
                                <td><?= $satuan; ?></td>
                                <td><?= $keterangan; ?></td>
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
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Barang Masuk</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post">
                                                <input type="hidden" name="idmasuk" value="<?= $idmasuk; ?>">
                                                <input type="number" name="idbarang" value="<?= $idbarang; ?>" class="form-control" readonly><br>
                                                
                                                <input type="datetime-local" name="tanggal" value="<?= $tanggal; ?>" class="form-control">
                                                <br>
                                                <select name="Keterangan" class="form-control" required>
                                                        <option value="" disabled selected>Keterangan</option>
                                                        <?php
                                                        $akun = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan', 'Pemilik')");
                                                        while ($data = mysqli_fetch_array($akun)) {
                                                            $nama = $data['namalengkap']; // Sesuaikan dengan kolom nama di tabel login
                                                            echo "<option value='$nama'>$nama</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                <br>
                                                <input type="number" name="Quantity" value="<?= $quantity; ?>" class="form-control" required>
                                                <br>
                                                <button type="submit" class="btn btn-primary" name="updatebarangmasuk">Submit</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Delete -->
                            <div class="modal fade" id="delete<?= $idbarang; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Hapus Barang Masuk</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post">
                                                <p>Apakah Anda yakin ingin menghapus <?= $namabarang; ?>?</p>
                                                <input type="hidden" name="idmasuk" value="<?= $idmasuk; ?>">
                                                <input type="hidden" name="idbarang" value="<?= $idbarang; ?>">
                                                <input type="hidden" name="Quantity" value="<?= $quantity; ?>">
                                                <button type="submit" class="btn btn-danger" name="hapusbarangmasuk1">Hapus</button>
                                            </form>
                                        </div>
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

<!-- Modal Tambah Barang Masuk -->
<!-- Modal Tambah Barang Masuk -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <!-- Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tambah Barang Masuk</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form method="post">
        <div class="modal-body">
          
          <!-- Pilih Barang -->
          <label></label>
          <select name="idbarang" class="form-control" placeholder="Nama Barang" required>
            <option value="" disabled selected>Nama Barang</option>
            <?php
            $queryBarang = mysqli_query($conn, "SELECT * FROM stock");
            while ($barang = mysqli_fetch_assoc($queryBarang)) {
              echo "<option value='{$barang['idbarang']}'>{$barang['namabarang']}</option>";
            }
            ?>
          </select>
          <br>

          <!-- Jumlah -->
          <label></label>
          <input type="number" name="jumlah" placeholder="Jumlah Masuk" class="form-control" required>
          <br>

          <!-- Penanggung Jawab -->
          <label></label>
          <select name="keterangan" class="form-control"  required>
            <option value="" disabled selected>Keterangan</option>
            <?php
            $queryUser = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan', 'Pemilik')");
            while ($user = mysqli_fetch_assoc($queryUser)) {
              echo "<option value='{$user['namalengkap']}'>{$user['namalengkap']}</option>";
            }
            ?>
          </select>
          <br>

          <!-- Tombol Submit -->
          <button type="submit" class="btn btn-primary" name="barangmasuk1">Simpan</button>
        </div>
      </form>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal Tambah Barang Baru -->

<div class="modal fade" id="barangbaru">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Baru</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required><br>
                    <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required><br>
                    <input type="number" name="stokawal" placeholder="Stok Awal" class="form-control" required><br>
                    
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
                    <input type="number" name="hargabarang" placeholder="Harga Barang" class="form-control" required><br>
                    
                    <!-- Keterangan (Karyawan dan Pemilik) -->
                        <select name="keterangan" class="form-control" required>
                            <option value="" disabled selected>Keterangan</option>
                            <?php
                            $akun = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan', 'Pemilik')");
                            while ($data = mysqli_fetch_array($akun)) {
                                $nama = $data['namalengkap']; // Hanya nama yang ditampilkan dan disimpan
                                echo "<option value='$nama'>$nama</option>";
                            }
                            ?>
                        </select>

                        <br>

                    <br>
                    <!-- Perbaikan name tombol submit -->
                    <button type="submit" class="btn btn-primary" name="barangbaru">Submit</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
</body>