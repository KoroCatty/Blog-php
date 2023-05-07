-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 07, 2023 at 04:04 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `adname` varchar(80) NOT NULL,
  `addedby` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `datetime`, `username`, `password`, `adname`, `addedby`) VALUES
(17, 'May 5, 2023, 1:12 am', 'Kazu', '$2y$10$pMLSv1ZCDuZfU4PhIYoOR.lGXtVOaEdJmcMfrcJ82cFruvaEJqtoK', 'k', 'Kazu'),
(18, 'May 5, 2023, 1:15 am', 'MIllie', '$2y$10$mVrl.X.POitoGwmsPnBB3uHGaNsmFk8T6weGLONQKgAKAeyqlM6NG', 'm', 'MIllie'),
(19, 'May 5, 2023, 8:27 am', 'cat', '$2y$10$B/Zb2aNa4kgIvcxfQcfXeuZXiqYuu6HgFc.90aE2Mw5mFIUwvHkPi', 'catty', 'cat'),
(22, 'May 5, 2023, 9:22 am', 'rabit', '$2y$10$AajNvxCwQYPaesVpRv/qr.Bb.SK4ZJEYO1R/DKQSJmzsId/2JIhvK', '', 'rabit'),
(24, 'May 5, 2023, 2:42 pm', 'Pet', '$2y$10$TEaGcrx5rU0yu4eGqtJcv.utYeH1m37Cjd48SRJVsKEhkvN6lqJGm', 'c', 'Pet');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `author`, `datetime`) VALUES
(23, 'Cats', 'Kazuya', 'March 8, 2023, 6:23 pm'),
(42, 'Cat Cafe', 'MIllie', 'May 5, 2023, 1:11 pm'),
(46, 'Journey of cats', 'MIllie', 'May 5, 2023, 2:23 pm');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `approvedby` varchar(50) NOT NULL,
  `status` varchar(3) NOT NULL,
  `post_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `datetime`, `name`, `email`, `comment`, `approvedby`, `status`, `post_id`) VALUES
(8, 'March 20, 2023, 2:13 pm', 'cheeky cat', 'bbb@gmail.com', 'cheecky bastard cat', '', 'ON', 5),
(12, 'March 31, 2023, 2:54 pm', 'neko', 'aaa@gmail.com', 'neko chan desu', 'c', 'OFF', 8),
(13, 'May 7, 2023, 10:33 am', 'neko', 'aaa@gmail.com', 'neko', 'Pending', 'OFF', 5);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) NOT NULL,
  `datetime` varchar(50) NOT NULL,
  `title` varchar(300) NOT NULL,
  `category` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `post` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `datetime`, `title`, `category`, `author`, `image`, `post`) VALUES
(5, 'March 8, 2023, 6:35 pm', '猫ちゃんが好きですa', '入りました', 'Kazuya', 'smile_design.jpg', 'ねこちゃーん　ねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーん'),
(8, 'March 17, 2023, 3:43 pm', 'Testing New ONe', 'Cats', 'Kazuya', 'AdobeStock_103211962.jpg', 'My name is John Nash.'),
(10, 'March 22, 2023, 9:54 am', 'Koroが家にやってきました', 'Cats', 'Eggman', 'AdobeStock_70051024.jpg', 'koro koro koro koro koro '),
(11, 'April 1, 2023, 1:11 am', 'HANABI', 'Cats', 'Eggman', 'one-handCat.jpg', 'hanabi is opened'),
(16, 'April 18, 2023, 7:28 pm', 'Thsi is the new post', 'Cats', 'MIllie', 'koro1.jpg', 'this is from Mille'),
(28, 'May 5, 2023, 10:35 am', 'cat is our family', 'Cats', 'Kazu', 'Screenshot 2023-02-15 at 12.49.33 pm.jpg', 'Test'),
(29, 'May 6, 2023, 6:03 pm', 'Test article', 'Cats', 'Kazu', 'AdobeStock_76612832.jpg', 'test'),
(36, 'May 6, 2023, 9:56 pm', 'When you got a cat', 'Cats', 'Kazu', 'Koro.jpg', 'test'),
(37, 'May 6, 2023, 9:57 pm', 'How to feed a cat', 'Cats', 'Kazu', 'IMG_9366.JPG.jpeg', 'test'),
(38, 'May 6, 2023, 10:20 pm', '10件目の記事です。', 'Cat Cafe', 'Kazu', 'AdobeStock_103211962.jpg', 'Test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Foreign_Relation` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
