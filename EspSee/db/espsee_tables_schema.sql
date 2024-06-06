-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2024 at 07:51 PM
-- Server version: 10.6.17-MariaDB-cll-lve
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alojttct_espsee`
--

-- --------------------------------------------------------

--
-- Table structure for table `espsee_cameras`
--

CREATE TABLE `espsee_cameras` (
  `id` bigint(20) NOT NULL,
  `wp_user_id` bigint(20) NOT NULL DEFAULT 0,
  `macAddressWiFi` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `macAddressEfuse` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `remoteAddr` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cameraProtocol` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'http://',
  `cameraPort` int(11) NOT NULL DEFAULT 80,
  `camera_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `created_time_unix` int(11) NOT NULL DEFAULT 0,
  `updated_time_unix` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `espsee_tokens`
--

CREATE TABLE `espsee_tokens` (
  `id` bigint(20) NOT NULL,
  `wp_user_id` bigint(20) NOT NULL,
  `user_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `espsee_cameras`
--
ALTER TABLE `espsee_cameras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `espsee_tokens`
--
ALTER TABLE `espsee_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_token` (`user_token`),
  ADD KEY `wp_user_id` (`wp_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `espsee_cameras`
--
ALTER TABLE `espsee_cameras`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `espsee_tokens`
--
ALTER TABLE `espsee_tokens`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
