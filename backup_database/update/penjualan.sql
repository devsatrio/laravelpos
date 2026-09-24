-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 5.7.44 - MySQL Community Server (GPL)
-- OS Server:                    Linux
-- HeidiSQL Versi:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- membuang struktur untuk table db_laravel_pos.penjualan
CREATE TABLE IF NOT EXISTS `penjualan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `keterangan` text,
  `status` varchar(200) DEFAULT 'Lunas',
  `status_penjualan` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `jenis_bayar` enum('Cash','QRIS','Transfer Bank') DEFAULT 'Cash',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Membuang data untuk tabel db_laravel_pos.penjualan: ~7 rows (lebih kurang)
REPLACE INTO `penjualan` (`id`, `kode`, `customer`, `subtotal`, `potongan`, `biaya_tambahan`, `total`, `terbayar`, `kekurangan`, `kembalian`, `pembuat`, `tgl_buat`, `keterangan`, `status`, `status_penjualan`, `created_at`, `updated_at`, `created_by`, `updated_by`, `jenis_bayar`) VALUES
	(1, 'PNJ-012024-0001', NULL, 2712000, 212000, 0, 2500000, 2500000, 0, 0, 1, '2024-01-27', NULL, 'Telah Lunas', 'Draft', '2024-01-27 00:12:22', NULL, 1, NULL, 'Cash'),
	(2, 'PNJ-012024-0002', NULL, 100000, 50000, 0, 50000, 50000, 0, 0, 1, '2024-01-27', NULL, 'Telah Lunas', 'Draft', '2024-01-27 00:31:07', NULL, 1, NULL, 'Cash'),
	(3, 'PNJ-052025-0001', 'CUS-004', 39000, 0, 0, 39000, 50000, 0, 11000, 1, '2025-05-04', NULL, 'Telah Lunas', 'Draft', '2025-05-04 07:33:39', NULL, 1, NULL, 'Cash'),
	(4, 'PNJ-052025-0002', NULL, 2720000, 0, 0, 2720000, 2720000, 0, 0, 1, '2025-05-14', NULL, 'Telah Lunas', 'Draft', '2025-05-04 12:53:32', NULL, 1, NULL, 'Cash'),
	(5, 'PNJ-052025-0003', NULL, 20000, 0, 0, 20000, 20000, 0, 0, 1, '2025-05-04', 'test', 'Telah Lunas', 'Draft', '2025-05-04 12:54:55', NULL, 1, NULL, 'Cash'),
	(6, 'PNJ-052025-0004', NULL, 15000, 0, 0, 15000, 15000, 0, 0, 1, '2025-05-04', NULL, 'Telah Lunas', 'Draft', '2025-05-04 12:55:38', NULL, 1, NULL, 'Cash'),
	(7, 'PNJ-092026-0001', 'CUS-005', 40000, 0, 10000, 50000, 30000, 20000, 0, 1, '2026-09-23', 'test', 'Belum Lunas', 'Draft', '2026-09-23 08:10:06', NULL, 1, NULL, 'QRIS');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
