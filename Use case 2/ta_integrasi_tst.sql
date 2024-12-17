-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2024 at 01:27 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ta_integrasi_tst`
--

-- --------------------------------------------------------

--
-- Table structure for table `geolocation`
--

CREATE TABLE `geolocation` (
  `id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `geolocation`
--

INSERT INTO `geolocation` (`id`, `city_name`, `latitude`, `longitude`) VALUES
(1, 'Jakarta', -6.20876300, 106.84559900),
(2, 'Surabaya', -7.25747200, 112.75208800),
(3, 'Bandung', -6.91746400, 107.61912300),
(4, 'Bogor', -6.59503800, 106.81663500),
(5, 'Mojokerto', -7.47261500, 112.43103500),
(6, 'Yogyakarta', -7.79706800, 110.37052900),
(7, 'Solo', -7.56661800, 110.81662300),
(8, 'Tanggerang', -6.17010200, 106.63188900);

-- --------------------------------------------------------

--
-- Table structure for table `weather`
--

CREATE TABLE `weather` (
  `id` int(11) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(5,2) NOT NULL,
  `air_quality_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `weather`
--

INSERT INTO `weather` (`id`, `city_name`, `temperature`, `humidity`, `air_quality_index`) VALUES
(1, 'Jakarta', 32.50, 70.00, 75),
(2, 'Surabaya', 34.00, 65.00, 80),
(3, 'Bandung', 25.00, 75.00, 50),
(4, 'Bogor', 27.00, 85.00, 60),
(5, 'Mojokerto', 33.00, 68.00, 90),
(6, 'Yogyakarta', 29.50, 72.00, 55),
(7, 'Solo', 30.00, 70.00, 65),
(8, 'Tanggerang', 30.00, 75.00, 70);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `geolocation`
--
ALTER TABLE `geolocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather`
--
ALTER TABLE `weather`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `geolocation`
--
ALTER TABLE `geolocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `weather`
--
ALTER TABLE `weather`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
