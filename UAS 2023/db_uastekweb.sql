-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 07:07 PM
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
-- Database: `db_uastekweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_log`
--

CREATE TABLE `detail_log` (
  `id_detail` int(11) NOT NULL,
  `no_resi` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `kota` varchar(255) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_log`
--

INSERT INTO `detail_log` (`id_detail`, `no_resi`, `tanggal`, `kota`, `keterangan`) VALUES
(9, 'RS-003', '2024-12-15', 'Surabaya', 'mas ivan');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_resi`
--

CREATE TABLE `transaksi_resi` (
  `no_resi` varchar(255) NOT NULL,
  `tanggal_resi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_resi`
--

INSERT INTO `transaksi_resi` (`no_resi`, `tanggal_resi`) VALUES
('RS-003', '2024-12-15'),
('RS-005', '2024-12-05');

-- --------------------------------------------------------

--
-- Table structure for table `user_admin`
--

CREATE TABLE `user_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_admin`
--

INSERT INTO `user_admin` (`id_admin`, `username`, `password`, `nama_admin`, `status_aktif`) VALUES
(1, 'tes', 'tes', 'Richie', 0),
(2, 'tes', 'tes', 'tes', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_log`
--
ALTER TABLE `detail_log`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `no_resi` (`no_resi`);

--
-- Indexes for table `transaksi_resi`
--
ALTER TABLE `transaksi_resi`
  ADD PRIMARY KEY (`no_resi`);

--
-- Indexes for table `user_admin`
--
ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_log`
--
ALTER TABLE `detail_log`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_admin`
--
ALTER TABLE `user_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_log`
--
ALTER TABLE `detail_log`
  ADD CONSTRAINT `detail_log_ibfk_1` FOREIGN KEY (`no_resi`) REFERENCES `transaksi_resi` (`no_resi`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
