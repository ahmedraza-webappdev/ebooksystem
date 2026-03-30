-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 01:29 PM
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
-- Database: `ebook_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `book_image` varchar(255) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_free` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `description`, `price`, `pdf_file`, `book_image`, `weight`, `created_at`, `is_free`) VALUES
(7, 'The Little Duke Or Richard', 'Charlotte	M. Yonge', 'BOOK', 'read', 0.00, '1772932915_book pdf.pdf', '1772932915_Book cover.jpeg', 2.00, '2026-03-08 01:21:55', 1),
(11, 'Both	Sides the	Border', 'G. A. Henty', 'General', 'Read', 150.00, '1773010085_Both Sides The Border.pdf', '1773010085_Both Sides The Border.jfif', 1.00, '2026-03-08 22:48:05', 0),
(13, 'The Orphan', 'Clarencee Mulford', 'General', 'Read', 200.00, '1773010369_Clarence E. Mulford.pdf', '1773010369_Clarence E. Mulford.jfif', 2.00, '2026-03-08 22:50:30', 0),
(15, 'The	New	Confessions	of	an	Economic	Hit	Man', 'John Perkins', 'Novel', 'Read', 0.00, '1773010540_econmics.pdf', '1773010540_Economic.jfif', 1.00, '2026-03-08 22:55:40', 1),
(16, 'Hidden	Water', 'Dane Coolidge', 'Novel', 'Read', 150.00, '1773010660_Hidden Water.pdf', '1773010660_Hidden Water.jfif', 1.00, '2026-03-08 22:57:40', 0),
(17, 'Electricity	for	Boys', 'J.	S.	Zerbe', 'General', 'Read', 350.00, '1773010759_Electricity For Boys.pdf', '1773010759_Electricity For Boys.jfif', 2.00, '2026-03-08 22:59:19', 0),
(18, 'Penny of	Top	Hill	Trail', 'Belle Kanaris Maniates', 'Novel', 'Read', 150.00, '1773011007_Penny Of Top Hill.pdf', '1773011007_Penny Of Top Hill.jfif', 1.00, '2026-03-08 23:03:27', 0),
(19, 'Famous	Privateersmen	and	Adventurers	of	the	Sea', 'Charles	H.	L.	Johnston', 'Comics', 'Read', 0.00, '1773011127_Famous Privateersmen.pdf', '1773011127_Famous Privateersmen.jfif', 2.00, '2026-03-08 23:05:27', 1),
(20, 'Kate Bonnet', 'Frank  R. Stockton', 'BOOK', 'Read', 300.00, '1773011226_Kate Bonnet.pdf', '1773011226_Kate Bonnet.jfif', 2.00, '2026-03-08 23:07:06', 0),
(21, 'Reflections on	War	and	Death', 'Sigmund	Freud', 'General', 'Read', 0.00, '1773011330_Reflections On War.pdf', '1773011330_Reflections On War.jfif', 2.00, '2026-03-08 23:08:50', 1),
(22, 'Heart of  the  Sunset', 'Rex	Beach', 'BOOK', 'Read', 350.00, '1773011407_Sunset By Rex.pdf', '1773011407_Sunset By Rex.jfif', 1.00, '2026-03-08 23:10:07', 0),
(23, 'Sir Nigel', 'Conan Doyle', 'Novel', 'Read', 0.00, '1773011497_Sir Nigel.pdf', '1773011497_Sir Nigel.jfif', 2.00, '2026-03-08 23:11:37', 1),
(25, 'The 5 Second Rule', 'Mel Robbins', 'General', 'Read', 350.00, '1773011714_The 5 Second Rule.pdf', '1773011714_The 5 Second Rule.jpg', 1.00, '2026-03-08 23:15:14', 0),
(27, 'The Bittermerds Mystery', 'E.R. Punshon', 'General', 'Read', 250.00, '1773011921_The Bittermeads Mystery.pdf', '1773011921_The Bittermeads Mystery.jfif', 2.00, '2026-03-08 23:18:41', 0),
(28, 'The Clock strikes Thirteen', 'Mildred A. Wirt', 'Comics', 'Read', 100.00, '1773012011_The Clock Strikes.pdf', '1773012011_The Clock Strikes.jfif', 2.00, '2026-03-08 23:20:11', 0),
(29, 'The Destroying Angel', 'Louis Joseph Vance', 'BOOK', 'Read', 0.00, '1773012076_The Destroying Angel.pdf', '1773012076_The Destroying Angel.jfif', 2.00, '2026-03-08 23:21:16', 1),
(31, 'The Great Quest', 'Charles Boardman Hawes', 'BOOK', 'Read', 0.00, '1773012233_The Great Quest.pdf', '1773012233_The Great Quest.jfif', 2.00, '2026-03-08 23:23:53', 1),
(32, 'The lows of Human Nature', 'Robert Greene', 'BOOK', 'Read', 350.00, '1773012366_The Laws of Human.pdf', '1773012366_The Laws of Human.jpg', 1.00, '2026-03-08 23:26:06', 0),
(33, 'Win the foreclosure Battle', 'Lame Adam', 'BOOK', 'Read', 0.00, '1773012529_Win The Foreclosure.pdf', '1773012529_Win The Foreclosure.png', 1.00, '2026-03-08 23:28:49', 1),
(34, 'The Purpose Driven® Life', 'Rick Warren', 'BOOK', 'Read', 0.00, '1773012677_The Purpose-Driven Life.pdf', '1773012677_The Purpose-Driven Life.jpg', 2.00, '2026-03-08 23:31:17', 1),
(35, 'The	Pathfinder', 'James Fenimore Cooper', 'BOOK', 'Read', 0.00, '1773012808_The Pathfinder.pdf', '1773012808_The Pathfinder.jfif', 2.00, '2026-03-08 23:33:28', 1),
(37, 'Two	Boy Gold Miners', 'Frank  V.	Webster', 'BOOK', 'Read', 350.00, '1773013091_Two Boy Gold Miner.pdf', '1773013091_Two Boy Gold Miner.jfif', 2.00, '2026-03-08 23:38:11', 0),
(38, 'The  Explosive  Child ', 'Ross W. Greene', 'BOOK', 'Read', 0.00, '1773013166_The Explosive Child.pdf', '1773013166_The Explosive Child.jpg', 2.00, '2026-03-08 23:39:26', 1),
(39, 'Big Timber   A	Story of the Northwest', 'Bertrand	W.	Sinclair', 'BOOK', 'Read', 100.00, '1773181941_Big Timber.pdf', '1773181941_Big Timber.jfif', 100.00, '2026-03-10 22:32:21', 0),
(40, 'Switch ON', 'Nikesh Lagun', 'General', 'Read', 0.00, '1773351561_Switch On.pdf', '1773351561_Switch On.jpg', 100.00, '2026-03-12 21:39:21', 1),
(41, 'Aim Higher Strategies ', 'Mike Morley', 'book', 'read', 100.00, '1773357874_Aiming higher.pdf', '1773357874_aiming higher.jpg', 100.00, '2026-03-12 23:24:34', 0),
(42, 'Bred  Of the Desert', 'Marcus  Horton', 'book', 'read', 0.00, '1773397965_Bred Of The Desert.pdf', '1773397965_Bred Of The Desert.jfif', 100.00, '2026-03-13 10:32:45', 1),
(43, 'Afar	in	the	Forest', 'W.H.G.	Kingston', 'Novel', 'Read', 150.00, '1774394677_afar-in-the-forest-by-w-h-g-kingston.pdf', '1774394677_Afar in the forest.jfif', 2.00, '2026-03-24 23:24:37', 0),
(44, 'The	Coming	of	Cassidy', 'Clarence	E.	Mulford', 'Novel', 'Read', 0.00, '1774394902_the-coming-of-cassidy-and-the-others.pdf', '1774394902_the coming of cassidy.jfif', 2.00, '2026-03-24 23:28:22', 1),
(45, 'The	Island	Home', 'Richard	Archer', 'General', 'Read', 0.00, '1774395088_the-island-home-the-adventures-of-six-young-crusoes-by-richard-archer.pdf', '1774395088_the island home.jfif', 1.00, '2026-03-24 23:31:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `prize` varchar(200) DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competitions`
--

INSERT INTO `competitions` (`id`, `title`, `description`, `start_date`, `end_date`, `prize`, `winner_id`) VALUES
(1, 'Essay', 'aadasd', '2026-03-08', '2026-03-27', '100', NULL),
(4, '2', 'wasada', '2026-03-08', '2026-03-28', '20', 2),
(8, 'AAAAAAA', 'AAAAAAAAAAAAAAAAA', '2026-03-10', '2026-03-20', '100', 2),
(11, 'essay', '300 words ', '2026-03-14', '2026-03-15', 'PKR 50,000 + Gold Medal', 10),
(12, 'Story Writing', 'Craft a unique short story (1000-1500 words) with a captivating plot; focus on character depth and flow.', '2026-03-13', '2026-03-14', '1st: 10000, 2nd: 5000, 3rd: 2000 PKR', 17),
(15, 'Story Writing', 'Craft a unique short story (1000-1500 words) with a captivating plot; focus on character depth and flow.', '2026-03-14', '2026-03-14', '1st: 10000, 2nd: 5000, 3rd: 2000 PKR', 18),
(16, 'Essay Writing', 'Submit a compelling essay (500-800 words) on social impact; focus on originality and clear structure.', '2026-03-26', '2026-03-27', '1st: 5000, 2nd: 3000, 3rd: 1000 PKR', NULL),
(17, 'Essay Writing', 'Submit a compelling essay (500-800 words) on social impact; focus on originality and clear structure.', '2026-03-27', '2026-03-28', '1st: 5000, 2nd: 3000, 3rd: 1000 PKR', 35),
(18, 'Story Writing', 'Create a mystery short story. Max 2000 words.', '2026-03-30', '2026-03-30', '1st: 10,000, 2nd: 5000, 3rd: 2000 PKR', NULL),
(19, 'Essay Writing', 'Write an essay on \'The Future of AI\'. Min 500 words.', '2026-03-23', '2026-03-30', '1st: 5000, 2nd: 3000, 3rd: 1500 PKR', NULL),
(20, 'Essay Writing', 'Write an essay on \'The Future of AI\'. Min 500 words.', '2026-03-30', '2026-03-30', '1st: 5000, 2nd: 3000, 3rd: 1500 PKR', NULL),
(21, 'Essay Writing', 'Write an essay on \'The Future of AI\'. Min 500 words.', '2026-03-30', '2026-03-30', '1st: 5000, 2nd: 3000, 3rd: 1500 PKR', NULL),
(22, 'Essay Writing', 'Write an essay on \'The Future of AI\'. Min 500 words.', '2026-03-30', '2026-03-30', '1st: 5000, 2nd: 3000, 3rd: 1500 PKR', NULL),
(23, 'Essay Writing', 'Write an essay on \'The Future of AI\'. Min 500 words.', '2026-04-01', '2026-04-01', '1st: 5000, 2nd: 3000, 3rd: 1500 PKR', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `order_type` enum('PDF','CD','Hard Copy') DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Pending','Paid') DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `order_status` enum('Pending','Confirmed','Dispatched','Delivered') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `book_id`, `order_type`, `shipping_address`, `total_price`, `payment_status`, `payment_proof`, `order_status`, `created_at`) VALUES
(28, 1, 20, 'Hard Copy', 'Name: MUHAMMAD FURQAN . | Phone: 03170010116 | Addr: five star', 600.00, 'Pending', NULL, '', '2026-03-10 21:03:49'),
(29, 1, 39, 'PDF', 'Name: MUHAMMAD FURQAN . | Phone: N/A | Addr: Digital Delivery', 100.00, 'Pending', NULL, '', '2026-03-10 23:11:54'),
(30, 1, 39, 'PDF', 'Name: MUHAMMAD FURQAN . | Phone: N/A | Addr: Digital Delivery', 100.00, '', NULL, '', '2026-03-10 23:28:52'),
(31, 1, 39, 'PDF', 'Name: MUHAMMAD FURQAN . | Phone: N/A | Addr: Digital Delivery', 100.00, 'Paid', NULL, '', '2026-03-10 23:32:45'),
(32, 10, 39, 'Hard Copy', 'Name: MUHAMMAD FURQAN . | Phone: 03170010116 | Addr: five star', 100.00, '', NULL, '', '2026-03-11 01:33:26'),
(35, 15, 38, 'Hard Copy', 'Name: ahmed | Phone: 0321456789 | Addr: five star', 120.00, '', NULL, '', '2026-03-12 20:58:39'),
(36, 15, 39, 'Hard Copy', 'Name: furqan | Phone: 03217894562 | Addr: five star', 100.00, '', NULL, '', '2026-03-12 21:06:15'),
(37, 15, 39, 'Hard Copy', 'Name: ahmed | Phone: 0321456789 | Addr: saddar', 100.00, '', NULL, '', '2026-03-12 21:10:25'),
(38, 18, 39, 'Hard Copy', 'Name: ali | Phone: 21458752 | Addr: 102', 100.00, '', NULL, '', '2026-03-12 23:22:23'),
(39, 21, 41, 'Hard Copy', 'Name: twest | Phone: 054662566 | Addr: 15', 100.00, '', NULL, '', '2026-03-13 12:56:48'),
(40, 22, 20, 'Hard Copy', 'Name: hussain | Phone: 0321456879 | Addr: 103', 300.00, 'Paid', NULL, 'Delivered', '2026-03-15 01:24:56'),
(41, 23, 41, 'Hard Copy', 'Name: ali | Phone: 44545 | Addr: 4445', 100.00, 'Paid', NULL, 'Delivered', '2026-03-17 20:44:38'),
(42, 23, 41, 'Hard Copy', 'Name: ali | Phone: 12341 | Addr: 104', 100.00, 'Paid', NULL, 'Delivered', '2026-03-17 21:04:59'),
(43, 23, 41, 'PDF', 'Name: ali | Phone: N/A | Addr: Digital Delivery', 100.00, 'Paid', NULL, 'Delivered', '2026-03-18 22:23:07'),
(44, 23, 28, 'PDF', 'Name: hasn | Phone: N/A | Addr: Digital Delivery', 100.00, 'Paid', NULL, 'Delivered', '2026-03-18 22:31:42'),
(46, 23, 22, 'PDF', 'Name: ali | Phone: N/A | Addr: Digital Delivery', 350.00, 'Paid', NULL, 'Confirmed', '2026-03-18 23:38:51'),
(47, 24, 43, 'PDF', 'Name: furqan | Phone: N/A | Addr: Digital Delivery', 150.00, 'Paid', NULL, 'Delivered', '2026-03-24 23:50:21'),
(48, 25, 43, 'Hard Copy', 'Name: hasan | Phone: 03214568877 | Addr: 102', 150.00, 'Paid', NULL, 'Delivered', '2026-03-25 22:15:04'),
(49, 27, 39, 'PDF', 'Name: ali | Phone: N/A | Addr: Digital Delivery', 100.00, 'Paid', NULL, 'Delivered', '2026-03-26 00:55:29'),
(50, 27, 37, 'PDF', 'Name: ali | Phone: N/A | Addr: Digital Delivery', 350.00, 'Paid', NULL, 'Delivered', '2026-03-26 00:56:37'),
(51, 27, 43, 'Hard Copy', 'Name: ali | Phone: 102345 | Addr: 102', 150.00, 'Paid', NULL, 'Delivered', '2026-03-26 01:08:58'),
(52, 33, 43, 'Hard Copy', 'Name: ali | Phone: 0321456987 | Addr: 104', 150.00, '', NULL, '', '2026-03-26 18:51:46'),
(53, 33, 43, 'Hard Copy', 'Name: kjhn | Phone: 03311 | Addr: 01266 | Sent To: N/A | Screenshot: PAY_1774551955_33.PNG', 150.00, '', NULL, '', '2026-03-26 19:05:55'),
(54, 33, 37, 'Hard Copy', 'Name: ali | Phone: 0320145697 | Addr: 102 | SentTo: 0317-0010116 | SS: PAY_1774552904_33.PNG', 350.00, '', NULL, '', '2026-03-26 19:21:44'),
(55, 33, 39, 'Hard Copy', 'Name: haider | Phone: 0321456987 | Addr: 102', 100.00, '', NULL, '', '2026-03-26 21:56:05'),
(56, 33, 41, 'Hard Copy', 'Name: haider | Phone: 0321456987 | Addr: 10235', 100.00, 'Paid', 'PAY_1774563590_33.PNG', 'Delivered', '2026-03-26 22:19:50'),
(57, 33, 25, 'Hard Copy', 'Name: haider | Phone: 0321456 | Addr: 1099', 350.00, 'Paid', 'PAY_1774564042_33.png', 'Dispatched', '2026-03-26 22:27:22'),
(58, 33, 11, 'Hard Copy', 'Name: haider | Phone: 03214569875 | Addr: 521', 150.00, 'Paid', 'PAY_1774566912_33.PNG', 'Delivered', '2026-03-26 23:15:12'),
(59, 35, 37, 'Hard Copy', 'Name: akram | Phone: 0321459874 | Addr: 201', 350.00, 'Paid', 'PAY_1774616822_35.PNG', 'Delivered', '2026-03-27 13:07:02'),
(60, 36, 43, 'Hard Copy', 'Name: ahmed | Phone: 032145698 | Addr: 102', 150.00, 'Paid', 'PAY_1774783707_36.PNG', 'Confirmed', '2026-03-29 11:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `competition_id` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rank` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `user_id`, `competition_id`, `file`, `submitted_at`, `rank`) VALUES
(5, 2, 1, 'essay_2_1772987087.txt', '2026-03-08 16:24:47', '3'),
(10, 2, 8, 'essay_2_1772988364.txt', '2026-03-08 16:46:04', '3'),
(11, 2, 4, 'essay_2_1772988624.txt', '2026-03-08 16:50:24', '3rd Place'),
(12, 1, 8, 'essay_1_1773176943.txt', '2026-03-10 21:09:03', '2'),
(13, 1, 8, 'essay_1_1773177739.txt', '2026-03-10 21:22:19', '3'),
(14, 10, 11, 'essay_10_1773194157.txt', '2026-03-11 01:55:57', '1st Place'),
(15, 17, 12, 'essay_17_1773354454.txt', '2026-03-12 22:27:34', '1st Place'),
(16, 17, 12, 'essay_17_1773356403.txt', '2026-03-12 23:00:03', '2nd place'),
(21, 18, 15, 'essay_18_1773364761.txt', '2026-03-13 01:19:21', '3'),
(22, 20, 15, 'essay_20_1773401844.txt', '2026-03-13 11:37:24', NULL),
(23, 35, 17, 'essay_35_1774617185.txt', '2026-03-27 13:13:05', '3'),
(24, 36, 23, 'essay_36_1774784686.txt', '2026-03-29 11:44:46', '1st place');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'Beingman', 'ahmedraza10222004@gmail.com', '4d6d955ca289f82e3a6e1f52f40108f3', '03120243061', 'aaaaaaaaaaaaaaaa', '2026-03-07 01:39:17'),
(2, 'Ahmed raza', 'ahmedraza10222004@gmail.com', '0192023a7bbd73250516f069df18b500', NULL, NULL, '2026-03-07 01:56:48'),
(3, 'Ahmed ', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '03120243061', 'burns road', '2026-03-08 00:51:31'),
(4, 'Ahmed ', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '03120243061', 'burns road', '2026-03-08 00:53:40'),
(5, 'Ahmed ', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '03120243061', 'burns road', '2026-03-08 00:53:43'),
(6, 'Ahmed ', 'admin@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '03120243061', NULL, '2026-03-08 00:54:02'),
(7, 'raza', 'admin@gmail.com', '4a7d1ed414474e4033ac29ccb8653d9b', '03120243061', NULL, '2026-03-08 00:55:08'),
(8, 'raza', 'admin@gmail.com', '4a7d1ed414474e4033ac29ccb8653d9b', '03120243061', NULL, '2026-03-08 01:01:03'),
(9, 'MUHAMMAD FURQAN .', 'mf600@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '03170010116', NULL, '2026-03-08 22:21:27'),
(10, 'MUHAMMAD FURQAN .', 'mf600@gmail.com', 'e00cf25ad42683b3df678c61f42c6bda', '03170010116', NULL, '2026-03-11 01:32:45'),
(11, 'MUHAMMAD FURQAN .', 'mf600@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '+923170010116', NULL, '2026-03-11 03:11:05'),
(12, 'MUHAMMAD FURQAN .', 'mf600@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '+923170010116', NULL, '2026-03-11 10:09:44'),
(13, 'MUHAMMAD FURQAN .', 'mf600@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '+923170010116', NULL, '2026-03-11 10:36:35'),
(14, 'MUHAMMAD FURQAN .', 'furqan5@gmail.com', '1605954bc4e552ad99e7f1a06f11b677', '+923170010116', 'HNO. D31 ARAFAT TOWN NORTH NAZIMABAD BLOCK M KARACHI', '2026-03-11 10:49:57'),
(15, 'abc', 'abc@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '+923170010116', 'five star', '2026-03-12 02:12:36'),
(16, 'ahmed', 'ahmed2@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '123456789', 'five star', '2026-03-12 02:37:49'),
(17, 'furqan', 'furqan@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '0321456489', '304', '2026-03-12 21:27:35'),
(18, 'ahmed', 'ahmed1@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '031245678', 'plot 304', '2026-03-12 23:20:58'),
(19, 'ali', 'ali@gmail.com', '46c17fb99b9c8c8ae8214834e1edf6b1', '03171565488', 'five star', '2026-03-13 09:40:17'),
(20, 'testuser', 'testuser@gmail.com', '0192023a7bbd73250516f069df18b500', '03331234567', '', '2026-03-13 11:34:12'),
(21, 'test', 'test@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '0321456678', '', '2026-03-13 12:51:45'),
(22, 'hussain', 'hussain@gmail.com', '0192023a7bbd73250516f069df18b500', '032104687', '103', '2026-03-15 01:23:22'),
(23, 'ali', 'ali12@gmail.com', '0192023a7bbd73250516f069df18b500', '0000000', '101', '2026-03-17 20:23:01'),
(24, 'abcd', 'abcd@gmail.com', '0192023a7bbd73250516f069df18b500', '1234567', '102', '2026-03-24 23:34:14'),
(25, 'hasan', 'hasan@gmail.com', '0192023a7bbd73250516f069df18b500', '0321456987', '102', '2026-03-25 22:13:51'),
(26, 'khan', 'khan@gmail.com', '0192023a7bbd73250516f069df18b500', '032145678', '1032', '2026-03-25 22:55:55'),
(27, 'saad', 'saad@gmail.com', '0192023a7bbd73250516f069df18b500', '032145679', '103', '2026-03-26 00:04:58'),
(28, 'ali khan', 'ali90@gmail.com', '0192023a7bbd73250516f069df18b500', '03214569874', '102', '2026-03-26 17:49:14'),
(29, 'adeel', 'ali901@gmail.com', '0192023a7bbd73250516f069df18b500', '03214568855', '102', '2026-03-26 17:52:08'),
(30, 'hussain', 'hussain12@gmail.com', '0192023a7bbd73250516f069df18b500', '03214569745', '102', '2026-03-26 17:57:07'),
(31, 'kazim', 'kazim@gmail.com', '0192023a7bbd73250516f069df18b500', '03214569875', 'a123', '2026-03-26 18:01:17'),
(32, 'ahmedraza', 'ahmedraza@gmail.com', '0192023a7bbd73250516f069df18b500', '03021456587', '102', '2026-03-26 18:08:52'),
(33, 'haider', 'haider@gmail.com', '0192023a7bbd73250516f069df18b500', '03201456987', '103', '2026-03-26 18:21:44'),
(34, 'arshad', 'arshad@gmail.com', '0192023a7bbd73250516f069df18b500', '03756981562', '103', '2026-03-27 00:08:44'),
(35, 'akram', 'akram@gmail.com', '0192023a7bbd73250516f069df18b500', '03214569875', '103', '2026-03-27 13:05:10'),
(36, 'ab', 'ab@gmail.com', '0192023a7bbd73250516f069df18b500', '03126547895', '102', '2026-03-29 11:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_books`
--

CREATE TABLE `user_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `unlocked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_books`
--

INSERT INTO `user_books` (`id`, `user_id`, `book_id`, `unlocked_at`) VALUES
(1, 23, 41, '2026-03-18 02:04:16'),
(2, 22, 20, '2026-03-19 03:09:27'),
(3, 23, 28, '2026-03-19 03:36:31'),
(5, 24, 43, '2026-03-25 04:51:39'),
(6, 25, 43, '2026-03-26 03:16:13'),
(7, 27, 39, '2026-03-26 05:55:52'),
(8, 27, 37, '2026-03-26 05:57:03'),
(9, 27, 43, '2026-03-26 06:18:05'),
(10, 33, 41, '2026-03-27 04:03:04'),
(11, 33, 11, '2026-03-27 04:16:47'),
(12, 35, 37, '2026-03-27 18:08:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user_new` (`user_id`),
  ADD KEY `fk_orders_book_new` (`book_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sub_user_final` (`user_id`),
  ADD KEY `fk_submissions_competitions_unique` (`competition_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_books`
--
ALTER TABLE `user_books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_book` (`user_id`,`book_id`),
  ADD KEY `fk_ub_book_final` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user_books`
--
ALTER TABLE `user_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_book_new` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_user_new` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `fk_sub_comp_final` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sub_user_final` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_submissions_competitions_unique` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_books`
--
ALTER TABLE `user_books`
  ADD CONSTRAINT `fk_ub_book_final` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ub_user_final` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
