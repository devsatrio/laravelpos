-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2021 at 08:56 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

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
  `nama` varchar(300) DEFAULT NULL,
  `kategori` int(11) DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `harga_jual_customer` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `diskon_customer` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode`, `nama`, `kategori`, `harga_beli`, `harga_jual`, `harga_jual_customer`, `diskon`, `diskon_customer`, `stok`, `keterangan`) VALUES
(2, 'BRG-0002', 'test edit', 5, 10, 20, 30, 40, 50, 0, '-'),
(3, 'BRG-0003', 'Barang C', 5, 1, 20000, 30000, 40, 0, 0, '-'),
(4, 'BRG-0004', 'Barang B', 5, 15000, 35000, 30000, 10, 0, 19, 'ket Barang B'),
(5, 'BRG-0005', 'Barang A', 2, 2000000, 3000000, 2500000, 10, 0, 10, 'ket barang A');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id`, `kode`, `supplier`, `subtotal`, `potongan`, `biaya_tambahan`, `total`, `terbayar`, `kekurangan`, `pembuat`, `tgl_buat`, `keterangan`, `status`, `status_pembelian`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(6, 'PMB-112021-0003', 'SUP-002', 2000000, 0, 200000, 2200000, 2200000, 0, 1, '2021-11-23', 'Tambahan biaya ongkir', 'Telah Lunas', 'Approve', NULL, '2021-11-24 03:39:39', NULL, 1),
(7, 'PMB-112021-0004', 'SUP-003', 40075000, 200000, 500000, 40375000, 40375000, 0, 1, '2021-11-23', 'Lunas', 'Telah Lunas', 'Approve', NULL, '2021-11-24 03:59:51', NULL, 1),
(11, 'PMB-112021-0006', 'SUP-003', 6045000, 0, 15000, 6060000, 3000000, 3060000, 1, '2021-11-24', 'belum lunas', 'Belum Lunas', 'Approve', '2021-11-23 22:25:30', '2021-11-24 05:58:58', 1, 1),
(12, 'PMB-112021-0007', 'SUP-003', 10000000, 0, 0, 10000000, 2000000, 8000000, 1, '2021-11-24', NULL, 'Belum Lunas', 'Approve', '2021-11-24 03:23:56', NULL, 1, NULL),
(13, 'PMB-112021-0008', 'SUP-003', 4150000, 100000, 50000, 4100000, 4100000, 0, 1, '2021-11-24', 'sfds', 'Telah Lunas', 'Approve', '2021-11-24 05:23:01', '2021-11-24 05:53:55', 1, 1),
(14, 'PMB-112021-0009', 'SUP-003', 90000, 50000, 200000, 240000, 240000, 0, 1, '2021-11-24', 'Lunas', 'Telah Lunas', 'Approve', '2021-11-24 05:26:38', '2021-11-24 05:35:37', 1, 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`id`, `kode_pembelian`, `kode_barang`, `jumlah`, `harga`, `total`) VALUES
(22, 'PMB-112021-0007', 'BRG-0005', 5, 2000000, 10000000),
(24, 'PMB-112021-0003', 'BRG-0005', 1, 2000000, 2000000),
(25, 'PMB-112021-0004', 'BRG-0004', 5, 15000, 75000),
(26, 'PMB-112021-0004', 'BRG-0005', 20, 2000000, 40000000),
(31, 'PMB-112021-0009', 'BRG-0004', 6, 15000, 90000),
(32, 'PMB-112021-0008', 'BRG-0005', 2, 2000000, 4000000),
(33, 'PMB-112021-0008', 'BRG-0004', 10, 15000, 150000),
(34, 'PMB-112021-0006', 'BRG-0004', 3, 15000, 45000),
(35, 'PMB-112021-0006', 'BRG-0005', 3, 2000000, 6000000);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembelian_thumb_detail`
--

INSERT INTO `pembelian_thumb_detail` (`id`, `kode_pembelian`, `kode_barang`, `jumlah`, `harga`, `total`, `pembuat`) VALUES
(144, 'PMB-112021-0003', 'BRG-0005', 1, 2000000, 2000000, 1),
(149, 'PMB-112021-0004', 'BRG-0004', 5, 15000, 75000, 1),
(150, 'PMB-112021-0004', 'BRG-0005', 20, 2000000, 40000000, 1);

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
  `pembuat` int(11) DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(200) DEFAULT 'Lunas',
  `status_penjualan` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(31, 'approve-pembelian', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `singkatan_nama_program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_program` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `nama_program`, `singkatan_nama_program`, `instansi`, `deskripsi_program`) VALUES
(1, 'Laravel POS V.1', 'LPV1', 'unknow', 'A laravel 7 pos v.1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(1, 'devasatrio', 'Deva Satrio', 'deva@example.com', '081209380909', 'super admin', NULL, NULL, '$2y$10$1bxkMCXJ0YQ.I//lJY4XJelWYJ/k/Bk5G28z31qCYdi2wJAlkqcAW', 'cppmUqduqYvxrt1RvxYZNsUEDHMMnPa83QJ2segeR5ZxL88ZPNSdE4wM7e8m', '2021-11-17 18:12:54', '2021-11-23 23:20:45'),
(2, 'admin', 'admin', 'admin@gmail.com', '234902', 'admin', '1637324169-user.png', NULL, '$2y$10$9EKR5n/fmKonHExiQzW49..H29KrhaP/ZVgFwKOregDa/Bxjn1/zq', 'J4DyK5hisB9oro56CqUgP5rSRjtHKj5FO7JEoE20JCcZsRDLIgNCyDR1kEzl', '2021-11-19 05:16:10', '2021-11-23 23:19:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `pembelian_thumb_detail`
--
ALTER TABLE `pembelian_thumb_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `penjualan_thumb_detail`
--
ALTER TABLE `penjualan_thumb_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
