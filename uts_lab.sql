-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2024 at 08:19 PM
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
-- Database: `uts_lab`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `assignment` varchar(255) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('unfinished','completed') DEFAULT 'unfinished'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `nim`, `name`, `assignment`, `deadline`, `status`) VALUES
(7, '00000099880', 'M.Irsyad', 'Makan', '2024-10-30', 'unfinished'),
(8, '00000104885', 'Hosea DS', 'Turu', '2024-10-23', 'unfinished');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `nim` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `assignment` varchar(255) NOT NULL,
  `deadline` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`nim`, `name`, `assignment`, `deadline`) VALUES
('00000104885', 'Hosea Dunatus Simanjuntak', 'Database', '2024-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(2, 'admin', 'admin@admin.com', '$2y$10$ReXegtjzv2yoXMbMu51QkuKB2BiOAsu7hHHZfF5zjflYKxAZ0m9Ee', '2024-10-07 16:51:29'),
(3, 'irsyad', 'irsyad@admin.com', '$2y$10$znMeCuDdOgBc.9O0gK2jVuw1qmNr0yNdRPHiNiI66gGV3jrWP31YO', '2024-10-11 13:43:41'),
(6, 'Hosea', 'Hosea@admin.com', '$2y$10$rtECC6oaNRJzWbdRWDcbdePESU2ANyV375TuMiqGLKFMd8HUw65Oa', '2024-10-21 18:19:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`nim`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
