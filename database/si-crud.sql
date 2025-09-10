-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 05:06 AM
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
-- Database: `si-crud`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(14, 'asdasdjashkj', 'wasdasd', 9.00, '2025-09-05 17:04:31', '2025-09-05 17:04:31'),
(16, 'Tornillossss', 'ASASA', 111.00, '2025-09-05 17:10:11', '2025-09-05 17:10:11'),
(17, 'Tsinelas', 'dsad', 10000.00, '2025-09-05 17:10:26', '2025-09-05 17:10:26'),
(18, 'Tornillossss', 'jkjjh', 9.00, '2025-09-05 17:10:36', '2025-09-05 17:10:36'),
(19, 'Tornillo', 'sadsad', 1000.00, '2025-09-05 17:10:48', '2025-09-05 17:10:48'),
(24, 'Von', 'j', 999.00, '2025-09-08 07:54:17', '2025-09-08 07:54:17'),
(25, 'Tsinelas', 'xsdsa', 999.00, '2025-09-08 07:55:31', '2025-09-08 07:55:31'),
(26, 'afafa', 'dasdas', 23123.00, '2025-09-09 02:09:04', '2025-09-09 02:09:04'),
(27, 'sdasdas', 'dsadas', 312321.00, '2025-09-09 02:09:19', '2025-09-09 02:09:19'),
(28, 'dasdasd', 'dasdsad', 23123.00, '2025-09-09 02:12:25', '2025-09-09 02:12:25'),
(29, 'sdasd', 'asdasd', 12132.00, '2025-09-09 02:12:35', '2025-09-09 02:12:35'),
(30, 'asdasdjashkj', 'dasdsadsdasdas', 9999.00, '2025-09-09 02:46:26', '2025-09-09 02:46:26'),
(31, 'dasdas', 'asdasdasdsaqweqw', 23231.00, '2025-09-09 02:46:48', '2025-09-09 02:46:48'),
(32, 'sadasdsa', 'sdsadassadasd', 1000.00, '2025-09-09 02:46:59', '2025-09-09 02:46:59'),
(33, 'asdasdsa', 'asdasdasdsadsa', 312321.00, '2025-09-09 02:48:18', '2025-09-09 02:48:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
