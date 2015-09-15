-- phpMyAdmin SQL Dump
-- version 4.4.14.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 11, 2015 at 04:53 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opencart`
--
CREATE DATABASE IF NOT EXISTS `opencart` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `opencart`;

-- --------------------------------------------------------

--
-- Table structure for table `opds_authors`
--

CREATE TABLE IF NOT EXISTS `opds_authors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `uri` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opds_authors`
--

INSERT IGNORE INTO `opds_authors` (`id`, `name`, `uri`, `description`) VALUES
(0, 'john', '/opencart/upload/author', 'gjhgjhgjh'),
(1, 'smith', '/opencar/upload/author', 'dfdfdfdfd'),
(2, 'charles', '/opencar/upload/author', 'ddgdgdgdgdgd');

-- --------------------------------------------------------

--
-- Table structure for table `opds_books`
--

CREATE TABLE IF NOT EXISTS `opds_books` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `isbn` varchar(200) NOT NULL,
  `publish_ts` date NOT NULL,
  `update_ts` date NOT NULL,
  `language` varchar(200) NOT NULL,
  `publisher` varchar(200) NOT NULL,
  `issue_ts` date NOT NULL,
  `summary` varchar(200) NOT NULL,
  `no_of_pages` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `alternate_link` varchar(200) NOT NULL,
  `buy_link` varchar(200) NOT NULL,
  `main_image` varchar(200) NOT NULL,
  `thumb_image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opds_books`
--

INSERT IGNORE INTO `opds_books` (`id`, `title`, `isbn`, `publish_ts`, `update_ts`, `language`, `publisher`, `issue_ts`, `summary`, `no_of_pages`, `size`, `alternate_link`, `buy_link`, `main_image`, `thumb_image`) VALUES
(1, 'don quixote', '12345', '2015-09-08', '2015-09-08', 'en', 'abc pub', '0000-00-00', 'adventure story of don quixote', 300, 3000000, '/opencar/upload/author', '/opencar/upload/author', '/opencar/upload/author', '/opencar/upload/author'),
(2, 'crugo', '45678', '2015-09-08', '2015-09-08', 'bn', 'onnoprokash', '0000-00-00', 'fgfhfgdgdfe', 100, 1000000, '/opencar/upload/author', '/opencar/upload/author', '/opencar/upload/author', '/opencar/upload/author'),
(3, 'project nebula', '4567833', '2015-09-08', '2015-09-08', 'bn', 'onnoprokash', '0000-00-00', 'llllokjko', 100, 1000000, '/opencar/upload/author', '/opencar/upload/author', '/opencar/upload/author', '/opencar/upload/author');

-- --------------------------------------------------------

--
-- Table structure for table `opds_book_to_author`
--

CREATE TABLE IF NOT EXISTS `opds_book_to_author` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opds_book_to_author`
--

INSERT IGNORE INTO `opds_book_to_author` (`id`, `book_id`, `author_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `opds_catalogs`
--

CREATE TABLE IF NOT EXISTS `opds_catalogs` (
  `id` int(11) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `opds_users`
--

CREATE TABLE IF NOT EXISTS `opds_users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `salt` varchar(150) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `opds_users`
--

INSERT IGNORE INTO `opds_users` (`id`, `username`, `salt`, `password`) VALUES
(1441945793, 'user1', 'bcb04b7e103a0cd8b54f63051cef085c55abe029edebae5e1d4c7e2ffb2a00a3', '+wufT1GhRsWwQ1rTQAnICHdxOvZ5m3heDtl2rhIS4OY=');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `opds_books`
--
ALTER TABLE `opds_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opds_book_to_author`
--
ALTER TABLE `opds_book_to_author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opds_catalogs`
--
ALTER TABLE `opds_catalogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opds_users`
--
ALTER TABLE `opds_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `opds_catalogs`
--
ALTER TABLE `opds_catalogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
