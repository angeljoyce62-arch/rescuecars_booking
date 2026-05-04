-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 09:25 AM
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
-- Database: `rescuecars_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `captain_id` int(11) DEFAULT NULL,
  `total_cars` int(11) DEFAULT 12,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`, `captain_id`, `total_cars`, `created_at`) VALUES
(1, 'Barangay Sumasap', NULL, 12, '2026-03-03 22:26:38'),
(2, 'Barangay Camanucan', NULL, 12, '2026-03-03 22:26:38'),
(3, 'Barangay Poblacion', NULL, 12, '2026-03-03 22:26:38'),
(4, 'Barangay Magsaysay', NULL, 12, '2026-03-03 22:26:38'),
(5, 'Barangay Salimpuno', NULL, 12, '2026-03-03 22:26:38'),
(6, 'Barangay Punta', NULL, 12, '2026-03-03 22:26:38'),
(7, 'Barangay Lutao', NULL, 12, '2026-03-03 22:26:38'),
(8, 'Barangay Map-an', NULL, 12, '2026-03-03 22:26:38'),
(9, 'Barangay Bangko', NULL, 12, '2026-03-03 22:26:38'),
(10, 'Barangay Baga', NULL, 12, '2026-03-03 22:26:38'),
(11, 'Barangay Sanjuan', NULL, 12, '2026-03-03 22:26:38'),
(12, 'Barangay Delapaz', NULL, 12, '2026-03-03 22:26:38'),
(13, 'Barangay Mohon', NULL, 12, '2026-03-03 22:26:38'),
(14, 'Barangay Vilallin', NULL, 12, '2026-03-03 22:26:38'),
(15, 'Barangay San Andres', NULL, 12, '2026-03-03 22:26:38'),
(16, 'Barangay Sanroque', NULL, 12, '2026-03-03 22:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `booking_type` varchar(50) DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed','cancelled') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approval_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `emergency_notify_sent` tinyint(1) DEFAULT 0,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_availability_log`
--

CREATE TABLE `car_availability_log` (
  `log_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `availability_date` date NOT NULL,
  `available_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`available_hours`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rescue_cars`
--

CREATE TABLE `rescue_cars` (
  `car_id` int(11) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `car_name` varchar(100) DEFAULT NULL,
  `car_number` varchar(20) DEFAULT NULL,
  `plate_number` varchar(20) DEFAULT NULL,
  `status` enum('available','in_use','maintenance') DEFAULT 'available',
  `driver_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_cars`
--

INSERT INTO `rescue_cars` (`car_id`, `barangay_id`, `car_name`, `car_number`, `plate_number`, `status`, `driver_name`, `created_at`) VALUES
(1, 1, 'Rescue Sumasap', 'RC-001', 'RCB-0001', 'available', 'Juan Dela Cruz', '2026-04-24 11:17:35'),
(2, 2, 'Rescue Camanucan', 'RC-002', 'RCB-0002', 'available', 'Maria Santos', '2026-04-24 11:17:35'),
(3, 3, 'Rescue Poblacion', 'RC-003', 'RCB-0003', 'available', 'Pedro Garcia', '2026-04-24 11:17:35'),
(4, 4, 'Rescue Magsaysay', 'RC-004', 'RCB-0004', 'available', 'Rosa Martinez', '2026-04-24 11:17:35'),
(5, 5, 'Rescue Salimpuno', 'RC-005', 'RCB-0005', 'available', 'Miguel Reyes', '2026-04-24 11:17:35'),
(6, 6, 'Rescue Punta', 'RC-006', 'RCB-0006', 'available', 'Ana Lopez', '2026-04-24 11:17:35'),
(7, 7, 'Rescue Lutao', 'RC-007', 'RCB-0007', 'available', 'Carlos Fernandez', '2026-04-24 11:17:35'),
(8, 8, 'Rescue Map-an', 'RC-008', 'RCB-0008', 'available', 'Teresa Gutierrez', '2026-04-24 11:17:35'),
(9, 9, 'Rescue Bangko', 'RC-009', 'RCB-0009', 'available', 'Francisco Morales', '2026-04-24 11:17:35'),
(10, 10, 'Rescue Baga', 'RC-010', 'RCB-0010', 'available', 'Gabriela Torres', '2026-04-24 11:17:35'),
(11, 11, 'Rescue Sanjuan', 'RC-011', 'RCB-0011', 'available', 'Diego Castro', '2026-04-24 11:17:35'),
(12, 12, 'Rescue Delapaz', 'RC-012', 'RCB-0012', 'available', 'Valentina Diaz', '2026-04-24 11:17:35'),
(13, 13, 'Rescue Mohon', 'RC-013', 'RCB-0013', 'available', 'Ricardo Flores', '2026-04-24 11:17:35'),
(14, 14, 'Rescue Vilallin', 'RC-014', 'RCB-0014', 'available', 'Camila Rojas', '2026-04-24 11:17:35'),
(15, 15, 'Rescue San Andres', 'RC-015', 'RCB-0015', 'available', 'Andres Silva', '2026-04-24 11:17:35'),
(16, 16, 'Rescue Sanroque', 'RC-016', 'RCB-0016', 'available', 'Beatriz Mendoza', '2026-04-24 11:17:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('citizen','captain') DEFAULT 'citizen',
  `barangay_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `phone`, `role`, `barangay_id`, `created_at`) VALUES
(64, 'IAN', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '', 'captain', 16, '2026-04-23 13:49:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD UNIQUE KEY `barangay_name` (`barangay_name`),
  ADD KEY `fk_barangays_captain` (`captain_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `unique_car_time` (`car_id`,`booking_date`,`start_time`,`end_time`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_car` (`car_id`),
  ADD KEY `idx_bookings_date` (`booking_date`),
  ADD KEY `idx_bookings_status` (`status`);

--
-- Indexes for table `car_availability_log`
--
ALTER TABLE `car_availability_log`
  ADD PRIMARY KEY (`log_id`),
  ADD UNIQUE KEY `unique_car_date` (`car_id`,`availability_date`);

--
-- Indexes for table `rescue_cars`
--
ALTER TABLE `rescue_cars`
  ADD PRIMARY KEY (`car_id`),
  ADD UNIQUE KEY `car_number` (`car_number`),
  ADD KEY `idx_rescue_cars_barangay` (`barangay_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `car_availability_log`
--
ALTER TABLE `car_availability_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rescue_cars`
--
ALTER TABLE `rescue_cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangays`
--
ALTER TABLE `barangays`
  ADD CONSTRAINT `fk_barangays_captain` FOREIGN KEY (`captain_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `rescue_cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `car_availability_log`
--
ALTER TABLE `car_availability_log`
  ADD CONSTRAINT `car_availability_log_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `rescue_cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rescue_cars`
--
ALTER TABLE `rescue_cars`
  ADD CONSTRAINT `rescue_cars_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
