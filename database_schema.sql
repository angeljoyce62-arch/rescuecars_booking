-- Rescue Cars Booking System - Complete Database Schema
-- Run: mysql -u root -p rescuecars_booking < database_schema.sql

DROP DATABASE IF EXISTS `rescuecars_booking`;
CREATE DATABASE IF NOT EXISTS `rescuecars_booking` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rescuecars_booking`;

-- Table: barangays
CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`barangay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: users
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(64) NOT NULL,
  `role` enum('admin','captain','resident') NOT NULL DEFAULT 'resident',
  `barangay_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `barangay_id` (`barangay_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: rescue_cars
CREATE TABLE `rescue_cars` (
  `car_id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(20) NOT NULL,
  `status` enum('available','booked','maintenance') NOT NULL DEFAULT 'available',
  `barangay_id` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`car_id`),
  KEY `barangay_id` (`barangay_id`),
  CONSTRAINT `rescue_cars_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: bookings
CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` datetime NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `rescue_cars` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: car_availability_log
CREATE TABLE `car_availability_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL,
  `status` enum('available','unavailable') NOT NULL,
  `log_timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `car_availability_log_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `rescue_cars` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `barangays` (`name`) VALUES 
('Barangay 1'), ('Barangay 2'), ('Barangay 3');

-- Sample ADMIN user (password: admin123 → SHA256)
INSERT INTO `users` (`username`, `password`, `role`, `barangay_id`, `full_name`) VALUES 
('admin', SHA2('admin123', 256), 'admin', 1, 'System Administrator');

-- Sample CAPTAIN user
INSERT INTO `users` (`username`, `password`, `role`, `barangay_id`, `full_name`) VALUES 
('captain1', SHA2('captain123', 256), 'captain', 1, 'Captain Barangay 1');

-- Sample RESIDENT user
INSERT INTO `users` (`username`, `password`, `role`, `barangay_id`, `full_name`) VALUES 
('resident1', SHA2('resident123', 256), 'resident', 1, 'Resident User');

-- Sample rescue car
INSERT INTO `rescue_cars` (`plate_number`, `status`, `barangay_id`) VALUES 
('ABC 123', 'available', 1);

SELECT 'Database setup complete! Test login: admin/admin123' as status;
