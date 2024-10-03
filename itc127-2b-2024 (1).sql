-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 12:06 PM
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
-- Database: `itc127-2b-2024`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Usertype` varchar(20) NOT NULL,
  `Userstatus` varchar(20) NOT NULL,
  `CreatedBy` varchar(50) NOT NULL,
  `Datecreated` varchar(20) NOT NULL,
  `Email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`Username`, `Password`, `Usertype`, `Userstatus`, `CreatedBy`, `Datecreated`, `Email`) VALUES
('22-0123', 'arellano1938', 'STUDENT', 'ACTIVE', 'admin', '04/24/2024', NULL),
('23-01402', 'arellano1938', 'STUDENT', 'ACTIVE', 'admin', '04/24/2024', NULL),
('admin', '12345', 'ADMINISTRATOR', 'ACTIVE', 'admin', '09/03/2024', NULL),
('registrar', '12345', 'REGISTRAR', 'ACTIVE', 'admin', '04/11/2024', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblgrades`
--

CREATE TABLE `tblgrades` (
  `studentnumber` varchar(50) NOT NULL,
  `subjectcode` varchar(50) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `encodedby` varchar(50) NOT NULL,
  `dateencoded` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `datelog` varchar(15) NOT NULL,
  `timelog` varchar(15) NOT NULL,
  `action` varchar(20) NOT NULL,
  `module` varchar(20) NOT NULL,
  `ID` varchar(30) NOT NULL,
  `performedby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `studentnumber` varchar(20) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `middlename` varchar(40) NOT NULL,
  `course` varchar(50) NOT NULL,
  `yearlevel` varchar(20) NOT NULL,
  `createdby` varchar(30) NOT NULL,
  `datecreated` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`studentnumber`, `lastname`, `firstname`, `middlename`, `course`, `yearlevel`, `createdby`, `datecreated`) VALUES
('22-0123', 'Pilapil', 'Arjay', 'T', 'Bachelor of Science in Computer Science', '2ND YEAR', 'admin', '04/24/2024'),
('23-01402', 'Alcantara', 'Anthony', 'A', 'Bachelor of Science in Computer Science', '2ND YEAR', 'admin', '04/24/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `subjectcode` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL,
  `prerequisite1` varchar(20) NOT NULL,
  `prerequisite2` varchar(50) NOT NULL,
  `prerequisite3` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`subjectcode`, `description`, `unit`, `course`, `createdby`, `datecreated`, `prerequisite1`, `prerequisite2`, `prerequisite3`) VALUES
('CS 210', 'Discrete Mathematics 1', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', '', '', ''),
('CS 211', 'Object Oriented Programming', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', 'ITC 120', '', ''),
('CS 221', 'Digital Design and Electronics', '3', 'Bachelor of Science in Computer Science', 'admin', '04/25/2024', 'ITC 110', 'CS 210', ''),
('CS 222', 'Computer Architecture', '3', 'Bachelor of Science in Computer Science', 'admin', '04/24/2024', 'ITC 110', 'CS 210', ''),
('CS 223', 'Discrete Mathematics 2', '3', 'Bachelor of Science in Computer Science', 'admin', '04/24/2024', 'CS 210', '', ''),
('CS 224', 'Networks and Communication', '3', 'Bachelor of Science in Computer Science', 'admin', '04/25/2024', 'ITC 110', 'ITC 124', ''),
('ITC 110', 'Introduction to Computing', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', '', '', ''),
('ITC 111', 'Computer Programming 1', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', '', '', ''),
('ITC 112', 'Intro to Graphics and design', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', '', '', ''),
('ITC 120', 'Computer Programming 2', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', 'ITC 111', '', ''),
('ITC 121', 'Introduction to Web Design', '3', 'Bachelor of Science in Computer Science', 'admin', '04/24/2024', 'ITC 112', '', ''),
('ITC 122', 'Operating System', '3', 'Bachelor of Science in Computer Science', 'admin', '04/24/2024', 'ITC 110', 'ITC 111', ''),
('ITC 123', 'Applications Dev\'t and Emerging Tech', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', 'ITC 122', '', ''),
('ITC 124', 'Fundamentals of Database System', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', 'ITC 120', '', ''),
('ITC 125', 'Data Structures and Algorithm', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', 'ITC 120', '', ''),
('ITC 126', 'Information Management', '3', 'Bachelor of Science in Computer Science', 'admin', '05/02/2024', 'ITC 124', '', ''),
('ITC 127', 'Advance Database Management', '3', 'Bachelor of Science in Computer Science', 'admin', '04/24/2024', 'ITC 124', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`studentnumber`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`subjectcode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
