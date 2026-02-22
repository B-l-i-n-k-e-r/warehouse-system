-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql105.infinityfree.com
-- Generation Time: Feb 22, 2026 at 09:52 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41211966_oswa_inv`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(25, 'Accessories + Jewelry'),
(13, 'Clothing'),
(10, 'Electronics/Gadgets'),
(18, 'Fitness'),
(16, 'Furniture'),
(26, 'Garden Products'),
(7, 'Home DÃ©cor'),
(6, 'Household  Cleaning'),
(27, 'Industrial/Construction Products'),
(20, 'Office Supplies'),
(15, 'Packaged Goods'),
(11, 'Perishable Goods'),
(23, 'Pharmaceuticals'),
(17, 'Sports'),
(21, 'Stationery'),
(24, 'Technology/IT Equipment'),
(22, 'Toys And Games');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) UNSIGNED NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `zone` varchar(100) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
(36, 'Screws.jpg', 'image/jpeg'),
(37, 'Safety helmets.jpg', 'image/jpeg'),
(38, 'Safety Gloves.jpg', 'image/jpeg'),
(39, 'Pipes.jpg', 'image/jpeg'),
(40, 'Paint.jpg', 'image/jpeg'),
(41, 'Nails.jpg', 'image/jpeg'),
(42, 'Electrical wires.jpg', 'image/jpeg'),
(43, 'Cement.jpg', 'image/jpeg'),
(44, 'Bricks.jpg', 'image/jpeg');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`, `location_id`) VALUES
(13, 'T-shirts', '23', '300.00', '453.00', 13, 14, '2026-02-22 03:34:41', 6),
(14, 'Jeans', '33', '500.00', '700.00', 13, 15, '2026-02-22 03:35:24', 6),
(15, 'Jackets', '19', '1000.00', '1200.00', 13, 16, '2026-02-22 03:36:19', 6),
(16, 'Dresses', '11', '450.00', '500.00', 13, 17, '2026-02-22 03:37:02', 6),
(17, 'Skirts', '54', '330.00', '400.00', 13, 18, '2026-02-22 03:37:42', 6),
(18, 'Socks', '33', '30.00', '50.00', 13, 19, '2026-02-22 03:38:21', 6),
(19, 'Shoes', '43', '2000.00', '2500.00', 13, 23, '2026-02-22 03:39:42', 6),
(20, 'Hats', '19', '255.00', '350.00', 13, 20, '2026-02-22 03:40:32', 6),
(21, 'Gloves', '43', '500.00', '650.00', 13, 21, '2026-02-22 03:41:05', 6),
(22, 'Scarves', '111', '67.00', '100.00', 13, 22, '2026-02-22 03:41:41', 6),
(23, 'Bed', '10', '4500.00', '5020.00', 16, 24, '2026-02-22 08:38:39', 8),
(24, 'Sofa', '18', '3400.00', '4000.00', 16, 31, '2026-02-22 08:39:49', 8),
(25, 'Dining table', '18', '4500.00', '4800.00', 16, 27, '2026-02-22 08:40:30', 8),
(26, 'Chair', '43', '650.00', '800.00', 16, 26, '2026-02-22 08:41:05', 8),
(27, 'Wardrobe', '18', '6500.00', '7000.00', 16, 34, '2026-02-22 08:41:49', 8),
(28, 'Smartphones', '44', '35000.00', '40000.00', 10, 30, '2026-02-22 08:42:43', 3),
(29, 'Laptops', '32', '35000.00', '44000.00', 10, 29, '2026-02-22 08:43:19', 3),
(30, 'Tablets', '22', '18000.00', '21000.00', 10, 32, '2026-02-22 08:43:57', 3),
(31, 'Televisions', '14', '23000.00', '25000.00', 10, 33, '2026-02-22 08:46:57', 3),
(32, 'Cameras', '33', '34000.00', '40000.00', 10, 25, '2026-02-22 08:47:52', 3),
(33, 'Game consoles', '25', '2500.00', '3000.00', 10, 28, '2026-02-22 08:48:39', 3),
(34, 'Safety Gloves', '21', '450.00', '500.00', 27, 38, '2026-02-22 08:58:02', 11),
(35, 'Cement', '36', '650.00', '700.00', 27, 43, '2026-02-22 08:58:39', 11),
(36, 'Steel rods', '45', '1450.00', '1600.00', 27, 35, '2026-02-22 08:59:16', 11),
(37, 'Bricks', '2234', '12.00', '15.00', 27, 44, '2026-02-22 08:59:58', 11),
(38, 'Paint', '50', '2456.00', '3050.00', 27, 40, '2026-02-22 09:00:35', 11),
(39, 'Nails', '12245', '1.00', '3.00', 27, 41, '2026-02-22 09:01:07', 11),
(40, 'Screws', '21345', '2.00', '4.00', 27, 36, '2026-02-22 09:01:46', 11),
(41, 'Pipes', '230', '550.00', '700.00', 27, 39, '2026-02-22 09:02:30', 11),
(42, 'Electrical wires', '118', '1100.00', '1300.00', 27, 42, '2026-02-22 09:03:03', 11),
(43, 'Safety helmets', '40', '975.00', '1000.00', 27, 37, '2026-02-22 09:03:31', 11);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(12, 15, 1, '1200.00', '2026-02-22'),
(13, 16, 2, '500.00', '2026-02-22'),
(14, 20, 3, '350.00', '2026-02-22'),
(15, 15, 3, '1200.00', '2026-02-22'),
(16, 38, 5, '3050.00', '2026-02-22'),
(17, 39, 100, '3.00', '2026-02-22'),
(18, 41, 4, '700.00', '2026-02-22'),
(19, 42, 5, '1300.00', '2026-02-22'),
(20, 43, 3, '1000.00', '2026-02-22'),
(21, 23, 2, '5020.00', '2026-02-22'),
(22, 24, 4, '4000.00', '2026-02-22'),
(23, 25, 6, '4800.00', '2026-02-22'),
(24, 27, 3, '7000.00', '2026-02-22'),
(25, 35, 8, '700.00', '2026-02-22'),
(26, 36, 9, '1600.00', '2026-02-22'),
(27, 37, 111, '15.00', '2026-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, ' Admin', 'Admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'file_0000000014ac71fdadf4077afc2b07f6.png', 1, '2026-02-22 08:25:41'),
(3, 'Valary', 'Pat', '7c222fb2927d828af22f592134e8932480637c0d', 2, 'uciqnk2f3.jpg', 1, '2026-02-20 12:03:01'),
(4, 'Roharn', 'Roharn', '7c222fb2927d828af22f592134e8932480637c0d', 2, 'harn.png', 1, '2026-02-22 07:12:47'),
(7, 'Vince', 'Vince', '7c222fb2927d828af22f592134e8932480637c0d', 3, '0008483.jpg', 1, '2026-02-22 07:54:32'),
(9, 'Kathryn', 'Kate', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 3, 'no_image.jpg', 1, '2026-02-22 08:26:46');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
