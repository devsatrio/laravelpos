-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 05:08 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_laravel_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_program` varchar(191) NOT NULL,
  `singkatan_nama_program` varchar(191) NOT NULL,
  `instansi` varchar(191) NOT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi_program` varchar(191) NOT NULL,
  `note` text DEFAULT NULL,
  `note_program` text DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `gunakan_scanner` enum('y','n') DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_program`, `singkatan_nama_program`, `instansi`, `alamat`, `deskripsi_program`, `note`, `note_program`, `logo`, `gunakan_scanner`) VALUES
(1, 'Sample Store POS', 'SSPOS', 'Sample Store', 'Desa Gurah 1, Kec.Gurah, Kab. Kediri', 'A Pos system for Sample Store', 'Barang Yang Sudah Dibeli Tidak Bisa Dikembalikan', 'Harap jangan matikan komputer sebelum proses backup dilakukan', '1639745101-ag-shop.jpg', 'n');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
