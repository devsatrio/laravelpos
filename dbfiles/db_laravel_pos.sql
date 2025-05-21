-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 03:44 AM
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
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(250) DEFAULT NULL,
  `kode_qr` text DEFAULT NULL,
  `nama` varchar(300) DEFAULT NULL,
  `kategori` int(11) DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `harga_jual_customer` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `diskon_customer` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `hitung_stok` enum('y','n') DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode`, `kode_qr`, `nama`, `kategori`, `harga_beli`, `harga_jual`, `harga_jual_customer`, `diskon`, `diskon_customer`, `stok`, `keterangan`, `hitung_stok`) VALUES
(11, 'BRG-0007', '-', 'Nasi Goreng', 5, 20000, 25000, 22000, 0, 0, 0, 'Nasi Goreng biasa', 'n'),
(12, 'BRG-0008', '-', 'gule bakar', 3, 100000, 150000, 125000, 0, 0, 24, 'Sample data', 'y'),
(13, 'BRG-0009', '-', 'Gurame Asam Manis', 3, 200000, 250000, 225000, 0, 0, 0, 'Sample data', 'n'),
(14, 'BRG-0010', '-', 'Le Minerale', 3, 5000, 7000, 6000, 0, 0, 8, 'test', 'y'),
(15, 'BRG-0011', '-', 'Aqua Gelas', 2, 1000, 2000, 2000, 0, 0, 50, 'Agua gelasan', 'y');

-- --------------------------------------------------------

--
-- Table structure for table `detail_perbaikan_stok`
--

CREATE TABLE `detail_perbaikan_stok` (
  `id` int(11) NOT NULL,
  `kode_perbaikan_stok` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `stok_lama` int(11) DEFAULT NULL,
  `stok_baru` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_perbaikan_stok`
--

INSERT INTO `detail_perbaikan_stok` (`id`, `kode_perbaikan_stok`, `kode_barang`, `stok_lama`, `stok_baru`, `keterangan`) VALUES
(13, 'PBS-0001', 'BRG-0008', 0, 40, 'STO'),
(14, 'PBS-0001', 'BRG-0010', 0, 40, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_perbaikan_stok_thumb`
--

CREATE TABLE `detail_perbaikan_stok_thumb` (
  `id` int(11) NOT NULL,
  `kode_perbaikan_stok` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `stok_lama` int(11) DEFAULT NULL,
  `stok_baru` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_perbaikan_stok_thumb`
--

INSERT INTO `detail_perbaikan_stok_thumb` (`id`, `kode_perbaikan_stok`, `kode_barang`, `stok_lama`, `stok_baru`, `keterangan`, `pembuat`) VALUES
(16, 'PBS-0001', 'BRG-0008', 0, 40, 'STO', 1),
(17, 'PBS-0001', 'BRG-0010', 0, 40, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_barang`
--

CREATE TABLE `kategori_barang` (
  `id` int(11) NOT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `slug` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_barang`
--

INSERT INTO `kategori_barang` (`id`, `nama`, `slug`) VALUES
(2, 'Kategori B', 'kategori-b'),
(3, 'Kategori A', 'kategori-a'),
(5, 'Kategori C', 'kategori-c');

-- --------------------------------------------------------

--
-- Table structure for table `log_stok_barang`
--

CREATE TABLE `log_stok_barang` (
  `id` bigint(20) NOT NULL,
  `kode_barang` varchar(300) DEFAULT NULL,
  `tipe` enum('Stok Masuk','Stok Keluar') DEFAULT 'Stok Masuk',
  `keterangan` text DEFAULT NULL,
  `stok_tertakhir` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `hasil_akhir` int(11) DEFAULT NULL,
  `users` int(11) DEFAULT NULL,
  `tgl_buat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_customer`
--

CREATE TABLE `master_customer` (
  `id` int(11) NOT NULL,
  `kode` varchar(250) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `telp` varchar(250) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_customer`
--

INSERT INTO `master_customer` (`id`, `kode`, `nama`, `telp`, `alamat`, `keterangan`) VALUES
(3, 'CUS-001', 'dono', '02348902', 'gurah', 'test'),
(4, 'CUS-002', 'dani', '567474', '-', '-'),
(5, 'CUS-003', 'Joko', '293048290', '-', '-'),
(7, 'CUS-004', 'deva', '23948', 'asdklfj', 'skldfj');

-- --------------------------------------------------------

--
-- Table structure for table `master_supplier`
--

CREATE TABLE `master_supplier` (
  `id` int(11) NOT NULL,
  `kode` varchar(250) DEFAULT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `telp` varchar(250) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_supplier`
--

INSERT INTO `master_supplier` (`id`, `kode`, `nama`, `telp`, `alamat`, `keterangan`) VALUES
(3, 'SUP-001', '-', '-', '-', '-'),
(4, 'SUP-002', 'PT. Jhon', '023948290', 'test', 'test'),
(5, 'SUP-003', 'PT. Dhoe', '234234', '-', '-');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_07_21_062729_create_permission_tables', 1),
(5, '2021_11_07_122500_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 2),
(2, 'App\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) NOT NULL,
  `kode_penjualan` text DEFAULT NULL,
  `customer` text DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `kode_penjualan`, `customer`, `jumlah`, `tgl_bayar`, `created_at`, `created_by`, `keterangan`) VALUES
(30, 'PNJ-012025-0002', NULL, 144000, '2025-01-30', '2025-01-30 01:00:17', 1, 'Pembayaran Pertama & Pelunasan'),
(31, 'PNJ-012025-0003', NULL, 510000, '2025-01-30', '2025-01-30 01:53:00', 1, 'Pembayaran Pertama & Pelunasan'),
(32, 'PNJ-012025-0004', NULL, 305000, '2025-01-30', '2025-01-30 01:56:06', 1, 'Pembayaran Pertama & Pelunasan'),
(33, 'PNJ-012025-0005', 'CUS-004', 200000, '2025-01-30', '2025-01-30 02:19:47', 1, 'Pembayaran Pertama'),
(34, 'PNJ-012025-0005', NULL, 40000, '2025-01-30', '2025-01-30 02:22:12', 1, 'Pembayaran Hutang'),
(35, 'PNJ-052025-0001', NULL, 750000, '2025-05-07', '2025-05-07 00:40:13', 1, 'Pembayaran Pertama & Pelunasan'),
(36, 'PNJ-052025-0002', NULL, 800000, '2025-05-07', '2025-05-07 00:42:19', 1, 'Pembayaran Pertama & Pelunasan'),
(37, 'PNJ-052025-0003', NULL, 150000, '2025-05-09', '2025-05-09 03:28:44', 1, 'Pembayaran Pertama & Pelunasan'),
(38, 'PNJ-052025-0004', NULL, 300000, '2025-05-09', '2025-05-09 03:34:02', 1, 'Pembayaran Pertama & Pelunasan'),
(39, 'PNJ-052025-0005', NULL, 300000, '2025-05-10', '2025-05-10 01:51:30', 1, 'Pembayaran Pertama & Pelunasan'),
(40, 'PNJ-052025-0006', NULL, 150000, '2025-05-10', '2025-05-10 01:57:30', 1, 'Pembayaran Pertama & Pelunasan'),
(41, 'PNJ-052025-0007', NULL, 450000, '2025-05-10', '2025-05-10 01:59:40', 1, 'Pembayaran Pertama & Pelunasan'),
(42, 'PNJ-052025-0008', NULL, 150000, '2025-05-10', '2025-05-10 02:10:31', 1, 'Pembayaran Pertama & Pelunasan'),
(43, 'PNJ-012025-0005', NULL, 100000, '2025-05-10', '2025-05-10 02:16:57', 1, 'Pembayaran Hutang'),
(44, 'PNJ-012025-0005', NULL, 100000, '2025-05-10', '2025-05-10 02:17:06', 1, 'Pembayaran Hutang'),
(47, 'PNJ-052025-0009', NULL, 500000, '2025-05-13', '2025-05-13 04:56:25', 1, 'Pembayaran Pertama & Pelunasan'),
(48, 'PNJ-052025-0010', NULL, 210000, '2025-05-13', '2025-05-13 05:03:56', 1, 'Pembayaran Pertama & Pelunasan'),
(49, 'PNJ-052025-0011', NULL, 140000, '2025-05-13', '2025-05-13 05:05:10', 1, 'Pembayaran Pertama & Pelunasan'),
(50, 'PNJ-052025-0012', NULL, 160000, '2025-05-21', '2025-05-21 01:22:53', 1, 'Pembayaran Pertama & Pelunasan'),
(51, 'PNJ-052025-0013', NULL, 160000, '2025-05-21', '2025-05-21 01:27:18', 1, 'Pembayaran Pertama & Pelunasan'),
(52, 'PNJ-052025-0014', NULL, 143000, '2025-05-21', '2025-05-21 01:31:00', 1, 'Pembayaran Pertama & Pelunasan'),
(53, 'PNJ-052025-0015', 'CUS-004', 60000, '2025-05-21', '2025-05-21 01:32:00', 1, 'Pembayaran Pertama');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(250) DEFAULT NULL,
  `supplier` varchar(250) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `potongan` int(11) DEFAULT NULL,
  `biaya_tambahan` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `terbayar` int(11) DEFAULT NULL,
  `kekurangan` int(11) DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(200) DEFAULT 'Lunas',
  `status_pembelian` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id`, `kode`, `supplier`, `subtotal`, `potongan`, `biaya_tambahan`, `total`, `terbayar`, `kekurangan`, `pembuat`, `tgl_buat`, `keterangan`, `status`, `status_pembelian`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(19, 'PMB-012025-0001', 'SUP-003', 150000, 0, 30000, 180000, 180000, 0, 1, '2025-01-30', 'tambah ongkir', 'Telah Lunas', 'Approve', '2025-01-30 03:03:55', '2025-01-30 03:22:59', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `id` bigint(20) NOT NULL,
  `kode_pembelian` varchar(250) DEFAULT NULL,
  `kode_barang` varchar(250) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id`, `kode_pembelian`, `kode_barang`, `jumlah`, `harga`, `total`) VALUES
(54, 'PMB-012025-0001', 'BRG-0010', 20, 5000, 100000),
(55, 'PMB-012025-0001', 'BRG-0011', 50, 1000, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_thumb_detail`
--

CREATE TABLE `pembelian_thumb_detail` (
  `id` bigint(20) NOT NULL,
  `kode_pembelian` varchar(250) DEFAULT NULL,
  `kode_barang` varchar(250) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` bigint(20) NOT NULL,
  `kode` varchar(250) DEFAULT NULL,
  `customer` varchar(250) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL,
  `potongan` int(11) DEFAULT NULL,
  `biaya_tambahan` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `terbayar` int(11) DEFAULT NULL,
  `kekurangan` int(11) DEFAULT NULL,
  `kembalian` int(11) DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(200) DEFAULT 'Lunas',
  `status_penjualan` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `kode`, `customer`, `subtotal`, `potongan`, `biaya_tambahan`, `total`, `terbayar`, `kekurangan`, `kembalian`, `pembuat`, `tgl_buat`, `keterangan`, `status`, `status_penjualan`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(40, 'PNJ-012025-0002', NULL, 139000, 0, 5000, 144000, 144000, 0, 0, 1, '2025-01-30', 'test', 'Telah Lunas', 'Draft', '2025-01-30 01:00:17', NULL, 1, NULL),
(41, 'PNJ-012025-0003', NULL, 500000, 0, 10000, 510000, 510000, 0, 0, 1, '2025-01-29', NULL, 'Telah Lunas', 'Draft', '2025-01-30 01:53:00', NULL, 1, NULL),
(42, 'PNJ-012025-0004', NULL, 325000, 20000, 0, 305000, 305000, 0, 0, 1, '2025-01-30', NULL, 'Telah Lunas', 'Draft', '2025-01-30 01:56:06', NULL, 1, NULL),
(43, 'PNJ-012025-0005', 'CUS-004', 440000, 0, 0, 440000, 440000, 0, 0, 1, '2025-01-30', NULL, 'Telah Lunas', 'Draft', '2025-01-30 02:19:47', NULL, 1, NULL),
(44, 'PNJ-052025-0001', NULL, 750000, 0, 0, 750000, 750000, 0, 0, 1, '2025-05-07', 'asdf', 'Telah Lunas', 'Draft', '2025-05-07 00:40:13', NULL, 1, NULL),
(45, 'PNJ-052025-0002', NULL, 750000, 0, 0, 750000, 800000, 0, 50000, 1, '2025-05-07', NULL, 'Telah Lunas', 'Draft', '2025-05-07 00:42:19', NULL, 1, NULL),
(46, 'PNJ-052025-0003', NULL, 150000, 0, 0, 150000, 150000, 0, 0, 1, '2025-05-09', NULL, 'Telah Lunas', 'Draft', '2025-05-09 03:28:44', NULL, 1, NULL),
(47, 'PNJ-052025-0004', NULL, 300000, 0, 0, 300000, 300000, 0, 0, 1, '2025-05-09', NULL, 'Telah Lunas', 'Draft', '2025-05-09 03:34:02', NULL, 1, NULL),
(48, 'PNJ-052025-0005', NULL, 300000, 0, 0, 300000, 300000, 0, 0, 1, '2025-05-10', NULL, 'Telah Lunas', 'Draft', '2025-05-10 01:51:30', NULL, 1, NULL),
(49, 'PNJ-052025-0006', NULL, 150000, 0, 0, 150000, 150000, 0, 0, 1, '2025-05-10', 'test', 'Telah Lunas', 'Draft', '2025-05-10 01:57:30', NULL, 1, NULL),
(50, 'PNJ-052025-0007', NULL, 450000, 0, 0, 450000, 450000, 0, 0, 1, '2025-05-10', NULL, 'Telah Lunas', 'Draft', '2025-05-10 01:59:40', NULL, 1, NULL),
(51, 'PNJ-052025-0008', NULL, 150000, 0, 0, 150000, 150000, 0, 0, 1, '2025-05-10', NULL, 'Telah Lunas', 'Draft', '2025-05-10 02:10:31', NULL, 1, NULL),
(54, 'PNJ-052025-0009', NULL, 500000, 0, 0, 500000, 500000, 0, 0, 1, '2025-05-13', NULL, 'Telah Lunas', 'Draft', '2025-05-13 04:56:25', NULL, 1, NULL),
(55, 'PNJ-052025-0010', NULL, 210000, 0, 0, 210000, 210000, 0, 0, 1, '2025-05-13', NULL, 'Telah Lunas', 'Draft', '2025-05-13 05:03:56', NULL, 1, NULL),
(56, 'PNJ-052025-0011', NULL, 140000, 0, 0, 140000, 140000, 0, 0, 1, '2025-05-13', NULL, 'Telah Lunas', 'Draft', '2025-05-13 05:05:10', NULL, 1, NULL),
(57, 'PNJ-052025-0012', NULL, 150000, 0, 0, 150000, 160000, 0, 10000, 1, '2025-05-21', 'test', 'Telah Lunas', 'Draft', '2025-05-21 01:22:53', NULL, 1, NULL),
(58, 'PNJ-052025-0013', NULL, 150000, 0, 0, 150000, 160000, 0, 10000, 1, '2025-05-21', 'test', 'Telah Lunas', 'Draft', '2025-05-21 01:27:18', NULL, 1, NULL),
(59, 'PNJ-052025-0014', NULL, 150000, 10000, 2000, 142000, 143000, 0, 1000, 1, '2025-05-21', 'test', 'Telah Lunas', 'Draft', '2025-05-21 01:31:00', NULL, 1, NULL),
(60, 'PNJ-052025-0015', 'CUS-004', 125000, 0, 2000, 127000, 60000, 67000, 0, 1, '2025-05-21', 'hutang', 'Belum Lunas', 'Draft', '2025-05-21 01:32:00', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE `penjualan_detail` (
  `id` bigint(20) NOT NULL,
  `kode_penjualan` varchar(250) DEFAULT NULL,
  `kode_barang` varchar(250) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan_detail`
--

INSERT INTO `penjualan_detail` (`id`, `kode_penjualan`, `kode_barang`, `jumlah`, `harga`, `diskon`, `total`) VALUES
(79, 'PNJ-012025-0002', 'BRG-0010', 2, 7000, 0, 14000),
(80, 'PNJ-012025-0002', 'BRG-0007', 5, 25000, 0, 125000),
(81, 'PNJ-012025-0003', 'BRG-0007', 20, 25000, 0, 500000),
(82, 'PNJ-012025-0004', 'BRG-0008', 2, 150000, 0, 300000),
(83, 'PNJ-012025-0004', 'BRG-0007', 1, 25000, 0, 25000),
(84, 'PNJ-012025-0005', 'BRG-0007', 20, 22000, 0, 440000),
(85, 'PNJ-052025-0001', 'BRG-0009', 3, 250000, 0, 750000),
(86, 'PNJ-052025-0002', 'BRG-0009', 3, 250000, 0, 750000),
(87, 'PNJ-052025-0003', 'BRG-0008', 1, 150000, 0, 150000),
(88, 'PNJ-052025-0004', 'BRG-0008', 2, 150000, 0, 300000),
(89, 'PNJ-052025-0005', 'BRG-0008', 2, 150000, 0, 300000),
(90, 'PNJ-052025-0006', 'BRG-0008', 1, 150000, 0, 150000),
(91, 'PNJ-052025-0007', 'BRG-0008', 3, 150000, 0, 450000),
(92, 'PNJ-052025-0008', 'BRG-0008', 1, 150000, 0, 150000),
(96, 'PNJ-052025-0009', 'BRG-0009', 2, 250000, 0, 500000),
(97, 'PNJ-052025-0010', 'BRG-0010', 30, 7000, 0, 210000),
(98, 'PNJ-052025-0011', 'BRG-0010', 20, 7000, 0, 140000),
(99, 'PNJ-052025-0012', 'BRG-0008', 1, 150000, 0, 150000),
(100, 'PNJ-052025-0013', 'BRG-0008', 1, 150000, 0, 150000),
(101, 'PNJ-052025-0014', 'BRG-0008', 1, 150000, 0, 150000),
(102, 'PNJ-052025-0015', 'BRG-0008', 1, 125000, 0, 125000);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_thumb_detail`
--

CREATE TABLE `penjualan_thumb_detail` (
  `id` bigint(20) NOT NULL,
  `kode_penjualan` varchar(250) DEFAULT NULL,
  `kode_barang` varchar(250) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbaikan_stok`
--

CREATE TABLE `perbaikan_stok` (
  `id` int(11) NOT NULL,
  `kode` text DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perbaikan_stok`
--

INSERT INTO `perbaikan_stok` (`id`, `kode`, `pembuat`, `tgl_buat`, `keterangan`, `status`) VALUES
(6, 'PBS-0001', 1, '2025-01-30', 'test', 'Approve');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-users', 'web', NULL, NULL),
(2, 'create-users', 'web', NULL, NULL),
(3, 'edit-users', 'web', NULL, NULL),
(4, 'delete-users', 'web', NULL, NULL),
(5, 'view-roles', 'web', NULL, NULL),
(6, 'create-roles', 'web', NULL, NULL),
(7, 'edit-roles', 'web', NULL, NULL),
(8, 'delete-roles', 'web', NULL, NULL),
(9, 'setting-web', 'web', NULL, NULL),
(10, 'view-customer', 'web', NULL, NULL),
(11, 'create-customer', 'web', NULL, NULL),
(12, 'edit-customer', 'web', NULL, NULL),
(13, 'delete-customer', 'web', NULL, NULL),
(14, 'view-kategori-barang', 'web', NULL, NULL),
(15, 'create-kategori-barang', 'web', NULL, NULL),
(16, 'edit-kategori-barang', 'web', NULL, NULL),
(17, 'delete-kategori-barang', 'web', NULL, NULL),
(18, 'view-supplier', 'web', NULL, NULL),
(19, 'create-supplier', 'web', NULL, NULL),
(20, 'edit-supplier', 'web', NULL, NULL),
(21, 'delete-supplier', 'web', NULL, NULL),
(22, 'view-barang', 'web', NULL, NULL),
(23, 'create-barang', 'web', NULL, NULL),
(24, 'edit-barang', 'web', NULL, NULL),
(25, 'delete-barang', 'web', NULL, NULL),
(26, 'view-harga-beli-barang', 'web', NULL, NULL),
(27, 'view-pembelian', 'web', NULL, NULL),
(28, 'create-pembelian', 'web', NULL, NULL),
(29, 'edit-pembelian', 'web', NULL, NULL),
(30, 'delete-pembelian', 'web', NULL, NULL),
(31, 'approve-pembelian', 'web', NULL, NULL),
(32, 'view-penjualan', 'web', NULL, NULL),
(33, 'create-penjualan', 'web', NULL, NULL),
(34, 'update-hutang-penjualan', 'web', NULL, NULL),
(35, 'delete-penjualan', 'web', NULL, NULL),
(36, 'view-transaksi-lain', 'web', NULL, NULL),
(37, 'create-transaksi-lain', 'web', NULL, NULL),
(38, 'edit-transaksi-lain', 'web', NULL, NULL),
(39, 'delete-transaksi-lain', 'web', NULL, NULL),
(40, 'create-perbaikan-stok', 'web', NULL, NULL),
(41, 'view-perbaikan-stok', 'web', NULL, NULL),
(42, 'edit-perbaikan-stok', 'web', NULL, NULL),
(43, 'delete-perbaikan-stok', 'web', NULL, NULL),
(44, 'approve-perbaikan-stok', 'web', NULL, NULL),
(45, 'cetak-barcode-barang', 'web', NULL, NULL),
(46, 'import-export-barang', 'web', NULL, NULL),
(47, 'view-laporan-penjualan', 'web', NULL, NULL),
(48, 'view-laporan-detail-penjualan', 'web', NULL, NULL),
(49, 'view-laporan-pembelian', 'web', NULL, NULL),
(50, 'view-laporan-detail-pembelian', 'web', NULL, NULL),
(51, 'view-laporan-pemasukan-pengeluaran-lain', 'web', NULL, NULL),
(52, 'view-laporan-laba-rugi', 'web', NULL, NULL),
(53, 'view-laporan-penjualan-barang', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', NULL, NULL),
(2, 'super admin', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 2),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(34, 2),
(35, 2),
(36, 1),
(36, 2),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 1),
(47, 2),
(48, 1),
(48, 2),
(49, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2);

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

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_lain`
--

CREATE TABLE `transaksi_lain` (
  `id` bigint(20) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_lain`
--

INSERT INTO `transaksi_lain` (`id`, `status`, `jumlah`, `keterangan`, `tgl_buat`, `created_by`, `created_at`) VALUES
(1, 'Pengeluaran', 20000, NULL, '2021-11-30', 1, '2021-11-29 13:04:43'),
(3, 'Pemasukan', 35000, 'test edit', '2021-11-29', 1, '2021-11-29 13:04:17'),
(4, 'Pengeluaran', 200000, 'Bayar Listrik & internet', '2024-01-16', 1, '2024-01-16 01:28:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `telp` varchar(191) DEFAULT NULL,
  `level` varchar(191) DEFAULT NULL,
  `gambar` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `telp`, `level`, `gambar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'devasatrio', 'Deva Satrio', 'deva_edit@example.com', '081209380909', 'super admin', NULL, NULL, '$2y$10$1bxkMCXJ0YQ.I//lJY4XJelWYJ/k/Bk5G28z31qCYdi2wJAlkqcAW', 'wEmoMVotel5axScdHF97aXcnf1KEjVMubYbVhegNFw3FlVc7UdJahnjIi1w1', '2021-11-17 18:12:54', '2024-01-15 18:29:41'),
(2, 'admin', 'admin', 'admin@gmail.com', '234902', 'admin', '1637324169-user.png', NULL, '$2y$10$9EKR5n/fmKonHExiQzW49..H29KrhaP/ZVgFwKOregDa/Bxjn1/zq', 'jXCQp3O9pOIC29xlli451g3m6sq82YDRJhPX9E3rpOqVubPvx5C0NmlEXC1E', '2021-11-19 05:16:10', '2021-11-23 23:19:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_perbaikan_stok`
--
ALTER TABLE `detail_perbaikan_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_perbaikan_stok_thumb`
--
ALTER TABLE `detail_perbaikan_stok_thumb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_barang`
--
ALTER TABLE `kategori_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_stok_barang`
--
ALTER TABLE `log_stok_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_customer`
--
ALTER TABLE `master_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_supplier`
--
ALTER TABLE `master_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembelian_thumb_detail`
--
ALTER TABLE `pembelian_thumb_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan_thumb_detail`
--
ALTER TABLE `penjualan_thumb_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perbaikan_stok`
--
ALTER TABLE `perbaikan_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_lain`
--
ALTER TABLE `transaksi_lain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `detail_perbaikan_stok`
--
ALTER TABLE `detail_perbaikan_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `detail_perbaikan_stok_thumb`
--
ALTER TABLE `detail_perbaikan_stok_thumb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_barang`
--
ALTER TABLE `kategori_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `log_stok_barang`
--
ALTER TABLE `log_stok_barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_customer`
--
ALTER TABLE `master_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_supplier`
--
ALTER TABLE `master_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `pembelian_thumb_detail`
--
ALTER TABLE `pembelian_thumb_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `penjualan_thumb_detail`
--
ALTER TABLE `penjualan_thumb_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `perbaikan_stok`
--
ALTER TABLE `perbaikan_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi_lain`
--
ALTER TABLE `transaksi_lain`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
