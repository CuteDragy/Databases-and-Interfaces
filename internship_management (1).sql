-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 23, 2026 at 06:25 AM
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
-- Database: `internship_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `assessment_id` int(11) NOT NULL,
  `internship_id` int(11) DEFAULT NULL,
  `assessor_id` int(11) DEFAULT NULL,
  `undertaking_projects` int(11) DEFAULT NULL,
  `health_safety_requirements` int(11) DEFAULT NULL,
  `knowledge` int(11) DEFAULT NULL,
  `report` int(11) DEFAULT NULL,
  `language_clarity` int(11) DEFAULT NULL,
  `lifelong_activities` int(11) DEFAULT NULL,
  `project_management` int(11) DEFAULT NULL,
  `time_management` int(11) DEFAULT NULL,
  `total_score` int(11) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`assessment_id`, `internship_id`, `assessor_id`, `undertaking_projects`, `health_safety_requirements`, `knowledge`, `report`, `language_clarity`, `lifelong_activities`, `project_management`, `time_management`, `total_score`, `comments`) VALUES
(1, 2, 12347, 8, 9, 9, 13, 9, 12, 13, 14, 87, 'Good collaboration and cooperation between coworkers. '),
(2, 2, 12346, 9, 9, 7, 13, 8, 13, 14, 13, 86, 'Firm basic knowledge regarding task given and good time allocation. ');

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
(3, 'Blue Horizon Media', '12 Ocean View Blvd, Miami, FL', 'Marketing', 'Elena Rodriguez', '+1-555-4433', 'hello@bluehorizon.co'),
(4, 'Soo Sdn. Bhd.', NULL, 'Cafe', 'SHJ', '+6012345678', 'hr@soocafe.com');

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
(1, 1001, 12347, 1, '2026-01-01', '2026-04-01', 90, 'Completed', 12346),
(2, 1002, 12347, 1, '2026-02-01', '2026-05-01', 89, 'Ongoing', 12346),
(3, 1003, 12346, 2, '2026-01-15', '2026-07-15', 181, 'Ongoing', 12347),
(4, 1234567, 12346, 1, '2026-03-01', '2026-03-31', 30, 'Ongoing', 12347);

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
(1001, 'Alex Tan Wei Kiat', 'Male', '2004-05-12', 'FOSE', 'BSc (Hons) Computer Science', '12, Jalan Ampang, 50450 Kuala Lumpur', '+60123456789', '+60129876543', 'Father', 'alex.tan88@gmail.com', 'tan.alex@student.edu.my'),
(1002, 'Sarah Jenkins', 'Female', '2003-11-28', 'FASS', 'BA (Hons) International Relations', 'A-15-03, Sky Condos, 47500 Subang Jaya', '+60176543210', '+60171112233', 'Mother', 'sarah.j@outlook.com', 'jenkins.sarah@student.edu.my'),
(1003, 'Sai Jia En', 'Female', '2006-10-09', 'FOSE', 'Bsc (Hons) Computer Science with Artificial Intelligence', '12, Jalan Selamat, Taman Bahagia, Johor Bahru', '+60123456789', '+0112345677', 'Daughter', 'cuttydragy@gmail.com', 'hfyhs12@nottingham.edu.my'),
(12351, 'Doreen', 'Female', '2026-04-23', 'FOSE', 'MBBS', 'University of Nottingham', '+60123456789', '+0112345677', 'Mother', 'doreen@yahoo.com', 'doreen@nottingham.edu.my'),
(1234567, 'Poong Zhi Yong', 'Male', '2006-02-04', 'FOSE', 'Social Science', 'Semenyih', '+966123456', '+0112345677', 'Mother', 'cuttydragy@gmail.com', 'hfyabc12@nottingham.edu.my');

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
(12345, '$2y$12$6GqGAZ8YC/xIPaItrhaHWunEFm8y9GNo4uwfY/9a/y1CAAxLeTKua', 'Poong Zhi Yong', 'Student', 'poong@gmail.com', 'University of  Nottingham Malaysia Campus ', '2026-03-27 01:57:42'),
(12346, 'abcdef', 'Jia En', 'Assessor', 'sai@nottingham.edu.my', 'University of Monash Malaysia', '2026-03-26 01:57:42'),
(12347, '12345abc', 'Ahmad', 'Assessor', 'ahmad@nottingham.edu.my', '', '2026-03-27 01:57:42'),
(12348, 'abcdefg', 'Alander Mok', 'Assessor', 'mok@nottingham.edu.my', 'University of Nottingham Malaysia Campus', '2026-04-02 06:57:47'),
(12350, '$2y$12$PetJQqOXth0KgZ4G22ZKPOrflXEK9.v.uc.gL29MR1aMDiGS3T4u.', 'Poong Zhi Yong', 'Assessor', 'cutydragy@edu.my', 'Sunway University', '2026-04-06 03:49:52'),
(12351, '$2y$12$nPGZufGqC8C9cAz1Qgjey.jR3xWMtDW8LpAXc0dlPDwBDyXKvrQKi', 'Doreen', 'Student', 'doreen@nottingham.edu.my', 'University of Nottingham Malaysia', '2026-04-23 04:26:00');

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
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `internship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
