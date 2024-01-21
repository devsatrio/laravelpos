-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 21 Jan 2024 pada 20.26
-- Versi server: 10.4.31-MariaDB-1:10.4.31+maria~ubu1804
-- Versi PHP: 7.4.33

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
-- Struktur dari tabel `barang`
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
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `kode`, `kode_qr`, `nama`, `kategori`, `harga_beli`, `harga_jual`, `harga_jual_customer`, `diskon`, `diskon_customer`, `stok`, `keterangan`) VALUES
(2, 'BRG-0002', 'BRG-0002', 'Barang D', 5, 10000, 20000, 12000, 0, 0, 15, '-'),
(3, 'BRG-0003', 'BRG-0003', 'Barang C', 5, 5000, 20000, 30000, 40, 0, 1, '-'),
(4, 'BRG-0004', 'BRG-0004', 'Barang B', 5, 15000, 35000, 30000, 10, 0, 0, 'ket Barang B'),
(5, 'BRG-0005', 'BRG-0005', 'Barang A', 2, 2000000, 3000000, 2500000, 10, 0, 50, 'ket barang A'),
(6, 'BRG-0006', '120823', 'barang E', 5, 25000, 50000, 40000, 0, 0, 0, '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_perbaikan_stok`
--

CREATE TABLE `detail_perbaikan_stok` (
  `id` int(11) NOT NULL,
  `kode_perbaikan_stok` text DEFAULT NULL,
  `kode_barang` text DEFAULT NULL,
  `stok_lama` int(11) DEFAULT NULL,
  `stok_baru` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_perbaikan_stok_thumb`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `kategori_barang`
--

CREATE TABLE `kategori_barang` (
  `id` int(11) NOT NULL,
  `nama` varchar(250) DEFAULT NULL,
  `slug` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_barang`
--

INSERT INTO `kategori_barang` (`id`, `nama`, `slug`) VALUES
(2, 'Kategori B', 'kategori-b'),
(3, 'Kategori A', 'kategori-a'),
(5, 'Kategori C', 'kategori-c');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_stok_barang`
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
-- Struktur dari tabel `master_customer`
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
-- Dumping data untuk tabel `master_customer`
--

INSERT INTO `master_customer` (`id`, `kode`, `nama`, `telp`, `alamat`, `keterangan`) VALUES
(3, 'CUS-001', 'dono', '02348902', 'gurah', 'test'),
(4, 'CUS-002', 'dani', '567474', '-', '-'),
(5, 'CUS-003', 'Joko', '293048290', '-', '-'),
(7, 'CUS-004', 'deva', '23948', 'asdklfj', 'skldfj');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_supplier`
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
-- Dumping data untuk tabel `master_supplier`
--

INSERT INTO `master_supplier` (`id`, `kode`, `nama`, `telp`, `alamat`, `keterangan`) VALUES
(3, 'SUP-001', '-', '-', '-', '-'),
(4, 'SUP-002', 'PT. Jhon', '023948290', 'test', 'test'),
(5, 'SUP-003', 'PT. Dhoe', '234234', '-', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_07_21_062729_create_permission_tables', 1),
(5, '2021_11_07_122500_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\User', 1),
(3, 'App\\User', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `id` bigint(20) NOT NULL,
  `kode_pembelian` varchar(250) DEFAULT NULL,
  `kode_barang` varchar(250) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian_thumb_detail`
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
-- Struktur dari tabel `penjualan`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan_detail`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan_thumb_detail`
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
-- Struktur dari tabel `perbaikan_stok`
--

CREATE TABLE `perbaikan_stok` (
  `id` int(11) NOT NULL,
  `kode` text DEFAULT NULL,
  `pembuat` int(11) DEFAULT NULL,
  `tgl_buat` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
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
(53, 'view-laporan-nilai-barang', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', NULL, NULL),
(2, 'super admin', 'web', NULL, NULL),
(3, 'test roles', 'web', '2023-10-26 18:54:59', '2023-10-26 18:54:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_has_permissions`
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
(22, 3),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 2),
(26, 2),
(26, 3),
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
(53, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
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
  `logo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `nama_program`, `singkatan_nama_program`, `instansi`, `alamat`, `deskripsi_program`, `note`, `note_program`, `logo`) VALUES
(1, 'AGSPEED POS V.1', 'AGPOS', 'AGSPEED SHOP', 'Desa Gurah 1, Kec.Gurah, Kab. Kediri', 'A Pos system for AGSPEED SHOP', 'Barang Yang Sudah Dibeli Tidak Bisa Dikembalikan', 'Harap jangan matikan komputer sebelum proses backup dilakukan', '1639745101-ag-shop.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_lain`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `telp`, `level`, `gambar`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'devasatrio', 'Deva Satrio', 'deva@example.com', '081209380909', 'super admin', NULL, NULL, '$2y$10$1bxkMCXJ0YQ.I//lJY4XJelWYJ/k/Bk5G28z31qCYdi2wJAlkqcAW', 'zoyV7wBI1W9FXD8qzcpSwaiAP0oTnjVABGfhT1qCOGK8UTgvHFB286WXy7qV', '2021-11-17 18:12:54', '2021-11-23 23:20:45'),
(2, 'admin', 'admin', 'admin@gmail.com', '234902', 'test roles', '1637324169-user.png', NULL, '$2y$10$9EKR5n/fmKonHExiQzW49..H29KrhaP/ZVgFwKOregDa/Bxjn1/zq', 'oLVBWNB0kuWf6VcqVTs7FXXhgU5aT4VGlUzGGzAPlJ0f01XVUS3r1XO1oBg6', '2021-11-19 05:16:10', '2023-10-26 18:56:04');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_perbaikan_stok`
--
ALTER TABLE `detail_perbaikan_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_perbaikan_stok_thumb`
--
ALTER TABLE `detail_perbaikan_stok_thumb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori_barang`
--
ALTER TABLE `kategori_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_stok_barang`
--
ALTER TABLE `log_stok_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_customer`
--
ALTER TABLE `master_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_supplier`
--
ALTER TABLE `master_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembelian_thumb_detail`
--
ALTER TABLE `pembelian_thumb_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan_thumb_detail`
--
ALTER TABLE `penjualan_thumb_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `perbaikan_stok`
--
ALTER TABLE `perbaikan_stok`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_lain`
--
ALTER TABLE `transaksi_lain`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `detail_perbaikan_stok`
--
ALTER TABLE `detail_perbaikan_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_perbaikan_stok_thumb`
--
ALTER TABLE `detail_perbaikan_stok_thumb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_barang`
--
ALTER TABLE `kategori_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `log_stok_barang`
--
ALTER TABLE `log_stok_barang`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `master_customer`
--
ALTER TABLE `master_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `master_supplier`
--
ALTER TABLE `master_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembelian_thumb_detail`
--
ALTER TABLE `pembelian_thumb_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualan_thumb_detail`
--
ALTER TABLE `penjualan_thumb_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `perbaikan_stok`
--
ALTER TABLE `perbaikan_stok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi_lain`
--
ALTER TABLE `transaksi_lain`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
