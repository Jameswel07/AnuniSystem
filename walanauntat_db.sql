-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 07:32 AM
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
  `isbn` varchar(20) NOT NULL,
  `status` enum('available','borrowed') DEFAULT 'available',
  `qr_code` text DEFAULT NULL,
  `shelf_id` int(11) DEFAULT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `available_copies` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `title`, `author`, `category`, `isbn`, `status`, `qr_code`, `shelf_id`, `total_copies`, `created_at`, `available_copies`) VALUES
(2, 'elfilibusterismo', 'rizal', 'Filipino', '', 'available', 'qrcodes/67d63bb7f27a0.png', NULL, 1, '2025-04-24 23:30:45', 0),
(3, 'plants', 'basta amo na', 'Science', '', 'available', 'qrcodes/67d63df06edb4.png', NULL, 1, '2025-04-24 23:30:45', 0),
(7, 'asasa', 'asasasasasa', 'History', '', 'available', 'qrcodes/67d67a63d88f4.png', NULL, 1, '2025-04-24 23:30:45', 0),
(8, 'kenkoyyy', 'williams', 'English', '', 'available', 'qrcodes/67d685c76d2b1.png', NULL, 1, '2025-04-24 23:30:45', 0),
(9, 'asasas', 'saasas', 'Mathematics', '', 'available', 'qrcodes/67d69ffda3bdc.png', NULL, 1, '2025-04-24 23:30:45', 0),
(11, 'Ang kamanguan ko', 'James Arthur', 'English', '', 'available', 'qrcodes/67d7af2d3389b.png', NULL, 1, '2025-04-24 23:30:45', 0),
(14, 'Juan tamad', 'Juan Tamad', 'Filipino', '', 'available', 'qrcodes/67ee9cad47e92.png', NULL, 1, '2025-04-24 23:30:45', 0),
(17, 'Ang manananggal', 'Si Batosay', 'Filipino', '', 'available', 'qrcodes/6804fb8b74276.png', NULL, 1, '2025-04-24 23:30:45', 0),
(18, 'yhjh', 'ddydhg', 'Filipino', '', 'available', 'qrcodes/6805edc42c3c9.png', NULL, 1, '2025-04-24 23:30:45', 0),
(19, ', m.,', 'jbj', 'Filipino', '', 'available', 'qrcodes/680a66992a21f.png', NULL, 1, '2025-04-25 00:28:09', 0),
(20, 'xzzx', 'zxz', 'Filipino', 'xz', 'available', 'qrcodes/680a706ed87a5.png', NULL, 1, '2025-04-25 01:10:06', 0),
(21, 'aswqqwdq', 'qwdwdwd', 'Filipino', 'wqwdwqdqd', 'available', 'qrcodes/680b32ee2f7a9.png', NULL, 1, '2025-04-25 14:59:58', 0),
(22, 'awawaw', 'awawaw', 'Filipino', 'awawa', 'available', 'qrcodes/68108af51d249.png', NULL, 1, '2025-04-29 16:16:53', 0),
(23, 'sasas', 'sasas', 'Filipino', 'asasa', 'available', 'qrcodes/68108dee27adf.png', NULL, 1, '2025-04-29 16:29:34', 0),
(24, 'anuuuu', 'anuuu', 'Filipino', 'anuuu', 'available', 'qrcodes/681090c63a5c0.png', NULL, 1, '2025-04-29 16:41:42', 4);

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
(49, '000120982', '7', '2025-04-25 14:57:20', NULL, 'borrowed', '0000-00-00', NULL, NULL, NULL),
(50, '000111', '21', '2025-04-25 15:00:58', NULL, 'borrowed', '2025-05-02', NULL, NULL, NULL),
(51, '0921001', '19', '2025-04-29 16:07:47', NULL, 'borrowed', '2025-05-06', NULL, NULL, NULL),
(52, 'aaawawaw', '24', '2025-04-29 16:42:04', NULL, 'borrowed', '2025-05-06', NULL, NULL, NULL);

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
(15, '5778577', 'tyiuggjg', '6ryhfhf', 'yfhfjf', 'Grade 11', 'qrcodes/students_6805ee1bb98b6.png', '6r6ryr@gmail.com', '557557557557'),
(16, 'add', 'asdsd', 'sdsadas', 'sd', 'Grade 7', 'qrcodes/students_680a6683145a6.png', 'araljameswel@gmail.com', 'sadasd'),
(17, 'aaawawaw', 'awawawawa', 'awawawawa', 'awyawyahwawa', 'Grade 11', 'qrcodes/students_68108ae1a375e.png', 'gianichaelvista@gmail.com', '123212312132');

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
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
