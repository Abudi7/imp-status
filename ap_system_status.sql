-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2023 at 03:59 PM
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
-- Database: `ap_system_status`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230504130458', '2023-05-04 15:05:16', 49),
('DoctrineMigrations\\Version20230505074713', '2023-05-05 09:47:24', 30),
('DoctrineMigrations\\Version20230505074905', '2023-05-05 09:49:10', 19),
('DoctrineMigrations\\Version20230508112526', '2023-05-08 13:25:43', 173),
('DoctrineMigrations\\Version20230508124322', '2023-05-08 14:43:32', 21),
('DoctrineMigrations\\Version20230509130038', '2023-05-10 10:30:07', 20);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`, `created_at`) VALUES
(1, 'Available', '2023-05-05 10:04:38'),
(2, 'In progress', '2023-05-05 10:04:38'),
(3, 'Down', '2023-05-05 10:08:55'),
(4, 'Update', '2023-05-05 10:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` int(11) NOT NULL,
  `is_subscribed` tinyint(1) NOT NULL,
  `system_status_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`id`, `is_subscribed`, `system_status_id`, `user_id`) VALUES
(77, 1, 17, 1),
(78, 1, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`id`, `name`, `created_at`) VALUES
(1, 'Email (Outlook)', '2023-05-05 09:54:10'),
(2, 'SAP ERP', '2023-05-05 09:54:40'),
(3, 'SAP CRM', '2023-05-05 09:55:07'),
(4, 'Teamcity', '2023-05-05 10:09:40'),
(5, 'Windchill', '2023-05-05 10:09:40'),
(6, 'Document Management System (DMS)', '2023-05-05 10:10:06'),
(7, 'Jira', '2023-05-05 10:10:06'),
(8, 'Academy (LMS)', '2023-05-05 12:22:00'),
(9, 'IT Shop (Graz)', '2023-05-05 12:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_status`
--

CREATE TABLE `system_status` (
  `id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `responsible_person` varchar(255) NOT NULL,
  `system_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_status`
--

INSERT INTO `system_status` (`id`, `status_id`, `created_at`, `updated_at`, `responsible_person`, `system_id`) VALUES
(10, 3, '2023-05-05 10:27:00', '2023-05-09 11:36:20', 'David Schaller', 2),
(11, 3, '2023-05-05 11:55:55', '2023-05-05 11:55:55', 'Markus Happer', 5),
(12, 2, '2023-05-08 11:57:23', '2023-05-10 10:17:51', 'Abud', 5),
(13, 2, '2023-05-09 09:32:12', '2023-05-09 11:41:23', 'David Schaller', 7),
(14, 3, '2023-05-09 09:32:38', '2023-05-08 09:32:38', 'Markus Happer', 9),
(15, 2, '2023-05-09 12:12:10', '2023-05-09 13:28:16', 'abud@anton-paar.com', 4),
(17, 1, '2023-05-09 13:17:28', '2023-05-09 13:17:28', 'abud@anton-paar.com', 6),
(18, 1, '2023-05-10 13:07:29', '2023-05-10 13:07:29', 'abud@anton-paar.com', 8),
(19, 1, '2023-05-10 15:13:09', '2023-05-10 15:13:09', 'abud@anton-paar.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tamplate`
--

CREATE TABLE `tamplate` (
  `id` int(11) NOT NULL,
  `maintenance` longtext DEFAULT NULL,
  `incident` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `maintenance` longtext DEFAULT NULL,
  `incident` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`id`, `maintenance`, `incident`) VALUES
(1, 'Subject: Maintenance Notification - [System Name]\r\n\r\nDear [Recipient Name],\r\n\r\nThis email is to inform you that we will be performing maintenance on [System Name] on [Maintenance Start Date and Time] to [Maintenance End Date and Time]. During this time, the system will not be accessible.\r\n\r\nThe maintenance is necessary to ensure that the system remains secure, stable and available for use. We apologize for any inconvenience this may cause.\r\n\r\nIf you have any questions or concerns, please contact us at [Contact Email].\r\n\r\nThank you for your cooperation.\r\n\r\nSincerely,\r\n[Your Name]\r\n[Your Organization]', 'Subject: [Incident] {{ system_name }} - {{ incident_type }}\r\n\r\nHello,\r\n\r\nThis is to inform you that an incident has been reported for {{ system_name }}. The details of the incident are as follows:\r\n\r\nType of Incident: {{ incident_type }}\r\n\r\nDescription: {{ incident_description }}\r\n\r\nDate/Time of Incident: {{ incident_date_time }}\r\n\r\nOur team is currently investigating the incident and working towards a resolution. We will keep you updated on the progress.\r\n\r\nThank you for your patience and understanding.\r\n\r\nBest regards,\r\n\r\n[Your Name]');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`) VALUES
(1, 'abud@anton-paar.com', '[\"ROLE_ADMIN\"]', '$2y$13$29/mHj5xJlWeU8sBlhh7Je3Ad6WWyKXEi.uBoLR66P8vZWmW1szJC', 'abud'),
(2, 'david@anton-paar.com', '[\"ROLE_USER\"]', '$2y$13$8VKJxrLQtG5u6KFtx.jR0.cQSnGKKZrSgpsNCjBD.1Ls4tHP2sPYW', 'david');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A3C664D368280800` (`system_status_id`),
  ADD KEY `IDX_A3C664D3A76ED395` (`user_id`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_status`
--
ALTER TABLE `system_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6B8ED0DDD0952FA5` (`system_id`),
  ADD KEY `IDX_6B8ED0DD6BF700BD` (`status_id`);

--
-- Indexes for table `tamplate`
--
ALTER TABLE `tamplate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_status`
--
ALTER TABLE `system_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tamplate`
--
ALTER TABLE `tamplate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `FK_A3C664D368280800` FOREIGN KEY (`system_status_id`) REFERENCES `system_status` (`id`),
  ADD CONSTRAINT `FK_A3C664D3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `system_status`
--
ALTER TABLE `system_status`
  ADD CONSTRAINT `FK_6B8ED0DD6BF700BD` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `FK_6B8ED0DDD0952FA5` FOREIGN KEY (`system_id`) REFERENCES `system` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
