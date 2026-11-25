-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2026 at 08:06 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `start_date`, `end_date`, `total_price`, `status`, `created_at`) VALUES
(1, 1, 2, '2027-02-23', '2027-03-29', 1697.92, 'cancelled', '2026-11-25 13:43:32'),
(2, 2, 2, '2028-09-23', '2028-09-28', 250.00, 'approved', '2026-11-25 14:11:27'),
(3, 2, 1, '2027-03-31', '2027-04-01', 40.00, 'approved', '2026-11-25 14:13:00'),
(4, 1, 3, '2029-09-23', '2029-10-02', 495.00, 'rejected', '2026-11-25 14:14:43'),
(5, 1, 3, '2030-05-09', '2030-05-15', 330.00, 'rejected', '2026-11-25 14:19:05'),
(6, 1, 5, '2030-02-22', '2030-03-01', 245.00, 'approved', '2026-11-25 14:36:53'),
(7, 1, 3, '2030-05-29', '2039-06-10', 181445.00, 'rejected', '2026-11-25 17:24:23'),
(8, 1, 1, '2030-03-24', '2030-03-26', 80.00, 'pending', '2026-11-25 17:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `color`, `price_per_day`, `image`, `description`, `status`, `created_at`) VALUES
(1, 'Audi', 'A6', 2016, 'White', 40.00, 'assets/images/a941f5dabc1371f77d46dce134bb9718.jpg', '', 'available', '2026-11-25 13:39:39'),
(2, 'BMW', 'M5', 2020, 'Blue', 50.00, NULL, '', 'available', '2026-11-25 13:39:57'),
(3, 'Mercedes', 'S500', 2021, 'Silver', 55.00, NULL, '', 'available', '2026-11-25 13:40:35'),
(5, 'Audi', 'S5', 2012, 'Silver', 35.00, 'assets/images/5f9dbe52c4f638d460170da917de7a54.jpg', '', 'available', '2026-11-25 14:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'enes', 'eness', 'test@test.com', '$2y$10$9ujw.Q1zy6eBRwmJJ7k02edwGBFE4STzb1Eea9hywV6TXxpsLC9xO', 'admin', '2026-11-25 13:32:45'),
(2, 'testi', 'testi2', 'test@gmail.com', '$2y$10$ddf0VZWZ65PrQFYQADAreeG15ZovpSJBEn.L.e9yVGYll12GtK1SC', 'user', '2026-11-25 13:33:31'),
(3, 'nesi', 'nesi', 'nesi@nesi.com', '$2y$10$f5b9fS7o2VtRukb5.5Y00OFOIul5ysPoNBf55vycYi3nng1cz9ndS', 'user', '2026-11-25 18:47:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
