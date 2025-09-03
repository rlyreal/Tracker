-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 01:35 AM
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
-- Database: `financial_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_balances`
--

CREATE TABLE `daily_balances` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_income` decimal(10,2) DEFAULT 0.00,
  `total_expenses` decimal(10,2) DEFAULT 0.00,
  `net_balance` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

CREATE TABLE `debts` (
  `id` int(11) NOT NULL,
  `debtor_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_date` datetime DEFAULT current_timestamp(),
  `paid_status` enum('pending','paid') DEFAULT 'pending',
  `paid_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debts`
--

INSERT INTO `debts` (`id`, `debtor_name`, `amount`, `due_date`, `notes`, `status`, `created_date`, `paid_status`, `paid_date`) VALUES
(1, 'Easun Jane Atabay', 5850.00, '0000-00-00', '', 'pending', '2025-08-29 14:23:04', 'pending', NULL),
(3, 'Alexus Sundae Sagaral', 100.00, '0000-00-00', '', 'paid', '2025-08-29 15:08:52', 'pending', NULL),
(4, 'Alexus Sundae Sagaral', 100.00, '0000-00-00', '', 'paid', '2025-08-29 15:14:55', 'pending', NULL),
(5, 'Alexus Sundae Sagaral', 100.00, '0000-00-00', 'Cash-in', 'paid', '2025-08-29 15:21:00', 'pending', NULL),
(6, 'Alexus Sundae Sagaral', 100.00, '0000-00-00', '', 'paid', '2025-08-29 15:25:29', 'pending', NULL),
(7, 'Alexus Sundae Sagaral', 1.11, '0000-00-00', '', 'paid', '2025-08-29 15:33:36', 'paid', '2025-08-29 15:56:53'),
(8, 'Alexus Sundae Sagaral', 100.00, '0000-00-00', '', 'paid', '2025-08-29 16:00:32', 'paid', '2025-08-29 16:00:36'),
(9, 'Easun Jane Atabay', 1000.00, '2025-08-29', '', 'paid', '2025-08-29 16:01:29', 'paid', '2025-08-30 18:14:34'),
(10, 'Zeniva Jane Atabay', 4850.00, '0000-00-00', 'Refrigarator', 'pending', '2025-08-29 16:04:08', 'pending', NULL),
(11, 'Zeniva Jane Atabay', 50.00, '2025-08-29', 'Lunch', 'paid', '2025-08-29 16:07:44', 'paid', '2025-08-29 16:07:50'),
(12, 'Zeniva Jane Atabay', 50.00, '2025-08-29', 'Lunch', 'pending', '2025-08-29 16:13:43', 'pending', NULL),
(13, 'Zeniva Jane Atabay', 50.00, '2025-08-29', 'Lunch', 'paid', '2025-08-29 16:13:43', 'paid', '2025-08-29 16:13:51'),
(14, 'Ricky Moradas', 25.00, '0000-00-00', 'Load', 'paid', '2025-08-30 18:33:21', 'paid', '2025-09-01 12:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Breakfast', NULL, '2025-08-22 06:19:23'),
(2, 'Lunch', NULL, '2025-08-22 06:19:23'),
(3, 'Dinner', NULL, '2025-08-22 06:19:23'),
(4, 'Wants/Needs/Fee', NULL, '2025-08-22 06:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `income_categories`
--

CREATE TABLE `income_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income_categories`
--

INSERT INTO `income_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Adopt Me', NULL, '2025-08-22 06:19:23'),
(2, 'Grow a Garden', NULL, '2025-08-22 06:19:23'),
(3, 'Debt', NULL, '2025-08-22 06:19:23'),
(4, 'MIDMAN fee', NULL, '2025-08-22 06:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `type`, `amount`, `category_id`, `transaction_date`, `created_at`) VALUES
(1, 'income', 1200.00, 1, '2025-08-22 00:00:00', '2025-08-22 11:44:59'),
(2, 'income', 300.00, 1, '2025-08-22 00:00:00', '2025-08-22 11:45:08'),
(3, 'income', 19.00, 4, '2025-08-22 00:00:00', '2025-08-22 11:45:15'),
(4, 'income', 6.00, 4, '2025-08-22 00:00:00', '2025-08-22 11:45:22'),
(9, 'expense', 103.00, 3, '2025-08-22 00:00:00', '2025-08-22 12:31:20'),
(13, 'expense', 86.00, 4, '2025-08-22 00:00:00', '2025-08-22 12:46:55'),
(14, 'expense', 120.00, 4, '2025-08-22 00:00:00', '2025-08-22 12:48:28'),
(15, 'expense', 100.00, 1, '2025-08-23 00:00:00', '2025-08-23 01:18:52'),
(16, 'expense', 15.00, 4, '2025-08-23 00:00:00', '2025-08-23 01:47:06'),
(17, 'expense', 65.00, 2, '2025-08-23 00:00:00', '2025-08-23 03:35:48'),
(18, 'expense', 80.00, 4, '2025-08-23 00:00:00', '2025-08-23 04:20:57'),
(19, 'income', 5.00, 4, '2025-08-23 00:00:00', '2025-08-23 12:52:53'),
(20, 'expense', 363.00, 4, '2025-08-23 00:00:00', '2025-08-23 12:55:34'),
(23, 'income', 130.00, 2, '2025-08-24 00:00:00', '2025-08-24 03:33:40'),
(24, 'expense', 85.00, 2, '2025-08-24 00:00:00', '2025-08-24 05:59:20'),
(25, 'expense', 71.00, 4, '2025-08-24 00:00:00', '2025-08-24 06:32:18'),
(26, 'expense', 18.00, 4, '2025-08-24 00:00:00', '2025-08-24 06:32:27'),
(27, 'income', 9.29, 4, '2025-08-24 00:00:00', '2025-08-24 11:23:44'),
(28, 'income', 10.00, 4, '2025-08-24 00:00:00', '2025-08-24 12:44:03'),
(29, 'income', 6.00, 4, '2025-08-24 00:00:00', '2025-08-24 13:28:18'),
(30, 'income', 6.00, 4, '2025-08-24 00:00:00', '2025-08-24 14:25:08'),
(31, 'expense', 60.00, 4, '2025-08-25 00:00:00', '2025-08-24 16:42:33'),
(32, 'expense', 430.00, 4, '2025-08-25 00:00:00', '2025-08-24 16:42:39'),
(33, 'expense', 1000.00, 4, '2025-08-25 00:00:00', '2025-08-24 20:28:55'),
(34, 'income', 145.00, 4, '2025-08-25 00:00:00', '2025-08-24 20:32:11'),
(35, 'income', 40.00, 2, '2025-08-25 00:00:00', '2025-08-25 11:01:11'),
(36, 'expense', 50.00, 1, '2025-08-25 00:00:00', '2025-08-25 11:01:35'),
(37, 'expense', 104.00, 3, '2025-08-25 00:00:00', '2025-08-25 11:35:11'),
(39, 'income', 6.00, 4, '2025-08-26 00:00:00', '2025-08-25 17:37:03'),
(40, 'income', 182.23, 2, '2025-08-26 00:00:00', '2025-08-25 18:30:25'),
(41, 'expense', 0.09, 4, '2025-08-26 00:00:00', '2025-08-25 18:31:03'),
(42, 'expense', 100.00, 1, '2025-08-26 00:00:00', '2025-08-26 01:30:33'),
(43, 'expense', 62.00, 4, '2025-08-26 00:00:00', '2025-08-26 09:06:00'),
(44, 'expense', 62.00, 2, '2025-08-26 00:00:00', '2025-08-26 09:06:33'),
(45, 'income', 1190.00, 2, '2025-08-26 00:00:00', '2025-08-26 09:06:52'),
(46, 'income', 38.00, 4, '2025-08-26 00:00:00', '2025-08-26 11:10:35'),
(47, 'expense', 76.00, 3, '2025-08-26 00:00:00', '2025-08-26 11:52:56'),
(48, 'income', 4000.00, 4, '2025-08-26 00:00:00', '2025-08-26 15:46:09'),
(49, 'income', 1000.00, 4, '2025-08-27 00:00:00', '2025-08-27 00:54:18'),
(50, 'expense', 75.00, 1, '2025-08-27 00:00:00', '2025-08-27 01:00:43'),
(51, 'expense', 40.00, 2, '2025-08-27 00:00:00', '2025-08-27 05:53:04'),
(52, 'expense', 22.00, 4, '2025-08-27 00:00:00', '2025-08-27 06:43:23'),
(53, 'expense', 37.00, 4, '2025-08-27 00:00:00', '2025-08-27 06:43:31'),
(54, 'expense', 179.00, 4, '2025-08-27 00:00:00', '2025-08-27 06:43:35'),
(55, 'expense', 70.00, 3, '2025-08-27 00:00:00', '2025-08-27 12:06:28'),
(56, 'expense', 33.00, 4, '2025-08-27 00:00:00', '2025-08-27 12:07:12'),
(57, 'expense', 39.00, 4, '2025-08-27 00:00:00', '2025-08-27 12:08:39'),
(58, 'expense', 75.00, 1, '2025-08-28 00:00:00', '2025-08-27 23:16:22'),
(59, 'expense', 40.00, 2, '2025-08-28 00:00:00', '2025-08-28 07:35:36'),
(60, 'income', 100.00, 2, '2025-08-28 00:00:00', '2025-08-28 07:35:57'),
(61, 'expense', 45.00, 4, '2025-08-28 00:00:00', '2025-08-28 12:11:45'),
(62, 'expense', 18.00, 4, '2025-08-28 00:00:00', '2025-08-28 13:13:54'),
(63, 'expense', 1194.97, 4, '2025-08-28 00:00:00', '2025-08-28 13:14:17'),
(64, 'expense', 100.00, 4, '2025-08-28 00:00:00', '2025-08-28 13:26:27'),
(65, 'expense', 75.00, 3, '2025-08-28 00:00:00', '2025-08-28 14:01:45'),
(66, 'expense', 76.00, 1, '2025-08-29 00:00:00', '2025-08-28 16:14:15'),
(68, 'expense', 75.00, 2, '2025-08-29 00:00:00', '2025-08-29 06:06:59'),
(69, 'income', 530.11, 2, '2025-08-29 00:00:00', '2025-08-29 06:08:30'),
(70, 'income', 1100.00, 3, '2025-08-29 00:00:00', '2025-08-29 06:13:34'),
(71, 'income', 581.00, 3, '2025-08-29 00:00:00', '2025-08-29 06:14:29'),
(72, 'expense', 37.00, 4, '2025-08-29 00:00:00', '2025-08-29 06:14:56'),
(112, 'expense', 40.00, 4, '2025-08-29 18:01:03', '2025-08-29 10:01:03'),
(113, 'expense', 36.00, 4, '2025-08-29 18:03:26', '2025-08-29 10:03:26'),
(114, 'expense', 24.00, 3, '2025-08-29 21:43:58', '2025-08-29 13:43:58'),
(118, 'expense', 50.00, 1, '2025-08-30 08:25:33', '2025-08-30 00:25:33'),
(119, 'income', 300.00, 2, '2025-08-30 09:02:59', '2025-08-30 01:02:59'),
(120, 'expense', 18.00, 4, '2025-08-30 12:13:22', '2025-08-30 04:13:22'),
(121, 'expense', 18.00, 4, '2025-08-30 12:13:22', '2025-08-30 04:13:22'),
(122, 'expense', 150.00, 4, '2025-08-30 12:37:03', '2025-08-30 04:37:03'),
(126, 'income', 1613.25, 4, '2025-08-30 18:18:42', '2025-08-30 10:18:42'),
(127, 'expense', 144.00, 4, '2025-08-30 20:04:42', '2025-08-30 12:04:42'),
(128, 'expense', 15.00, 4, '2025-08-30 21:53:30', '2025-08-30 13:53:30'),
(129, 'expense', 154.99, 4, '2025-08-30 21:53:46', '2025-08-30 13:53:46'),
(130, 'income', 57.00, 4, '2025-08-30 21:53:58', '2025-08-30 13:53:58'),
(131, 'income', 6.00, 4, '2025-08-30 22:20:23', '2025-08-30 14:20:23'),
(132, 'expense', 50.00, 1, '2025-08-31 12:14:05', '2025-08-31 04:14:05'),
(133, 'expense', 50.00, 2, '2025-08-31 12:14:15', '2025-08-31 04:14:15'),
(139, 'expense', 344.66, 4, '2025-08-31 12:41:26', '2025-08-31 04:41:26'),
(140, 'expense', 44.00, 3, '2025-08-31 20:11:06', '2025-08-31 12:11:06'),
(142, 'income', 84.00, 4, '2025-08-31 21:57:56', '2025-08-31 13:57:56'),
(143, 'income', 304.00, 4, '2025-08-31 22:13:13', '2025-08-31 14:13:13'),
(144, 'expense', 4.84, 4, '2025-08-31 22:43:00', '2025-08-31 14:43:00'),
(146, 'expense', 6960.00, 4, '2025-08-31 23:23:41', '2025-08-31 15:23:41'),
(148, 'income', 57.00, 4, '2025-09-01 00:48:20', '2025-08-31 16:48:20'),
(150, 'expense', 15.00, 4, '2025-09-01 12:16:34', '2025-09-01 04:16:34'),
(152, 'expense', 43.00, 4, '2025-09-01 17:55:26', '2025-09-01 09:55:26'),
(153, 'expense', 75.00, 3, '2025-09-01 20:05:12', '2025-09-01 12:05:12'),
(154, 'income', 500.00, 4, '2025-09-01 22:09:52', '2025-09-01 14:09:52'),
(155, 'income', 3183.00, 4, '2025-09-01 23:27:46', '2025-09-01 15:27:46'),
(156, 'income', 130.00, 4, '2025-09-02 06:35:41', '2025-09-01 22:35:41'),
(157, 'expense', 43.00, 4, '2025-09-02 09:32:00', '2025-09-02 01:32:00'),
(158, 'income', 1000.00, 4, '2025-09-02 09:32:08', '2025-09-02 01:32:08'),
(159, 'expense', 265.00, 4, '2025-09-02 09:32:19', '2025-09-02 01:32:19'),
(160, 'income', 25.00, 4, '2025-09-02 10:07:29', '2025-09-02 02:07:29'),
(161, 'expense', 15.00, 4, '2025-09-02 18:59:31', '2025-09-02 10:59:31'),
(162, 'expense', 12.00, 4, '2025-09-02 18:59:41', '2025-09-02 10:59:41'),
(163, 'expense', 37.00, 4, '2025-09-02 19:00:02', '2025-09-02 11:00:02'),
(164, 'expense', 30.00, 3, '2025-09-02 19:07:23', '2025-09-02 11:07:23'),
(165, 'income', 10.00, 4, '2025-09-02 21:50:59', '2025-09-02 13:50:59'),
(166, 'income', 4290.72, 3, '2025-09-03 17:36:21', '2025-09-03 09:36:21'),
(167, 'expense', 1200.00, 4, '2025-09-03 17:36:40', '2025-09-03 09:36:40'),
(168, 'expense', 5886.75, 4, '2025-09-03 17:37:55', '2025-09-03 09:37:55'),
(169, 'income', 6.00, 4, '2025-09-03 19:20:18', '2025-09-03 11:20:18'),
(170, 'income', 32.00, 4, '2025-09-03 19:20:27', '2025-09-03 11:20:27'),
(171, 'income', 34.00, 4, '2025-09-03 19:20:36', '2025-09-03 11:20:36'),
(172, 'income', 11200.00, 1, '2025-09-03 19:22:40', '2025-09-03 11:22:40'),
(174, 'income', 37.00, 4, '2025-09-03 21:47:39', '2025-09-03 13:47:39'),
(177, 'income', 66.46, 3, '2025-09-03 23:45:58', '2025-09-03 15:45:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_balances`
--
ALTER TABLE `daily_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `debts`
--
ALTER TABLE `debts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_income_category` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_balances`
--
ALTER TABLE `daily_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debts`
--
ALTER TABLE `debts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_income_category` FOREIGN KEY (`category_id`) REFERENCES `income_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
