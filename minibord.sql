-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 21, 2023 at 12:38 PM
-- Server version: 10.5.18-MariaDB-0+deb11u1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minibordv1.03test`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `displayorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `displayorder`) VALUES
(1, 'General', 0);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `name` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE `forums` (
  `id` smallint(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `catid` smallint(5) NOT NULL DEFAULT 0,
  `displayorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `name`, `description`, `catid`, `displayorder`) VALUES
(1, 'General Chat', 'A general forum for chatting', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `ip` varchar(256) NOT NULL,
  `firstview` bigint(15) NOT NULL,
  `lastview` bigint(15) NOT NULL,
  `views` int(11) NOT NULL,
  `lastuseragent` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `headlinks`
--

CREATE TABLE `headlinks` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `catid` smallint(5) UNSIGNED NOT NULL,
  `displayorder` tinyint(5) UNSIGNED NOT NULL DEFAULT 0,
  `showwhenloggedout` tinyint(1) NOT NULL DEFAULT 1,
  `showwhenloggedin` tinyint(1) NOT NULL DEFAULT 1,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `url` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `headlinks`
--

INSERT INTO `headlinks` (`id`, `name`, `catid`, `displayorder`, `showwhenloggedout`, `showwhenloggedin`, `restricted`, `url`) VALUES
(1, 'Login', 1, 0, 1, 0, 0, 'login.php'),
(2, 'Register', 1, 0, 1, 0, 0, 'register.php'),
(3, 'Edit Profile', 1, 0, 0, 1, 0, 'editprofile.php'),
(4, 'Logout', 1, 0, 0, 1, 0, 'login.php?action=logout'),
(5, 'Main', 2, 0, 1, 1, 0, 'index.php'),
(6, 'Cookie Settings', 2, 0, 1, 1, 0, 'settings.php'),
(7, 'Userlist', 2, 0, 1, 1, 0, 'userlist.php'),
(8, 'RSS', 2, 0, 1, 1, 0, 'rss.php'),
(11, 'Smilies', 2, 0, 1, 1, 0, 'smilies.php');

-- --------------------------------------------------------

--
-- Table structure for table `headlinks_categories`
--

CREATE TABLE `headlinks_categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `displayorder` smallint(5) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `headlinks_categories`
--

INSERT INTO `headlinks_categories` (`id`, `name`, `displayorder`) VALUES
(1, 'User', 0),
(2, 'Main', 0);

-- --------------------------------------------------------

--
-- Table structure for table `iphistory_global`
--

CREATE TABLE `iphistory_global` (
  `id` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `views` mediumint(8) NOT NULL,
  `lastuseragent` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `iphistory_user`
--

CREATE TABLE `iphistory_user` (
  `id` int(11) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `ip` varchar(256) NOT NULL,
  `views` mediumint(8) NOT NULL,
  `lastuseragent` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipverificationfailures`
--

CREATE TABLE `ipverificationfailures` (
  `id` int(11) NOT NULL,
  `originalip` varchar(256) NOT NULL,
  `failedip` varchar(256) NOT NULL,
  `userid` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lastforumread`
--

CREATE TABLE `lastforumread` (
  `id` int(11) UNSIGNED NOT NULL,
  `userid` mediumint(8) UNSIGNED NOT NULL,
  `forumid` smallint(5) UNSIGNED NOT NULL,
  `date` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lastthreadread`
--

CREATE TABLE `lastthreadread` (
  `id` int(11) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `threadid` mediumint(8) NOT NULL,
  `date` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` mediumint(8) NOT NULL,
  `threadid` mediumint(8) NOT NULL,
  `date` bigint(15) NOT NULL,
  `text` text NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `disablesmilies` tinyint(1) NOT NULL DEFAULT 0,
  `ip` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smilies`
--

CREATE TABLE `smilies` (
  `code` varchar(15) NOT NULL,
  `image` varchar(50) NOT NULL,
  `displayorder` smallint(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `smilies`
--

INSERT INTO `smilies` (`code`, `image`, `displayorder`) VALUES
(':(', 'images/smilies/1.png', 1),
(':)', 'images/smilies/2.png', 2),
(':annoyed:', 'images/smilies/3.png', 3),
(':D', 'images/smilies/4.png', 4);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  `displayorder` smallint(5) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `name`, `path`, `displayorder`) VALUES
(1, 'NinCollin\'s Corner Lite', 'themes/corner.php', 50),
(7, 'AckBlue', 'themes/ackblue.php', 0),
(8, 'NinCollin\'s Domain (Random)', 'themes/domainrand.php', 51),
(9, 'Classic White', 'themes/white.php', 20),
(11, 'NinCollin\'s Domain (Daily Cycle)', 'themes/domain.php', 52),
(12, 'Classic Blue', 'themes/blue.php', 21),
(13, 'Classic Night', 'themes/night.php', 22);

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `id` mediumint(8) NOT NULL,
  `date` bigint(15) NOT NULL,
  `lastactivity` bigint(15) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `forumid` mediumint(5) UNSIGNED NOT NULL,
  `ip` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(60) NOT NULL,
  `ipverification` tinyint(1) NOT NULL,
  `ip` varchar(150) NOT NULL,
  `expires` bigint(13) NOT NULL,
  `userid` mediumint(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` mediumint(8) NOT NULL,
  `name` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `regip` varchar(256) NOT NULL,
  `regdate` bigint(15) NOT NULL,
  `namecolor` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `lastview` bigint(15) DEFAULT NULL,
  `avatarurl` varchar(100) DEFAULT NULL,
  `minipicurl` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `homepageurl` varchar(100) DEFAULT NULL,
  `homepagename` varchar(100) DEFAULT NULL,
  `birthday` bigint(15) DEFAULT 0,
  `bio` text DEFAULT NULL,
  `postheader` text DEFAULT NULL,
  `postfooter` text DEFAULT NULL,
  `theme` smallint(5) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD UNIQUE KEY `ip` (`ip`);

--
-- Indexes for table `headlinks`
--
ALTER TABLE `headlinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `headlinks_categories`
--
ALTER TABLE `headlinks_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iphistory_global`
--
ALTER TABLE `iphistory_global`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip` (`ip`,`token`);

--
-- Indexes for table `iphistory_user`
--
ALTER TABLE `iphistory_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip` (`ip`,`userid`);

--
-- Indexes for table `ipverificationfailures`
--
ALTER TABLE `ipverificationfailures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lastforumread`
--
ALTER TABLE `lastforumread`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`,`forumid`);

--
-- Indexes for table `lastthreadread`
--
ALTER TABLE `lastthreadread`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`,`threadid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smilies`
--
ALTER TABLE `smilies`
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` smallint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `headlinks`
--
ALTER TABLE `headlinks`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `headlinks_categories`
--
ALTER TABLE `headlinks_categories`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `iphistory_global`
--
ALTER TABLE `iphistory_global`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iphistory_user`
--
ALTER TABLE `iphistory_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipverificationfailures`
--
ALTER TABLE `ipverificationfailures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lastforumread`
--
ALTER TABLE `lastforumread`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lastthreadread`
--
ALTER TABLE `lastthreadread`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
