-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2025 at 03:17 PM
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
-- Database: `r&r_dbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cuslist`
--

CREATE TABLE `cuslist` (
  `id` int(11) NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `addr` varchar(255) NOT NULL,
  `cit` varchar(255) NOT NULL,
  `civ` varchar(255) NOT NULL,
  `gen` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuslist`
--

INSERT INTO `cuslist` (`id`, `mobile`, `lname`, `fname`, `mname`, `addr`, `cit`, `civ`, `gen`, `filename`) VALUES
(1, '09555172281', 'Villaflores', 'Jiro', 'Tritin', 'Brgy. Datagon, Pamplona, Negros Oriental', 'Filipino', 'Single', 'Male', 'WIN_20250503_21_05_33_Pro.jpg'),
(2, '09438796045', 'Villaflores', 'Lezalde', 'Ib-ib', 'Brgy. Datagon, Pamplona, Negros Oriental', 'Filipino', 'Married', 'Male', 'WIN_20250503_21_05_54_Pro.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cus_pay`
--

CREATE TABLE `cus_pay` (
  `cid` int(11) NOT NULL,
  `cname` varchar(255) NOT NULL,
  `bill` decimal(10,2) NOT NULL,
  `pay` decimal(10,2) NOT NULL,
  `mchange` decimal(10,2) NOT NULL,
  `bdate` datetime NOT NULL,
  `VAT` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product`, `price`) VALUES
(1, 'COCO LUMBER (1 * 2  * 8)', 30.00),
(2, 'COCO LUMBER (1 * 2  * 10)', 38.00),
(3, 'COCO LUMBER (1 * 2  * 12)', 46.00),
(4, 'COCO LUMBER (2 * 2  * 8)', 61.00),
(5, 'COCO LUMBER (2 * 2  * 10)', 76.00),
(6, 'COCO LUMBER (2 * 2  * 12)', 92.00),
(7, 'COCO LUMBER (2 * 3  * 8)', 92.00),
(8, 'COCO LUMBER (2 * 3  * 10)', 115.00),
(9, 'COCO LUMBER (2 * 3 * 12)', 138.00),
(10, 'COCO LUMBER (2 * 4  * 8)', 122.00),
(11, 'COCO LUMBER (2 * 4  * 10)', 153.00),
(12, 'COCO LUMBER (2 * 4  * 12)', 184.00),
(13, 'COCO LUMBER (4 * 4  * 8)', 245.00),
(14, 'COCO LUMBER (4 * 4  * 10)', 306.00),
(15, 'COCO LUMBER (4 * 4  * 12)', 368.00),
(16, 'SAND (1 SACK)', 44.00),
(17, 'GRAVEL (1 SACK) ', 52.00),
(18, 'HOLLOW BLOCK', 13.00),
(19, 'CEMENT (1 SACK)', 181.00),
(20, 'COMMON NAILS ( 1\" )', 75.00),
(21, 'COMMON NAILS ( 1 1/2\" ) ', 70.00),
(22, 'COMMON NAILS ( 2\" )', 65.00),
(23, 'COMMON NAILS ( 2 1/2\" )', 65.00),
(24, 'COMMON NAILS ( 3\" )', 62.00),
(25, 'COMMON NAILS ( 4\" )', 60.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_solds`
--

CREATE TABLE `product_solds` (
  `s_id` int(11) NOT NULL,
  `s_item` varchar(255) NOT NULL,
  `s_price` decimal(10,2) NOT NULL,
  `s_qty` int(11) NOT NULL,
  `s_total` decimal(10,2) NOT NULL,
  `s_cus` int(11) NOT NULL,
  `s_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_solds`
--

INSERT INTO `product_solds` (`s_id`, `s_item`, `s_price`, `s_qty`, `s_total`, `s_cus`, `s_date`) VALUES
(1, 'COCO LUMBER (1 * 2  * 12)', 46.00, 1, 46.00, 1, '2025-06-04 00:00:00'),
(2, 'COCO LUMBER (2 * 4  * 10)', 153.00, 3, 459.00, 1, '2025-06-04 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cuslist`
--
ALTER TABLE `cuslist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cus_pay`
--
ALTER TABLE `cus_pay`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_solds`
--
ALTER TABLE `product_solds`
  ADD PRIMARY KEY (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cuslist`
--
ALTER TABLE `cuslist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cus_pay`
--
ALTER TABLE `cus_pay`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `product_solds`
--
ALTER TABLE `product_solds`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
