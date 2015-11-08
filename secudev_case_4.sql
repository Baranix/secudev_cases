-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2015 at 12:43 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `secudev_case_4`
--

-- --------------------------------------------------------

--
-- Table structure for table `backup_message`
--

CREATE TABLE IF NOT EXISTS `backup_message` (
  `id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `backup_message`
--

INSERT INTO `backup_message` (`id`, `created_on`) VALUES
(1, '2015-09-30 07:53:42'),
(2, '2015-09-30 08:47:33'),
(3, '2015-10-12 03:23:48'),
(4, '2015-10-14 03:46:17'),
(5, '2015-10-14 03:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE IF NOT EXISTS `badge` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE IF NOT EXISTS `gender` (
  `id` tinyint(1) NOT NULL,
  `gender` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `gender`) VALUES
(1, 'M'),
(2, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `hasbadge`
--

CREATE TABLE IF NOT EXISTS `hasbadge` (
  `id` int(11) NOT NULL,
  `badge_id` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `desc` text
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `name`, `image`, `price`, `desc`) VALUES
(1, 'Winged Jacket', 'img/items/jacket.jpg', '1000.00', 'Winged Jacket'),
(2, 'Lace Dress', 'img/items/lace-dress.jpg', '850.55', 'Lace Dress'),
(3, 'Purple Loose Top', 'img/items/loose-top.jpg', '350.75', 'Purple Loose Top'),
(4, 'Blue Polo', 'img/items/polo.PNG', '250.00', 'Blue Polo');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user`, `message`, `created_on`, `edited_on`) VALUES
(1, 1, 'Hello world!', '2015-09-30 07:53:38', NULL),
(2, 1, 'Rawr', '2015-09-30 08:47:27', NULL),
(3, 1, 'Rawrzers', '2015-10-07 06:40:40', NULL),
(4, 2, 'Hello, everyone!', '2015-10-12 00:30:56', NULL),
(7, 2, '', '2015-10-14 03:41:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salutation`
--

CREATE TABLE IF NOT EXISTS `salutation` (
  `id` int(11) NOT NULL,
  `salutation` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salutation`
--

INSERT INTO `salutation` (`id`, `salutation`) VALUES
(1, 'Mr.'),
(2, 'Sir'),
(3, 'Señor'),
(4, 'Count'),
(5, 'Miss'),
(6, 'Ms.'),
(7, 'Mrs.'),
(8, 'Madame'),
(9, 'Majesty'),
(10, 'Señora');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salutation` tinyint(10) NOT NULL DEFAULT '1',
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '1',
  `birthdate` date NOT NULL,
  `about` varchar(255) NOT NULL,
  `date_joined` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_superuser` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `salutation`, `first_name`, `last_name`, `gender`, `birthdate`, `about`, `date_joined`, `is_superuser`) VALUES
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 5, 'Admin', 'Istrator', 1, '1990-01-01', 'Admin', '2015-09-16 12:12:23', 1),
(2, 'nikkie', '827ccb0eea8a706c4c34a16891f84e7b', 5, 'Nikki', 'Ebora', 2, '1990-03-30', 'Describe yourself.', '2015-10-07 11:55:33', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backup_message`
--
ALTER TABLE `backup_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hasbadge`
--
ALTER TABLE `hasbadge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salutation`
--
ALTER TABLE `salutation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `backup_message`
--
ALTER TABLE `backup_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hasbadge`
--
ALTER TABLE `hasbadge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
