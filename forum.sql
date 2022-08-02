-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 02, 2022 at 02:46 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum2`
--

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `badge_owner` int(11) NOT NULL,
  `badge_date` datetime NOT NULL DEFAULT current_timestamp(),
  `badge_text` varchar(50) NOT NULL,
  `badge_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL,
  `blog_title` varchar(35) NOT NULL,
  `blog_body` text NOT NULL,
  `blog_created` datetime NOT NULL,
  `blog_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(25) NOT NULL,
  `cat_desc` varchar(125) NOT NULL,
  `cat_created` datetime NOT NULL DEFAULT current_timestamp(),
  `cat_creator` int(11) NOT NULL,
  `cat_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `request` tinyint(4) NOT NULL DEFAULT 1,
  `time_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ip_bans`
--

CREATE TABLE `ip_bans` (
  `ip` varchar(50) NOT NULL,
  `ip_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `market`
--

CREATE TABLE `market` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(35) NOT NULL,
  `item_desc` varchar(150) NOT NULL,
  `item_price` int(11) NOT NULL,
  `item_type` tinyint(4) NOT NULL,
  `item_created` datetime NOT NULL,
  `item_creator` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `msg_sender` int(11) NOT NULL,
  `msg_receiver` int(11) NOT NULL,
  `msg_seen` tinyint(1) NOT NULL DEFAULT 0,
  `msg_title` varchar(255) NOT NULL,
  `msg_body` text NOT NULL,
  `msg_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `post_cat` int(11) NOT NULL,
  `post_title` varchar(60) NOT NULL,
  `post_body` text NOT NULL,
  `post_created` datetime NOT NULL DEFAULT current_timestamp(),
  `post_locked` tinyint(1) NOT NULL DEFAULT 0,
  `post_poster` int(11) NOT NULL,
  `post_updated` datetime NOT NULL,
  `post_pinned` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `reply_post` int(11) NOT NULL,
  `reply_poster` int(11) NOT NULL,
  `reply_created` datetime NOT NULL,
  `reply_body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `alert` tinyint(1) NOT NULL DEFAULT 0,
  `alert_type` varchar(20) NOT NULL,
  `alert_text` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `maintenance` int(11) NOT NULL DEFAULT 0,
  `alert_link` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `staff_log`
--

CREATE TABLE `staff_log` (
  `log_id` int(11) NOT NULL,
  `log_action` text NOT NULL,
  `log_user_id` int(11) NOT NULL,
  `time_logged` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_password` text NOT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp(),
  `user_status` varchar(250) NOT NULL DEFAULT 'Hi, I''m new here!',
  `user_bio` text DEFAULT NULL,
  `user_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_email` varchar(500) NOT NULL,
  `user_signup_ip` varchar(500) NOT NULL,
  `user_ip` varchar(500) NOT NULL,
  `user_admin` tinyint(4) NOT NULL DEFAULT 0,
  `user_flood` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_theme` tinyint(4) NOT NULL DEFAULT 1,
  `wearing_shirt` int(11) NOT NULL,
  `wearing_pants` int(11) NOT NULL,
  `wearing_glasses` int(11) NOT NULL,
  `wearing_face` int(11) NOT NULL,
  `wearing_hat` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wall`
--

CREATE TABLE `wall` (
  `wall_id` int(11) NOT NULL,
  `wall_creator` int(11) NOT NULL,
  `wall_message` varchar(255) NOT NULL,
  `wall_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `ip_bans`
--
ALTER TABLE `ip_bans`
  ADD PRIMARY KEY (`ip_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market`
--
ALTER TABLE `market`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_log`
--
ALTER TABLE `staff_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wall`
--
ALTER TABLE `wall`
  ADD PRIMARY KEY (`wall_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ip_bans`
--
ALTER TABLE `ip_bans`
  MODIFY `ip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market`
--
ALTER TABLE `market`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_log`
--
ALTER TABLE `staff_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wall`
--
ALTER TABLE `wall`
  MODIFY `wall_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
