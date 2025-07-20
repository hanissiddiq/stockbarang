-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 20, 2025 at 11:00 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stockbarang`
--

-- --------------------------------------------------------

--
-- Table structure for table `keluar`
--

CREATE TABLE `keluar` (
  `idkeluar` int NOT NULL,
  `idbarang` int NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `penerima` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `Quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluar`
--

INSERT INTO `keluar` (`idkeluar`, `idbarang`, `tanggal`, `penerima`, `Quantity`) VALUES
(26, 118, '2025-06-24 22:22:00', 'RISKA ZAHARA', 2),
(28, 121, '2025-06-24 23:57:06', 'RISKA ZAHARA', 5),
(30, 123, '2025-07-20 03:13:30', 'Hanis siddiq', 2),
(31, 125, '2025-07-20 03:40:36', 'Hanis siddiq', 10);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `iduser` int NOT NULL,
  `namalengkap` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Alamat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `posisi` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `status_verifikasi` tinyint(1) DEFAULT NULL,
  `token_verifikasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`iduser`, `namalengkap`, `email`, `password`, `Alamat`, `no_hp`, `posisi`, `status_verifikasi`, `token_verifikasi`) VALUES
(68123, 'RISKA ZAHARA', 'ayangkusayang395@gmail.com', '1234567890', 'paloh', '2147483647', 'pemilik', 1, 'e6495851156043d3ebe16e015c3fd325'),
(68124, 'zahara', 'riskazahara43@gmail.com', '12345678901', 'juli', '2147483647', 'Karyawan', 1, '2cdb1449710f9dcd46244743ee202d62'),
(68131, 'vivi', 'vivilutfi8387@gmail.com', '$2y$10$iTg0dSpwnhhohJXo.YvVh.yM2cVrsuL3Wme4rQK/K8I', 'PULO ARA', '2147483647', 'karyawan', 0, '7bb0363e8e80249b43500d0ea77815ac'),
(68157, 'suryani', 'suryaniabdullah98@gmail.com', '$2y$10$4gM3rM3rTp.oRs3VW22VhuFGmNe/E25YXK.mT9pke4ZSpXSBuPX1i', '1', '1', 'pemilik', 1, NULL),
(68168, 'Hanis siddiq', 'hanissiddiq10@gmail.com', '$2y$10$BfDOcBcvPkxMginYbdq/ZeCcsakwRBKz3y2wDSEOSTL0HfwHk.x0O', 'Jln. Tgk Imum Cut Haji, Gampong Kubu', '082211887735', 'karyawan', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `masuk`
--

CREATE TABLE `masuk` (
  `idmasuk` int NOT NULL,
  `idbarang` int NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `Quantity` int NOT NULL,
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masuk`
--

INSERT INTO `masuk` (`idmasuk`, `idbarang`, `tanggal`, `keterangan`, `Quantity`, `satuan`) VALUES
(175, 120, '2025-06-24 21:44:44', 'RISKA ZAHARA', 1, ''),
(176, 118, '2025-06-24 22:20:14', 'RISKA ZAHARA', 3, ''),
(177, 121, '2025-06-24 23:56:29', 'RISKA ZAHARA', 9, ''),
(178, 123, '2025-07-20 03:25:46', 'Hanis siddiq', 4, NULL),
(179, 125, '2025-07-20 03:40:13', 'Hanis siddiq', 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `idbarang` int NOT NULL,
  `namabarang` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `stok_awal` int NOT NULL,
  `stock` int NOT NULL,
  `satuan` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hargabarang` decimal(10,2) NOT NULL,
  `status_barang` enum('baru','lama') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`idbarang`, `namabarang`, `deskripsi`, `stok_awal`, `stock`, `satuan`, `hargabarang`, `status_barang`) VALUES
(118, 'Oreo', 'Cookies', 10, 2, 'pcs', '1500.00', ''),
(119, 'Goreo reo', 'Cookies', 12, 1, 'pcs', '500.00', 'baru'),
(120, 'Lays', 'Snack', 10, 2, 'pcs', '2500.00', ''),
(121, 'Potabee', 'Snack', 15, 5, 'pcs', '5000.00', ''),
(122, 'Toritos', 'Snack', 5, 5, 'pcs', '2000.00', 'baru'),
(123, 'Chocolatos', 'Cookies', 10, 24, 'pcs', '2000.00', NULL),
(124, 'Taroo (barang baru)', 'Snack Ringan', 4, 4, 'pcs', '2500.00', NULL),
(125, 'Mr.Potato', 'Snack Ringan', 3, 0, 'box', '35000.00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keluar`
--
ALTER TABLE `keluar`
  ADD PRIMARY KEY (`idkeluar`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`iduser`);

--
-- Indexes for table `masuk`
--
ALTER TABLE `masuk`
  ADD PRIMARY KEY (`idmasuk`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idbarang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keluar`
--
ALTER TABLE `keluar`
  MODIFY `idkeluar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68169;

--
-- AUTO_INCREMENT for table `masuk`
--
ALTER TABLE `masuk`
  MODIFY `idmasuk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `idbarang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
