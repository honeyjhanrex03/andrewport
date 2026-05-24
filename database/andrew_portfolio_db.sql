-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2026 at 04:29 AM
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
-- Database: `andrew_portfolio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$NxU6ICJBmm4aPhQpvf3yYunHebUhAdUwFhfBHSJ9vsixdW0plR8R6');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `issued_by` varchar(255) NOT NULL,
  `month` varchar(20) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `years` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `degree`, `institution`, `years`) VALUES
(1, 'Bachelor of Science in Information System', 'Davao del Norte State College', '2024 - Present'),
(2, 'ICT Strand (Junior High School)', 'Alejandra L. Navarro National High School', '2018 - 2024');

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `dates` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `title`, `company`, `dates`, `description`, `icon`) VALUES
(1, 'Encoder and Assistant (OJT)', 'Central Elementary School', 'March 1-15, 2024', 'Provided essential encoding and administrative assistance during the on-the-job training period.', 'fa-school'),
(2, 'Assistant', 'Water Refilling Station', 'August 1-5, 2021', 'Provided fresh water for everyone, ensuring smooth daily operations and excellent customer service.', 'fa-faucet-drip');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_views`
--

CREATE TABLE `page_views` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `page` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_views`
--

INSERT INTO `page_views` (`id`, `ip_address`, `page`, `created_at`) VALUES
(1, '::1', 'Home', '2026-05-24 00:41:04'),
(2, '::1', 'services.php', '2026-05-24 00:41:05'),
(3, '::1', 'about.php', '2026-05-24 00:41:07'),
(4, '::1', 'Home', '2026-05-24 00:46:30'),
(5, '::1', 'skills.php', '2026-05-24 00:46:32'),
(6, '::1', 'about.php', '2026-05-24 00:48:10'),
(7, '::1', 'skills.php', '2026-05-24 00:51:38'),
(8, '::1', 'Home', '2026-05-24 00:51:43'),
(9, '::1', 'services.php', '2026-05-24 00:53:10'),
(10, '::1', 'Home', '2026-05-24 01:20:21'),
(11, '::1', 'certificates.php', '2026-05-24 01:20:22'),
(12, '::1', 'skills.php', '2026-05-24 01:23:52'),
(13, '::1', 'services.php', '2026-05-24 01:23:54'),
(14, '::1', 'portfolio.php', '2026-05-24 01:23:54'),
(15, '::1', 'contact.php', '2026-05-24 01:23:55'),
(16, '::1', 'about.php', '2026-05-24 01:24:52'),
(17, '::1', 'certificates.php', '2026-05-24 01:29:53'),
(18, '::1', 'services.php', '2026-05-24 01:29:53'),
(19, '::1', 'portfolio.php', '2026-05-24 01:29:55'),
(20, '::1', 'skills.php', '2026-05-24 01:30:00'),
(21, '::1', 'Home', '2026-05-24 01:31:14'),
(22, '::1', 'about.php', '2026-05-24 01:33:17'),
(23, '::1', 'contact.php', '2026-05-24 01:33:22'),
(24, '::1', 'Home', '2026-05-24 01:37:24'),
(25, '::1', 'about.php', '2026-05-24 01:39:03'),
(26, '::1', 'certificates.php', '2026-05-24 01:41:55'),
(27, '::1', 'services.php', '2026-05-24 01:41:56'),
(28, '::1', 'skills.php', '2026-05-24 01:42:00'),
(29, '::1', 'Home', '2026-05-24 01:44:02'),
(30, '::1', 'about.php', '2026-05-24 01:45:28'),
(31, '::1', 'portfolio.php', '2026-05-24 01:45:43'),
(32, '::1', 'contact.php', '2026-05-24 01:45:44'),
(33, '::1', 'about.php', '2026-05-24 01:55:57'),
(34, '::1', 'certificates.php', '2026-05-24 01:55:58'),
(35, '::1', 'services.php', '2026-05-24 01:55:58'),
(36, '::1', 'portfolio.php', '2026-05-24 01:55:59'),
(37, '::1', 'contact.php', '2026-05-24 01:55:59'),
(38, '::1', 'Home', '2026-05-24 01:56:00'),
(39, '::1', 'contact.php', '2026-05-24 02:01:08'),
(40, '::1', 'Home', '2026-05-24 02:08:13'),
(41, '::1', 'about.php', '2026-05-24 02:08:23'),
(42, '::1', 'skills.php', '2026-05-24 02:08:25'),
(43, '::1', 'certificates.php', '2026-05-24 02:10:27'),
(44, '::1', 'Home', '2026-05-24 02:19:34'),
(45, '::1', 'about.php', '2026-05-24 02:19:36'),
(46, '::1', 'skills.php', '2026-05-24 02:19:36'),
(47, '::1', 'certificates.php', '2026-05-24 02:19:37'),
(48, '::1', 'services.php', '2026-05-24 02:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `tech_stack` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `project_date` varchar(50) DEFAULT NULL,
  `additional_images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `objective` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `years_experience` int(11) DEFAULT 2,
  `ojt_hours` int(11) DEFAULT 80,
  `profile_image` varchar(255) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `name`, `title`, `objective`, `phone`, `email`, `address`, `linkedin`, `years_experience`, `ojt_hours`, `profile_image`, `cv_path`) VALUES
(1, 'Rovic Andrew V. Bungalan', 'Information Systems Student', 'Enthusiastic information systems Bachelor of Science student with experience in data encoding, some knowledge of UI design in Figma and Microsoft Office applications. Possesses effective time management, communication and technical abilities from academic projects and work experience. Looking to use analytical and IT skills in a professional setting and to keep developing skills in Information Systems.', '09489632834', 'bungalanandrew707@gmail.com', 'Brgy. Lasang Davao City', 'https://www.linkedin.com/in/rovic-bungalan-4b132a409/', 2, 80, '/andrew/assets/images/andrew.jpg', '/andrew/resume/Bungalan_CV.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `references_list`
--

CREATE TABLE `references_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `references_list`
--

INSERT INTO `references_list` (`id`, `name`, `title`) VALUES
(1, 'Rosevale Tulayan', 'Teacher and Managing lab'),
(2, 'Albert Arriba', 'Owner of Water Refilling Station<br>Fb: Albert Arriba');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `icon_type` enum('class','image') NOT NULL DEFAULT 'class',
  `icon_image` varchar(255) DEFAULT NULL,
  `icon_color` varchar(20) DEFAULT '#ff6b57'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `icon`, `icon_type`, `icon_image`, `icon_color`) VALUES
(1, 'Data Encoding', 'Accurate and fast data entry and encoding services, honed through practical OJT experience at Central Elementary School.', 'fa-solid fa-keyboard', 'class', NULL, '#ffffff'),
(2, 'UI Design', 'Creating clean, intuitive, and modern user interfaces using Figma, applying basic UI design principles for web and mobile.', 'fa-brands fa-figma', 'class', NULL, '#F24E1E'),
(3, 'Administrative Support', 'Proficient in Microsoft Office applications to assist with professional documentation, organization, and daily tasks.', 'fa-brands fa-microsoft', 'class', NULL, '#00a4ef');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `icon_type` enum('class','image') NOT NULL DEFAULT 'class',
  `icon_value` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `level`, `icon_type`, `icon_value`) VALUES
(1, 'Figma Design', 95, 'image', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/figma/figma-original.svg'),
(2, 'UI Design', 90, 'image', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sketch/sketch-original.svg'),
(3, 'Data Encoding', 85, 'image', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'),
(4, 'MS Office', 80, 'image', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/windows8/windows8-original.svg'),
(5, 'Time Management', 100, 'image', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/jira/jira-original.svg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_views`
--
ALTER TABLE `page_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `references_list`
--
ALTER TABLE `references_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `page_views`
--
ALTER TABLE `page_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `references_list`
--
ALTER TABLE `references_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
