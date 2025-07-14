-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2025 at 07:41 AM
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
-- Database: `sgn_girl_admission`
--

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `form_no` varchar(50) NOT NULL,
  `class` varchar(50) NOT NULL,
  `part` varchar(10) NOT NULL,
  `medium` enum('English','Hindi') NOT NULL,
  `faculty` enum('Arts','Science','Commerce','Computer') NOT NULL,
  `applicant_name` varchar(100) NOT NULL,
  `hindi_name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) NOT NULL,
  `f_occupation` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) NOT NULL,
  `m_occupation` varchar(100) DEFAULT NULL,
  `dob` date NOT NULL,
  `category` enum('General','SC','ST','OBC','Other') NOT NULL,
  `aadhar` varchar(12) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `perm_address` text NOT NULL,
  `same_address` tinyint(1) DEFAULT 0,
  `local_address` text DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject1` varchar(100) DEFAULT NULL,
  `subject2` varchar(100) DEFAULT NULL,
  `subject3` varchar(100) DEFAULT NULL,
  `comp_computer` tinyint(1) DEFAULT 0,
  `comp_env` tinyint(1) DEFAULT 0,
  `comp_english` tinyint(1) DEFAULT 0,
  `comp_hindi` tinyint(1) DEFAULT 0,
  `prev_course_title` varchar(100) NOT NULL,
  `prev_year` varchar(4) NOT NULL,
  `prev_board` varchar(100) NOT NULL,
  `prev_subjects` varchar(255) NOT NULL,
  `prev_percentage` decimal(5,2) NOT NULL,
  `prev_division` enum('1st','2nd','3rd') NOT NULL,
  `institution_name` varchar(255) NOT NULL,
  `institution_address` text NOT NULL,
  `institution_contact` varchar(15) NOT NULL,
  `university_enrollment` varchar(50) DEFAULT NULL,
  `nss_offered` enum('Yes','No') DEFAULT NULL,
  `other_activities` text DEFAULT NULL,
  `declaration` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `form_no` (`form_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
