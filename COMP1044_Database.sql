-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2026 at 06:25 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `COMP1044_Database`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `internship_id` int(11) DEFAULT NULL,
  `assessor_id` int(11) DEFAULT NULL,
  `undertaking_projects` int(11) DEFAULT '0',
  `health_safety_requirements` int(11) DEFAULT '0',
  `knowledge` int(11) DEFAULT '0',
  `report` int(11) DEFAULT '0',
  `language_clarity` int(11) DEFAULT '0',
  `lifelong_activities` int(11) DEFAULT '0',
  `project_management` int(11) DEFAULT '0',
  `time_management` int(11) DEFAULT '0',
  `total_score` int(11) DEFAULT '0',
  `comments` varchar(255) DEFAULT ''''''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessment_id`, `internship_id`, `assessor_id`, `undertaking_projects`, `health_safety_requirements`, `knowledge`, `report`, `language_clarity`, `lifelong_activities`, `project_management`, `time_management`, `total_score`, `comments`) VALUES
(1, 1, 10001000, 9, 8, 7, 8, 10, 10, 13, 12, 77, 'Good basic knowledge'),
(2, 1, 10001001, 10, 10, 10, 10, 10, 13, 14, 15, 92, 'Excellent performance'),
(3, 2, 10001002, 9, 9, 9, 13, 9, 13, 14, 15, 91, 'Firm Foundation of Knowledge'),
(4, 2, 10001003, 7, 8, 8, 12, 9, 12, 13, 12, 81, 'Cooperative with coworkers ');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` varchar(255) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `person_in_charge` varchar(100) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `company_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `company_address`, `industry`, `person_in_charge`, `contact_no`, `company_email`) VALUES
(1, 'Nova Technology', '123 Innovation Way, San Francisco, CA', 'Technology', 'Sarah Jenkin', '+1-555-0102', 'info@novatech.com'),
(2, 'GreenLeaf Logistics', '4580 Industrial Pkwy, Chicago, IL', 'Supply Chain', 'Marcus Thorne', '+1-555-9876', 'ops@greenleaf.log'),
(3, 'Blue Horizon Media', '12 Ocean View Blvd, Miami, FL', 'Marketing', 'Elena Rodriguez', '+1-555-4433', 'hello@bluehorizon.co');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `internship_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `internal_assessor_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `current_status` enum('Ongoing','Completed') DEFAULT NULL,
  `external_assessor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `internships`
--

INSERT INTO `internships` (`internship_id`, `student_id`, `internal_assessor_id`, `company_id`, `startDate`, `endDate`, `duration`, `current_status`, `external_assessor_id`) VALUES
(1, 20000001, 10001000, 1, '2026-04-24', '2026-07-24', 91, 'Ongoing', 10001001),
(2, 20000002, 10001002, 2, '2026-04-10', '2026-07-10', 91, 'Ongoing', 10001003);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` text,
  `gender` enum('Male','Female') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `faculty` enum('FOSE','FASS') DEFAULT NULL,
  `programme` text,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `emergency_contact_no` varchar(15) DEFAULT NULL,
  `emergency_contact_relation` varchar(100) DEFAULT NULL,
  `personal_email` varchar(100) DEFAULT NULL,
  `school_email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `gender`, `date_of_birth`, `faculty`, `programme`, `address`, `contact_no`, `emergency_contact_no`, `emergency_contact_relation`, `personal_email`, `school_email`) VALUES
(20000001, 'Chloe Tang', 'Female', '2005-12-21', 'FOSE', 'Mechanical Engineering', '12, Jalan Selamat, Taman Bahagia, Sabah, Malaysia', '+60123456789', '+60112345677', 'Mother', 'tang@email.com', 'hcabc123@nottingham.edu.my'),
(20000002, 'Alex Tan Wei Kiat', 'Male', '2004-07-31', 'FASS', 'Business, Finance and Accounts', '12, Jalan Sentosa, Taman Cerdik, Kuala Terengganu, Terengganu, Malaysia', '+60123456897', '+60112345689', 'Mother', 'alex.tan88@gmail.com', 'hfyabc12@nottingham.edu.my'),
(20000003, 'Alander Mok', 'Male', '2006-05-08', 'FOSE', 'Psychology', '67, Jalan Adil, Taman Betul, Kajang, Malaysia ', '+60123456788', '+60112345699', 'Mother', 'alander.mok@email.com', 'hfycde12@nottingham.edu.my');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `role` enum('Admin','Assessor','Student') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `organization` varchar(256) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `name`, `role`, `email`, `organization`, `creation_date`) VALUES
(10000001, '$2y$12$tODoIfLopbxKABZhW1kMH.R4AmVcfp.0uv7XJI8ZeKXbFAzvJv8Kq', 'Poong Zhi Yong', 'Admin', 'admin@nottingham.edu.my', 'University of Nottingham Malaysia', '2026-04-24 05:59:55'),
(10001000, '$2y$10$qPtD.ps349duAFwzFU.aNuxmWo7JKtdT3uB5NkmOvxnJJaeE/fIFW', 'Sai Jia En', 'Assessor', 'jiaen.sai@nottingham.edu.my', 'University of Nottingham', '2026-04-24 06:02:10'),
(10001001, '$2y$10$D4io3xPeks.aTMa9lMudBOKzdUQWr8KkarD.tNWujwdzFLkzWNYaO', 'Sophia Lee', 'Assessor', 'sophia.lee@sunway.edu.my', 'Sunway University', '2026-04-24 06:03:12'),
(10001002, '$2y$10$ElpA4yWq7yZo3B4VJ8oLvOLNb5WIihG4749C5tZWoEnHvEOoYn1Ba', 'New Esuan', 'Assessor', 'esuan.new@nottingham.edu.my', 'University of Nottingham Malaysia', '2026-04-24 06:16:21'),
(10001003, '$2y$10$JphBc3.dQnu0pnbE.LcJ2ebp7Wqhgv4pOFNeTrvKptQaIy9wDogx.', 'Wong Zhi San', 'Assessor', 'zhisan.wong@oneacademy.edu.my', 'The One Academy', '2026-04-24 06:17:14'),
(20000001, '$2y$12$UCmzv/gR.DypQBhnaBoDduP0i6E0rB7Btp.qfuclVbG4OGdmOv8yi', 'Chloe Tang', 'Student', 'hcabc123@nottingham.edu.my', 'University of Nottingham Malaysia', '2026-04-24 06:06:51'),
(20000002, '$2y$12$cXEkagWboJIio7jknCZw.OS0hSDF8NKNhOTN686qpJmewJEFTu/m2', 'Alex Tan Wei Kiat', 'Student', 'hfyabc12@nottingham.edu.my', 'University of Nottingham Malaysia', '2026-04-24 06:08:49'),
(20000003, '$2y$12$xnW5F2cjlRUogT8ArjfuNeW2NPprgw425rPUHvSJ91dv0nvp6pbe6', 'Alander Mok', 'Student', 'hfycde12@nottingham.edu.my', 'University of Nottingham Malaysia', '2026-04-24 06:10:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `internship_id` (`internship_id`),
  ADD KEY `fk_assessor_id` (`assessor_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`internship_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `assessor_id` (`internal_assessor_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `fk_external_assessor_id` (`external_assessor_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `passwords` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `internship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`internship_id`),
  ADD CONSTRAINT `fk_assessor_id` FOREIGN KEY (`assessor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `fk_external_assessor_id` FOREIGN KEY (`external_assessor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `internships_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `internships_ibfk_2` FOREIGN KEY (`internal_assessor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `internships_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
