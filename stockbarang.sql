-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 09:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `idkeluar` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `penerima` varchar(25) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluar`
--

INSERT INTO `keluar` (`idkeluar`, `idbarang`, `tanggal`, `penerima`, `Quantity`) VALUES
(26, 118, '2025-06-24 22:22:00', 'RISKA ZAHARA', 2),
(28, 121, '2025-06-24 23:57:06', 'RISKA ZAHARA', 5);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `iduser` int(11) NOT NULL,
  `namalengkap` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `Alamat` varchar(100) NOT NULL,
  `no_hp` int(12) NOT NULL,
  `posisi` varchar(25) NOT NULL,
  `status_verifikasi` tinyint(1) DEFAULT NULL,
  `token_verifikasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`iduser`, `namalengkap`, `email`, `password`, `Alamat`, `no_hp`, `posisi`, `status_verifikasi`, `token_verifikasi`) VALUES
(68123, 'RISKA ZAHARA', 'ayangkusayang395@gmail.com', '1234567890', 'paloh', 2147483647, 'pemilik', 1, 'e6495851156043d3ebe16e015c3fd325'),
(68124, 'zahara', 'riskazahara43@gmail.com', '12345678901', 'juli', 2147483647, 'Karyawan', 1, '2cdb1449710f9dcd46244743ee202d62'),
(68131, 'vivi', 'vivilutfi8387@gmail.com', '$2y$10$iTg0dSpwnhhohJXo.YvVh.yM2cVrsuL3Wme4rQK/K8I', 'PULO ARA', 2147483647, 'karyawan', 0, '7bb0363e8e80249b43500d0ea77815ac');

-- --------------------------------------------------------

--
-- Table structure for table `masuk`
--

CREATE TABLE `masuk` (
  `idmasuk` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` varchar(25) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `satuan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masuk`
--

INSERT INTO `masuk` (`idmasuk`, `idbarang`, `tanggal`, `keterangan`, `Quantity`, `satuan`) VALUES
(175, 120, '2025-06-24 21:44:44', 'RISKA ZAHARA', 1, ''),
(176, 118, '2025-06-24 22:20:14', 'RISKA ZAHARA', 3, ''),
(177, 121, '2025-06-24 23:56:29', 'RISKA ZAHARA', 9, '');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `idbarang` int(1) NOT NULL,
  `namabarang` varchar(25) NOT NULL,
  `deskripsi` varchar(25) NOT NULL,
  `stok_awal` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `hargabarang` decimal(10,2) NOT NULL,
  `status_barang` enum('baru','lama') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`idbarang`, `namabarang`, `deskripsi`, `stok_awal`, `stock`, `satuan`, `hargabarang`, `status_barang`) VALUES
(118, 'f', 'f', 1, 2, 'box', 1000.00, ''),
(119, 'a', 'a', 1, 1, 'pcs', 1000.00, 'baru'),
(120, 'q', 'q', 1, 2, 'kardus', 1000.00, ''),
(121, 'e', 'e', 1, 5, 'box', 10000.00, '');

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
  MODIFY `idkeluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68132;

--
-- AUTO_INCREMENT for table `masuk`
--
ALTER TABLE `masuk`
  MODIFY `idmasuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `idbarang` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
