-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2026 at 12:26 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kargo_ekspedisi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kargo`
--

CREATE TABLE `kargo` (
  `id_resi` varchar(20) NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `kota_tujuan` varchar(50) NOT NULL,
  `berat_barang` decimal(10,2) NOT NULL,
  `tarif_dasar_perKg` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kargo`
--

INSERT INTO `kargo` (`id_resi`, `pengirim`, `kota_tujuan`, `berat_barang`, `tarif_dasar_perKg`) VALUES
('KRG-001', 'PT Maju Jaya', 'Jakarta', '150.50', '10000.00'),
('KRG-002', 'Budi Santoso', 'Surabaya', '5.25', '15000.00'),
('KRG-003', 'Siti Aminah', 'Bandung', '12.00', '12000.00'),
('KRG-004', 'CV Makmur Abadi', 'Medan', '45.75', '25000.00'),
('KRG-005', 'Andi Permana', 'Yogyakarta', '2.50', '18000.00'),
('KRG-006', 'Toko Elektronik Bintang', 'Semarang', '25.00', '14000.00'),
('KRG-007', 'Rina Marlina', 'Makassar', '8.30', '30000.00'),
('KRG-008', 'PT Global Sukses', 'Balikpapan', '120.00', '35000.00'),
('KRG-009', 'Hendra Gunawan', 'Denpasar', '15.50', '20000.00'),
('KRG-010', 'Agus Riyadi', 'Palembang', '7.80', '22000.00'),
('KRG-011', 'Toko Buku Pintar', 'Malang', '30.20', '16000.00'),
('KRG-012', 'Diana Sari', 'Surakarta', '4.00', '15000.00'),
('KRG-013', 'PT Agrobisnis Nusantara', 'Pontianak', '85.50', '32000.00'),
('KRG-014', 'Lukman Hakim', 'Banjarmasin', '11.25', '28000.00'),
('KRG-015', 'Fajar Pratama', 'Padang', '6.50', '24000.00'),
('KRG-016', 'CV Karya Mandiri', 'Pekanbaru', '55.00', '26000.00'),
('KRG-017', 'Maya Indah', 'Bandar Lampung', '3.75', '19000.00'),
('KRG-018', 'PT Tekstil Sentosa', 'Jakarta', '210.00', '9000.00'),
('KRG-019', 'Rizki Aditya', 'Surabaya', '1.50', '15000.00'),
('KRG-020', 'Toko Kelontong Berkah', 'Bandung', '40.00', '12000.00'),
('KRG-021', 'Klinik Sehat Selalu', 'Medan', '18.50', '25000.00'),
('KRG-022', 'Joko Susanto', 'Yogyakarta', '9.90', '18000.00'),
('KRG-023', 'PT Mebel Kayu Jati', 'Semarang', '350.00', '11000.00'),
('KRG-024', 'Siska Wulandari', 'Makassar', '2.20', '30000.00'),
('KRG-025', 'CV Sinar Harapan', 'Manado', '28.75', '40000.00'),
('KRG-026', 'Ahmad Fauzi', 'Denpasar', '14.40', '20000.00'),
('KRG-027', 'PT Sparepart Motor', 'Palembang', '62.50', '22000.00'),
('KRG-028', 'Nita Kusumawati', 'Malang', '5.80', '16000.00'),
('KRG-029', 'Toko Sepatu Gaya', 'Surakarta', '22.00', '15000.00'),
('KRG-030', 'PT Chemindo Perkasa', 'Cilegon', '95.00', '13000.00'),
('KRG-031', 'PT Petrokimia Gresik', 'Surabaya', '500.00', '12000.00'),
('KRG-032', 'CV Sumber Asam', 'Jakarta', '150.50', '15000.00'),
('KRG-033', 'PT Bayer Indonesia', 'Bandung', '75.00', '13000.00'),
('KRG-034', 'Toko Tani Makmur', 'Medan', '25.50', '25000.00'),
('KRG-035', 'PT Pupuk Kaltim', 'Balikpapan', '1000.00', '35000.00'),
('KRG-036', 'Laboratorium BioMedika', 'Yogyakarta', '5.00', '18000.00'),
('KRG-037', 'PT Indo Acid', 'Semarang', '220.00', '14000.00'),
('KRG-038', 'CV Gas Industri', 'Makassar', '80.00', '30000.00'),
('KRG-039', 'Klinik Estetika', 'Denpasar', '12.50', '20000.00'),
('KRG-040', 'PT Avian Brands', 'Palembang', '310.00', '22000.00'),
('KRG-041', 'Pabrik Kertas Nusantara', 'Malang', '450.00', '16000.00'),
('KRG-042', 'PT Unilever', 'Surakarta', '125.00', '15000.00'),
('KRG-043', 'Budi Pestisida', 'Pontianak', '45.00', '32000.00'),
('KRG-044', 'PT Aneka Tambang', 'Banjarmasin', '500.00', '28000.00'),
('KRG-045', 'RSUD Pusat', 'Padang', '18.50', '24000.00'),
('KRG-046', 'CV Kimia Jaya', 'Pekanbaru', '95.00', '26000.00'),
('KRG-047', 'PT Pertamina', 'Bandar Lampung', '850.00', '19000.00'),
('KRG-048', 'Pabrik Tekstil Biru', 'Jakarta', '210.00', '15000.00'),
('KRG-049', 'Laboratorium Kampus', 'Surabaya', '3.50', '12000.00'),
('KRG-050', 'PT Sinar Mas', 'Bandung', '400.00', '13000.00'),
('KRG-051', 'CV Pembersih Kaca', 'Medan', '55.00', '25000.00'),
('KRG-052', 'PT Cat Dulux', 'Yogyakarta', '180.00', '18000.00'),
('KRG-053', 'Pabrik Sabun Cuci', 'Semarang', '270.00', '14000.00'),
('KRG-054', 'Koperasi Petani', 'Makassar', '60.00', '30000.00'),
('KRG-055', 'RS Siloam', 'Manado', '15.00', '40000.00'),
('KRG-056', 'PT Kalbe Farma', 'Denpasar', '45.50', '20000.00'),
('KRG-057', 'CV Lem Super', 'Palembang', '35.00', '22000.00'),
('KRG-058', 'Pabrik Plastik Eka', 'Malang', '320.00', '16000.00'),
('KRG-059', 'Toko Cat Warna', 'Surakarta', '85.00', '15000.00'),
('KRG-060', 'PT Chandra Asri', 'Cilegon', '900.00', '13000.00'),
('KRG-061', 'Toko Kaca Cemerlang', 'Surabaya', '45.00', '15000.00'),
('KRG-062', 'Galeri Keramik Antik', 'Jakarta', '12.50', '18000.00'),
('KRG-063', 'PT Elektronik Maju', 'Bandung', '5.00', '14000.00'),
('KRG-064', 'Boutique Piring Hias', 'Medan', '8.20', '25000.00'),
('KRG-065', 'CV Lampu Kristal', 'Balikpapan', '25.00', '35000.00'),
('KRG-066', 'Toko Figura Custom', 'Yogyakarta', '4.50', '16000.00'),
('KRG-067', 'Handicraft Nusantara', 'Semarang', '15.00', '14000.00'),
('KRG-068', 'Suryani Sitorus', 'Makassar', '2.50', '30000.00'),
('KRG-069', 'PT Monitor Layar Sentuh', 'Denpasar', '55.00', '20000.00'),
('KRG-070', 'Toko Gerabah Halus', 'Palembang', '30.00', '22000.00'),
('KRG-071', 'Studio Patung Kaca', 'Malang', '18.50', '15000.00'),
('KRG-072', 'Kolektor Guci', 'Surakarta', '40.00', '16000.00'),
('KRG-073', 'Distributor Lensa Kamera', 'Pontianak', '1.50', '32000.00'),
('KRG-074', 'Toko Souvenir Pernikahan', 'Banjarmasin', '60.00', '28000.00'),
('KRG-075', 'CV Akrilik Bening', 'Padang', '22.00', '24000.00'),
('KRG-076', 'Pusat Aquarium', 'Pekanbaru', '85.00', '26000.00'),
('KRG-077', 'Toko Alat Lab Kaca', 'Bandar Lampung', '6.00', '19000.00'),
('KRG-078', 'Galeri Seni Rupa', 'Jakarta', '35.50', '15000.00'),
('KRG-079', 'PT Smart TV Indonesia', 'Surabaya', '120.00', '14000.00'),
('KRG-080', 'Pengrajin Terakota', 'Bandung', '50.00', '13000.00'),
('KRG-081', 'Toko Cermin Hias', 'Medan', '28.00', '25000.00'),
('KRG-082', 'Hendra Laptop Bekas', 'Yogyakarta', '3.50', '16000.00'),
('KRG-083', 'CV Kaca Film', 'Semarang', '14.00', '14000.00'),
('KRG-084', 'Distributor Porselen', 'Makassar', '75.00', '30000.00'),
('KRG-085', 'Toko Smartphone', 'Manado', '2.00', '40000.00'),
('KRG-086', 'Butik Vas Bunga', 'Denpasar', '9.00', '20000.00'),
('KRG-087', 'Klinik Gigi (Alat Medis)', 'Palembang', '5.50', '22000.00'),
('KRG-088', 'PT Kaca Jendela', 'Malang', '210.00', '15000.00'),
('KRG-089', 'Toko Frame Kacamata', 'Surakarta', '1.20', '16000.00'),
('KRG-090', 'Galeri Marmer', 'Cilegon', '150.00', '14000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kargo_bahan_kimia`
--

CREATE TABLE `kargo_bahan_kimia` (
  `id_resi` varchar(20) NOT NULL,
  `tingkat_bahaya` int NOT NULL,
  `jenis_sertifikasi_sandi` varchar(100) NOT NULL,
  `biaya_penanganan_khusus` decimal(12,2) NOT NULL
) ;

--
-- Dumping data for table `kargo_bahan_kimia`
--

INSERT INTO `kargo_bahan_kimia` (`id_resi`, `tingkat_bahaya`, `jenis_sertifikasi_sandi`, `biaya_penanganan_khusus`) VALUES
('KRG-031', 5, 'MSDS-OXIDIZER-501', '500000.00'),
('KRG-032', 8, 'UN-1789-CORROSIVE', '800000.00'),
('KRG-033', 6, 'MSDS-TOXIC-602', '600000.00'),
('KRG-034', 3, 'UN-1203-FLAMMABLE', '300000.00'),
('KRG-035', 9, 'MSDS-MISC-901', '900000.00'),
('KRG-036', 6, 'UN-2814-INFECTIOUS', '600000.00'),
('KRG-037', 8, 'MSDS-CORROSIVE-802', '800000.00'),
('KRG-038', 2, 'UN-1072-GAS', '200000.00'),
('KRG-039', 4, 'MSDS-FLAMMABLE-SOLID', '400000.00'),
('KRG-040', 3, 'UN-1263-PAINT', '300000.00'), 
('KRG-041', 5, 'MSDS-OXIDIZING-LIQUID', '500000.00'),
('KRG-042', 8, 'UN-1824-CORROSIVE', '800000.00'),
('KRG-043', 6, 'MSDS-PESTICIDE-TOXIC', '600000.00'),
('KRG-044', 9, 'UN-3077-ENV-HAZARD', '900000.00'),
('KRG-045', 6, 'MSDS-BIOHAZARD', '600000.00'),
('KRG-046', 8, 'UN-2794-BATTERY-ACID', '800000.00'),
('KRG-047', 3, 'MSDS-PETROLEUM', '300000.00'),
('KRG-048', 4, 'UN-1325-FLAM-SOLID', '400000.00'),
('KRG-049', 7, 'MSDS-RADIOACTIVE', '700000.00'),
('KRG-050', 5, 'UN-1942-AMMONIUM', '500000.00'),
('KRG-051', 8, 'MSDS-ACID-CLEANER', '800000.00'),
('KRG-052', 3, 'UN-1263-PAINT-MAT', '300000.00'),
('KRG-053', 8, 'MSDS-CAUSTIC-SODA', '800000.00'),
('KRG-054', 6, 'UN-2588-PESTICIDE', '600000.00'),
('KRG-055', 2, 'MSDS-OXYGEN-COMP', '200000.00'),
('KRG-056', 6, 'UN-1851-MEDICINE-TOXIC', '600000.00'),
('KRG-057', 3, 'MSDS-ADHESIVE-FLAM', '300000.00'),
('KRG-058', 9, 'UN-3082-POLYMER', '900000.00'),
('KRG-059', 3, 'MSDS-THINNER', '300000.00'),
('KRG-060', 2, 'UN-1033-PROPYLENE', '200000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kargo_pecah_belah`
--

CREATE TABLE `kargo_pecah_belah` (
  `id_resi` varchar(20) NOT NULL,
  `ketebalan_bubbleWrap` int NOT NULL,
  `biaya_asuransiWajib` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kargo_pecah_belah`
--

INSERT INTO `kargo_pecah_belah` (`id_resi`, `ketebalan_bubbleWrap`, `biaya_asuransiWajib`) VALUES
('KRG-061', 3, '50000.00'),
('KRG-062', 5, '150000.00'),
('KRG-063', 3, '25000.00'),
('KRG-064', 4, '30000.00'),
('KRG-065', 5, '200000.00'),
('KRG-066', 2, '15000.00'),
('KRG-067', 3, '20000.00'),
('KRG-068', 2, '10000.00'),
('KRG-069', 4, '100000.00'),
('KRG-070', 3, '40000.00'),
('KRG-071', 5, '120000.00'),
('KRG-072', 4, '80000.00'),
('KRG-073', 5, '250000.00'),
('KRG-074', 2, '35000.00'),
('KRG-075', 3, '25000.00'),
('KRG-076', 4, '75000.00'),
('KRG-077', 5, '50000.00'),
('KRG-078', 4, '150000.00'),
('KRG-079', 5, '300000.00'),
('KRG-080', 2, '20000.00'),
('KRG-081', 3, '45000.00'),
('KRG-082', 4, '50000.00'),
('KRG-083', 2, '15000.00'),
('KRG-084', 5, '180000.00'),
('KRG-085', 3, '50000.00'),
('KRG-086', 4, '30000.00'),
('KRG-087', 5, '100000.00'),
('KRG-088', 2, '85000.00'),
('KRG-089', 3, '10000.00'),
('KRG-090', 4, '90000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kargo_reguler`
--

CREATE TABLE `kargo_reguler` (
  `id_resi` varchar(20) NOT NULL,
  `jenis_paket` enum('Koli','Dus') NOT NULL,
  `estimasi_hari` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kargo_reguler`
--

INSERT INTO `kargo_reguler` (`id_resi`, `jenis_paket`, `estimasi_hari`) VALUES
('KRG-001', 'Dus', 3),
('KRG-002', 'Koli', 2),
('KRG-003', 'Dus', 4),
('KRG-004', 'Koli', 5),
('KRG-005', 'Dus', 1),
('KRG-006', 'Dus', 3),
('KRG-007', 'Koli', 7),
('KRG-008', 'Koli', 4),
('KRG-009', 'Dus', 2),
('KRG-010', 'Koli', 3),
('KRG-011', 'Dus', 5),
('KRG-012', 'Dus', 2),
('KRG-013', 'Koli', 6),
('KRG-014', 'Dus', 4),
('KRG-015', 'Koli', 1),
('KRG-016', 'Dus', 3),
('KRG-017', 'Koli', 2),
('KRG-018', 'Koli', 5),
('KRG-019', 'Dus', 7),
('KRG-020', 'Dus', 3),
('KRG-021', 'Koli', 4),
('KRG-022', 'Dus', 2),
('KRG-023', 'Koli', 5),
('KRG-024', 'Dus', 1),
('KRG-025', 'Koli', 6),
('KRG-026', 'Dus', 4),
('KRG-027', 'Koli', 3),
('KRG-028', 'Dus', 2),
('KRG-029', 'Koli', 5),
('KRG-030', 'Dus', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kargo`
--
ALTER TABLE `kargo`
  ADD PRIMARY KEY (`id_resi`);

--
-- Indexes for table `kargo_bahan_kimia`
--
ALTER TABLE `kargo_bahan_kimia`
  ADD PRIMARY KEY (`id_resi`);

--
-- Indexes for table `kargo_pecah_belah`
--
ALTER TABLE `kargo_pecah_belah`
  ADD PRIMARY KEY (`id_resi`);

--
-- Indexes for table `kargo_reguler`
--
ALTER TABLE `kargo_reguler`
  ADD PRIMARY KEY (`id_resi`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kargo_bahan_kimia`
--
ALTER TABLE `kargo_bahan_kimia`
  ADD CONSTRAINT `kargo_bahan_kimia_ibfk_1` FOREIGN KEY (`id_resi`) REFERENCES `kargo` (`id_resi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kargo_pecah_belah`
--
ALTER TABLE `kargo_pecah_belah`
  ADD CONSTRAINT `kargo_pecah_belah_ibfk_1` FOREIGN KEY (`id_resi`) REFERENCES `kargo` (`id_resi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kargo_reguler`
--
ALTER TABLE `kargo_reguler`
  ADD CONSTRAINT `kargo_reguler_ibfk_1` FOREIGN KEY (`id_resi`) REFERENCES `kargo` (`id_resi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
