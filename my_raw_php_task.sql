-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 23, 2025 at 07:07 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_raw_php_task`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`, `created_at`) VALUES
(3, '2025_10_21_201045_create_products_table', 1, '2025-10-21 20:11:34'),
(5, '2025_10_21_201159_create_orders_table', 2, '2025-10-21 20:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_name`, `product_id`, `qty`, `total_price`, `created_at`) VALUES
(3, 'ORD-1001', 'Asif Mostofa', 3, 5, 1250.00, '2025-10-22 07:07:59'),
(18, 'ORD-1002', 'Asif Mostofa', 2, 2, 15000.00, '2025-10-22 19:02:39'),
(19, 'ORD-1003', 'Asif Sazid', 1, 1, 38000.00, '2025-10-22 19:03:05'),
(20, 'ORD-1004', 'Asif Sazid', 18, 2, 100000.00, '2025-10-22 19:58:08'),
(21, 'ORD-1005', 'Asif Sazid', 18, 1, 50000.00, '2025-10-22 19:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` decimal(10,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `created_at`) VALUES
(1, 'HP Laptop', 38000.00, 9, '2025-10-21 21:29:28'),
(2, 'Redmi Note 15', 45000.00, 13, '2025-10-21 21:33:39'),
(3, 'Butterfly Machine', 12000.00, 15, '2025-10-22 06:42:13'),
(4, 'Realme 15', 35000.00, 12, '2025-10-22 06:49:14'),
(5, 'Mum', 15.00, 100, '2025-10-22 19:22:02'),
(6, 'Smart Watch', 1700.00, 50, '2025-10-22 19:27:27'),
(7, 'Smart Watch', 1300.00, 130, '2025-10-22 19:33:23'),
(8, 'Xiaomi 17 Pro', 130000.00, 2, '2025-10-22 19:34:28'),
(9, 'Iphone 17', 120000.00, 5, '2025-10-22 19:37:20'),
(10, 'Fan', 2900.00, 100, '2025-10-22 19:42:51'),
(11, 'Light', 570.00, 1000, '2025-10-22 19:44:16'),
(12, 'Light', 400.00, 1200, '2025-10-22 19:44:56'),
(13, 'Light', 600.00, 800, '2025-10-22 19:45:14'),
(14, 'Mum', 30.00, 120, '2025-10-22 19:46:09'),
(15, 'Mum', 40.00, 80, '2025-10-22 19:49:51'),
(16, 'Light', 100.00, 1000, '2025-10-22 19:50:23'),
(17, 'Smart Watch', 1200.00, 50, '2025-10-22 19:50:40'),
(18, 'Redmi Note 15 Pro', 50000.00, 17, '2025-10-22 19:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`) VALUES
(1, 'Asif Mostofa', 'asif@example.com'),
(2, 'Test User', 'test@example.com'),
(5, 'New User', 'newuser@example.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_products` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
