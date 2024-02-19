-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 19, 2024 at 11:28 AM
-- Server version: 8.0.36-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sparingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `maintb`
--

CREATE TABLE `maintb` (
  `id` int NOT NULL,
  `unixtime` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `cod` float NOT NULL,
  `tss` float NOT NULL,
  `nh3n` float NOT NULL,
  `ph` float NOT NULL,
  `debit` float NOT NULL,
  `debit2` float NOT NULL,
  `feedback` varchar(100) NOT NULL,
  `stat_conn` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rawtb`
--

CREATE TABLE `rawtb` (
  `id` int NOT NULL,
  `ids` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `ph` float NOT NULL,
  `cod` float NOT NULL,
  `tss` float NOT NULL,
  `nh3n` float NOT NULL,
  `debit` float NOT NULL,
  `debit2` float NOT NULL,
  `rs_stat` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int NOT NULL,
  `time` datetime NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `maintb`
--
ALTER TABLE `maintb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rawtb`
--
ALTER TABLE `rawtb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `maintb`
--
ALTER TABLE `maintb`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rawtb`
--
ALTER TABLE `rawtb`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
