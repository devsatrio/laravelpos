-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2025 at 09:08 PM
-- Server version: 8.0.42-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `id` bigint NOT NULL,
  `kode` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_qr` text COLLATE utf8mb4_general_ci,
  `nama` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kategori` int DEFAULT NULL,
  `harga_beli` int DEFAULT NULL,
  `harga_jual` int DEFAULT NULL,
  `harga_jual_customer` int DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `diskon_customer` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `hitung_stok` enum('y','n') COLLATE utf8mb4_general_ci DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode`, `kode_qr`, `nama`, `kategori`, `harga_beli`, `harga_jual`, `harga_jual_customer`, `diskon`, `diskon_customer`, `stok`, `keterangan`, `hitung_stok`) VALUES
(2, 'BRG-0002', 'BRG-0002', 'Barang D', 5, 10000, 20000, 12000, 0, 0, 15, '-', 'y'),
(3, 'BRG-0003', 'BRG-0003', 'Barang C', 5, 5000, 20000, 30000, 40, 0, 50, '-', 'y'),
(4, 'BRG-0004', 'BRG-0004', 'Barang B', 5, 15000, 35000, 30000, 10, 0, 50, 'ket Barang B', 'y'),
(5, 'BRG-0005', 'BRG-0005', 'Barang A', 2, 2000000, 3000000, 2500000, 10, 0, 48, 'ket barang A', 'y'),
(6, 'BRG-0006', '120823', 'barang E', 5, 25000, 50000, 40000, 0, 0, 30, '-', 'y'),
(11, 'BRG-0007', '-', 'barang tidak dihitung stok', 3, 10000, 15000, 15000, 0, 0, 0, 'barang tidak dihitung stok', 'n');

-- --------------------------------------------------------

--
-- Table structure for table `detail_perbaikan_stok`
--

CREATE TABLE `detail_perbaikan_stok` (
  `id` int NOT NULL,
  `kode_perbaikan_stok` text COLLATE utf8mb4_general_ci,
  `kode_barang` text COLLATE utf8mb4_general_ci,
  `stok_lama` int DEFAULT NULL,
  `stok_baru` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_perbaikan_stok`
--

INSERT INTO `detail_perbaikan_stok` (`id`, `kode_perbaikan_stok`, `kode_barang`, `stok_lama`, `stok_baru`, `keterangan`) VALUES
(2, 'PBS-0001', 'BRG-0006', 0, 20, NULL),
(8, 'PBS-0002', 'BRG-0003', 0, 50, NULL),
(9, 'PBS-0003', 'BRG-0006', 20, 30, 'test'),
(10, 'PBS-0003', 'BRG-0004', 0, 50, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_perbaikan_stok_thumb`
--

CREATE TABLE `detail_perbaikan_stok_thumb` (
  `id` int NOT NULL,
  `kode_perbaikan_stok` text COLLATE utf8mb4_general_ci,
  `kode_barang` text COLLATE utf8mb4_general_ci,
  `stok_lama` int DEFAULT NULL,
  `stok_baru` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `pembuat` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_barang`
--

CREATE TABLE `kategori_barang` (
  `id` int NOT NULL,
  `nama` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL
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
  `id` bigint NOT NULL,
  `kode_barang` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipe` enum('Stok Masuk','Stok Keluar') COLLATE utf8mb4_general_ci DEFAULT 'Stok Masuk',
  `keterangan` text COLLATE utf8mb4_general_ci,
  `stok_tertakhir` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `hasil_akhir` int DEFAULT NULL,
  `users` int DEFAULT NULL,
  `tgl_buat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_customer`
--

CREATE TABLE `master_customer` (
  `id` int NOT NULL,
  `kode` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telp` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `keterangan` text COLLATE utf8mb4_general_ci
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
  `id` int NOT NULL,
  `kode` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telp` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `keterangan` text COLLATE utf8mb4_general_ci
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
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
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
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\User', 1),
(1, 'App\\User', 2),
(2, 'App\\User', 3),
(2, 'App\\User', 5),
(1, 'App\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint NOT NULL,
  `kode_penjualan` text COLLATE utf8mb4_general_ci,
  `customer` text COLLATE utf8mb4_general_ci,
  `jumlah` int DEFAULT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `kode_penjualan`, `customer`, `jumlah`, `tgl_bayar`, `created_at`, `created_by`, `keterangan`) VALUES
(1, 'PNJ-012024-0001', NULL, 2500000, '2024-01-27', '2024-01-27 00:12:22', 1, 'Pembayaran Pertama & Pelunasan'),
(2, 'PNJ-012024-0002', NULL, 50000, '2024-01-27', '2024-01-27 00:31:07', 1, 'Pembayaran Pertama & Pelunasan'),
(3, 'PNJ-052025-0001', 'CUS-004', 50000, '2025-05-04', '2025-05-04 07:33:39', 1, 'Pembayaran Pertama & Pelunasan'),
(4, 'PNJ-052025-0002', NULL, 2720000, '2025-05-04', '2025-05-04 12:53:32', 1, 'Pembayaran Pertama & Pelunasan'),
(5, 'PNJ-052025-0003', NULL, 20000, '2025-05-04', '2025-05-04 12:54:55', 1, 'Pembayaran Pertama & Pelunasan'),
(6, 'PNJ-052025-0004', NULL, 15000, '2025-05-04', '2025-05-04 12:55:38', 1, 'Pembayaran Pertama & Pelunasan');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id` bigint NOT NULL,
  `kode` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `supplier` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subtotal` int DEFAULT NULL,
  `potongan` int DEFAULT NULL,
  `biaya_tambahan` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `terbayar` int DEFAULT NULL,
  `kekurangan` int DEFAULT NULL,
  `pembuat` int DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` varchar(200) COLLATE utf8mb4_general_ci DEFAULT 'Lunas',
  `status_pembelian` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id`, `kode`, `supplier`, `subtotal`, `potongan`, `biaya_tambahan`, `total`, `terbayar`, `kekurangan`, `pembuat`, `tgl_buat`, `keterangan`, `status`, `status_pembelian`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'PMB-072024-0001', 'SUP-003', 10000, 0, 0, 10000, 2000, 8000, 3, '2024-07-11', 'belum lunas', 'Belum Lunas', 'Approve', '2024-07-10 23:19:22', '2025-12-06 01:49:20', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `id` bigint NOT NULL,
  `kode_pembelian` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_barang` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `total` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id`, `kode_pembelian`, `kode_barang`, `jumlah`, `harga`, `total`) VALUES
(4, 'PMB-072024-0001', 'BRG-0002', 1, 10000, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_thumb_detail`
--

CREATE TABLE `pembelian_thumb_detail` (
  `id` bigint NOT NULL,
  `kode_pembelian` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_barang` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `pembuat` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` bigint NOT NULL,
  `kode` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subtotal` int DEFAULT NULL,
  `potongan` int DEFAULT NULL,
  `biaya_tambahan` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `terbayar` int DEFAULT NULL,
  `kekurangan` int DEFAULT NULL,
  `kembalian` int DEFAULT NULL,
  `pembuat` int DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` varchar(200) COLLATE utf8mb4_general_ci DEFAULT 'Lunas',
  `status_penjualan` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `kode`, `customer`, `subtotal`, `potongan`, `biaya_tambahan`, `total`, `terbayar`, `kekurangan`, `kembalian`, `pembuat`, `tgl_buat`, `keterangan`, `status`, `status_penjualan`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'PNJ-012024-0001', NULL, 2712000, 212000, 0, 2500000, 2500000, 0, 0, 1, '2024-01-27', NULL, 'Telah Lunas', 'Draft', '2024-01-27 00:12:22', NULL, 1, NULL),
(2, 'PNJ-012024-0002', NULL, 100000, 50000, 0, 50000, 50000, 0, 0, 1, '2024-01-27', NULL, 'Telah Lunas', 'Draft', '2024-01-27 00:31:07', NULL, 1, NULL),
(3, 'PNJ-052025-0001', 'CUS-004', 39000, 0, 0, 39000, 50000, 0, 11000, 1, '2025-05-04', NULL, 'Telah Lunas', 'Draft', '2025-05-04 07:33:39', NULL, 1, NULL),
(4, 'PNJ-052025-0002', NULL, 2720000, 0, 0, 2720000, 2720000, 0, 0, 1, '2025-05-14', NULL, 'Telah Lunas', 'Draft', '2025-05-04 12:53:32', NULL, 1, NULL),
(5, 'PNJ-052025-0003', NULL, 20000, 0, 0, 20000, 20000, 0, 0, 1, '2025-05-04', 'test', 'Telah Lunas', 'Draft', '2025-05-04 12:54:55', NULL, 1, NULL),
(6, 'PNJ-052025-0004', NULL, 15000, 0, 0, 15000, 15000, 0, 0, 1, '2025-05-04', NULL, 'Telah Lunas', 'Draft', '2025-05-04 12:55:38', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE `penjualan_detail` (
  `id` bigint NOT NULL,
  `kode_penjualan` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_barang` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `total` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan_detail`
--

INSERT INTO `penjualan_detail` (`id`, `kode_penjualan`, `kode_barang`, `jumlah`, `harga`, `diskon`, `total`) VALUES
(1, 'PNJ-012024-0001', 'BRG-0003', 1, 20000, 40, 12000),
(2, 'PNJ-012024-0001', 'BRG-0005', 1, 3000000, 10, 2700000),
(3, 'PNJ-012024-0002', 'BRG-0002', 5, 20000, 0, 100000),
(4, 'PNJ-052025-0001', 'BRG-0002', 2, 12000, 0, 24000),
(5, 'PNJ-052025-0001', 'BRG-0007', 1, 15000, 0, 15000),
(6, 'PNJ-052025-0002', 'BRG-0002', 1, 20000, 0, 20000),
(7, 'PNJ-052025-0002', 'BRG-0005', 1, 3000000, 10, 2700000),
(8, 'PNJ-052025-0003', 'BRG-0002', 1, 20000, 0, 20000),
(9, 'PNJ-052025-0004', 'BRG-0007', 1, 15000, 0, 15000);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_thumb_detail`
--

CREATE TABLE `penjualan_thumb_detail` (
  `id` bigint NOT NULL,
  `kode_penjualan` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_barang` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `pembuat` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbaikan_stok`
--

CREATE TABLE `perbaikan_stok` (
  `id` int NOT NULL,
  `kode` text COLLATE utf8mb4_general_ci,
  `pembuat` int DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perbaikan_stok`
--

INSERT INTO `perbaikan_stok` (`id`, `kode`, `pembuat`, `tgl_buat`, `keterangan`, `status`) VALUES
(2, 'PBS-0001', 3, '2025-12-18', NULL, 'Approve'),
(4, 'PBS-0002', 3, '2025-12-22', 'test', 'Approve'),
(5, 'PBS-0003', 3, '2025-12-22', 'test', 'Approve');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grub` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `grub`, `created_at`, `updated_at`) VALUES
(1, 'view-users', 'web', 'users', NULL, NULL),
(2, 'create-users', 'web', 'users', NULL, NULL),
(3, 'edit-users', 'web', 'users', NULL, NULL),
(4, 'delete-users', 'web', 'users', NULL, NULL),
(5, 'view-roles', 'web', 'roles', NULL, NULL),
(6, 'create-roles', 'web', 'roles', NULL, NULL),
(7, 'edit-roles', 'web', 'roles', NULL, NULL),
(8, 'delete-roles', 'web', 'roles', NULL, NULL),
(9, 'setting-web', 'web', 'lainnya', NULL, NULL),
(10, 'view-customer', 'web', 'customer', NULL, NULL),
(11, 'create-customer', 'web', 'customer', NULL, NULL),
(12, 'edit-customer', 'web', 'customer', NULL, NULL),
(13, 'delete-customer', 'web', 'customer', NULL, NULL),
(14, 'view-kategori-barang', 'web', 'kategori-barang', NULL, NULL),
(15, 'create-kategori-barang', 'web', 'kategori-barang', NULL, NULL),
(16, 'edit-kategori-barang', 'web', 'kategori-barang', NULL, NULL),
(17, 'delete-kategori-barang', 'web', 'kategori-barang', NULL, NULL),
(18, 'view-supplier', 'web', 'supplier', NULL, NULL),
(19, 'create-supplier', 'web', 'supplier', NULL, NULL),
(20, 'edit-supplier', 'web', 'supplier', NULL, NULL),
(21, 'delete-supplier', 'web', 'supplier', NULL, NULL),
(22, 'view-barang', 'web', 'barang', NULL, NULL),
(23, 'create-barang', 'web', 'barang', NULL, NULL),
(24, 'edit-barang', 'web', 'barang', NULL, NULL),
(25, 'delete-barang', 'web', 'barang', NULL, NULL),
(26, 'view-harga-beli-barang', 'web', 'barang', NULL, NULL),
(27, 'view-pembelian', 'web', 'pembelian', NULL, NULL),
(28, 'create-pembelian', 'web', 'pembelian', NULL, NULL),
(29, 'edit-pembelian', 'web', 'pembelian', NULL, NULL),
(30, 'delete-pembelian', 'web', 'pembelian', NULL, NULL),
(31, 'approve-pembelian', 'web', 'pembelian', NULL, NULL),
(32, 'view-penjualan', 'web', 'penjualan', NULL, NULL),
(33, 'create-penjualan', 'web', 'penjualan', NULL, NULL),
(34, 'update-hutang-penjualan', 'web', 'penjualan', NULL, NULL),
(35, 'delete-penjualan', 'web', 'penjualan', NULL, NULL),
(36, 'view-transaksi-lain', 'web', 'transaksi-lain', NULL, NULL),
(37, 'create-transaksi-lain', 'web', 'transaksi-lain', NULL, NULL),
(38, 'edit-transaksi-lain', 'web', 'transaksi-lain', NULL, NULL),
(39, 'delete-transaksi-lain', 'web', 'transaksi-lain', NULL, NULL),
(40, 'create-perbaikan-stok', 'web', 'perbaikan-stok', NULL, NULL),
(41, 'view-perbaikan-stok', 'web', 'perbaikan-stok', NULL, NULL),
(42, 'edit-perbaikan-stok', 'web', 'perbaikan-stok', NULL, NULL),
(43, 'delete-perbaikan-stok', 'web', 'perbaikan-stok', NULL, NULL),
(44, 'approve-perbaikan-stok', 'web', 'perbaikan-stok', NULL, NULL),
(45, 'cetak-barcode-barang', 'web', 'barang', NULL, NULL),
(46, 'import-export-barang', 'web', 'barang', NULL, NULL),
(47, 'view-laporan-penjualan', 'web', 'laporan', NULL, NULL),
(48, 'view-laporan-detail-penjualan', 'web', 'laporan', NULL, NULL),
(49, 'view-laporan-pembelian', 'web', 'laporan', NULL, NULL),
(50, 'view-laporan-detail-pembelian', 'web', 'laporan', NULL, NULL),
(51, 'view-laporan-pemasukan-pengeluaran-lain', 'web', 'laporan', NULL, NULL),
(52, 'view-laporan-laba-rugi', 'web', 'laporan', NULL, NULL),
(53, 'view-laporan-nilai-barang', 'web', 'laporan', NULL, NULL),
(54, 'view-laporan-penjualan-barang', 'web', 'laporan', NULL, NULL),
(55, 'view-laporan-modal', 'web', 'laporan', NULL, NULL),
(56, 'view-permission', 'web', 'permission', NULL, NULL),
(57, 'create-permission', 'web', 'permission', NULL, NULL),
(58, 'edit-permission', 'web', 'permission', NULL, NULL),
(59, 'delete-permission', 'web', 'permission', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', NULL, NULL),
(2, 'super admin', 'web', NULL, NULL),
(3, 'test roles', 'web', '2023-10-26 18:54:59', '2023-10-26 18:54:59');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(22, 1),
(23, 1),
(24, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
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
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(58, 2),
(59, 2),
(22, 3),
(26, 3);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `singkatan_nama_program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `deskripsi_program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `note_program` text COLLATE utf8mb4_unicode_ci,
  `logo` text COLLATE utf8mb4_unicode_ci,
  `gunakan_scanner` enum('y','n') COLLATE utf8mb4_unicode_ci DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_program`, `singkatan_nama_program`, `instansi`, `alamat`, `deskripsi_program`, `note`, `note_program`, `logo`, `gunakan_scanner`) VALUES
(1, 'AGSPEED POS V.1', 'AGPOS', 'AGSPEED SHOP', 'Desa Gurah 1, Kec.Gurah, Kab. Kediri', 'A Pos system for AGSPEED SHOP', 'Barang Yang Sudah Dibeli Tidak Bisa Dikembalikan', 'Harap jangan matikan komputer sebelum proses backup dilakukan', '1639745101-ag-shop.jpg', 'n');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_lain`
--

CREATE TABLE `transaksi_lain` (
  `id` bigint NOT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci,
  `tgl_buat` date DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_lain`
--

INSERT INTO `transaksi_lain` (`id`, `status`, `jumlah`, `keterangan`, `tgl_buat`, `created_by`, `created_at`) VALUES
(1, 'Pengeluaran', 500000, 'biaya listrik', '2025-12-06', 3, '2025-12-06 02:00:19'),
(2, 'Pengeluaran', 500000, 'Gaji kasir 1', '2025-12-23', 3, '2025-12-22 13:40:49'),
(3, 'Pemasukan', 50000, 'Jual kardus bekas barang', '2025-12-22', 3, '2025-12-22 13:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` text COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `telp`, `level`, `gambar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'devasatrio', 'Deva Satrio', 'deva@example.com', '081209380909', 'super admin', NULL, NULL, '$2y$10$1bxkMCXJ0YQ.I//lJY4XJelWYJ/k/Bk5G28z31qCYdi2wJAlkqcAW', 'rfZGCoJJnPkf4Y5lhATYtb7iFCNGV1DPZGqLg0yZB3LXsjAwS1uGWWfvTWA7', '2021-11-17 18:12:54', '2021-11-23 23:20:45'),
(2, 'admin', 'admin', 'admin@gmail.com', '234902', 'admin', '1637324169-user.png', NULL, '$2y$10$9EKR5n/fmKonHExiQzW49..H29KrhaP/ZVgFwKOregDa/Bxjn1/zq', 'oLVBWNB0kuWf6VcqVTs7FXXhgU5aT4VGlUzGGzAPlJ0f01XVUS3r1XO1oBg6', '2021-11-19 05:16:10', '2025-12-05 16:27:03'),
(3, 'superadmin', 'superadmin', 'superadmin@test.id', '111', 'super admin', '1764978941-madao.jpeg', NULL, '$2y$10$GbEzeLwKsz6HEvwq2BH7Oed2M74D/YphxOhPHaXddR/tXnvBVR9PW', 'sFFYyCzfMnkAdIcKWDvzIaa01X05n6KBk6qRtdhAVypmPQIMtZ9HJClnxVon', '2025-12-05 16:55:41', '2025-12-05 16:55:41'),
(5, 'test_admin_dua', 'test_admin_dua', 'test_admin_dua321@gmail.com', '12345678', 'super admin', '1766101960-logo.png', NULL, '$2y$10$YP/1C36jO.Y.m7RUrSj99OTY2.TaN1IdxwkqhGH2fd/dbMsjErJY6', '9JVn5cWRnJ6cPUT33ggG42PDQpS0dZ6276eKgT2rCsUfJiVDAeBE4hkFVs01', '2025-12-18 16:52:40', '2025-12-18 16:55:17'),
(6, 'test', 'test', 'test@gmail.com', '123214', 'admin', NULL, NULL, '$2y$10$83smP7JCvVwKLRUcImCZ/e5azXoPu2Pza0WX3Wjzq3Qzn0xKAuqP2', 'eu4D6fGj0gIEbAbNQPgyUvtLOzCa062Ekpx5dLW844Jua2B4mIcKsPpaicCF', '2025-12-18 17:11:05', '2025-12-18 17:11:05');

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `detail_perbaikan_stok`
--
ALTER TABLE `detail_perbaikan_stok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `detail_perbaikan_stok_thumb`
--
ALTER TABLE `detail_perbaikan_stok_thumb`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_barang`
--
ALTER TABLE `kategori_barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `log_stok_barang`
--
ALTER TABLE `log_stok_barang`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_customer`
--
ALTER TABLE `master_customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_supplier`
--
ALTER TABLE `master_supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembelian_thumb_detail`
--
ALTER TABLE `pembelian_thumb_detail`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `penjualan_thumb_detail`
--
ALTER TABLE `penjualan_thumb_detail`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `perbaikan_stok`
--
ALTER TABLE `perbaikan_stok`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi_lain`
--
ALTER TABLE `transaksi_lain`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
