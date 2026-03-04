-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2026 at 01:08 AM
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
-- Database: `oswa_inv_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(20, 'Adhesives &amp; Sealants'),
(17, 'Electrical Supplies'),
(26, 'Fasteners &amp; Fixings'),
(23, 'Garden Hardware'),
(6, 'Hand Tools'),
(15, 'Lumber &amp; Wood Supplies'),
(21, 'Masonry &amp; Concrete'),
(11, 'Plumbing Supplies'),
(27, 'Power Tools'),
(24, 'Safety &amp; Workwear');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) UNSIGNED NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `zone` varchar(100) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`, `zone`, `status`) VALUES
(3, 'AL-01-A-04', 'Alpha Sector', 1),
(4, 'CC-FRZ-12-B', 'Cold Core', 1),
(5, 'VLT-SEC-09', 'The Vault', 1),
(6, 'NW-44-R2-S1', 'North Wing', 1),
(7, 'RF-PICK-012', 'Rapid Flow', 1),
(8, 'BB10-PAL-22', 'Bulk Bay 10', 1),
(9, 'HZ-CHM-05-D', 'HazMat Hub', 1),
(10, 'MEZ-L2-A7-4', 'Mezzanine Level', 1),
(11, 'RECV-STG-01', 'Inbound Dock A', 1),
(12, 'HIVE-77-X-9', 'The Hive', 1);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `file_name`, `file_type`) VALUES
(14, 'T-shirts.jpg', 'image/jpeg'),
(15, 'Jeans.jpg', 'image/jpeg'),
(16, 'Jackets.jpg', 'image/jpeg'),
(17, 'Dresses.jpg', 'image/jpeg'),
(18, 'Skirts.jpg', 'image/jpeg'),
(19, 'Socks.jpg', 'image/jpeg'),
(20, 'Hats.jpg', 'image/jpeg'),
(21, 'Gloves.jpg', 'image/jpeg'),
(22, 'Scarves.jpg', 'image/jpeg'),
(23, 'Shoes.jpg', 'image/jpeg'),
(24, 'Bed.jpg', 'image/jpeg'),
(25, 'Cameras.jpg', 'image/jpeg'),
(26, 'Chair.jpg', 'image/jpeg'),
(27, 'Dining table.jpg', 'image/jpeg'),
(28, 'Game consoles.jpg', 'image/jpeg'),
(29, 'Laptops.jpg', 'image/jpeg'),
(30, 'Smartphones.jpg', 'image/jpeg'),
(31, 'Sofa.jpg', 'image/jpeg'),
(32, 'Tablets.jpg', 'image/jpeg'),
(33, 'Televisions.jpg', 'image/jpeg'),
(34, 'Wardrobe.jpg', 'image/jpeg'),
(35, 'Steel rods.jpg', 'image/jpeg'),
(37, 'Safety helmets.jpg', 'image/jpeg'),
(38, 'Safety Gloves.jpg', 'image/jpeg'),
(39, 'Pipes.jpg', 'image/jpeg'),
(40, 'Paint.jpg', 'image/jpeg'),
(41, 'Nails.jpg', 'image/jpeg'),
(42, 'Electrical wires.jpg', 'image/jpeg'),
(43, 'Cement.jpg', 'image/jpeg'),
(45, 'Construction adhesive.jpg', 'image/jpeg'),
(46, 'Super glue.jpg', 'image/jpeg'),
(47, 'Epoxy adhesive.jpg', 'image/jpeg'),
(48, 'Silicone sealant.jpg', 'image/jpeg'),
(49, 'Ear protection.jpg', 'image/jpeg'),
(50, 'Safety boots.jpg', 'image/jpeg'),
(51, 'Reflective vest.jpg', 'image/jpeg'),
(52, 'Work gloves.jpg', 'image/jpeg'),
(53, 'Safety helmet.jpg', 'image/jpeg'),
(54, 'Wheelbarrow.jpg', 'image/jpeg'),
(55, 'Bolts.jpg', 'image/jpeg'),
(56, 'Nuts.jpg', 'image/jpeg'),
(57, 'Washers.jpg', 'image/jpeg'),
(58, 'Screws.jpg', 'image/jpeg'),
(59, 'Pruning shears.jpg', 'image/jpeg'),
(60, 'Anchors & Wall plugs.jpg', 'image/jpeg'),
(61, 'Hammer.jpg', 'image/jpeg'),
(62, 'Adjustable spanner.jpg', 'image/jpeg'),
(63, 'Screwdriver set.jpg', 'image/jpeg'),
(64, 'Tape measure.jpg', 'image/jpeg'),
(65, 'Pliers.jpg', 'image/jpeg'),
(66, 'Circular saw.jpg', 'image/jpeg'),
(67, 'Electric drill.jpg', 'image/jpeg'),
(68, 'Impact driver.jpg', 'image/jpeg'),
(69, 'Angle grinder.jpg', 'image/jpeg'),
(70, 'Garden hose.jpg', 'image/jpeg'),
(71, 'Rake.jpg', 'image/jpeg'),
(72, 'Shovel.jpg', 'image/jpeg'),
(73, 'Trowel.jpg', 'image/jpeg'),
(74, 'Concrete blocks.jpg', 'image/jpeg'),
(75, 'Sand.jpg', 'image/jpeg'),
(76, 'Bricks (each).jpg', 'image/jpeg'),
(77, 'Cement bag (50 kg).jpg', 'image/jpeg'),
(78, 'Bulbs (LED).jpg', 'image/jpeg'),
(79, 'Electrical tape.jpg', 'image/jpeg'),
(80, 'Jigsaw.jpg', 'image/jpeg'),
(81, 'Timber planks.jpg', 'image/jpeg'),
(82, 'Sandpaper pack.jpg', 'image/jpeg'),
(83, 'PVC pipes.jpg', 'image/jpeg'),
(84, 'Elbow fittings.jpg', 'image/jpeg'),
(85, 'Ball valve.jpg', 'image/jpeg'),
(86, 'Basin waste.jpg', 'image/jpeg'),
(87, 'Teflon tape (roll).jpg', 'image/jpeg'),
(88, 'Light switches.jpg', 'image/jpeg'),
(89, 'Electrical sockets.jpg', 'image/jpeg'),
(90, 'Extension cords.jpg', 'image/jpeg'),
(91, 'Wood glue.jpg', 'image/jpeg'),
(92, 'MDF sheet.jpg', 'image/jpeg'),
(93, 'Plywood sheet.jpg', 'image/jpeg'),
(94, 'Screenshot 2026-03-02 203036.png', 'image/png');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) UNSIGNED NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  `location_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `location_id`) VALUES
(44, 'Bolts (1/2″, per 100 pcs)', '1106', 2000.00, 3000.00, 26, 55, '2026-03-02 15:10:21', 3),
(45, 'Nuts (1/2″, per 100 pcs)', '2303', 1800.00, 2500.00, 26, 56, '2026-03-02 15:10:55', 3),
(46, 'Washers (per 100 pcs)', '1227', 800.00, 1500.00, 26, 57, '2026-03-02 15:11:32', 3),
(47, 'Screws (wood, per 100 pcs)', '1226', 1200.00, 2000.00, 26, 58, '2026-03-02 15:12:11', 3),
(48, 'Anchors & Wall plugs (per 100)', '1284', 1000.00, 1800.00, 26, 60, '2026-03-02 15:12:53', 3),
(49, 'Hammer', '43', 700.00, 1500.00, 6, 61, '2026-03-02 15:17:35', 11),
(50, 'Adjustable spanner', '53', 800.00, 2000.00, 6, 62, '2026-03-02 15:18:10', 11),
(51, 'Screwdriver set', '34', 1000.00, 2500.00, 6, 63, '2026-03-02 15:18:52', 11),
(52, 'Tape measure (5 m)', '22', 400.00, 900.00, 6, 64, '2026-03-02 15:19:28', 11),
(53, 'Pliers', '32', 600.00, 1500.00, 6, 65, '2026-03-02 15:19:54', 11),
(54, 'Angle grinder', '54', 3500.00, 6000.00, 27, 69, '2026-03-02 15:21:02', 6),
(55, 'Electric drill', '43', 3000.00, 7000.00, 27, 67, '2026-03-02 15:21:34', 6),
(56, 'Circular saw', '33', 4000.00, 8000.00, 27, 66, '2026-03-02 15:22:06', 6),
(57, 'Impact driver', '44', 3500.00, 4000.00, 27, 68, '2026-03-02 15:22:43', 6),
(58, 'Jigsaw', '54', 3500.00, 4500.00, 27, 80, '2026-03-02 15:23:19', 6),
(59, 'Timber planks (per board)', '121', 400.00, 650.00, 15, 81, '2026-03-02 15:24:01', 12),
(60, 'Plywood sheet (8×4)', '104', 2500.00, 3400.00, 15, 93, '2026-03-02 15:24:30', 12),
(61, 'MDF sheet (8×4)', '212', 2000.00, 3540.00, 15, 92, '2026-03-02 15:25:08', 12),
(62, 'Wood glue (1 L)', '234', 900.00, 1230.00, 15, 91, '2026-03-02 15:25:40', 12),
(63, 'Sandpaper pack', '407', 400.00, 550.00, 15, 82, '2026-03-02 15:26:09', 12),
(64, 'PVC pipes (3 in, per meter)', '123', 400.00, 600.00, 11, 83, '2026-03-02 15:26:52', 4),
(65, 'Elbow fittings', '221', 100.00, 270.00, 11, 84, '2026-03-02 15:27:26', 4),
(66, 'Ball valve', '113', 400.00, 544.00, 11, 85, '2026-03-02 15:27:53', 4),
(67, 'Basin waste', '300', 300.00, 555.00, 11, 86, '2026-03-02 15:28:33', 4),
(68, 'Teflon tape (roll)', '123', 100.00, 230.00, 11, 87, '2026-03-02 15:43:57', 4),
(69, 'Light switches', '321', 200.00, 281.00, 17, 88, '2026-03-02 15:47:38', 9),
(70, 'Electrical sockets', '419', 300.00, 435.00, 17, 89, '2026-03-02 15:48:16', 9),
(71, 'Extension cords', '123', 800.00, 1100.00, 17, 90, '2026-03-02 15:48:50', 9),
(72, 'Electrical tape', '123', 300.00, 435.00, 17, 79, '2026-03-02 15:49:13', 9),
(73, 'Bulbs (LED)', '321', 300.00, 350.00, 17, 78, '2026-03-02 15:49:44', 9),
(74, 'Cement bag (50 kg)', '124', 700.00, 870.00, 21, 77, '2026-03-02 15:50:22', 8),
(75, 'Bricks (each)', '1123', 50.00, 65.00, 21, 76, '2026-03-02 15:50:56', 8),
(76, 'Sand (per ton)', '123', 2000.00, 3400.00, 21, 75, '2026-03-02 15:51:28', 8),
(77, 'Concrete blocks (each)', '549', 100.00, 123.00, 21, 74, '2026-03-02 15:52:10', 8),
(78, 'Trowel', '132', 700.00, 874.00, 21, 73, '2026-03-02 15:52:42', 8),
(79, 'Shovel', '123', 800.00, 980.00, 23, 72, '2026-03-02 15:53:29', 7),
(80, 'Rake', '124', 800.00, 987.00, 23, 71, '2026-03-02 15:54:02', 7),
(81, 'Garden hose', '324', 768.00, 897.00, 23, 70, '2026-03-02 15:54:35', 7),
(82, 'Pruning shears', '432', 700.00, 912.00, 23, 59, '2026-03-02 15:55:06', 7),
(83, 'Wheelbarrow', '123', 4000.00, 5560.00, 23, 54, '2026-03-02 15:55:32', 7),
(84, 'Safety helmet', '342', 800.00, 987.00, 24, 53, '2026-03-02 15:56:20', 10),
(85, 'Work gloves', '321', 300.00, 465.00, 24, 52, '2026-03-02 15:56:49', 10),
(86, 'Reflective vest', '432', 600.00, 876.00, 24, 51, '2026-03-02 15:57:17', 10),
(87, 'Safety boots', '324', 2000.00, 3500.00, 24, 50, '2026-03-02 15:57:41', 10),
(88, 'Ear protection', '123', 500.00, 768.00, 24, 49, '2026-03-02 15:58:07', 10),
(89, 'Silicone sealant', '321', 800.00, 987.00, 20, 48, '2026-03-02 15:58:59', 5),
(90, 'Epoxy adhesive', '32', 1000.00, 1254.00, 20, 47, '2026-03-02 15:59:34', 5),
(91, 'Super glue', '154', 300.00, 456.00, 20, 46, '2026-03-02 16:00:21', 5),
(92, 'Construction adhesive', '321', 1200.00, 3000.00, 20, 45, '2026-03-02 16:00:51', 5);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(34, 44, 8, 3000.00, '2026-03-02'),
(35, 60, 7, 3400.00, '2026-03-02'),
(36, 63, 13, 7150.00, '2026-03-02'),
(37, 67, 14, 555.00, '2026-03-02'),
(38, 70, 13, 435.00, '2026-03-02'),
(39, 67, 7, 555.00, '2026-03-02'),
(40, 44, 9, 3000.00, '2026-03-03'),
(41, 45, 9, 2500.00, '2026-03-03'),
(42, 46, 4, 1500.00, '2026-03-03'),
(43, 47, 6, 2000.00, '2026-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `user_level`, `image`, `reset_token`, `reset_token_expires`, `status`, `last_login`) VALUES
(1, ' Admin', 'Admin', 'admin@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, '0002171.jpg', NULL, NULL, 1, '2026-03-03 03:03:20'),
(3, 'Valery', 'Pat', 'pat@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 2, 'uciqnk2f3.jpg', NULL, NULL, 1, '2026-03-03 02:58:44'),
(4, 'Roharn', 'Roharn', 'roharn@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 2, 'harn.png', NULL, NULL, 1, '2026-03-03 02:34:02'),
(9, 'Kathryn', 'Kate', 'kathrynmwamburi@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3, 'no_image.jpg', NULL, NULL, 1, '2026-02-22 08:26:46'),
(14, 'Vince', 'vince', 'vinniemariba2004@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3, 'no_image.jpg', '73407f218f3af1fe70dc27a62708099aeb37fc4e14ec52aaef4121e0d4717be9', '2026-03-04 03:04:24', 1, '2026-03-03 00:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'Manager', 2, 1),
(3, 'Staff', 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `categorie_id` (`categorie_id`),
  ADD KEY `media_id` (`media_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
