-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2023 at 11:08 PM
-- Server version: 8.0.32-0ubuntu0.22.04.2
-- PHP Version: 8.1.2-1ubuntu2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `biblioteca_carti`
--

CREATE TABLE `biblioteca_carti` (
  `id` varchar(20) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `autor` tinytext CHARACTER SET utf8mb4  NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titlu` tinytext NOT NULL,
  `descriere` mediumtext NOT NULL,
  `imagine` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `biblioteca_categorii`
--

CREATE TABLE `biblioteca_categorii` (
  `id` varchar(20) NOT NULL,
  `nume` tinytext NOT NULL,
  `descriere` text NOT NULL,
  `icon` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `biblioteca_imprumuturi`
--

CREATE TABLE `biblioteca_imprumuturi` (
  `id` varchar(20) NOT NULL,
  `user` tinytext NOT NULL,
  `carte` tinytext NOT NULL,
  `inceput` date NOT NULL,
  `durata` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `biblioteca_salvate`
--

CREATE TABLE `biblioteca_salvate` (
  `id_user` varchar(32) NOT NULL,
  `id_carte` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `biblioteca_useri`
--

CREATE TABLE `biblioteca_useri` (
  `id` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `parola` tinytext NOT NULL,
  `tip` tinytext NOT NULL,
  `nume` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biblioteca_carti`
--
ALTER TABLE `biblioteca_carti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `biblioteca_carti` ADD FULLTEXT KEY `autor` (`autor`,`titlu`,`descriere`);

--
-- Indexes for table `biblioteca_categorii`
--
ALTER TABLE `biblioteca_categorii`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `biblioteca_imprumuturi`
--
ALTER TABLE `biblioteca_imprumuturi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `biblioteca_salvate`
--
ALTER TABLE `biblioteca_salvate`
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_carte` (`id_carte`);

--
-- Indexes for table `biblioteca_useri`
--
ALTER TABLE `biblioteca_useri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `email` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
