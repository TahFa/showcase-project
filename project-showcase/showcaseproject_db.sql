-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 06:28 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `showcaseproject_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(1, 'Website', '2026-05-15 04:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `downloaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `user_id`, `project_id`, `downloaded_at`) VALUES
(2, 4, 10, '2026-05-15 17:06:28'),
(3, 1, 9, '2026-05-17 10:19:38'),
(4, 6, 8, '2026-05-17 15:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `project_id`) VALUES
(4, 1, 8),
(13, 1, 10),
(6, 4, 8),
(11, 4, 9),
(7, 4, 10),
(15, 6, 8);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tech_stack` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `zip_file` varchar(255) DEFAULT NULL,
  `demo_link` varchar(255) DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `status` enum('published','private','deleted') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `category_id`, `title`, `description`, `tech_stack`, `image`, `zip_file`, `demo_link`, `likes_count`, `download_count`, `status`, `created_at`, `updated_at`) VALUES
(8, 1, 1, 'SavePass', 'Website untuk menyimpan semua sandimu dengan aman dan Zero Knowladge', 'PHP, Javascript, Bootstrap', '1778834034_img_6a06da72f21b8.jpg', '1778834034_zip_6a06da72f23e9.zip', '', 3, 1, 'published', '2026-05-15 08:33:54', '2026-05-17 15:08:25'),
(9, 1, 1, 'test', 'testing', 'Laravel, Bootstrap, React', '1778837853_img_6a06e95d7c07a.jpg', '1778837853_zip_6a06e95d7c61b.zip', '', 1, 1, 'published', '2026-05-15 09:37:33', '2026-05-17 11:52:40'),
(10, 4, 1, 'Booking Lapangan', 'Website untuk booking lapangan berbasis online tanpa harus datang ke tempat', 'PHP, Javascript, Bootstrap 5', '1778844646_img_6a0703e6be6d7.jpg', '1778844646_zip_6a0703e6beed2.zip', 'https://github.com/TahFa/website_booking_lapangan', 2, 1, 'published', '2026-05-15 11:30:46', '2026-05-17 10:19:29'),
(11, 5, 1, 'Website UMKM', 'Website Untuk profile UMKM', 'Laravel, Bootstrap 5, React', '1779018991_img_6a09acef41355.jpg', '1779018991_zip_6a09acef418c4.zip', '', 0, 0, 'published', '2026-05-17 11:56:31', '2026-05-17 11:56:31'),
(12, 6, 1, 'Perpustakaan 400', 'Website untuk management perpustakaan', 'PHP, Javascript, Bootstrap 5', '1779030722_img_6a09dac287a21.png', '1779030722_zip_6a09dac288764.zip', '', 0, 0, 'private', '2026-05-17 15:12:02', '2026-05-17 15:13:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `bio` text DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `banned_until` datetime DEFAULT NULL,
  `ban_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `bio`, `profile_picture`, `banned_until`, `ban_reason`, `created_at`) VALUES
(1, 'user', '$2y$10$ZHs0/JXbjGy3ddu3F3NluuW8lK8PF0x0ZEWKfWjnNIxlLLSZQbei6', 'user', NULL, 'default.png', NULL, NULL, '2026-05-14 04:36:17'),
(3, 'admin', '$2y$10$0JCYrVDzaQe2Mz2UnMb.v.nwOqHDpUFT69bYC.mdxnOxx1uAI1WHu', 'admin', NULL, 'default.png', NULL, NULL, '2026-05-14 07:18:50'),
(4, 'fattah', '$2y$10$0nakckYDx6busjgDOVMUjeDv.KwyTG0YvDsQQ8yxb4MOVxzm.3vvC', 'user', NULL, 'default.png', '2026-05-18 12:28:40', 'Tidak senonoh', '2026-05-15 11:28:30'),
(5, 'mipa', '$2y$10$6JvYCQMqZ.E/A.KfKOBp6e34teE1qQ8dyvv6k3IP6Nr0faaVvSj0a', 'user', NULL, 'default.png', NULL, NULL, '2026-05-17 11:53:46'),
(6, 'user1', '$2y$10$Xh9wor8kfSrkgsKbYISwruaBuJwfR8opVfeQYrAaDut8hlvEjGjDO', 'user', NULL, 'default.png', NULL, NULL, '2026-05-17 15:06:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_download` (`user_id`,`project_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`project_id`),
  ADD KEY `fk_like_project` (`project_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_user` (`user_id`),
  ADD KEY `fk_project_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_like_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_like_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_project_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_project_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
