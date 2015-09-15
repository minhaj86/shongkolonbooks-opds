-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 07, 2015 at 07:54 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opencart`
--

-- --------------------------------------------------------

--
-- Table structure for table `opds_catalogs`
--
-- Creation: Sep 07, 2015 at 12:57 PM
--

CREATE TABLE IF NOT EXISTS `opds_catalogs` (
  `id` int(10) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `description` varchar(25) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opds_catalogs`
--

INSERT IGNORE INTO `opds_catalogs` (`id`, `type`, `name`, `description`) VALUES
(1, 'all', 'All Books', 'All'),
(2, 'writer', 'By Writer', 'Writer'),
(3, 'publisher', 'By Publisher', 'Publisher'),
(4, 'bestseller', 'Best Seller', 'Best Seller'),
(5, 'featured', 'Featured', 'Featured'),
(6, 'latest', 'Latest', 'Latest');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `opds_catalogs`
--
ALTER TABLE `opds_catalogs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `opds_catalogs`
--
ALTER TABLE `opds_catalogs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
