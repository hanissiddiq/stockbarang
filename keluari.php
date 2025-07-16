<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>BARANG KELUAR</title>
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
                <nav class="sb-sidenav accordion " id="sidenavAccordion">
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
                            
                            </a>
                        </div>
                    </div>
                    
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4 text-center">BARANG KELUAR</h1>
                        
                            
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                TAMBAH BARANG
                            </button>
                            </div>
                        </div>
                                <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Id Keluar</th>
                                            <th>Id Barang</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Penerima</th>
                                            
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                    <?php
                                        $ambilsemuadatastock = mysqli_query($conn,"SELECT * FROM keluar k, stock s WHERE s.idbarang = k.idbarang");
                                        while($data = mysqli_fetch_array($ambilsemuadatastock)){
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $idkeluar = $data['idkeluar'];
                                            $idbarang = $data['idbarang'];
                                            $Quantity = $data['Quantity'];
                                            $satuan = $data['satuan'];
                                            $penerima = $data['penerima'];
                                    
                                    ?>
                                        <tr>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$idkeluar;?></td>
                                            <td><?=$idbarang;?></td>
                                            <td><?=$Quantity;?></td>
                                            <td><?=$satuan;?></td>
                                            <td><?=$penerima;?></td>
                                            
                                        </tr>
                                        <?php
                                        };
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>       
                                
                            </div>
                    </div>
                </main>
                
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


        <!-- The Modal -->
            <div class="modal fade" id="myModal">
            <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Tambah Barang Keluar</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <form method="post">

            <select name="barangnya" class="form-control" required>
                    <?php
                    $ambilsemuadatanya=mysqli_query($conn,"SELECT * FROM stock");
                    while ($fetcharray = mysqli_fetch_array(($ambilsemuadatanya))){
                        $namabarang = $fetcharray['namabarang'];
                        $idbarang = $fetcharray['idbarang'];

                    ?>


                    <option value="<?=$idbarang;?>"><?=$namabarang;?></option>

                    <?php
                            }   
                    ?>
                </select>
                <br>
                 <input type="number" name="Quantity" placeholder="Quantity" class="form-control" required>
                 <br>
                 <select name="penerima" class="form-control" required>
                        <option value="" disabled selected>Dikeluarkan Oleh</option>
                        <?php
                        $akun = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan', 'Pemilik')");
                        while ($data = mysqli_fetch_array($akun)) {
                            $nama = $data['namalengkap']; // Sesuaikan dengan kolom nama di tabel login
                            echo "<option value='$nama'>$nama</option>";
                        }
                        ?>
                    </select>
                 <br>
                 <button type="submit" class="btn btn-primary" name="addbarangkeluar2">Submit</button>
        </div>
        </form>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
    </div>

</html>


