-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 04:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `five_outbound`
--

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', '(555) 123-4567', 'I need a new website for my business. Looking for modern design with e-commerce functionality.', 'read', '2025-08-04 08:50:27', '2025-08-04 08:55:06'),
(4, 'Diwash Panta', 'deepapanta124@gmail.com', '(986) 353-0600', 'I need a website', 'new', '2025-08-04 08:55:56', '2025-08-04 08:55:56'),
(5, 'mizuy', 'diwash@gmail.com', '', 'I need a website', 'new', '2025-08-23 13:54:44', '2025-08-23 13:54:44'),
(6, 'mizuy', 'diwash@gmail.com', '', 'I need a website', 'new', '2025-08-23 13:56:04', '2025-08-23 13:56:04'),
(7, 'mizu', 'mizu@gmail.com', '', 'kashflksa', 'new', '2025-08-23 13:57:22', '2025-08-23 13:57:22'),
(8, 'fgsfs', 'sdfsdf@gmail.com', '', 'asfasf', 'new', '2025-08-23 14:24:11', '2025-08-23 14:24:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
