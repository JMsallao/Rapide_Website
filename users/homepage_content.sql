-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 04:38 PM
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
-- Database: `rapide_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `homepage_content`
--

CREATE TABLE `homepage_content` (
  `id` int(11) NOT NULL,
  `subhead` text NOT NULL,
  `heading` text NOT NULL,
  `description` text NOT NULL,
  `button_text` varchar(100) NOT NULL,
  `button_link` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homepage_content`
--

INSERT INTO `homepage_content` (`id`, `subhead`, `heading`, `description`, `button_text`, `button_link`, `type`) VALUES
(1, '', 'We Provide Automotive Services That You Can Trust', 'Rapide\'s quality assurance can be summed up in these words: CASA- quality services at affordable prices.', 'Get Appointment', '../booking/customerAlwaysRight/service_list.php', 'slider'),
(2, 'Emergency Towing', 'Emergency cases', 'Need immediate help? We\'re available for towing and repairs during emergencies.', 'LEARN MORE', '../map/emap.php', 'schedule'),
(3, 'Schedule', 'Expected Schedule', 'Book ur appointment at a time that works best for you.', 'LEARN MORE', '../booking/customerAlwaysRight/service_list.php', 'schedule'),
(4, 'Time', 'Opening Hours', 'Check our operating hours for your convenience.', 'LEARN MORE', '#', 'schedule'),
(5, '3', 'Branches', '', '', '', 'stats'),
(6, '50', 'Workers', '', '', '', 'stats'),
(7, '4379', 'Happy Client', '', '', '', 'stats'),
(8, '20', 'Years of experience', '', '', '', 'stats'),
(9, '', 'We Maintain Cleanliness Rules Inside Our Shop', 'We keep our shop clean and follow hygiene rules to make it safe and comfortable for everyone.', '', '', 'clean'),
(10, '', 'We Provide a Variety of Services to Keep Your Car in Top Condition', 'From preventive maintenance to specialized repairs, our services are designed to keep your car running smoothly and safely.', '', '', 'general'),
(11, '', 'PMS Package', 'A comprehensive Preventive Maintenance Service to keep your vehicle running smoothly and avoid unexpected issues.', '', '', 'service'),
(12, '', 'Periodic Service', 'Regular check-ups and servicing to ensure your vehicle’s optimal performance and longevity.', '', '', 'service'),
(13, '', 'AC Services & Repair', 'Inspection, maintenance, and repair of your car’s air conditioning system for comfort during your drive.', '', '', 'service'),
(14, '', 'Brakes Services', 'Professional brake inspections, repairs, and replacements to ensure your safety on the road.', '', '', 'service');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `homepage_content`
--
ALTER TABLE `homepage_content`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `homepage_content`
--
ALTER TABLE `homepage_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
