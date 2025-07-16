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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

        
        
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
                <nav class="sb-sidenav accordion " id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <!-- <div class="sb-sidenav-menu-heading">Core</div> -->
                            
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
                            


                            <!-- <div class="sb-sidenav-menu-heading">Interface</div> -->
                      
                            
                            
                            </div>
                            
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4 text-center">BARANG KELUAR</h1>
                        <ol class="breadcrumb justify-content-center">
                            
                        </ol>
                        
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                TAMBAH BARANG KELUAR
                            </button>
                            </div>
                        </div>
                                <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <!-- <th>Id Keluar</th> -->
                                            <th>Kode Barang</th>
                                            <th>Jumlah</th>
                                            <th>Dikeluarkan Oleh</th>
                                            <th>Action</th>
                                            
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
                        $penerima = $data['penerima'];
                    ?>
                    <tr>
                        <td><?= $tanggal; ?></td>
                        <td><?= $namabarang; ?></td>
                        <td><?= $idbarang; ?></td>
                        <td><?= $Quantity; ?></td>
                        <td><?= $penerima; ?></td>
                        <td>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $idkeluar; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapusbarangkeluar<?= $idkeluar; ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit<?= $idkeluar; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        
                                            <div class="modal-header">
                                            <h4 class="modal-title">Edit Barang Keluar</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="post">
                                            <div class="modal-body">
                                            <input type="hidden" name="idkeluar" value="<?= $idkeluar; ?>">
                                            
                                            <input type="number" name="idbarang" class="form-control" value="<?= $idbarang; ?>" readonly><br>
                                            
                                            <input type="datetime-local" name="tanggal" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($tanggal)); ?>" required><br>
                                            
                                            <input type="number" name="Quantity" class="form-control" value="<?= $Quantity; ?>" required><br>
                                            
                                            <select name="penerima" class="form-control" required>
                                                <option value="" disabled>Dikeluarkan Oleh</option>
                                                <?php
                                                $akun = mysqli_query($conn, "SELECT * FROM login WHERE posisi IN ('Karyawan', 'Pemilik')");
                                                while ($dataakun = mysqli_fetch_array($akun)) {
                                                    $nama = $dataakun['namalengkap'];
                                                    $selected = ($nama == $penerima) ? "selected" : "";
                                                    echo "<option value='$nama' $selected>$nama</option>";
                                                }
                                                ?>
                                            </select>
                                            <br>
                                            <button type="submit" name="updatebarangkeluar" class="btn btn-primary">Submit</button>
                                            </div>
                                            <div class="modal-footer">
                                            
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                    </div>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="hapusbarangkeluar<?= $idkeluar; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        
                                            <div class="modal-header">
                                            <h5 class="modal-title">Hapus Barang Keluar</h5>
                                            <button type="button" class="btn-close " data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="post">
                                            <div class="modal-body">
                                            Yakin ingin menghapus <strong><?= htmlspecialchars($namabarang); ?></strong> sebanyak <strong><?= $Quantity; ?></strong>?
                                            <input type="hidden" name="idkeluar" value="<?= $idkeluar; ?>">
                                            <input type="hidden" name="idbarang" value="<?= $idbarang; ?>">
                                            <input type="hidden" name="Quantity" value="<?= $Quantity; ?>"><br>
                                            <br>
                                            <button type="submit" name="hapusbarangkeluar" class="btn btn-danger">Hapus</button>
                                            </div>
                                            
                                            
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
                                                <div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </footer>
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
                                <button type="submit" class="btn btn-primary" name="addbarangkeluar">Submit</button>
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
