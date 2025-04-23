-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 02:26 PM
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
-- Database: `walanauntat_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` enum('Filipino','English','Science','Mathematics','History') NOT NULL,
  `status` enum('available','borrowed') DEFAULT 'available',
  `qr_code` text DEFAULT NULL,
  `shelf_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `title`, `author`, `category`, `status`, `qr_code`, `shelf_id`) VALUES
(1, 'sanaysanay', 'akoo', 'Science', 'borrowed', 'qrcodes/67d63b8f12539.png', NULL),
(2, 'elfilibusterismo', 'rizal', 'Filipino', 'available', 'qrcodes/67d63bb7f27a0.png', NULL),
(3, 'plants', 'basta amo na', 'Science', 'available', 'qrcodes/67d63df06edb4.png', NULL),
(5, 'manguuu', 'akoooo', 'Mathematics', 'borrowed', 'qrcodes/67d643d66262d.png', NULL),
(6, 'basta libro', 'hahahahhahh', 'History', 'borrowed', 'qrcodes/67d64ce3adb21.png', NULL),
(7, 'asasa', 'asasasasasa', 'History', 'available', 'qrcodes/67d67a63d88f4.png', NULL),
(8, 'kenkoyyy', 'williams', 'English', 'available', 'qrcodes/67d685c76d2b1.png', NULL),
(9, 'asasas', 'saasas', 'Mathematics', 'available', 'qrcodes/67d69ffda3bdc.png', NULL),
(10, 'asasas', 'saasas', 'Mathematics', 'borrowed', 'qrcodes/67d6a001b2d31.png', NULL),
(11, 'Ang kamanguan ko', 'James Arthur', 'English', 'available', 'qrcodes/67d7af2d3389b.png', NULL),
(14, 'Juan tamad', 'Juan Tamad', 'Filipino', 'available', 'qrcodes/67ee9cad47e92.png', NULL),
(16, 'gfvfyd', 'ydresss', 'Mathematics', 'available', 'qrcodes/67f4f2bc99263.png', NULL),
(17, 'Ang manananggal', 'Si Batosay', 'Filipino', 'available', 'qrcodes/6804fb8b74276.png', NULL),
(18, 'yhjh', 'ddydhg', 'Filipino', 'available', 'qrcodes/6805edc42c3c9.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `book_id` varchar(50) NOT NULL,
  `borrow_date` datetime DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL,
  `status` enum('borrowed','returned') DEFAULT 'borrowed',
  `due_date` date NOT NULL DEFAULT (curdate() + interval 7 day),
  `borrow_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`id`, `student_id`, `book_id`, `borrow_date`, `return_date`, `status`, `due_date`, `borrow_id`, `firstname`, `lastname`) VALUES
(26, '2232323', '8', '2025-04-08 00:00:00', '2025-04-08 00:00:00', 'returned', '2025-04-15', NULL, 'Ella', 'Pedojan'),
(28, '0921001', '2', '2025-04-08 00:00:00', '2025-04-08 00:00:00', 'returned', '2025-04-15', NULL, 'JOSE', 'CHAN'),
(35, '36136179129', '13', '2025-04-08 00:00:00', '2025-04-08 00:00:00', 'returned', '2025-04-15', NULL, 'Alita', 'Soriano'),
(36, '55346', '16', '2025-04-08 00:00:00', '2025-04-08 00:00:00', 'returned', '2025-04-15', NULL, 'hul', 'hal'),
(40, '000120982', '17', '2025-04-21 12:36:51', '2025-04-21 23:21:29', 'returned', '2025-04-28', NULL, NULL, NULL),
(41, '5778577', '18', '2025-04-21 15:12:20', '2025-04-21 23:07:22', 'returned', '2025-04-28', NULL, NULL, NULL),
(42, '000120982', '17', '2025-04-21 15:14:11', '2025-04-21 23:21:29', 'returned', '2025-04-28', NULL, NULL, NULL),
(43, '5778577', '18', '2025-04-21 15:17:24', '2025-04-21 23:07:22', 'returned', '2025-04-28', NULL, NULL, NULL),
(44, '5778577', '18', '2025-04-21 15:18:56', '2025-04-21 23:07:22', 'returned', '2025-04-28', NULL, NULL, NULL),
(45, '5778577', '18', '2025-04-21 15:26:00', '2025-04-21 23:07:22', 'returned', '2025-04-28', NULL, NULL, NULL),
(46, '000120982', '17', '2025-04-21 23:24:09', NULL, 'borrowed', '2025-04-28', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `return_book`
--

CREATE TABLE `return_book` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `days_late` int(11) DEFAULT 0,
  `penalty` decimal(10,2) DEFAULT 0.00,
  `condition_on_return` varchar(100) DEFAULT NULL,
  `received_by` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scan_logs`
--

CREATE TABLE `scan_logs` (
  `id` int(11) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `scan_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shelves`
--

CREATE TABLE `shelves` (
  `id` int(11) NOT NULL,
  `shelf_name` varchar(255) NOT NULL,
  `shelf_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `student_id`, `firstname`, `lastname`, `course`, `year_level`, `qr_code`, `email`, `phone`) VALUES
(4, '2232323', 'Ella', 'Pedojan', 'Vinson', 'Grade 9', 'qrcodes/student_67d653ceb33a5.png', '', ''),
(5, '0921001', 'JOSE', 'CHAN', 'HUMSS', 'Grade 12', 'qrcodes/students_67d6873cc85db.png', 'JOSEMARIE@GMAIL.COM', '09915543484'),
(7, '000111', 'Jameswel', 'Aral', 'Resuma', 'Grade 10', 'qrcodes/students_67e498539ff27.png', 'jameswelaral@gmail.com', '09105997417'),
(8, '111222', 'Juvy', 'Aral', 'manlapao', 'Grade 9', 'qrcodes/students_67e4a40464812.png', 'juvy@gmail.com', '091122334455'),
(12, '36136179129', 'Alita', 'Soriano', 'barcoma', 'Grade 8', 'qrcodes/students_67f4ca764b575.png', 'alita@gmail.com', '1218682398212'),
(14, '000120982', 'Gerald', 'Galvez', 'Melvin Perje', 'Grade 11', 'qrcodes/students_6804fb2b7b17c.png', 'gerald@gmail.com', '09105991117'),
(15, '5778577', 'tyiuggjg', '6ryhfhf', 'yfhfjf', 'Grade 11', 'qrcodes/students_6805ee1bb98b6.png', '6r6ryr@gmail.com', '557557557557');

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
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `shelf_id` (`shelf_id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scan_logs`
--
ALTER TABLE `scan_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shelves`
--
ALTER TABLE `shelves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `scan_logs`
--
ALTER TABLE `scan_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shelves`
--
ALTER TABLE `shelves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`shelf_id`) REFERENCES `shelves` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
