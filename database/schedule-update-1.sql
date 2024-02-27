-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2024 at 09:00 AM
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
-- Database: `payroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `ID` int(11) NOT NULL,
  `employee_ID` int(11) NOT NULL COMMENT 'ID of ef emplyoee',
  `department_ID` int(11) NOT NULL COMMENT 'department ID',
  `date` date NOT NULL,
  `time_start` varchar(255) NOT NULL COMMENT 'sched time start',
  `time_end` varchar(255) NOT NULL COMMENT 'sched time end',
  `isOT` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--
-- remove the data
-- INSERT INTO `schedule` (`ID`, `employee_ID`, `department_ID`, `date`, `time_start`, `time_end`, `isOT`) VALUES
-- (3, 11, 2, '2024-02-05', '', '', 0),
-- (9, 9, 4, '2024-02-05', '', '', 0),
-- (11, 9, 1, '2024-01-29', '', '', 0),
-- (12, 11, 1, '2024-01-30', '', '', 0),
-- (13, 9, 3, '2024-01-30', '', '', 0),
-- (14, 10, 2, '2024-02-07', '', '', 0),
-- (20, 10, 3, '2024-02-19', '', '', 0),
-- (22, 9, 3, '2024-02-20', '', '', 0),
-- (24, 10, 1, '2024-02-20', '', '', 0),
-- (25, 12, 3, '2024-02-20', '', '', 0),
-- (26, 14, 2, '2024-02-20', '', '', 0),
-- (27, 13, 4, '2024-02-20', '', '', 0),
-- (28, 11, 2, '2024-02-20', '', '', 0),
-- (30, 12, 3, '2024-02-04', '', '', 0),
-- (39, 9, 1, '2024-02-25', '12:02 PM', '05:02 AM', 0),
-- (40, 11, 1, '2024-02-25', '11:55 AM', '10:55 PM', 0),
-- (43, 9, 3, '2024-02-13', '07:00 AM', '07:00 PM', 1),
-- (47, 11, 4, '2024-02-13', '10:00 AM', '07:00 PM', 1),
-- (49, 13, 2, '2024-02-13', '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
