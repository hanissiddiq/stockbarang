<?php 
session_start();
$conn = mysqli_connect("localhost" , "root" , "" ,  "stockbarang");


if (isset($_POST['addnewbarang'])) {
    $namabarang = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stokawal = (int) $_POST['stokawal']; // stok awal diinput user
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $hargabarang = (int) $_POST['hargabarang']; // harga dalam angka

    // Untuk pertama kali, stok awal = stock (sisa barang)
    $add = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stok_awal, stock, satuan, hargabarang) 
                                 VALUES ('$namabarang', '$deskripsi', '$stokawal', '$stokawal', '$satuan', '$hargabarang')");

    if ($add) {
        header('location:index.php');
    } else {
        echo "Gagal menambahkan barang.";
    }
}

if(isset($_POST['addnewbarangbaru'])){
    $namabarang = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $hargabarang = mysqli_real_escape_string($conn, $_POST['hargabarang']);

    // Tambah ke tabel stock
    $addtotable = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, satuan, hargabarang) VALUES ('$namabarang','$deskripsi','$stock','$satuan','$hargabarang')");

    if($addtotable){
        // Ambil idbarang yang baru saja ditambahkan
        $idbarang = mysqli_insert_id($conn);

        // Tambahkan otomatis ke tabel masuk
        $tanggal = date("Y-m-d H:i:s");
        $keterangan = "Barang Baru";
        
        $insertMasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, tanggal, Quantity, keterangan) VALUES ('$idbarang', '$tanggal', '$stock', '$keterangan')");

        if($insertMasuk){
            header('location:masuk.php');
        } else {
            echo "Gagal input ke tabel masuk";
        }
    } else {
        echo 'Gagal tambah barang baru';
    }
}
if (isset($_POST['addnewbarangbaruK'])) {
    $namabarang = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stock = (int) $_POST['stock'];
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $hargabarang = isset($_POST['hargabarang']) ? (int) $_POST['hargabarang'] : 0;
    $penerima = mysqli_real_escape_string($conn, $_POST['penerima_baru']);

    // 1. Tambahkan ke tabel stock
    $insertStock = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, satuan, hargabarang) 
                                        VALUES ('$namabarang', '$deskripsi', '$stock', '$satuan', '$hargabarang')");

    if ($insertStock) {
        // 2. Ambil idbarang terakhir
        $idbarang = mysqli_insert_id($conn);

        // 3. Tambahkan ke tabel masuk (untuk mencatat siapa penerimanya dan jumlah)
        $insertMasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, Quantity) 
                                            VALUES ('$idbarang', '$penerima', '$stock')");

        if ($insertMasuk) {
            header('Location: masuki.php');
        } else {
            echo "Gagal menambahkan ke tabel masuk.";
        }
    } else {
        echo "Gagal menambahkan ke tabel stock.";
    }
}

if (isset($_POST['addnewbarangoperator'])) {
    $namabarang = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $stokawal = (int) $_POST['stokawal']; // stok awal diinput user
    $satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
    $hargabarang = (int) $_POST['hargabarang']; // harga dalam angka

    // Untuk pertama kali, stok awal = stock (sisa barang)
    $add = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stok_awal, stock, satuan, hargabarang) 
                                 VALUES ('$namabarang', '$deskripsi', '$stokawal', '$stokawal', '$satuan', '$hargabarang')");

    if ($add) {
        header('location:operator.php');
    } else {
        echo "Gagal menambahkan barang.";
    }
}


if (isset($_POST['barangmasuk1'])) {
   // Pastikan koneksi disertakan

    // Ambil dan validasi input
    $idbarang = intval($_POST['idbarang'] ?? 0);
    $jumlah = intval($_POST['jumlah'] ?? 0);
    $keterangan = htmlspecialchars($_POST['keterangan'] ?? '');
    $tanggal = date('Y-m-d H:i:s');

    if ($idbarang <= 0 || $jumlah <= 0 || empty($keterangan)) {
        echo "<script>alert('Data tidak valid. Pastikan semua diisi dengan benar.');</script>";
        return;
    }

    // Ambil data barang
    $cek = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idbarang'");
    $data = mysqli_fetch_assoc($cek);
    $namabarang_saat_ini = $data['namabarang'] ?? '';

    // Jika barang masih "baru", hapus label di stock (bukan di riwayat)
    if ($data && $data['status_barang'] == 'baru') {
        $namaBersih = str_replace(' (barang baru)', '', $namabarang_saat_ini);
        mysqli_query($conn, "
            UPDATE stock 
            SET namabarang = '$namaBersih', status_barang = NULL 
            WHERE idbarang = '$idbarang'
        ");
    }

    // Update stok
    $update = mysqli_query($conn, "
        UPDATE stock 
        SET stock = stock + $jumlah 
        WHERE idbarang = '$idbarang'
    ");

    // Catat riwayat masuk (dengan idbarang asli)
    $insert = mysqli_query($conn, "
        INSERT INTO masuk (idbarang, tanggal, quantity, keterangan)
        VALUES ('$idbarang', '$tanggal', '$jumlah', '$keterangan')
    ");

    if ($update && $insert) {
        echo "<script>alert('Barang masuk berhasil dicatat!'); window.location.href='masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal mencatat barang masuk!');</script>";
    }
}



if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $Penerima = $_POST['Penerima'];
    $Quantity = $_POST['Quantity'];

    $cekstockbarang = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    
    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstoksekarangdenganquantity = $stocksekarang + $Quantity;

    $addtomasuk = mysqli_query($conn,"INSERT INTO masuk (idbarang, keterangan, Quantity) VALUES ('$barangnya','$Penerima','$Quantity')");
    $updatestockmasuk = mysqli_query($conn,"UPDATE stock SET stock='$tambahkanstoksekarangdenganquantity' WHERE  idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        
        header ('location: index.php');
        
    } else {
        echo 'gagal';
        

    }
};

if(isset($_POST['barangmasuki'])){
    $barangnya = $_POST['barangnya'];
    $Penerima = $_POST['Penerima'];
    $Quantity = $_POST['Quantity'];

    $cekstockbarang = mysqli_query($conn,"SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    
    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstoksekarangdenganquantity = $stocksekarang + $Quantity;

    $addtomasuk = mysqli_query($conn,"INSERT INTO masuk (idbarang, keterangan, Quantity) VALUES ('$barangnya','$Penerima','$Quantity')");
    $updatestockmasuk = mysqli_query($conn,"UPDATE stock SET stock='$tambahkanstoksekarangdenganquantity' WHERE  idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        
        header ('location: operator.php');
        
    } else {
        echo 'gagal';
        

    }
};

if (isset($_POST['barangmasuki1'])) {
   // Pastikan koneksi disertakan

    // Ambil dan validasi input
    $idbarang = intval($_POST['idbarang'] ?? 0);
    $jumlah = intval($_POST['jumlah'] ?? 0);
    $keterangan = htmlspecialchars($_POST['keterangan'] ?? '');
    $tanggal = date('Y-m-d H:i:s');

    if ($idbarang <= 0 || $jumlah <= 0 || empty($keterangan)) {
        echo "<script>alert('Data tidak valid. Pastikan semua diisi dengan benar.');</script>";
        return;
    }

    // Ambil data barang
    $cek = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang = '$idbarang'");
    $data = mysqli_fetch_assoc($cek);
    $namabarang_saat_ini = $data['namabarang'] ?? '';

    // Jika barang masih "baru", hapus label di stock (bukan di riwayat)
    if ($data && $data['status_barang'] == 'baru') {
        $namaBersih = str_replace(' (barang baru)', '', $namabarang_saat_ini);
        mysqli_query($conn, "
            UPDATE stock 
            SET namabarang = '$namaBersih', status_barang = NULL 
            WHERE idbarang = '$idbarang'
        ");
    }

    // Update stok
    $update = mysqli_query($conn, "
        UPDATE stock 
        SET stock = stock + $jumlah 
        WHERE idbarang = '$idbarang'
    ");

    // Catat riwayat masuk (dengan idbarang asli)
    $insert = mysqli_query($conn, "
        INSERT INTO masuk (idbarang, tanggal, quantity, keterangan)
        VALUES ('$idbarang', '$tanggal', '$jumlah', '$keterangan')
    ");

    if ($update && $insert) {
        echo "<script>alert('Barang masuk berhasil dicatat!'); window.location.href='masuki.php';</script>";
    } else {
        echo "<script>alert('Gagal mencatat barang masuk!');</script>";
    }
}


if (isset($_POST['addbarangkeluar'])) {
    $barangnya = intval($_POST['barangnya']);
    $penerima = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty = intval($_POST['Quantity']);

    $cekstok = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang = '$barangnya'");
    $ambildata = mysqli_fetch_array($cekstok);
    $stoksekarang = $ambildata['stock'];

    if ($stoksekarang >= $qty) {
        $stokbaru = $stoksekarang - $qty;
        $tanggal = date("Y-m-d H:i:s");

        // INSERT ke tabel keluar
        $insertkeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, Quantity, tanggal) VALUES ('$barangnya', '$penerima', '$qty', '$tanggal')");

        // UPDATE stok
        $updatestok = mysqli_query($conn, "UPDATE stock SET stock = '$stokbaru' WHERE idbarang = '$barangnya'");

        if ($insertkeluar && $updatestok) {
            header('Location: keluar.php');
            exit;
        } else {
            echo "❌ Gagal mengupdate stok!";
        }
    } else {
        echo "⚠️ Stok tidak mencukupi!";
    }
}



if (isset($_POST['addbarangkeluar2'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty = (int) $_POST['Quantity'];

    // Ambil data stok saat ini
    $cekstok = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildata = mysqli_fetch_array($cekstok);
    $stoksekarang = $ambildata['stock'];

    // Cek apakah stok mencukupi
    if ($stoksekarang >= $qty) {
        $stokbaru = $stoksekarang - $qty;

        // Masukkan ke tabel keluar dan kurangi stok
        $insertkeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, Quantity) VALUES ('$barangnya', '$penerima', '$qty')");
        $updatestok = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$barangnya'");

        if ($insertkeluar && $updatestok) {
            header('Location: keluari.php');
        } else {
            echo 'Gagal menambahkan data barang keluar.';
        }
    } else {
        echo 'Stok tidak mencukupi.';
    }
}

//update info barang stock
if(isset($_POST['updatebarang'])){
    $idbarang = $_POST['idbarang'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stok_awal = $_POST['stok_awal'];
    $satuan = $_POST['satuan'];
    $hargabarang = $_POST['hargabarang'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi', stok_awal='$stok_awal', satuan='$satuan', hargabarang='$hargabarang' WHERE idbarang='$idbarang'");
    if($update){
        // header('location:keluar.php');
        header ('location: index.php');
    } else {
        echo 'gagal';
    }
}
 //menghapus barang stock
 if(isset($_POST['hapusbarang'])){
    $idbarang = $_POST['idbarang'];

    $hapus = mysqli_query($conn, "delete FROM stock WHERE idbarang='$idbarang'");
    if($hapus){
        // header('location:keluar.php');
        header ('location: index.php');
        
    } else {
        echo 'gagal';
    }
 }
 //edit barang masuk
 if (isset($_POST['updatebarangmasuk'])) {
    $idmasuk = $_POST['idmasuk'];
    $idbarang = $_POST['idbarang'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $QuantityBaru = (int)$_POST['Quantity'];

    // Ambil data stok sekarang
    $ambilStock = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang = '$idbarang'");
    $dataStock = mysqli_fetch_assoc($ambilStock);
    $stockSekarang = (int)$dataStock['stock'];

    // Ambil data quantity lama
    $ambilQtyLama = mysqli_query($conn, "SELECT Quantity FROM masuk WHERE idmasuk = '$idmasuk'");
    $dataQtyLama = mysqli_fetch_assoc($ambilQtyLama);
    $QuantityLama = (int)$dataQtyLama['Quantity'];

    // Hitung selisih dan update stok
    if ($QuantityBaru > $QuantityLama) {
        // Barang masuk ditambah
        $selisih = $QuantityBaru - $QuantityLama;
        $stockBaru = $stockSekarang + $selisih;
    } else if ($QuantityBaru < $QuantityLama) {
        // Barang masuk dikurangi
        $selisih = $QuantityLama - $QuantityBaru;
        $stockBaru = $stockSekarang - $selisih;
    } else {
        // Tidak ada perubahan jumlah
        $stockBaru = $stockSekarang;
    }

    // Update stok dan data masuk
    $updateStock = mysqli_query($conn, "UPDATE stock SET stock = '$stockBaru' WHERE idbarang = '$idbarang'");
    $updateMasuk = mysqli_query($conn, "UPDATE masuk SET tanggal = '$tanggal', keterangan = '$keterangan', Quantity = '$QuantityBaru' WHERE idmasuk = '$idmasuk'");

    if ($updateStock && $updateMasuk) {
        header('Location: masuk.php');
        exit;
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}



    
    

// hapus barang masuk
if (isset($_POST['hapusbarangmasuk1'])) {
    $idmasuk = mysqli_real_escape_string($conn, $_POST['idmasuk']);
    $idbarang = mysqli_real_escape_string($conn, $_POST['idbarang']);
    $Quantity = (int) mysqli_real_escape_string($conn, $_POST['Quantity']); // pastikan integer

    // Ambil data stok saat ini
    $getdatastock = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang='$idbarang'");
    $data = mysqli_fetch_array($getdatastock);

    if ($data) {
        $stoksekarang = (int) $data['stock'];
        $stokbaru = $stoksekarang - $Quantity;

        // Cek agar stok tidak negatif
        if ($stokbaru >= 0) {
            $update = mysqli_query($conn, "UPDATE stock SET stock = '$stokbaru' WHERE idbarang='$idbarang'");

            if ($update) {
                $hapus = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idmasuk'");

                if ($hapus) {
                    echo " window.location.href='masuk.php';</script>";
                } else {
                    echo "<script>alert('Gagal menghapus data dari tabel masuk.');</script>";
                }
            } else {
                echo "<script>alert('Gagal mengupdate stok.');</script>";
            }
        } else {
            echo "<script>alert('Stok tidak boleh negatif.');</script>";
        }
    } else {
        echo "<script>alert('Data barang tidak ditemukan.');</script>";
    }
}





//edit barang keluar
 if (isset($_POST['updatebarangkeluar'])) {
    $idkeluar = (int)$_POST['idkeluar'];
    $idbarang = (int)$_POST['idbarang'];
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $penerima = mysqli_real_escape_string($conn, $_POST['penerima']);
    $QuantityBaru = (int)$_POST['Quantity'];

    // Ambil stok sekarang dari tabel stock
    $ambilStock = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang = '$idbarang'");
    $dataStock = mysqli_fetch_assoc($ambilStock);
    $stockSekarang = (int)$dataStock['stock'];

    // Ambil quantity lama dari tabel keluar
    $ambilQtyLama = mysqli_query($conn, "SELECT Quantity FROM keluar WHERE idkeluar = '$idkeluar'");
    $dataQtyLama = mysqli_fetch_assoc($ambilQtyLama);
    $QuantityLama = (int)$dataQtyLama['Quantity'];

    // Hitung stok baru berdasarkan perubahan quantity keluar
    if ($QuantityBaru > $QuantityLama) {
        // Barang keluar ditambah => stok dikurangi
        $selisih = $QuantityBaru - $QuantityLama;
        $stockBaru = $stockSekarang - $selisih;
    } else if ($QuantityBaru < $QuantityLama) {
        // Barang keluar dikurangi => stok ditambah
        $selisih = $QuantityLama - $QuantityBaru;
        $stockBaru = $stockSekarang + $selisih;
    } else {
        // Tidak ada perubahan quantity
        $stockBaru = $stockSekarang;
    }

    // Update stok dan data keluar
    $updateStock = mysqli_query($conn, "UPDATE stock SET stock = '$stockBaru' WHERE idbarang = '$idbarang'");
    $updateKeluar = mysqli_query($conn, "UPDATE keluar SET tanggal = '$tanggal', penerima = '$penerima', Quantity = '$QuantityBaru' WHERE idkeluar = '$idkeluar'");

    if ($updateStock && $updateKeluar) {
        header('Location: keluar.php');
        exit;
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}


// hapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idkeluar = mysqli_real_escape_string($conn, $_POST['idkeluar']);
    $idbarang = mysqli_real_escape_string($conn, $_POST['idbarang']);
    $Quantity = (int) mysqli_real_escape_string($conn, $_POST['Quantity']);

    // Cek data stock
    $getdatastock = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang='$idbarang'");
    if ($getdatastock && mysqli_num_rows($getdatastock) > 0) {
        $data = mysqli_fetch_assoc($getdatastock);
        $stoksekarang = (int)$data['stock'];
        $stokbaru = $stoksekarang + $Quantity;

        // Update stok
        $update = mysqli_query($conn, "UPDATE stock SET stock = '$stokbaru' WHERE idbarang='$idbarang'");

        if ($update) {
            $hapus = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idkeluar'");
            if ($hapus) {
                echo "<script>window.location.href='keluar.php';</script>";
            } else {
                echo "<script>alert('Gagal menghapus data dari tabel keluar.');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengupdate stok.');</script>";
        }
    } else {
        echo "<script>alert('Data stok tidak ditemukan.');</script>";
    } 
}




//UPDATE AKUN
if (isset($_POST['updateakun'])) {
    $iduser = $_POST['iduser'];
    $namalengkap = $_POST['namalengkap'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $alamat = $_POST['Alamat'];
    $no_hp = $_POST['no_hp'];

    // Hash password jika kamu ingin menyimpannya dengan aman (opsional, tapi sangat disarankan)
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Persiapkan query dengan placeholder
    $stmt = $conn->prepare("UPDATE login SET namalengkap = ?, email = ?, password = ?, Alamat = ?, no_hp = ? WHERE iduser = ?");
    
    // Periksa apakah prepare berhasil
    if ($stmt) {
        $stmt->bind_param("sssssi", $namalengkap, $email, $password, $alamat, $no_hp, $iduser);
        if ($stmt->execute()) {
            header("Location: akun.php");
            exit;
        } else {
            echo "Gagal memperbarui data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Query tidak valid: " . $conn->error;
    }
}


//HAPUS AKUN
if (isset($_POST['hapusakun'])) {
    $iduser = $_POST['iduser'];

    $query = "DELETE FROM login WHERE iduser = '$iduser'";

    if (mysqli_query($conn, $query)) {
        header("Location: akun.php");
    } else {
        echo "Gagal menghapus akun: " . mysqli_error($conn);
    }
}
?>



