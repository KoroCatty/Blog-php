-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2023 at 06:50 AM
-- Server version: 10.5.19-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u496040768_blog`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `datetime`, `username`, `password`, `adname`, `addedby`) VALUES
(2, 'March 20, 2023, 5:48 pm', 'Eggman', '1234', '', 'Kazuya'),
(7, 'March 22, 2023, 9:57 am', 'MIllie', '1234', '', 'Eggman'),
(23, 'April 20, 2023, 1:52 pm', 'Kaz', '1234', 'kazzy', 'Kaz');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `datetime` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `author`, `datetime`) VALUES
(3, '入りました', 'Kazuya', 'March 6, 2023, 9:57 am'),
(23, 'Cats', 'Kazuya', 'March 8, 2023, 6:23 pm'),
(24, 'SCIENCE', 'Eggman', 'March 22, 2023, 9:41 am'),
(29, 'test', 'Eggman', 'April 19, 2023, 8:56 am'),
(38, 'problem', 'Eggman', 'April 19, 2023, 8:58 am'),
(41, '1234', 'Eggman', 'April 19, 2023, 10:54 am');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `datetime`, `name`, `email`, `comment`, `approvedby`, `status`, `post_id`) VALUES
(8, 'March 20, 2023, 2:13 pm', 'cheeky cat', 'bbb@gmail.com', 'cheecky bastard cat', '', 'ON', 5),
(12, 'March 31, 2023, 2:54 pm', 'neko', 'aaa@gmail.com', 'neko chan desu', '', 'OFF', 8),
(14, 'April 18, 2023, 7:46 pm', 'Milli bastard', 'aaa@gmail.com', '651564', '', 'OFF', 16);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `datetime`, `title`, `category`, `author`, `image`, `post`) VALUES
(5, 'March 8, 2023, 6:35 pm', '猫ちゃんが好きです', 'SCIENCE', 'Kazuya', 'cat-transparent.png', 'ねこちゃーん　ねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーんねこちゃーん'),
(8, 'March 17, 2023, 3:43 pm', 'Testing New ONe', 'Cats', 'Kazuya', 'Screenshot 2023-02-15 at 12.49.33 pm.png', 'My name is John Nash.'),
(10, 'March 22, 2023, 9:54 am', 'Koroが家にやってきました', 'Cats', 'Eggman', 'one-handCat.jpg', 'koro koro koro koro koro '),
(11, 'April 1, 2023, 1:11 am', 'HANABI', 'SCIENCE', 'Eggman', 'Fuji_hanabi.jpg', 'hanabi is opened'),
(13, 'April 1, 2023, 1:11 am', 'fadsfasdfadsfasd', '入りました', 'Eggman', 'tv_shows.jpg', 'jhkjghhjghjg'),
(16, 'April 18, 2023, 7:28 pm', 'Thsi is the new post', 'Cats', 'MIllie', 'koro1.jpg', 'this is from Mille'),
(27, 'April 19, 2023, 11:01 am', 'Koroが家にやってきました', '入りました', 'Eggman', 'drug_icon.svg', 'fghdfg'),
(31, 'April 20, 2023, 1:14 pm', 'cats are cute animals!!!', 'Cats', 'Eggman', 'catMv.jpg', 'this is cat found in Japan'),
(32, 'April 20, 2023, 1:56 pm', 'test', 'Cats', 'Kaz', 'osaka-zyo-small2.jpg', 'test'),
(33, 'April 25, 2023, 2:23 pm', 'fluffy friend', 'Cats', 'Kaz', 'B454C380-8239-4774-820F-5B473938076A.jpeg', 'This is fluffy as pun cake ');

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
