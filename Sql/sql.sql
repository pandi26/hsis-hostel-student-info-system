-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 07:18 PM
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
-- Database: `hsis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `student_register_number` varchar(50) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `student_mobile` varchar(20) NOT NULL,
  `parent_mobile` varchar(20) NOT NULL,
  `leave_type` enum('sick','casual','annual') NOT NULL,
  `from_date` date NOT NULL,
  `from_time` time NOT NULL,
  `to_date` date NOT NULL,
  `to_time` time NOT NULL,
  `leave_reason` text NOT NULL,
  `selected_hour` varchar(50) DEFAULT NULL,
  `session` enum('morning','afternoon') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `student_register_number`, `room_number`, `student_mobile`, `parent_mobile`, `leave_type`, `from_date`, `from_time`, `to_date`, `to_time`, `leave_reason`, `selected_hour`, `session`, `created_at`, `status`) VALUES
(2, '720723207001', '428', '9345530265', '9042439252', 'casual', '2024-10-14', '09:00:00', '2024-10-14', '20:30:00', 'Outing', '', 'morning', '2024-10-14 05:19:40', 'approve'),
(3, '720723207001', '428', '7810910009', '9345530265', 'casual', '2024-10-14', '04:56:00', '2024-10-14', '05:42:00', 'holiday', '4,5,6', '', '2024-10-14 05:19:49', 'approve'),
(4, '720723207007', '427', '7845612537', '6382682872', 'casual', '2024-10-21', '09:15:00', '2024-10-21', '11:00:00', 'health check', '', 'morning', '2024-10-21 10:25:39', 'reject'),
(5, '720723207035', '428', '9345530265', '7810910009', 'casual', '2024-11-05', '05:30:00', '2024-11-05', '09:00:00', 'fever', '', 'afternoon', '2024-11-05 04:35:43', 'Pending'),
(6, '720723207035', '428', '9345530265', '7810910009', 'casual', '2024-11-05', '20:00:00', '2024-11-05', '04:00:00', 'fever', '4,6', '', '2024-11-05 04:54:33', 'approve'),
(7, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '05:00:00', '2024-11-05', '09:00:00', 'fever', '3,4,5,6,7', 'morning', '2024-11-05 06:29:29', 'approve'),
(8, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '05:00:00', '2024-11-05', '09:00:00', 'fever', '1,2,3', 'afternoon', '2024-11-05 06:32:16', 'approve'),
(9, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '', 'afternoon', '2024-11-05 06:53:18', 'approve'),
(10, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '4', 'morning', '2024-11-05 07:04:08', 'approve'),
(11, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '4', 'afternoon', '2024-11-05 07:08:38', 'approve'),
(12, '720723207035', '428', '9345530265', '7810910009', 'casual', '2024-11-05', '04:00:00', '2024-11-05', '16:00:00', 'fever', '', 'afternoon', '2024-11-05 09:05:13', 'approve'),
(13, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '3,4,5', 'afternoon', '2024-11-05 09:23:08', 'approve'),
(14, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '2,3,4', 'morning', '2024-11-05 09:29:45', 'approve'),
(15, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '19:00:00', 'fever', '', 'morning', '2024-11-05 09:39:47', 'approve'),
(16, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '3,4,5,6,7', 'morning', '2024-11-05 09:42:12', 'approve'),
(17, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '', 'morning', '2024-11-05 09:53:22', 'approve'),
(18, '720723207035', '428', '9345530265', '7810910009', 'casual', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '3', 'afternoon', '2024-11-05 10:05:28', 'approve'),
(19, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '3,4', 'afternoon', '2024-11-05 10:08:06', 'approve'),
(20, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '3,4', 'afternoon', '2024-11-05 10:19:03', 'approve'),
(21, '720723207035', '428', '9345530265', '7810910009', 'sick', '2024-11-05', '08:00:00', '2024-11-05', '16:00:00', 'fever', '', 'morning', '2024-11-05 10:23:49', 'approve'),
(0, '720723207035', '428', '9345530265', '7810910009', 'casual', '2024-11-09', '08:00:00', '2024-11-09', '12:00:00', 'fever', '4,5,6,7', '', '2024-11-09 17:30:50', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_register_number` varchar(50) NOT NULL,
  `student_name` varchar(15) NOT NULL,
  `student_mobile` varchar(10) NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `parent_mobile` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `academic_year` varchar(255) NOT NULL,
  `engineering_dept` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `student_register_number`, `student_name`, `student_mobile`, `parent_name`, `parent_mobile`, `address`, `room_number`, `created_at`, `academic_year`, `engineering_dept`) VALUES
(2, 'kumar@gmail.com', '789456', '720723207001', 'pothees', '9345530262', 'pandian', '9345530264', 'sivakasi', '427', '2024-10-14 05:13:00', '2025', 'mba'),
(3, '720723207007@hicet.a', '$2y$10$V0a7MEGIM31c/', '720723207007', 'dinesh kumar', '7845612537', 'hari', '6382682872', 'attur,salem', '427', '2024-10-21 10:12:31', '2024', 'Computer Engineering'),
(6, '720723207036@hicet.a', '$2y$10$BkcHXnt3GGwCd', '720723207036', 'potheswaran', '9345530262', 'pandian', '7845961235', 'sivakasi', '425', '2024-11-03 09:50:41', '2025', 'mba'),
(8, '720723207035@hicet.a', '$2y$10$Dg8J4JfRq2HAS33bAr.CMe0F3TRwL8vltyfNH1qvU0zgpX6sYl6Se', '720723207035', 'pandi', '9345530265', 'seetha', '7810910009', 'sivakasi', '428', '2024-11-09 17:29:56', '2025', 'Chemical Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `warden`
--

CREATE TABLE `warden` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warden`
--

INSERT INTO `warden` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'warden', '$2y$10$EqkaBEDoWUw2tBWik7gz9ev.jN5BtXgS5vsKE0iCWpIcFAPWuL2aq', '2024-10-14 10:51:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_register_number` (`student_register_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
