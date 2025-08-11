-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2025 at 07:14 AM
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
-- Database: `surplus_to_serve`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `title`, `message`, `created_at`) VALUES
(1, 'hello', 'volunteers', '2024-11-25 10:40:24'),
(2, 'hello2', 'volunteers2', '2024-11-25 10:41:06');

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `badge_name` varchar(255) NOT NULL,
  `volunteer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `badge_name`, `volunteer_id`) VALUES
(3, '100hours', 1),
(5, '50tasks', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reg_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `item_id`, `item_name`, `quantity`, `added_at`, `reg_number`, `created_at`) VALUES
(4, 2, 'noodles', 1, '2024-11-28 13:06:16', '123456', '2024-11-28 13:11:57'),
(5, 2, 'noodles', 2, '2025-01-03 05:21:42', 'user123', '2025-01-03 05:21:42'),
(6, 2, 'noodles', 1, '2025-01-03 05:23:57', 'user123', '2025-01-03 05:23:57'),
(7, 6, 'meals', 1, '2025-01-03 05:25:25', 'user123', '2025-01-03 05:25:25'),
(8, 2, 'noodles', 2, '2025-01-03 05:29:50', '12345', '2025-01-03 05:29:50'),
(9, 2, 'noodles', 2, '2025-01-03 06:06:05', '12345', '2025-01-03 06:06:05'),
(10, 2, 'noodles', 2, '2025-01-03 06:06:10', '12345', '2025-01-03 06:06:10'),
(11, 6, 'meals', 2, '2025-01-03 06:06:24', '12345', '2025-01-03 06:06:24'),
(12, 6, 'meals', 2, '2025-01-03 06:07:28', '12345', '2025-01-03 06:07:28'),
(13, 6, 'meals', 1, '2025-01-03 06:08:49', '12345', '2025-01-03 06:08:49');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `phone`, `message`) VALUES
(1, 'Pardhu', '787859', 'Just testing the website'),
(2, 'tharun', '787859785', 'Hi im tharun'),
(3, 'tharun1', '787859785', 'Hi im tharun'),
(4, 'tharun1', '787859785', 'Hi im tharun'),
(5, 'tharun2', '7878597857', 'Hi im tharun....'),
(6, 'KANKIPATI PARDHU', '123456', '15 november');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donation_type` varchar(50) NOT NULL,
  `food_type` varchar(50) DEFAULT NULL,
  `clothes_type` varchar(100) DEFAULT NULL,
  `clothes_quantity` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `donor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donation_type`, `food_type`, `clothes_type`, `clothes_quantity`, `details`, `pickup_location`, `name`, `phone`, `donor_id`) VALUES
(2, 'food', 'raw food', '', 0, 'rice', 'chennai', 'pardhu', '7207733736', 6),
(3, 'clothes', NULL, 'tt', 10, 'rerg', 'chennai', 'vyshnavi ', '7207733735', 8),
(5, 'clothes', NULL, 'Caargo pants', 10, 'Be Young', 'Chettipedu', 'pardhu', '7878597857', 6),
(6, 'food', 'cooked', NULL, NULL, 'vegetables', 'vijayshanti infiti tower 3 7c', 'tharun', '7207733736', 4),
(7, 'food', 'raw', NULL, NULL, 'rice 10kg, vegetables', 'chennai', '123', '7207733736', 8),
(8, 'food', 'raw', NULL, NULL, 'rice 1kg ,vegetables', 'saveetha school of engineering', 'pardhuk', '7207733736', 6);

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(11) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`donor_id`, `donor_name`, `email`, `contact_number`, `address`, `registration_date`, `password`) VALUES
(4, 'user4', 'user4@gmail.com', '72089564785', 'CHENNAIs', '2024-11-14 05:36:00', '$2y$10$02Go6PJ/is.BiHE1JTuYROKyTtVcJNNWX5fEgfFzQB88odTg2YGeG'),
(6, 'user4', 'user1@gmail.com', '787859', 'CHENNAI', '2024-11-14 08:46:13', '$2y$10$Lq55SXNM6LdiYBG/IoKWb.MEsmQOwMbZq20Ny/.1OMxAPWb01B7re'),
(7, 'new', 'new22@gmail.com', '123456', 'sdsdv', '2024-11-15 08:51:49', '$2y$10$nbQJ2djvpDyFp.fikPcL5OSKZyPKTdAaRsbk6aMFYU9chDJmMJgPK'),
(8, 'new2', 'new2@gmail.com', '123456', 'kanchi', '2024-11-15 08:57:59', '$2y$10$og7HFGbMTQgE8q0pFsP5uOalGz2AXnlCJ9ejL9KN75WI.xtNNtc6e'),
(10, 'KANKIPATI PARDHU', 'kankipatipardhu@gmail.com', '123456', '1-1-52 OLD KALAHASTI STREET NAIDUPETA', '2024-11-22 09:16:54', '$2y$10$LpQrWJSReDJRntVRZbQSz.qp7KiXTltYGxWYDqGK/cHQwcseN1t8C');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `expense_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_name`, `amount`, `category`, `expense_date`, `created_at`) VALUES
(1, 'transport for chennai', 2000.00, 'Transport', '2024-11-21', '2024-11-25 06:34:38'),
(3, 'food packing', 1000.00, 'Food', '2024-11-24', '2024-11-25 09:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `impact_us`
--

CREATE TABLE `impact_us` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `impact_us`
--

INSERT INTO `impact_us` (`id`, `title`, `description`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Feeding Hope: John Paul\'s Story', 'John, a single father of two, was struggling to make ends meet after losing his job. Through SurplusToServe, he received nutritious meals and essential supplies for his family. Today, John has a stable job and volunteers with us to give back to the community that helped him during his toughest times.', 'https://example.com/images/johns_story.jpg', '2024-11-24 14:27:20', '2024-11-24 14:42:06'),
(2, 'A Community United: Helping Anna', 'When Anna\'s small bakery was impacted by a natural disaster, she thought her dream was over. SurplusToServe connected her with surplus ingredients from local businesses, helping her rebuild and continue serving her community. Anna now donates her baked goods to local shelters through our platform.', 'https://example.com/images/anna_bakery.jpg', '2024-11-24 14:27:20', '2024-11-24 14:27:20');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `partner_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `quantity`, `partner_id`) VALUES
(2, 'noodles', 16, 'HOT-237670'),
(3, 'fried rice', 10, 'HOT-237670'),
(6, 'meals', 5, 'GRO-045754');

-- --------------------------------------------------------

--
-- Table structure for table `journey`
--

CREATE TABLE `journey` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `milestone` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `journey`
--

INSERT INTO `journey` (`id`, `year`, `milestone`, `description`) VALUES
(1, 2024, 'created our new journey', 'Lets do it');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `created_at`) VALUES
(1, 'new', 'hello everyone 1st edit', '2024-11-19 04:08:03'),
(3, 'new notifcation for client', 'Hello Mr ', '2024-11-25 09:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `org_name` varchar(255) NOT NULL,
  `reg_number` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `org_name`, `reg_number`, `contact_person`, `email`, `password`, `phone_number`, `address`) VALUES
(2, 'savi', '123456', 'abcdef', 'user1@gmail.com', '$2y$10$r4EXHt4jfwuQ.Ri0VSN7xuTS1qvrNzGd6jaqlX4nXsEHzqjQ8q/AC', '7207773736', '1-1-53 Sivalyam Street'),
(4, 'savir', '124879r', 'KANKIPATI PARDHUs', 'vyshnavikankipati2001@gmail.com', '$2y$10$94Eu3cuoQF4zKV/F44uEXuEYmeBaU2WbJyQQuFG3lhlJnWiDQB5Je', '484845', '1-1-52 OLD KALAHASTI STREET NAIDUPETA'),
(5, 'new', '34', '7207733736', 'newuser@gmail.com', '$2y$10$b5J8cZvMyJ.e.9HL.bZWhOgIDqZxySQCfrcu6uDCOgYmhgYyHf3TG', '7207733736', 'chennai');

-- --------------------------------------------------------

--
-- Table structure for table `organization_requests`
--

CREATE TABLE `organization_requests` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `org_name` varchar(255) NOT NULL,
  `reg_number` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `request_type` enum('food','clothing','funds','medication') NOT NULL,
  `additional_details` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization_requests`
--

INSERT INTO `organization_requests` (`id`, `reference_number`, `org_name`, `reg_number`, `contact_person`, `email`, `address`, `request_type`, `additional_details`, `request_date`, `status`) VALUES
(1, '', 'surplustoserve', '192211078', 'pardhu', 'kankipati.pardhu@gmail.com', '1-1-52 OLD KALAHASTI STREET NAIDUPETA', 'funds', 'ntg', '2024-11-13 06:18:16', 'Rejected'),
(2, 'REF67346633CEF95', 'surplustoserve2', '192211433', 'knull', '', '1-1-52 OLD KALAHASTI STREET NAIDUPETA', 'funds', 'none', '2024-11-13 08:41:23', 'Approved'),
(3, 'REQ-673712087C235', 'new', '34', 'KANKIPATI PARDHU', 'new@gmail.com', '1-1-52 OLD KALAHASTI STREET NAIDUPETA', 'clothing', '', '2024-11-15 04:49:04', 'Pending'),
(4, 'REQ-67456B01379E6', 'surplustoserve', '34', 'KANKIPATI PARDHU', 'user1@gmail.com', '1-1-52 OLD KALAHASTI STREET NAIDUPETA', 'clothing', 'no', '2024-11-26 02:00:25', 'Approved'),
(5, 'REQ-6777613641641', 'surplustoserve', '123456', 'pardhu kankipati', 'kankipatipardhu@gmail.com', '1-1-53 Sivalyam Street\r\nClock Tower Road', 'clothing', '10shhirts', '2025-01-02 23:31:58', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `partner_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('Hotel','Grocery') NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`partner_id`, `name`, `category`, `address`, `contact`, `password`) VALUES
('GRO-045754', 'store2', 'Grocery', 'kanchi', '7207733736', '$2y$10$dEZ5Qwhf61dH9ryaVJVR6OvDSXWraN6UMINPK2DNupIpoPE.z14DW'),
('HOT-237670', 'newstore', 'Hotel', '1-1-52 OLD KALAHASTI STREET NAIDUPETA', '7207733736', '$2y$10$UpVgFfMTvm8qkWEsKHfBAu/gBTD6J8WVGQCIKmzUNOuSqCyd2X6qi');

-- --------------------------------------------------------

--
-- Table structure for table `password_backup`
--

CREATE TABLE `password_backup` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('donor','volunteer','organization') NOT NULL,
  `original_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `reference_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `donor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_method`, `payment_status`, `reference_id`, `amount`, `created_at`, `donor_id`) VALUES
(1, 'upi_number', 'Success', 'PAY674175833dd1a', 500.00, '2024-11-23 06:26:11', 6),
(4, 'upi', 'Success', 'PAY674197af7e971', 725.00, '2024-11-23 08:51:59', 4),
(5, 'qr_code', 'Success', 'PAY674410a41f6de', 10000.00, '2024-11-25 05:52:36', 4),
(6, 'upi', 'Success', 'PAY674569314e606', 1116.00, '2024-11-26 06:22:41', 6);

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `name`, `value`) VALUES
(1, 'Donations Received', '30,000+'),
(2, 'Requests Fulfilled', '15,000+'),
(3, 'Volunteer Hours', '50,000+');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `accepted_by` int(11) DEFAULT NULL,
  `status` enum('available','accepted','completed') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `task_description`, `category`, `assigned_to`, `accepted_by`, `status`) VALUES
(12, 'task2', '2', NULL, 1, 1, 'completed'),
(13, 'task3', 'tasks3', NULL, 1, 1, 'completed'),
(15, 'task4', 'efew', NULL, 1, 1, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `position`, `image_url`, `bio`) VALUES
(2, 'Pardhu', 'Admin', 'uploads/team_images/1732463014-Creator.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `volunteer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`volunteer_id`, `name`, `email`, `mobile`, `password`) VALUES
(1, 'KANKIPATI PARDHU', 'kankipati.pardhu@gmail.com', '7207733736', '$2y$10$nPBz1uzNL1xSd3O4/M7F4OApYhUZeniD46jENio6vLG0V6/EJvLze'),
(4, 'KANKIPATI PARDHU', 'user1@gmail.com', '7207733736', '$2y$10$KmH9DSigbSXuY.hvpDpteel197ekilnFnwZfwHrK8rRTBrIuCwIzq'),
(7, 'abcd', 'abcd@gmail.com', '7207796964', '$2y$10$cl1ZAMtCIJI50qaIr7iprO2GId3ZwjskUe1kIV3dSqyeRfGjYhSCK');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_stats`
--

CREATE TABLE `volunteer_stats` (
  `id` int(11) NOT NULL,
  `volunteer_id` int(11) NOT NULL,
  `hours_volunteered` int(11) DEFAULT 0,
  `tasks_completed` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_stats`
--

INSERT INTO `volunteer_stats` (`id`, `volunteer_id`, `hours_volunteered`, `tasks_completed`) VALUES
(1, 1, 6, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `volunteer_id` (`volunteer_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `impact_us`
--
ALTER TABLE `impact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `partner_id` (`partner_id`);

--
-- Indexes for table `journey`
--
ALTER TABLE `journey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_requests`
--
ALTER TABLE `organization_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `password_backup`
--
ALTER TABLE `password_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_id` (`reference_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `accepted_by` (`accepted_by`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`volunteer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volunteer_stats`
--
ALTER TABLE `volunteer_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `volunteer_id` (`volunteer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `impact_us`
--
ALTER TABLE `impact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `journey`
--
ALTER TABLE `journey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `organization_requests`
--
ALTER TABLE `organization_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `password_backup`
--
ALTER TABLE `password_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `volunteer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `volunteer_stats`
--
ALTER TABLE `volunteer_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badges`
--
ALTER TABLE `badges`
  ADD CONSTRAINT `badges_ibfk_1` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`volunteer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`partner_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `volunteers` (`volunteer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`accepted_by`) REFERENCES `volunteers` (`volunteer_id`) ON DELETE SET NULL;

--
-- Constraints for table `volunteer_stats`
--
ALTER TABLE `volunteer_stats`
  ADD CONSTRAINT `volunteer_stats_ibfk_1` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`volunteer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
