-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 17, 2023 at 05:16 PM
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
-- Database: `hospital_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountants`
--

CREATE TABLE `accountants` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('unconfirmed','pending','confirmed') NOT NULL DEFAULT 'unconfirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accountants`
--

INSERT INTO `accountants` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `location`, `password`, `status`) VALUES
(4, 'Jonas', 'jonas@gmail.com', '0201478523', '2023-08-03', 'Male', 'GN-0380-2258', 'Central Region,  Ghana', '$2y$10$j26IIGmrn4H8B3z/YPYlJeazxcY0qGgSOx91eyu.8mEzJsEfXZs2S', 'confirmed'),
(5, 'Prince Mopo', 'princemopo@gmail.com', '0201478523', '2023-08-05', 'Male', 'GN-0380-2258', 'Central Region,  Ghana', '$2y$10$.XfeCxqheOcHdpVhO1SJ7Ohdm7oIL2mgr/7pAnAOvQnY4m6KiQxIm', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `location`, `password`) VALUES
(1, 'Admin 1', 'admin1@gmail.com', '0503333333', '2023-06-26', 'Female', NULL, NULL, '$2y$10$svd4j5tJT2jViZOKBZLNfOK7aupKfr26e23ClwuU3/SIYW1FhJvGW');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_time` time NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`) VALUES
(4, 1, 1, '2023-08-04 00:00:00', '13:16:00', 'Canceled'),
(5, 1, 1, '2023-08-04 00:00:00', '13:18:00', 'Completed'),
(8, 2, 4, '2023-08-05 00:00:00', '10:16:00', 'Scheduled'),
(9, 1, 4, '2023-08-01 00:00:00', '12:47:00', 'Completed'),
(10, 1, 4, '2023-08-01 00:00:00', '12:47:00', 'Completed'),
(11, 1, 4, '2023-08-01 00:00:00', '12:47:00', 'Canceled'),
(12, 1, 4, '2023-08-01 00:00:00', '12:47:00', 'Canceled'),
(13, 1, 4, '2023-08-01 00:00:00', '12:47:00', 'Completed'),
(15, 2, 4, '2023-08-05 00:00:00', '13:03:00', 'Scheduled'),
(16, 2, 1, '2023-08-05 00:00:00', '13:05:00', 'Scheduled'),
(17, 1, 1, '2023-08-05 00:00:00', '17:09:00', 'Scheduled'),
(18, 1, 1, '2023-08-05 00:00:00', '17:09:00', 'Scheduled'),
(19, 1, 1, '2023-08-05 00:00:00', '17:09:00', 'Scheduled'),
(20, 4, 4, '2023-08-05 00:00:00', '21:16:00', 'Scheduled'),
(21, 4, 4, '2023-08-05 00:00:00', '21:16:00', 'Scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `bed_ward_allocation`
--

CREATE TABLE `bed_ward_allocation` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `bed_number` varchar(50) NOT NULL,
  `ward` varchar(50) NOT NULL,
  `allocation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bed_ward_allocation`
--

INSERT INTO `bed_ward_allocation` (`id`, `patient_id`, `bed_number`, `ward`, `allocation_date`) VALUES
(1, 2, '5', '8', '2023-07-31'),
(2, 2, '3', 'Women ward', '2023-08-07'),
(3, 2, '3', 'Women ward', '2023-08-07'),
(4, 2, '3', 'Women ward', '2023-08-07'),
(5, 4, '12', 'Children', '2023-08-12'),
(6, 4, '12', 'Children', '2023-08-12'),
(7, 4, '12', 'Children', '2023-08-12'),
(8, 4, '12', 'Children', '2023-08-12'),
(9, 2, '7', '7', '2023-08-12'),
(10, 2, '3', 'Men\'s ward', '2023-08-12'),
(11, 1, '4', 'Children', '2023-08-12'),
(12, 1, '34', '14', '2023-08-12'),
(13, 4, '45', '45', '2023-08-12'),
(14, 1, '12', '12', '2023-08-12'),
(15, 2, '12', 'Children', '2023-08-12'),
(16, 2, '12', 'Children', '2023-08-12'),
(17, 1, '12', 'Children', '2023-08-12'),
(18, 1, '12', 'Children', '2023-08-12'),
(19, 4, '5', 'Women ward', '2023-08-12');

-- --------------------------------------------------------

--
-- Table structure for table `blood_bank`
--

CREATE TABLE `blood_bank` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(4) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_bank`
--

INSERT INTO `blood_bank` (`id`, `blood_group`, `quantity`) VALUES
(1, 'A-', 47),
(2, 'A+', 47),
(3, 'O+', 78),
(4, 'AB-', 1468),
(5, 'B+', 711),
(6, 'B-', 12),
(7, 'O-', 104),
(8, 'AB+', 75);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('unconfirmed','pending','confirmed') NOT NULL DEFAULT 'unconfirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `location`, `specialization`, `password`, `status`) VALUES
(1, 'Agbaga Benjamin', 'honorablepharaoh819@gmail.com', '+233549762017', '2023-08-04', 'Male', 'GN-0380-2258', 'Dawhenya', 'Cardio', '$2y$10$Pc9Jnp8mSjfL5Ypm5/GxQOMREBhrrMnGLb6XELgOz20l7zSnGkqaG', 'confirmed'),
(4, 'Doctor', 'doctor@gmail.com', '0554444444', '2023-08-17', 'Male', 'GN-0380-2258', 'Accra', 'Optomologist', '$2y$10$a/VnFp5/QvxiEdzifleGseBMA3w8JwGcBBVNTZThtayNw4PHdF6N2', 'confirmed'),
(5, 'Mansur Mohammed', 'mansur@gmail.ccm', '0201478523', '2023-08-18', 'Male', 'GN-0380-2258', 'Accra, Ghana', 'Psychology', '$2y$10$cbWwhcUJfwn1ln7xm2Pl5.fGLWJt.pnZ7gayzArvato/roMCQs7o.', 'confirmed'),
(6, 'Lucky Aku', 'lucky@gmail.com', '0247894563', '2023-08-16', 'Male', 'GN-0380-2258', 'Accra, Ghana', 'Cardio', '$2y$10$L1GKBC8b1ddGw2upC/EUhOOqlUdxKIGAmJFj9R5l.KBMdlb5Dwlcm', 'confirmed'),
(7, 'Afenyo Korsi Fiakodzo', 'afenyo@gmail.com', '0214578963', '2023-08-11', 'Male', 'GN-0380-2258', 'Accra, Ghana', 'E-commerce', '$2y$10$zuYyEy0BHhd126ky4p1OleoYPgqZjb9DiKSPnDGhXEzEq5wl.B2Y2', 'confirmed'),
(8, 'Stubborn Cat', 'stubborn@gmail.com', '0247894561', '2023-08-04', 'Male', 'Dawhenya', 'Accra, Ghana', 'Call Of Duty', '$2y$10$gGxCHpX5OeRLXmrkDXLtY.jtgeXLRqX36EmBZS2.JC2Labbt7WzTm', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `invoice_amount` decimal(10,2) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `invoice_time` time DEFAULT NULL,
  `additional_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `patient_id`, `invoice_amount`, `invoice_date`, `invoice_time`, `additional_info`) VALUES
(2, 2, 5555.00, '2023-08-11 00:00:00', '00:22:47', NULL),
(4, 2, 4343.00, '2023-08-12 00:00:00', '12:46:00', NULL),
(5, 1, 56.00, '2023-08-12 00:00:00', '21:08:04', NULL),
(6, 1, 56.00, '2023-08-12 00:00:00', '21:08:22', NULL),
(7, 1, 56.00, '2023-08-12 00:00:00', '21:08:24', NULL),
(8, 4, 41.00, '2023-08-12 00:00:00', '21:11:55', NULL),
(9, 1, 78.00, '2023-08-12 00:00:00', '21:12:04', NULL),
(10, 1, 78.00, '2023-08-12 00:00:00', '21:13:46', NULL),
(11, 1, 78.00, '2023-08-12 00:00:00', '21:14:03', NULL),
(12, 1, 78.00, '2023-08-12 00:00:00', '21:19:41', NULL),
(13, 4, 78.00, '2023-08-12 00:00:00', '21:19:48', NULL),
(14, 4, 784.00, '2023-08-16 00:00:00', '16:27:33', NULL),
(15, 4, 789.00, '2023-08-16 00:00:00', '16:33:00', NULL),
(16, 1, 789.00, '2023-08-16 00:00:00', '16:36:11', 'Surgery'),
(17, 4, 25.00, '2023-08-16 00:00:00', '16:39:12', 'Drugs');

-- --------------------------------------------------------

--
-- Table structure for table `laboratorists`
--

CREATE TABLE `laboratorists` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('unconfirmed','pending','confirmed') NOT NULL DEFAULT 'unconfirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laboratorists`
--

INSERT INTO `laboratorists` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `location`, `password`, `status`) VALUES
(1, 'Kekeli Lit', 'kekelilit@gmail.com', '0202222222', '2022-09-08', 'Female', NULL, NULL, '$2y$10$xVj.NuaJDLhMA47Ai4tHWuVNsLTpw8VoxV6LXhdHIr8jmnrCgg6B6', 'confirmed'),
(2, 'Benjamin', 'benjamin@gmail.com', '0201478523', '2023-08-04', 'Male', 'GN-0380-2258', 'Accra, Ghana', '$2y$10$Ya9PeUxjj8rl.cWb6Lm4ze0vdEe67yk8KA.7VZ2uIieFOBPKOOuYK', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `lab_requests`
--

CREATE TABLE `lab_requests` (
  `id` int(11) NOT NULL,
  `doctor_id` int(125) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `request_date` date NOT NULL,
  `status` enum('Pending','Completed','Canceled') NOT NULL DEFAULT 'Pending',
  `test_results` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_requests`
--

INSERT INTO `lab_requests` (`id`, `doctor_id`, `patient_id`, `test_name`, `request_date`, `status`, `test_results`) VALUES
(1, 1, 2, 'Sperm Count', '2023-08-09', 'Completed', 'Fertile'),
(3, 1, 2, 'Blood Sugar', '2023-08-10', 'Completed', 'Blood Sugar : High'),
(4, 1, 4, 'Testicles', '2023-08-12', 'Completed', 'No testicles found'),
(5, 1, 1, 'Fever', '2023-08-12', 'Completed', 'Fever: Positive'),
(6, 1, 1, 'Diabetes', '2023-08-12', 'Pending', NULL),
(7, 1, 4, 'Asthma', '2023-08-12', 'Pending', NULL),
(8, 1, 4, 'Cocidiosis', '2023-08-12', 'Pending', NULL),
(9, 1, 4, 'Arthritis', '2023-08-12', 'Pending', NULL),
(10, 4, 1, 'Hello', '2023-08-12', 'Pending', NULL),
(11, 4, 1, 'dfsgfsdg', '2023-08-12', 'Pending', NULL),
(12, 4, 4, 'ewrweqr', '2023-08-12', 'Pending', NULL),
(13, 4, 4, 'Eyes', '2023-08-12', 'Completed', 'Eyes: Short vision'),
(14, 4, 4, 'Testicles', '2023-08-13', 'Completed', 'Testicles: Non available'),
(15, 1, 4, 'Hairloss', '2023-08-16', 'Completed', 'Hairloss: Yes'),
(16, 1, 4, 'Blood type', '2023-08-16', 'Completed', 'Blood type : AB');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `category_id`, `name`, `quantity`) VALUES
(1, 5, 'Fiesta', 159),
(2, 5, 'High sensation', 7989),
(3, 7, 'Carotone', 14),
(4, 10, 'Johnson Powder', 789),
(5, 10, 'Johnson Powder', 748),
(6, 10, 'Johnson Powder', 748),
(7, 10, 'Johnson Powder', 748),
(8, 5, 'Tender Kiss', 748),
(9, 6, 'Well men', 455),
(10, 6, 'Well men', 455),
(11, 7, 'Jeba', 47);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_categories`
--

CREATE TABLE `medicine_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_categories`
--

INSERT INTO `medicine_categories` (`id`, `name`) VALUES
(1, 'Pain Relief'),
(2, 'Antibiotics'),
(3, 'Cold and Cough'),
(4, 'Vitamins'),
(5, 'Contraception'),
(6, 'Strength '),
(7, 'Beauty'),
(8, 'Puberty'),
(9, 'Puberty'),
(10, 'Skin');

-- --------------------------------------------------------

--
-- Table structure for table `nurses`
--

CREATE TABLE `nurses` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('unconfirmed','pending','confirmed') NOT NULL DEFAULT 'unconfirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nurses`
--

INSERT INTO `nurses` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `password`, `address`, `location`, `status`) VALUES
(4, 'Nana Ama Akoto', 'nanaamaakoto@gmail.com', '0214578965', '2023-08-04', 'Female', '$2y$10$8.p/rXUh83RC5r3WLI4dOedJ102yKg6kwr2KWl9N3AfDodKibVQTm', 'GN-0380-2258', 'Accra, Ghana', 'confirmed'),
(5, 'Wanye', 'wanye@gmail.com', '0201234567', '2023-08-03', 'Female', '$2y$10$Unf1m2jp.rTll9g.i7zh8.Cl9pX6blCbhUlhYckLL/e4u519K8mfy', 'GN-0380-2258', 'Accra, Ghana', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

CREATE TABLE `operations` (
  `id` int(11) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `operation_name` varchar(255) NOT NULL,
  `operation_date` date NOT NULL,
  `operation_time` time NOT NULL,
  `operation_notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `operations`
--

INSERT INTO `operations` (`id`, `patient_id`, `doctor_id`, `operation_name`, `operation_date`, `operation_time`, `operation_notes`, `status`) VALUES
(3, '2', 1, 'Fertility', '2023-08-01', '17:25:00', 'Check the womb', 'pending'),
(4, '1', 1, 'Typhoid', '2023-08-02', '09:54:00', 'Nice', 'pending'),
(5, '2', 1, 'Head break', '2023-08-05', '01:30:00', 'Remove brain tumors', 'pending'),
(6, '4', 1, 'Brain surgery', '2023-08-04', '14:28:00', 'Check the tumors', 'pending'),
(7, '4', 4, 'Heart surgery', '2023-08-03', '19:01:00', 'Check heart conditions', 'pending'),
(8, '1', 4, 'Testicle dissection ', '2023-08-05', '20:03:00', 'Check the testicles', 'pending'),
(9, '1', 4, 'Testicle dissection ', '2023-08-05', '20:03:00', 'Check the testicles', 'pending'),
(10, '1', 4, 'Testicle dissection ', '2023-08-05', '20:03:00', 'Check the testicles', 'pending'),
(11, '2', 4, 'Check brain', '2023-08-04', '19:09:00', 'Check water volume', 'pending'),
(12, '2', 4, 'Check brain', '2023-08-04', '19:09:00', 'Check water volume', 'pending'),
(13, '2', 4, 'Check brain', '2023-08-04', '19:09:00', 'Check water volume', 'pending'),
(14, '1', 4, 'Check muscles', '2023-08-03', '19:12:00', 'Check muscles', 'pending'),
(15, '1', 4, 'Check muscles', '2023-08-03', '19:12:00', 'Check muscles', 'pending'),
(16, '1', 4, 'Check muscles', '2023-08-03', '19:12:00', 'Check muscles', 'pending'),
(17, '1', 4, 'Check muscles', '2023-08-03', '19:12:00', 'Check muscles', 'pending'),
(18, '4', 4, 'Head break', '2023-08-19', '19:17:00', 'Check the skull', 'pending'),
(19, '4', 4, 'Head break', '2023-08-19', '19:17:00', 'Check the skull', 'pending'),
(20, '4', 4, 'Head break', '2023-08-19', '19:17:00', 'Check the skull', 'pending'),
(21, '4', 4, 'Head break', '2023-08-19', '19:17:00', 'Check the skull', 'pending'),
(22, '4', 4, 'Head break', '2023-08-19', '19:17:00', 'Check the skull', 'pending'),
(23, '4', 4, 'Head break', '2023-08-19', '19:17:00', 'Check the skull', 'pending'),
(24, '2', 4, 'Head break', '2023-08-19', '21:21:00', 'Head', 'pending'),
(25, '4', 4, 'Balls', '2023-08-05', '20:23:00', 'Check balls', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `operation_reports`
--

CREATE TABLE `operation_reports` (
  `id` int(11) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `report_notes` text NOT NULL,
  `status` enum('successful','unsuccessful') NOT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `operation_reports`
--

INSERT INTO `operation_reports` (`id`, `patient_email`, `doctor_id`, `report_notes`, `status`, `report_date`, `report_time`) VALUES
(3, 'ahurenraymond@gmail.com', 1, 'Tumor removed\r\n', 'successful', '2023-08-12', '01:07:00'),
(4, 'amegatcherjoseph@gmail.com', 1, 'Fever: Cured', 'successful', '2023-08-04', '14:06:00'),
(5, 'davidson@gmail.com', 1, 'Sperm count: Fertile', 'successful', '2023-08-05', '15:04:00'),
(6, 'davidson@gmail.com', 4, 'Checked brain successfully', 'successful', '2023-08-05', '20:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` varchar(650) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'unconfirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `location`, `password`, `status`) VALUES
(1, 'Amegatcher Joseph', 'amegatcherjoseph@gmail.com', '02578456624', '2023-08-03', 'Male', 'GN-0380-2258', 'Accra, Ghana', '$2y$10$aPy8Z9SQjcvSVT6ayaE0KudZgl9MOnr0Sr7JI4T/yfucxCpZ3HW.q', 'confirmed'),
(2, 'Ahuren Raymond', 'ahurenraymond@gmail.com', '02569877458', '2023-08-04', 'Male', 'GN-0380-2258', 'Central Region,  Ghana', '$2y$10$XxXwb9G9feXPtx5CEHFlhOcvvnOk328BoUXIarCTnwUFGipFwxXQe', 'confirmed'),
(3, 'Turban Prince', 'turban@gmail.com', '0201456325', '2023-08-10', 'Male', 'GN-0380-2258', 'Accra, Ghana', '$2y$10$d/H3y1R7bJCg7aMwGe6mqu/hgh/1an9i9dWutT4eIz2yLw0P1NjdO', 'confirmed'),
(4, 'Davidson', 'davidson@gmail.com', '0247894563', '2023-08-04', 'Male', 'GN-0380-2258', 'Accra', '$2y$10$d3hI.F/o27EUSYKbQMXEMeJ3v5gwywUdTLi0CAh2SkoE70UDpZS92', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `patient_id` int(125) DEFAULT NULL,
  `patient_email` varchar(125) DEFAULT NULL,
  `amount` int(125) DEFAULT NULL,
  `payment_method` varchar(125) DEFAULT NULL,
  `payment_time` time DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `additional_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `patient_id`, `patient_email`, `amount`, `payment_method`, `payment_time`, `payment_date`, `additional_info`) VALUES
(1, 2, 'ahurenraymond@gmail.com', 458, 'Cash', '18:01:00', '2023-08-11', 'Drugs'),
(7, 1, 'amegatcherjoseph@gmail.com', 744, 'Cash', '10:47:00', '2023-08-24', 'Drugs'),
(8, 1, 'amegatcherjoseph@gmail.com', 744, 'Cash', '10:47:00', '2023-08-24', 'Drugs'),
(12, 1, 'amegatcherjoseph@gmail.com', 417, 'Cash', '11:20:00', '2023-08-19', 'Operation'),
(13, 4, 'davidson@gmail.com', 424, 'Electronic', '12:08:00', '2023-08-19', 'Surgery'),
(15, 2, 'ahurenraymond@gmail.com', 3434, 'Electronic', '12:16:00', '2023-08-05', '147'),
(16, 4, 'davidson@gmail.com', 4343, 'Cash', '12:25:00', '2023-08-04', 'Medicine'),
(17, 1, 'amegatcherjoseph@gmail.com', 471, 'Electronic', '12:37:00', '2023-08-19', 'Medicines'),
(18, 2, 'ahurenraymond@gmail.com', 77, 'Electronic', '18:51:00', '2023-08-25', 'Tax'),
(19, 2, 'ahurenraymond@gmail.com', 1234, 'Electronic', '15:26:00', '2023-08-04', 'dfdfd');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacists`
--

CREATE TABLE `pharmacists` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('unconfirmed','pending','confirmed') NOT NULL DEFAULT 'unconfirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacists`
--

INSERT INTO `pharmacists` (`id`, `fullname`, `email`, `phone`, `date_of_birth`, `gender`, `address`, `location`, `password`, `status`) VALUES
(1, 'Agegintina Tetteh ', 'tetteh@gmail.com', '0552222222', '2023-07-08', 'Female', NULL, NULL, '$2y$10$YWRTaYHXV/MM3HyJwo87qePYD6uMRLZih2EviqqAKoTyx/IeRNpNS', 'confirmed'),
(2, 'Bra Chemi', 'brachemi@gmail.com', '0247896541', '2023-08-02', 'Male', 'GN-0380-2258', 'Accra', '$2y$10$HqBbc1vvH4bxo9fUcqchdeTLKRZC0RWZJLt7WUMR2/0aHNM8sBpDK', 'confirmed'),
(3, 'Jerry Azumah', 'jerry@gmail.com', '0245789456', '2023-08-04', 'Male', 'GN-0380-2258', 'Central Region,  Ghana', '$2y$10$t2jJ6SJyXj7xOru1zykxZub/V0VQVG882cpMISuTTicyrgX2xxpmG', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` varchar(255) NOT NULL,
  `medication` varchar(255) NOT NULL,
  `dosage` varchar(100) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `doctor_id`, `patient_id`, `medication`, `dosage`, `cost`, `created_at`) VALUES
(1, 1, '2', 'Paracetamol', '2*2 After meal', 254.00, '2023-08-03 15:09:37'),
(2, 1, '2', 'Ibuprofen', '10ml 2x daily', 70.00, '2023-08-07 17:23:40'),
(3, 1, '1', 'See clear\r\nCyprodine', '1 drop per day\r\n10ml per day', 214.00, '2023-08-10 12:41:04'),
(4, 1, '4', 'Pain Freeze', 'Spray on affected place', 758.00, '2023-08-12 14:06:57'),
(5, 1, '4', 'Pain Freeze', 'Spray on affected place', 758.00, '2023-08-12 14:07:41'),
(6, 1, '2', 'Panadol', '2x2', 784.00, '2023-08-12 14:21:04'),
(7, 1, '1', 'Panadol', '3x1', 784.00, '2023-08-12 14:21:47'),
(8, 4, '4', 'Panadol', '2x2', 745.00, '2023-08-12 21:56:12'),
(9, 4, '4', 'Testicles Enhancer', '50ml x 2', 748.00, '2023-08-13 20:21:21'),
(10, 4, '4', 'Testicles Enhancer', '50ml x 2', 748.00, '2023-08-13 20:22:20');

-- --------------------------------------------------------

--
-- Table structure for table `vital_information`
--

CREATE TABLE `vital_information` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `temperature` float NOT NULL,
  `blood_pressure` varchar(20) NOT NULL,
  `heart_rate` decimal(5,2) NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `sugar_level` float NOT NULL,
  `record_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vital_information`
--

INSERT INTO `vital_information` (`id`, `patient_id`, `temperature`, `blood_pressure`, `heart_rate`, `weight`, `height`, `sugar_level`, `record_date`) VALUES
(1, 3, 24, '25', 475.00, 458, 524, 999, '2023-06-30'),
(2, 4, 24, '25', 475.00, 458, 524, 999, '2023-08-17'),
(6, 2, 24, '25', 52.00, 458, 524, 999, '2023-08-08'),
(7, 1, 43, '34', 34.00, 43, 34, 34, '2023-08-03'),
(8, 1, 41, '144', 435.00, 3456, 365, 54, '2023-08-11'),
(9, 4, 1, '1', 1.00, 1, 1, 1, '2023-08-03'),
(10, 2, 23, '32', 32.00, 32, 23, 32, '2023-08-17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountants`
--
ALTER TABLE `accountants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `bed_ward_allocation`
--
ALTER TABLE `bed_ward_allocation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `blood_bank`
--
ALTER TABLE `blood_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `laboratorists`
--
ALTER TABLE `laboratorists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_requests`
--
ALTER TABLE `lab_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `medicine_categories`
--
ALTER TABLE `medicine_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nurses`
--
ALTER TABLE `nurses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_reports`
--
ALTER TABLE `operation_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pharmacists`
--
ALTER TABLE `pharmacists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vital_information`
--
ALTER TABLE `vital_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accountants`
--
ALTER TABLE `accountants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `bed_ward_allocation`
--
ALTER TABLE `bed_ward_allocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `blood_bank`
--
ALTER TABLE `blood_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `laboratorists`
--
ALTER TABLE `laboratorists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lab_requests`
--
ALTER TABLE `lab_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medicine_categories`
--
ALTER TABLE `medicine_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nurses`
--
ALTER TABLE `nurses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `operations`
--
ALTER TABLE `operations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `operation_reports`
--
ALTER TABLE `operation_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pharmacists`
--
ALTER TABLE `pharmacists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vital_information`
--
ALTER TABLE `vital_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `bed_ward_allocation`
--
ALTER TABLE `bed_ward_allocation`
  ADD CONSTRAINT `bed_ward_allocation_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `lab_requests`
--
ALTER TABLE `lab_requests`
  ADD CONSTRAINT `lab_requests_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `medicine_categories` (`id`);

--
-- Constraints for table `vital_information`
--
ALTER TABLE `vital_information`
  ADD CONSTRAINT `vital_information_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
