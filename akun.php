]<?php
require 'function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>DAFTAR AKUN KARYAWAN</title>
    <link href="style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                "paging": true,
                "lengthChange": false,
                "pageLength": 10,
                "lengthMenu": [[10], [10]]
            });
        });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            <div class="sb-nav-link-icon"><i class="fas fa-user-circle"></i></div>
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
                    <h1 class="mt-4 text-center">DAFTAR AKUN KARYAWAN</h1>
                    <ol class="breadcrumb justify-content-center">
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <div style="text-align: no; margin-top: 20px;">
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="daftar.php" class="btn btn-primary">TAMBAH AKUN</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Alamat</th>
                                        <th>No.Hp</th>
                                        <th>Posisi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = mysqli_query($conn, "SELECT * FROM login");
                                    $i = 1;
                                    while ($row = mysqli_fetch_array($data)) {
                                        $iduser = $row['iduser'];
                                        $namalengkap = $row['namalengkap'];
                                        $email = $row['email'];
                                        $password = $row['password'];
                                        $alamat = $row['Alamat'];
                                        $no_hp = $row['no_hp'];
                                        $posisi = $row['posisi'];
                                        
                                    ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $namalengkap; ?></td>
                                        <td><?= $email; ?></td>
                                        <td><?= $password; ?></td>
                                        <td><?= $alamat; ?></td>
                                        <td><?= $no_hp; ?></td>
                                        <td><?= $posisi; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editakun<?= $iduser; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteakun<?= $iduser; ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Akun -->
                                    <div class="modal fade" id="editakun<?= $iduser; ?>" tabindex="-1" aria-labelledby="editakunLabel<?= $iduser; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editakunLabel<?= $iduser; ?>">Edit Akun</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="post" >
                                                    <div class="modal-body">
                                                        <input type="hidden" name="iduser" value="<?= $iduser; ?>"> 
                                                        <input type="text" name="namalengkap" value="<?= $namalengkap; ?>" class="form-control" placeholder="Nama Lengkap" required><br>
                                                        <input type="email" name="email" value="<?= $email; ?>" class="form-control" placeholder="Email" required><br>
                                                        <input type="text" name="password" value="<?= $password; ?>" class="form-control" placeholder="Password" required><br>
                                                        <input type="text" name="Alamat" value="<?= $alamat; ?>" class="form-control" placeholder="Alamat" required><br>
                                                        <input type="number" name="no_hp" value="<?= $no_hp; ?>" class="form-control" placeholder="No.HP" required><br>
                                                        <!-- <select name="posisi" class="form-select" required>
                                                            <option value="pemilik" <?= $posisi == 'pemilik' ? 'selected' : ''; ?>>Pemilik</option>
                                                            <option value="operator" <?= $posisi == 'operator' ? 'selected' : ''; ?>>Karyawan</option>
                                                        </select> -->
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" name="updateakun">Ubah</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus Akun -->
                                    <div class="modal fade" id="deleteakun<?= $iduser; ?>" tabindex="-1" aria-labelledby="deleteAkunLabel<?= $iduser; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteAkunLabel<?= $iduser; ?>">Hapus Akun - <?= $namalengkap; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah kamu yakin ingin menghapus akun <strong><?= $namalengkap; ?></strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="post">
                                                        <input type="hidden" name="iduser" value="<?= $iduser; ?>">
                                                        <button type="submit" name="hapusakun" class="btn btn-danger">Hapus</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                "lengthChange": false  // Menonaktifkan pilihan "entries per page"
            });
        });
    </script>
</body>
</html>
