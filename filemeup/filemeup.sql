-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 08, 2021 at 11:27 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `filemeup`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `folderID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `type`, `userID`, `folderID`) VALUES
(1, 'tempFile.txt', 'txt', NULL, NULL),
(3, 'tryName', 'txt', NULL, NULL),
(4, 'IMG_0185 2.jpg', 'jpg', NULL, NULL),
(5, 'IMG_2944.jpg', 'jpg', NULL, NULL),
(6, 'IMG_1093.jpg', 'jpg', NULL, NULL),
(7, 'IMG_2944.jpg', 'jpg', NULL, NULL),
(9, 'IMG_2944.jpg', 'jpg', NULL, NULL),
(10, 'CV2.docx', 'docx', NULL, NULL),
(11, 'IMG_0185 2 (1).jpg', 'jpg', NULL, NULL),
(12, 'IMG_0185 2 (2).jpg', 'jpg', NULL, NULL),
(13, 'DS_Store (7)', 'DS_Store (7)', NULL, NULL),
(14, 'IMG_0185 2 (2).jpg', 'jpg', NULL, NULL),
(15, 'DS_Store (7)', 'DS_Store (7)', NULL, NULL),
(16, 'DS_Store (2)', 'DS_Store (2)', NULL, NULL),
(17, 'DS_Store (1)', 'DS_Store (1)', NULL, NULL),
(18, 'DS_Store (6)', 'DS_Store (6)', NULL, NULL),
(19, 'IMG_2944.jpg', 'jpg', NULL, NULL),
(20, 'filemeup.sql', 'sql', NULL, NULL),
(21, 'MISproj.pptx', 'pptx', NULL, NULL),
(22, 'IMG_0185 2 (2).jpg', 'jpg', NULL, NULL),
(23, 'АСИ-1-2.docx', 'docx', NULL, NULL),
(24, '', '', NULL, NULL),
(25, 'ReportIt-HW1-3.docx', 'docx', NULL, NULL),
(26, '', '', NULL, NULL),
(27, 'Todor_Dimitrov_-_IOS_Developer-2.docx', 'docx', NULL, NULL),
(28, 'TodorDimitrovResume 2.docx', 'docx', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `shared` tinyint(1) NOT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `folderID` (`folderID`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`folderID`) REFERENCES `folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
