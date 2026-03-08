-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 06:05 PM
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
(7, 'Book', 'Ahmed', 'BOOK', 'read', 0.00, '1772932915_book pdf.pdf', '1772932915_Book cover.jpeg', 2.00, '2026-03-08 01:21:55', 1),
(8, 'new', 'Ahmed', 'BOOK', 'aaaaaaaaaaaaaaaaa', 100.00, '1772936103_book pdf.pdf', '1772936103_Book cover.jpeg', 1.00, '2026-03-08 02:15:03', 0);

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
(8, 'AAAAAAA', 'AAAAAAAAAAAAAAAAA', '2026-03-10', '2026-03-20', '100', 2);

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
  `order_status` enum('Pending','Confirmed','Delivered') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `book_id`, `order_type`, `shipping_address`, `total_price`, `payment_status`, `order_status`, `created_at`) VALUES
(1, 0, 4, 'PDF', NULL, NULL, 'Pending', NULL, '2026-03-07 21:37:35'),
(2, 2, 4, 'PDF', NULL, NULL, 'Pending', NULL, '2026-03-07 21:58:31'),
(3, 2, 4, 'PDF', NULL, NULL, 'Pending', NULL, '2026-03-07 22:04:29'),
(4, 2, 4, 'PDF', NULL, NULL, 'Pending', NULL, '2026-03-07 22:39:46'),
(5, 2, 4, 'PDF', NULL, NULL, 'Pending', NULL, '2026-03-07 22:40:01'),
(6, 2, 4, 'PDF', 'burns road', 40.00, 'Pending', '', '2026-03-07 22:43:46'),
(7, 2, 4, 'PDF', 'Name:  | Phone:  | Addr: ', 40.00, 'Pending', '', '2026-03-07 22:45:15'),
(8, 2, 4, 'PDF', 'Name:  | Phone:  | Addr: ', 40.00, 'Pending', '', '2026-03-07 22:46:29'),
(9, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 40.00, 'Pending', '', '2026-03-07 22:47:52'),
(10, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: eee', 560.00, 'Pending', '', '2026-03-07 22:48:50'),
(11, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 40.00, 'Pending', '', '2026-03-07 22:56:19'),
(12, 2, 3, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 12221.00, 'Pending', '', '2026-03-07 22:57:09'),
(13, 2, 4, 'Hard Copy', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 480.00, 'Pending', '', '2026-03-07 22:58:46'),
(14, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 40.00, '', '', '2026-03-07 23:02:14'),
(15, 2, 3, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 1111.00, '', '', '2026-03-07 23:03:22'),
(16, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 40.00, '', '', '2026-03-07 23:07:16'),
(17, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 40.00, '', '', '2026-03-07 23:07:59'),
(18, 2, 3, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 1111.00, '', '', '2026-03-07 23:28:14'),
(19, 2, 4, 'Hard Copy', 'Name: Ahmed Raza | Phone: 03120243061 | Addr: burns road', 40.00, '', '', '2026-03-07 23:29:43'),
(20, 2, 4, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 40.00, '', '', '2026-03-07 23:29:51'),
(21, 2, 5, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 20.00, '', '', '2026-03-08 01:56:50'),
(22, 2, 5, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 20.00, '', '', '2026-03-08 02:12:12'),
(23, 2, 5, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 20.00, '', '', '2026-03-08 02:13:57'),
(24, 2, 8, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 100.00, '', '', '2026-03-08 02:15:28'),
(25, 2, 8, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 100.00, '', '', '2026-03-08 03:03:15'),
(26, 2, 8, 'PDF', 'Name: Ahmed Raza | Phone: N/A | Addr: Digital Delivery', 100.00, '', '', '2026-03-08 03:20:57');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `competition_id` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `user_id`, `competition_id`, `file`, `submitted_at`) VALUES
(5, 2, 1, 'essay_2_1772987087.txt', '2026-03-08 16:24:47'),
(10, 2, 8, 'essay_2_1772988364.txt', '2026-03-08 16:46:04'),
(11, 2, 4, 'essay_2_1772988624.txt', '2026-03-08 16:50:24');

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
(8, 'raza', 'admin@gmail.com', '4a7d1ed414474e4033ac29ccb8653d9b', '03120243061', NULL, '2026-03-08 01:01:03');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
