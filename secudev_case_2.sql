-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2015 at 04:41 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `secudev_case_2`
--

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
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited_on` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user`, `message`, `created_on`, `edited_on`) VALUES
(1, 1, 'Hello, world!', '2015-09-20 12:30:18', NULL),
(2, 1, 'SPAM', '2015-09-20 13:09:56', NULL),
(3, 1, 'LOL', '2015-09-20 13:10:02', NULL),
(4, 1, 'MOAR', '2015-09-20 13:10:07', NULL),
(5, 1, 'Need like 10', '2015-09-20 13:10:13', NULL),
(6, 1, 'For testing', '2015-09-20 13:10:20', NULL),
(7, 1, 'This is gonna take forever', '2015-09-20 13:10:30', NULL),
(8, 1, 'Looks pretty though', '2015-09-20 13:10:39', NULL),
(9, 1, 'Might need to adjust the CSS further', '2015-09-20 13:10:48', NULL),
(10, 1, 'Like more borders', '2015-09-20 13:10:54', NULL),
(11, 1, '#11', '2015-09-20 13:11:01', NULL),
(12, 2, 'Hello! :)', '2015-09-20 13:22:36', '2015-09-21 03:04:28'),
(15, 2, 'Hi', '2015-09-21 04:21:39', NULL);

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
(1, 'admin', '827ccb0eea8a706c4c34a16891f84e7b', 1, 'Admin', 'Istrator', 1, '1990-01-01', 'Admin', '2015-09-16 12:12:23', 1),
(2, 'nikkie', '827ccb0eea8a706c4c34a16891f84e7b', 5, 'Nikki', 'Ebora', 2, '1990-03-30', 'Hello', '0000-00-00 00:00:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
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
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
