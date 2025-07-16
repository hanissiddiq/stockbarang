<?php
require 'function.php';
require 'cek.php';

if (isset($_POST['barangbaruK'])) {
    $namabarang_input = htmlspecialchars($_POST['namabarang']);
    $namabarang = $namabarang_input . " (barang baru)";
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $stokawal = intval($_POST['stokawal']);
    $satuan = $_POST['satuan'];
    $hargabarang = intval($_POST['hargabarang']);
    $keterangan = $_POST['keterangan']; // Nama pengguna dari dropdown
    $tanggal = date('Y-m-d H:i:s');

    // Insert ke tabel stock
    $insertStock = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, satuan, stok_awal, stock, hargabarang) 
        VALUES ('$namabarang', '$deskripsi', '$satuan', '$stokawal', '$stokawal', '$hargabarang')");

    if ($insertStock) {
        $idbarang = mysqli_insert_id($conn);

        // Insert ke tabel masuk (tanpa keterangan stok_awal)
        mysqli_query($conn, "INSERT INTO masuk (idbarang, tanggal, quantity, keterangan) 
            VALUES ('$idbarang', '$tanggal', '$stokawal', '$keterangan')");

        echo "<script>alert('Barang berhasil ditambahkan!'); window.location.href='operator.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan barang!');</script>";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>BARANG MASUK</title>
    <link href="style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand-sm ">
    <!-- Logo di kiri -->
    <a class="navbar-brand fw-bold" href="operator.php">TOKO BAROKAH</a>

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
                    <a class="nav-link" href="masuki.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-sign-in-alt"></i></div>
                        Barang Masuk
                    </a>
                    <a class="nav-link" href="keluari.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                        Barang Keluar
                    </a>
                    <a class="nav-link" href="operator.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                        Stok Barang
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#barangbaruK">TAMBAH BARANG BARU</button>
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
                                
                            </tr>
                            
                            
                           
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
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Masuk</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <!-- Pilihan barang -->
                    <select name="barangnya" class="form-control" required>
                        <?php
                        $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stock");
                        while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                            $namabarang = $fetcharray['namabarang'];
                            $idbarang = $fetcharray['idbarang'];
                            echo "<option value='$idbarang'>$namabarang</option>";
                        }
                        ?>
                    </select>
                    <br>

                    <!-- Quantity -->
                    <input type="number" name="Quantity" placeholder="Quantity" class="form-control" required>
                    <br>

                    <!-- Pilihan penanggung jawab (karyawan) -->
                    <select name="Penerima" class="form-control" required>
                        <option value="" disabled selected>Penanggung Jawab</option>
                        <?php
                        $akun = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan')");
                        while ($data = mysqli_fetch_array($akun)) {
                            $nama = $data['namalengkap']; // Sesuaikan dengan kolom nama di tabel login
                            echo "<option value='$nama'>$nama</option>";
                        }
                        ?>
                    </select>

                    <br>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary" name="barangmasuk1">Submit</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tambah Barang Baru -->

<div class="modal fade" id="barangbaruK">
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
                            $akun = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan')");
                            while ($data = mysqli_fetch_array($akun)) {
                                $nama = $data['namalengkap']; // Hanya nama yang ditampilkan dan disimpan
                                echo "<option value='$nama'>$nama</option>";
                            }
                            ?>
                        </select>

                        <br>

                    <br>
                    <!-- Perbaikan name tombol submit -->
                    <button type="submit" class="btn btn-primary" name="barangbaruK">Submit</button>
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