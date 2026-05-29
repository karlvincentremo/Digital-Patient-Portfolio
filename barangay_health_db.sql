-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 12:31 PM
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
-- Database: `barangay_health_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time DEFAULT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_id`, `appointment_date`, `appointment_time`, `purpose`, `notes`, `reason`, `status`) VALUES
(9, 21, '2026-05-24', '19:35:00', 'General Checkup', '', NULL, 'Completed'),
(12, 23, '2026-05-25', '22:00:00', 'Vaccination', '', NULL, 'Completed'),
(13, 24, '2026-05-24', '21:00:00', 'Vaccination', '', NULL, 'Pending'),
(14, 26, '2026-05-26', '23:59:00', 'Blood Sugar Monitoring', '', NULL, 'Completed'),
(15, 26, '2026-05-28', '14:25:00', 'Follow-up Consultation', '', NULL, 'Completed'),
(16, 26, '2026-05-31', '14:21:00', 'General Checkup', '', NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `babies`
--

CREATE TABLE `babies` (
  `baby_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `baby_name` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_records`
--

CREATE TABLE `health_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `record_date` date DEFAULT NULL,
  `record_time` time DEFAULT NULL,
  `pulse_rate` int(3) DEFAULT NULL,
  `respiratory_rate` int(3) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `blood_sugar` decimal(5,2) DEFAULT NULL,
  `oxygen_saturation` int(3) DEFAULT NULL,
  `blood_pressure_systolic` int(3) DEFAULT NULL,
  `blood_pressure_diastolic` int(3) DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `chief_complaint` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment_given` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `attending_staff` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_records`
--

INSERT INTO `health_records` (`id`, `patient_id`, `record_date`, `record_time`, `pulse_rate`, `respiratory_rate`, `weight`, `height`, `blood_sugar`, `oxygen_saturation`, `blood_pressure_systolic`, `blood_pressure_diastolic`, `temperature`, `chief_complaint`, `diagnosis`, `treatment_given`, `remarks`, `attending_staff`, `notes`) VALUES
(1, 23, '2026-05-25', '22:00:00', 72, 17, 45.00, 145.00, 100.00, 98, 119, 80, 37.00, 'Heavy Cough', 'Sore Throat, dry throat', 'Cough Syrup', NULL, 'Dr. strange', 'Secret'),
(3, 27, '2026-05-26', '17:59:00', 77, 16, 3.50, 165.00, 100.00, 98, 120, 80, 37.00, 'tb', 'cough', 'panadol', NULL, 'Dr. Karl Vincent C. Remo', ''),
(4, 28, '2026-05-28', '07:39:00', 73, 16, 45.00, 145.00, 100.00, 98, 120, 80, 38.00, 'Cough', 'Sore throat, slight hot temp', 'paracetamol', NULL, 'Dr. Karl Vincent C. Remo', ''),
(6, 28, '2026-05-28', '08:20:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TEST', 'TEST', 'TEST', NULL, 'Dr. Lewis Hamilton', ''),
(7, 29, '2026-05-28', '08:23:00', 72, 16, 65.00, 165.00, 100.00, 98, 120, 80, 36.00, 'TEST test test', 'TEST', 'TEST', NULL, 'Dr. Lewis Hamilton', 'TEST'),
(8, 30, '2026-05-29', '08:12:00', 73, 26, 3.50, 30.00, 100.00, 98, 120, 80, 37.00, '', 'TEST', 'TEST', NULL, 'Dr. Karl Vincent C. Remo', '');

-- --------------------------------------------------------

--
-- Table structure for table `immunization_records`
--

CREATE TABLE `immunization_records` (
  `id` int(11) NOT NULL,
  `baby_id` int(11) DEFAULT NULL,
  `vaccine_name` varchar(100) DEFAULT NULL,
  `type` enum('Injection','Oral') DEFAULT NULL,
  `scheduled_age` varchar(50) DEFAULT NULL,
  `date_administered` date DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `administered_by` varchar(100) DEFAULT NULL,
  `date_given` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `immunization_records`
--

INSERT INTO `immunization_records` (`id`, `baby_id`, `vaccine_name`, `type`, `scheduled_age`, `date_administered`, `status`, `administered_by`, `date_given`) VALUES
(1, 1, 'HepB1 and BCG', 'Injection', 'At Birth', NULL, 'Pending', NULL, NULL),
(2, 1, '6in1 1 (DTP1, iPV1, HiB1 and HepB2)', 'Injection', '1 1/2 mos', NULL, 'Pending', NULL, NULL),
(3, 1, 'Rotavirus 1', 'Oral', '1 1/2 mos', NULL, 'Pending', NULL, NULL),
(4, 1, 'PCV13 1', 'Injection', '2 mos', NULL, 'Pending', NULL, NULL),
(5, 1, '6in1 2', 'Injection', '3 mos', NULL, 'Pending', NULL, NULL),
(6, 1, 'Rotavirus 2', 'Oral', '3 mos', NULL, 'Pending', NULL, NULL),
(7, 1, 'PCV13 2', 'Injection', '4 mos', NULL, 'Pending', NULL, NULL),
(8, 1, '6in1 3', 'Injection', '5 mos', NULL, 'Pending', NULL, NULL),
(9, 1, 'Rotavirus 3', 'Oral', '5 mos', NULL, 'Pending', NULL, NULL),
(10, 1, 'PCV13 3, Flu 1', 'Injection', '6 mos', NULL, 'Pending', NULL, NULL),
(11, 1, 'Flu 2', 'Injection', '7 mos', NULL, 'Pending', NULL, NULL),
(12, 1, 'Japanese Encephalitis 1', 'Injection', '9 mos', NULL, 'Pending', NULL, NULL),
(13, 1, 'Chicken Pox 1, MMR 1', 'Injection', '1 y/o', NULL, 'Pending', NULL, NULL),
(14, 1, 'HepA 1', 'Injection', '1 y/o', NULL, 'Pending', NULL, NULL),
(15, 1, '6in1 Booster 1', 'Injection', '1 y 6 mos', NULL, 'Pending', NULL, NULL),
(16, 1, 'PCV13 Booster', 'Injection', '1 y 6 mos', NULL, 'Pending', NULL, NULL),
(17, 1, 'HepA 2, Yearly Flu', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(18, 1, 'Meningococcal, Pneumo23', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(19, 1, 'Typhoid Vaccine 1', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(20, 1, 'Japanese Encephalitis 2, Flu', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(21, 1, 'Yearly Flu', 'Injection', '3 y/o', NULL, 'Pending', NULL, NULL),
(22, 1, 'Chicken Pox 2, MMR 2', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(23, 1, 'DT Booster 2', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(24, 1, 'Yearly Flu', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(25, 8, 'BCG Vaccine', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-23'),
(26, 8, 'Hepatitis B Vaccine', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-23'),
(27, 8, 'Penta (DPT-HepB-HiB) 1st Dose', NULL, '1.5 Months', NULL, 'Completed', NULL, '2026-05-23'),
(28, 8, 'Oral Polio Vaccine (OPV) 1st Dose', NULL, '1.5 Months', NULL, 'Completed', NULL, '2026-05-23'),
(29, 8, 'Pneumococcal Conjugate Vaccine (PCV) 1st Dose', NULL, '1.5 Months', NULL, 'Pending', NULL, NULL),
(30, 8, 'Penta (DPT-HepB-HiB) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(31, 8, 'Oral Polio Vaccine (OPV) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(32, 8, 'Pneumococcal Conjugate Vaccine (PCV) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(33, 8, 'Penta (DPT-HepB-HiB) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(34, 8, 'Oral Polio Vaccine (OPV) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(35, 8, 'Inactivated Polio Vaccine (IPV)', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(36, 8, 'Pneumococcal Conjugate Vaccine (PCV) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(37, 8, 'Measles, Mumps, Rubella (MMR) 1st Dose', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(38, 8, 'Measles, Mumps, Rubella (MMR) 2nd Dose', NULL, '1 Year', NULL, 'Pending', NULL, NULL),
(39, 2, 'HepB1 and BCG', 'Injection', 'At Birth', NULL, 'Pending', NULL, NULL),
(40, 2, '6in1 1 (DTP1, iPV1, HiB1 and HepB2)', 'Injection', '1 1/2 mos', NULL, 'Pending', NULL, NULL),
(41, 2, 'Rotavirus 1', 'Oral', '1 1/2 mos', NULL, 'Pending', NULL, NULL),
(42, 2, 'PCV13 1', 'Injection', '2 mos', NULL, 'Pending', NULL, NULL),
(43, 2, '6in1 2', 'Injection', '3 mos', NULL, 'Pending', NULL, NULL),
(44, 2, 'Rotavirus 2', 'Oral', '3 mos', NULL, 'Pending', NULL, NULL),
(45, 2, 'PCV13 2', 'Injection', '4 mos', NULL, 'Pending', NULL, NULL),
(46, 2, '6in1 3', 'Injection', '5 mos', NULL, 'Pending', NULL, NULL),
(47, 2, 'Rotavirus 3', 'Oral', '5 mos', NULL, 'Pending', NULL, NULL),
(48, 2, 'PCV13 3, Flu 1', 'Injection', '6 mos', NULL, 'Pending', NULL, NULL),
(49, 2, 'Flu 2', 'Injection', '7 mos', NULL, 'Pending', NULL, NULL),
(50, 2, 'Japanese Encephalitis 1', 'Injection', '9 mos', NULL, 'Pending', NULL, NULL),
(51, 2, 'Chicken Pox 1, MMR 1', 'Injection', '1 y/o', NULL, 'Pending', NULL, NULL),
(52, 2, 'HepA 1', 'Injection', '1 y/o', NULL, 'Pending', NULL, NULL),
(53, 2, '6in1 Booster 1', 'Injection', '1 y 6 mos', NULL, 'Pending', NULL, NULL),
(54, 2, 'PCV13 Booster', 'Injection', '1 y 6 mos', NULL, 'Pending', NULL, NULL),
(55, 2, 'HepA 2, Yearly Flu', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(56, 2, 'Meningococcal, Pneumo23', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(57, 2, 'Typhoid Vaccine 1', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(58, 2, 'Japanese Encephalitis 2, Flu', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(59, 2, 'Yearly Flu', 'Injection', '3 y/o', NULL, 'Pending', NULL, NULL),
(60, 2, 'Chicken Pox 2, MMR 2', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(61, 2, 'DT Booster 2', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(62, 2, 'Yearly Flu', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(63, 9, 'BCG Vaccine', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(64, 9, 'Hepatitis B Vaccine', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(65, 9, 'Penta (DPT-HepB-HiB) 1st Dose', NULL, '1.5 Months', NULL, 'Pending', NULL, NULL),
(66, 9, 'Oral Polio Vaccine (OPV) 1st Dose', NULL, '1.5 Months', NULL, 'Pending', NULL, NULL),
(67, 9, 'Pneumococcal Conjugate Vaccine (PCV) 1st Dose', NULL, '1.5 Months', NULL, 'Pending', NULL, NULL),
(68, 9, 'Penta (DPT-HepB-HiB) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(69, 9, 'Oral Polio Vaccine (OPV) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(70, 9, 'Pneumococcal Conjugate Vaccine (PCV) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(71, 9, 'Penta (DPT-HepB-HiB) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(72, 9, 'Oral Polio Vaccine (OPV) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(73, 9, 'Inactivated Polio Vaccine (IPV)', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(74, 9, 'Pneumococcal Conjugate Vaccine (PCV) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(75, 9, 'Measles, Mumps, Rubella (MMR) 1st Dose', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(76, 9, 'Measles, Mumps, Rubella (MMR) 2nd Dose', NULL, '1 Year', NULL, 'Pending', NULL, NULL),
(77, 10, 'BCG Vaccine', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-23'),
(78, 10, 'Hepatitis B Vaccine', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-23'),
(79, 10, 'Penta (DPT-HepB-HiB) 1st Dose', NULL, '1.5 Months', NULL, 'Completed', NULL, '2026-05-23'),
(80, 10, 'Oral Polio Vaccine (OPV) 1st Dose', NULL, '1.5 Months', NULL, 'Completed', NULL, '2026-05-23'),
(81, 10, 'Pneumococcal Conjugate Vaccine (PCV) 1st Dose', NULL, '1.5 Months', NULL, 'Pending', NULL, NULL),
(82, 10, 'Penta (DPT-HepB-HiB) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(83, 10, 'Oral Polio Vaccine (OPV) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(84, 10, 'Pneumococcal Conjugate Vaccine (PCV) 2nd Dose', NULL, '2.5 Months', NULL, 'Pending', NULL, NULL),
(85, 10, 'Penta (DPT-HepB-HiB) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(86, 10, 'Oral Polio Vaccine (OPV) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(87, 10, 'Inactivated Polio Vaccine (IPV)', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(88, 10, 'Pneumococcal Conjugate Vaccine (PCV) 3rd Dose', NULL, '3.5 Months', NULL, 'Pending', NULL, NULL),
(89, 10, 'Measles, Mumps, Rubella (MMR) 1st Dose', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(90, 10, 'Measles, Mumps, Rubella (MMR) 2nd Dose', NULL, '1 Year', NULL, 'Pending', NULL, NULL),
(91, 3, 'HepB1 and BCG', 'Injection', 'At Birth', NULL, 'Pending', NULL, NULL),
(92, 3, '6in1 1 (DTP1, iPV1, HiB1 and HepB2)', 'Injection', '1 1/2 mos', NULL, 'Pending', NULL, NULL),
(93, 3, 'Rotavirus 1', 'Oral', '1 1/2 mos', NULL, 'Pending', NULL, NULL),
(94, 3, 'PCV13 1', 'Injection', '2 mos', NULL, 'Pending', NULL, NULL),
(95, 3, '6in1 2', 'Injection', '3 mos', NULL, 'Pending', NULL, NULL),
(96, 3, 'Rotavirus 2', 'Oral', '3 mos', NULL, 'Pending', NULL, NULL),
(97, 3, 'PCV13 2', 'Injection', '4 mos', NULL, 'Pending', NULL, NULL),
(98, 3, '6in1 3', 'Injection', '5 mos', NULL, 'Pending', NULL, NULL),
(99, 3, 'Rotavirus 3', 'Oral', '5 mos', NULL, 'Pending', NULL, NULL),
(100, 3, 'PCV13 3, Flu 1', 'Injection', '6 mos', NULL, 'Pending', NULL, NULL),
(101, 3, 'Flu 2', 'Injection', '7 mos', NULL, 'Pending', NULL, NULL),
(102, 3, 'Japanese Encephalitis 1', 'Injection', '9 mos', NULL, 'Pending', NULL, NULL),
(103, 3, 'Chicken Pox 1, MMR 1', 'Injection', '1 y/o', NULL, 'Pending', NULL, NULL),
(104, 3, 'HepA 1', 'Injection', '1 y/o', NULL, 'Pending', NULL, NULL),
(105, 3, '6in1 Booster 1', 'Injection', '1 y 6 mos', NULL, 'Pending', NULL, NULL),
(106, 3, 'PCV13 Booster', 'Injection', '1 y 6 mos', NULL, 'Pending', NULL, NULL),
(107, 3, 'HepA 2, Yearly Flu', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(108, 3, 'Meningococcal, Pneumo23', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(109, 3, 'Typhoid Vaccine 1', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(110, 3, 'Japanese Encephalitis 2, Flu', 'Injection', '2 y/o', NULL, 'Pending', NULL, NULL),
(111, 3, 'Yearly Flu', 'Injection', '3 y/o', NULL, 'Pending', NULL, NULL),
(112, 3, 'Chicken Pox 2, MMR 2', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(113, 3, 'DT Booster 2', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(114, 3, 'Yearly Flu', 'Injection', '4 y/o', NULL, 'Pending', NULL, NULL),
(115, 11, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(116, 11, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(117, 11, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(118, 11, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(119, 11, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(120, 11, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(121, 11, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(122, 11, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(123, 11, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(124, 11, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(125, 4, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(126, 4, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(127, 4, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(128, 4, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(129, 4, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(130, 4, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(131, 4, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(132, 4, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(133, 4, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(134, 4, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(135, 12, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(136, 12, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(137, 12, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(138, 12, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(139, 12, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(140, 12, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(141, 12, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(142, 12, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(143, 12, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(144, 12, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(145, 5, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(146, 5, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(147, 5, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(148, 5, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(149, 5, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(150, 5, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(151, 5, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(152, 5, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(153, 5, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(154, 5, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(155, 13, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(156, 13, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(157, 13, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(158, 13, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(159, 13, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(160, 13, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(161, 13, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(162, 13, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(163, 13, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(164, 13, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(165, 14, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(166, 14, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(167, 14, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(168, 14, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(169, 14, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(170, 14, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(171, 14, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(172, 14, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(173, 14, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(174, 14, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(175, 15, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(176, 15, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(177, 15, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(178, 15, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(179, 15, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(180, 15, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(181, 15, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(182, 15, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(183, 15, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(184, 15, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(185, 16, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(186, 16, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(187, 16, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(188, 16, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(189, 16, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(190, 16, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(191, 16, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(192, 16, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(193, 16, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(194, 16, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(195, 0, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(196, 0, 'Penta 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(197, 0, 'OPV 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(198, 0, 'Penta 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(199, 0, 'OPV 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(200, 0, 'Penta 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(201, 0, 'OPV 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(202, 0, 'IPV', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(203, 0, 'MMR 1', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(204, 0, 'MMR 2', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(205, 18, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-24'),
(206, 18, 'Penta 1', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(207, 18, 'OPV 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(208, 18, 'Penta 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(209, 18, 'OPV 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(210, 18, 'Penta 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(211, 18, 'OPV 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(212, 18, 'IPV', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(213, 18, 'MMR 1', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(214, 18, 'MMR 2', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(215, 19, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-24'),
(216, 19, 'Penta 1', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(217, 19, 'OPV 1', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(218, 19, 'Penta 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(219, 19, 'OPV 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(220, 19, 'Penta 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(221, 19, 'OPV 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(222, 19, 'IPV', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(223, 19, 'MMR 1', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(224, 19, 'MMR 2', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(225, 6, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, '', NULL, NULL),
(226, 6, 'Penta 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(227, 6, 'OPV 1', NULL, '6 Weeks', NULL, '', NULL, NULL),
(228, 6, 'Penta 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(229, 6, 'OPV 2', NULL, '10 Weeks', NULL, '', NULL, NULL),
(230, 6, 'Penta 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(231, 6, 'OPV 3', NULL, '14 Weeks', NULL, '', NULL, NULL),
(232, 6, 'IPV', NULL, '14 Weeks', NULL, '', NULL, NULL),
(233, 6, 'MMR 1', NULL, '9 Months', NULL, '', NULL, NULL),
(234, 6, 'MMR 2', NULL, '12 Months', NULL, '', NULL, NULL),
(235, 20, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-24'),
(236, 20, 'Penta 1', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(237, 20, 'OPV 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(238, 20, 'Penta 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(239, 20, 'OPV 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(240, 20, 'Penta 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(241, 20, 'OPV 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(242, 20, 'IPV', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(243, 20, 'MMR 1', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(244, 20, 'MMR 2', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(245, 22, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(246, 22, 'Penta 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(247, 22, 'OPV 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(248, 22, 'Penta 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(249, 22, 'OPV 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(250, 22, 'Penta 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(251, 22, 'OPV 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(252, 22, 'IPV', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(253, 22, 'MMR 1', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(254, 22, 'MMR 2', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(255, 23, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(256, 23, 'Penta 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(257, 23, 'OPV 1', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(258, 23, 'Penta 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(259, 23, 'OPV 2', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(260, 23, 'Penta 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(261, 23, 'OPV 3', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(262, 23, 'IPV', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(263, 23, 'MMR 1', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(264, 23, 'MMR 2', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(265, 24, 'BCG (Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-24'),
(266, 24, 'Penta 1', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(267, 24, 'OPV 1', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(268, 24, 'Penta 2', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(269, 24, 'OPV 2', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(270, 24, 'Penta 3', NULL, '14 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(271, 24, 'OPV 3', NULL, '14 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(272, 24, 'IPV', NULL, '14 Weeks', NULL, 'Completed', NULL, '2026-05-24'),
(273, 24, 'MMR 1', NULL, '9 Months', NULL, 'Completed', NULL, '2026-05-24'),
(274, 24, 'MMR 2', NULL, '12 Months', NULL, 'Completed', NULL, '2026-05-24'),
(275, 25, 'BCG (Anti-Tuberculosis)', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(276, 25, 'Hepatitis B (Birth Dose)', NULL, 'At Birth', NULL, 'Pending', NULL, NULL),
(277, 25, 'Pentavalent 1 (DPT-HepB-HiB)', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(278, 25, 'OPV 1 (Oral Polio)', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(279, 25, 'PCV 1 (Pneumococcal)', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(280, 25, 'Pentavalent 2 (DPT-HepB-HiB)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(281, 25, 'OPV 2 (Oral Polio)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(282, 25, 'PCV 2 (Pneumococcal)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(283, 25, 'Pentavalent 3 (DPT-HepB-HiB)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(284, 25, 'OPV 3 (Oral Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(285, 25, 'IPV (Inactivated Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(286, 25, 'PCV 3 (Pneumococcal)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(287, 25, 'MMR 1 (Measles, Mumps, Rubella)', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(288, 25, 'MMR 2 (Measles, Mumps, Rubella)', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(289, 27, 'BCG (Anti-Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-27'),
(290, 27, 'Hepatitis B (Birth Dose)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-27'),
(291, 27, 'Pentavalent 1 (DPT-HepB-HiB)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-27'),
(292, 27, 'OPV 1 (Oral Polio)', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(293, 27, 'PCV 1 (Pneumococcal)', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(294, 27, 'Pentavalent 2 (DPT-HepB-HiB)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(295, 27, 'OPV 2 (Oral Polio)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(296, 27, 'PCV 2 (Pneumococcal)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(297, 27, 'Pentavalent 3 (DPT-HepB-HiB)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(298, 27, 'OPV 3 (Oral Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(299, 27, 'IPV (Inactivated Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(300, 27, 'PCV 3 (Pneumococcal)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(301, 27, 'MMR 1 (Measles, Mumps, Rubella)', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(302, 27, 'MMR 2 (Measles, Mumps, Rubella)', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(303, 28, 'BCG (Anti-Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-28'),
(304, 28, 'Hepatitis B (Birth Dose)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-28'),
(305, 28, 'Pentavalent 1 (DPT-HepB-HiB)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-28'),
(306, 28, 'OPV 1 (Oral Polio)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-28'),
(307, 28, 'PCV 1 (Pneumococcal)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-28'),
(308, 28, 'Pentavalent 2 (DPT-HepB-HiB)', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-28'),
(309, 28, 'OPV 2 (Oral Polio)', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-28'),
(310, 28, 'PCV 2 (Pneumococcal)', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-28'),
(311, 28, 'Pentavalent 3 (DPT-HepB-HiB)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(312, 28, 'OPV 3 (Oral Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(313, 28, 'IPV (Inactivated Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(314, 28, 'PCV 3 (Pneumococcal)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(315, 28, 'MMR 1 (Measles, Mumps, Rubella)', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(316, 28, 'MMR 2 (Measles, Mumps, Rubella)', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(317, 29, 'BCG (Anti-Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-28'),
(318, 29, 'Hepatitis B (Birth Dose)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-28'),
(319, 29, 'Pentavalent 1 (DPT-HepB-HiB)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(320, 29, 'OPV 1 (Oral Polio)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(321, 29, 'PCV 1 (Pneumococcal)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(322, 29, 'Pentavalent 2 (DPT-HepB-HiB)', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(323, 29, 'OPV 2 (Oral Polio)', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(324, 29, 'PCV 2 (Pneumococcal)', NULL, '10 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(325, 29, 'Pentavalent 3 (DPT-HepB-HiB)', NULL, '14 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(326, 29, 'OPV 3 (Oral Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(327, 29, 'IPV (Inactivated Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(328, 29, 'PCV 3 (Pneumococcal)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(329, 29, 'MMR 1 (Measles, Mumps, Rubella)', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(330, 29, 'MMR 2 (Measles, Mumps, Rubella)', NULL, '12 Months', NULL, 'Pending', NULL, NULL),
(331, 30, 'BCG (Anti-Tuberculosis)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-29'),
(332, 30, 'Hepatitis B (Birth Dose)', NULL, 'At Birth', NULL, 'Completed', NULL, '2026-05-29'),
(333, 30, 'Pentavalent 1 (DPT-HepB-HiB)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(334, 30, 'OPV 1 (Oral Polio)', NULL, '6 Weeks', NULL, 'Completed', NULL, '2026-05-29'),
(335, 30, 'PCV 1 (Pneumococcal)', NULL, '6 Weeks', NULL, 'Pending', NULL, NULL),
(336, 30, 'Pentavalent 2 (DPT-HepB-HiB)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(337, 30, 'OPV 2 (Oral Polio)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(338, 30, 'PCV 2 (Pneumococcal)', NULL, '10 Weeks', NULL, 'Pending', NULL, NULL),
(339, 30, 'Pentavalent 3 (DPT-HepB-HiB)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(340, 30, 'OPV 3 (Oral Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(341, 30, 'IPV (Inactivated Polio)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(342, 30, 'PCV 3 (Pneumococcal)', NULL, '14 Weeks', NULL, 'Pending', NULL, NULL),
(343, 30, 'MMR 1 (Measles, Mumps, Rubella)', NULL, '9 Months', NULL, 'Pending', NULL, NULL),
(344, 30, 'MMR 2 (Measles, Mumps, Rubella)', NULL, '12 Months', NULL, 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `status` enum('In Stock','Unavailable') DEFAULT 'In Stock',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_name`, `category`, `status`, `updated_at`) VALUES
(1, 'Amoxicillin', 'Anti-infectious', 'In Stock', '2026-05-26 01:32:03'),
(2, 'Paracetamol', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:32:03'),
(3, 'Salbutamol', 'Anti-asthma and COPD', 'In Stock', '2026-05-26 01:41:45'),
(4, 'Albendazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(5, 'Amoxicillin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(6, 'Azithromycin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(7, 'Cefixime', 'Anti-infectious', 'Unavailable', '2026-05-26 01:55:47'),
(8, 'Cefuroxime', 'Anti-infectious', 'Unavailable', '2026-05-26 01:55:47'),
(9, 'Ciprofloxacin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(10, 'Clarithromycin', 'Anti-infectious', 'Unavailable', '2026-05-26 01:55:46'),
(11, 'Clindamycin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(12, 'Clotrimazole', 'Anti-infectious', 'Unavailable', '2026-05-26 01:55:48'),
(13, 'Cloxacillin', 'Anti-infectious', 'Unavailable', '2026-05-26 01:55:49'),
(14, 'Co-amoxiclav', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(15, 'Co-trimoxazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(16, 'Doxycycline', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(17, 'Erythromycin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(18, 'Fluconazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(19, 'Ketoconazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(20, 'Mebendazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(21, 'Metronidazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(22, 'Nitrofurantoin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(23, 'Oseltamivir', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(24, 'Tobramycin', 'Anti-infectious', 'In Stock', '2026-05-26 01:38:27'),
(25, 'Clopidogrel', 'Anti-thrombotics', 'In Stock', '2026-05-26 01:38:27'),
(26, 'Aspirin', 'Anti-thrombotics', 'In Stock', '2026-05-26 01:38:27'),
(27, 'Budesonide + Formoterol', 'Anti-asthma and COPD', 'In Stock', '2026-05-26 01:55:42'),
(28, 'Fluticasone + Salmeterol', 'Anti-asthma and COPD', 'In Stock', '2026-05-26 01:55:42'),
(29, 'Ipratropium', 'Anti-asthma and COPD', 'Unavailable', '2026-05-29 05:59:03'),
(30, 'Montelukast', 'Anti-asthma and COPD', 'In Stock', '2026-05-28 06:29:44'),
(31, 'Prednisone', 'Anti-asthma and COPD', 'In Stock', '2026-05-26 01:55:40'),
(32, 'Salbutamol', 'Anti-asthma and COPD', 'In Stock', '2026-05-26 01:38:27'),
(33, 'Ipratropium + Salbutamol', 'Anti-asthma and COPD', 'Unavailable', '2026-05-29 05:59:04'),
(34, 'Tiotropium', 'Anti-asthma and COPD', 'In Stock', '2026-05-26 01:38:27'),
(35, 'Aluminum Hydroxide + Magnesium Hydroxide', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(36, 'Butamirate', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(37, 'Celecoxib', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(38, 'Cetirizine', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(39, 'Colchicine', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(40, 'Chlorphenamine', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(41, 'Diphenhydramine', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(42, 'Ferrous Salt (Iron Preparations)', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(43, 'Folic acid + Iron Ferrous', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(44, 'Ibuprofen', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(45, 'Lagundi (Vitex Negundo)', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(46, 'Loratadine', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(47, 'Mefenamic Acid', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(48, 'Naproxen', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(49, 'Omeprazole', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(50, 'Oral Rehydration Salts', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(51, 'Paracetamol', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(52, 'Zinc', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:38:27'),
(53, 'Dapagliflozin', 'Anti-diabetics', 'Unavailable', '2026-05-26 01:55:43'),
(54, 'Gliclazide', 'Anti-diabetics', 'Unavailable', '2026-05-28 06:29:42'),
(55, 'Metformin', 'Anti-diabetics', 'Unavailable', '2026-05-28 06:29:41'),
(56, 'Atorvastatin', 'Anti-dyslipidemia', 'In Stock', '2026-05-26 01:38:27'),
(57, 'Fenofibrate', 'Anti-dyslipidemia', 'Unavailable', '2026-05-26 01:55:44'),
(58, 'Rosuvastatin', 'Anti-dyslipidemia', 'Unavailable', '2026-05-28 06:25:37'),
(59, 'Simvastatin', 'Anti-dyslipidemia', 'In Stock', '2026-05-26 01:38:27'),
(60, 'Amlodipine', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(61, 'Atenolol', 'Anti-hypertensive and Cardiology', 'Unavailable', '2026-05-29 06:08:54'),
(62, 'Captopril', 'Anti-hypertensive and Cardiology', 'Unavailable', '2026-05-29 06:08:53'),
(63, 'Clonidine', 'Anti-hypertensive and Cardiology', 'Unavailable', '2026-05-29 06:08:55'),
(64, 'Diltiazem', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(65, 'Enalapril', 'Anti-hypertensive and Cardiology', 'Unavailable', '2026-05-26 01:55:45'),
(66, 'Enalapril + Hydrochlorothiazide', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(67, 'Hydrochlorothiazide', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(68, 'Isosorbide Dinitrate', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(69, 'Isosorbide Mononitrate', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(70, 'Losartan', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(71, 'Methyldopa', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(72, 'Metoprolol', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(73, 'Tamsulosin', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(74, 'Telmisartan', 'Anti-hypertensive and Cardiology', 'Unavailable', '2026-05-26 01:55:53'),
(75, 'Telmisartan + Hydrochlorothiazide', 'Anti-hypertensive and Cardiology', 'Unavailable', '2026-05-26 01:55:52'),
(76, 'Valsartan', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(77, 'Valsartan + Hydrochlorothiazide', 'Anti-hypertensive and Cardiology', 'In Stock', '2026-05-26 01:38:27'),
(78, 'Gabapentin', 'Nervous System', 'In Stock', '2026-05-26 01:38:27'),
(79, 'Albendazole', 'Anti-infectious', 'In Stock', '2026-05-26 01:50:49'),
(80, 'Amoxicillin', 'Anti-infectious', 'In Stock', '2026-05-26 01:50:49'),
(81, 'Paracetamol', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:50:49'),
(82, 'Ibuprofen', 'Supportive/Other Therapy', 'In Stock', '2026-05-26 01:50:49'),
(83, 'Amlodipine', 'Anti-hypertensive & Cardiology', 'In Stock', '2026-05-26 01:50:49'),
(84, 'Metformin', 'Anti-diabetics', 'Unavailable', '2026-05-28 06:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_inventory`
--

CREATE TABLE `medicine_inventory` (
  `id` int(11) NOT NULL,
  `med_name` varchar(100) DEFAULT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_checkups`
--

CREATE TABLE `monthly_checkups` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `checkup_date` date DEFAULT NULL,
  `weight_kg` decimal(5,2) DEFAULT NULL,
  `height_cm` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `remarks` enum('Normal','Underweight','Overweight') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(20) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_number` varchar(20) NOT NULL,
  `patient_category` varchar(50) NOT NULL,
  `has_chronic_illness` tinyint(1) NOT NULL,
  `chronic_illness_details` text NOT NULL,
  `blood_type` varchar(10) NOT NULL,
  `allergies` text NOT NULL,
  `patient_id` int(11) GENERATED ALWAYS AS (`id`) VIRTUAL,
  `fullname` varchar(255) GENERATED ALWAYS AS (concat(`last_name`,', ',`first_name`)) VIRTUAL,
  `contactnumber` varchar(20) GENERATED ALWAYS AS (`contact_number`) VIRTUAL,
  `uid` varchar(10) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `contact_number`, `address`, `emergency_contact_name`, `emergency_contact_number`, `patient_category`, `has_chronic_illness`, `chronic_illness_details`, `blood_type`, `allergies`, `uid`, `parent_id`) VALUES
(26, 'Lando', 'Norris', '1956-02-22', 'Male', '09603045082', 'pala-o\\\\r\\\\npurok 6\\\\r\\\\niligan lanao del norte', 'KARL VINCENT REMO', '09603045082', 'Senior', 0, '', 'Unknown', '', '872655', NULL),
(28, 'Ethan Kendrick', 'Remo', '2020-02-27', 'Male', 'N/A', 'pala-o\\r\\npurok 6\\r\\niligan lanao del norte', 'KARL VINCENT REMO', '09603045082', 'Child', 0, '', 'O+', '', '895697', 18),
(29, 'Kimi', 'Antonelli', '2026-05-28', 'Male', '09603045082', 'TIBANGA ILIGAN\\r\\npurok 6', 'Toto Wolf', '09603045082', 'Child', 0, '', 'A+', '', '631435', 20),
(30, 'kevin', 'conception', '2026-05-29', 'Male', '09603045082', 'TIBANGA ILIGAN\\r\\npurok 6', 'KARL VINCENT REMO', '09603045082', 'Child', 0, '', 'A+', '', '378370', 20);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medication` varchar(255) DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `date_prescribed` date DEFAULT NULL,
  `time_prescribed` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffid` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `position` varchar(50) DEFAULT NULL,
  `contactnumber` varchar(20) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'client',
  `password` varchar(255) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffid`, `fullname`, `username`, `position`, `contactnumber`, `role`, `password`, `isactive`) VALUES
(7, 'karl vincent remo', 'karl', 'Doctor', '09603045082', 'staff', '$2y$10$g117OfiqztAOemBWxU96BuwlD8AF/PYAg6uLQS4Nt5B.bqVonEbci', 1),
(18, 'Ryan Remo', 'nayr', 'Patient', '0501604295', 'client', '$2y$10$EO/dqOhYle3F9DBwZHPQy.xu.BZOR9zh62.Ok4Pvx9LFrpZ3qhcHu', 1),
(19, 'Lewis Hamilton', 'LH', 'Doctor', '09603045082', 'staff', '$2y$10$SIijzC6uOYOL82nCjoLfJeyNdCZbvf3YBbKZQjsa.VqDF1uyQ0ve6', 1),
(20, 'Toto Wolf', 'Toto', 'Patient', '09603045082', 'client', '$2y$10$dP6Tqbt4bEdDtRkB9FyY..ZRIzU2Q2eWClNF2nM6KocoFT8LDrbwO', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `babies`
--
ALTER TABLE `babies`
  ADD PRIMARY KEY (`baby_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `health_records`
--
ALTER TABLE `health_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `immunization_records`
--
ALTER TABLE `immunization_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_inventory`
--
ALTER TABLE `medicine_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_checkups`
--
ALTER TABLE `monthly_checkups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `idx_patient_uid` (`uid`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `babies`
--
ALTER TABLE `babies`
  MODIFY `baby_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `health_records`
--
ALTER TABLE `health_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `immunization_records`
--
ALTER TABLE `immunization_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `medicine_inventory`
--
ALTER TABLE `medicine_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_checkups`
--
ALTER TABLE `monthly_checkups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `babies`
--
ALTER TABLE `babies`
  ADD CONSTRAINT `babies_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `staff` (`staffid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
