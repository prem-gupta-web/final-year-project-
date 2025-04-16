-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2025 at 04:38 PM
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
-- Database: `shopping_website`
--
CREATE DATABASE IF NOT EXISTS `shopping_website` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shopping_website`;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `message`, `submitted_at`) VALUES
(1, 'prem ', 'gprem6783@gmail.com', 4, 'hiiiiiiiiii', '2025-04-01 06:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` datetime NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending Payment',
  `payment_proof` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `email`, `phone`, `address`, `city`, `state`, `zip`, `country`, `total`, `order_date`, `payment_method`, `status`, `payment_proof`, `user_id`, `created_at`) VALUES
(14, 'Prem Bhunjwa', 'gprem6783@gmail.com', '09399143685', 'sarkari kua bai ka baghicha ghamapur', 'Jabalpur', 'Madhya Pradesh', '482001', 'India', 500.00, '2025-04-01 09:48:28', 'Cash on Delivery', 'Pending Payment', NULL, 1, '2025-04-01 07:48:28'),
(15, 'Bhavesh Kundwani', 'bhavesh@gmail.com', '6262942196', 'house no 316, damohnaka shantinagar ', 'Jabalpur', 'Madhya Pradesh', '482001', 'India', 555.00, '2025-04-03 06:40:20', 'UPI', 'Pending Payment', NULL, 2, '2025-04-03 04:40:20'),
(16, 'Bhavesh Kundwani', 'bhavesh@gmail.com', '6262942196', 'house no 316, damohnaka shantinagar ', 'Jabalpur', 'Madhya Pradesh', '482001', 'India', 555.00, '2025-04-03 06:40:42', 'UPI', 'Confirmed', 'Untitled.jpg', 2, '2025-04-03 04:40:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 14, 2, 1, 500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`) VALUES
(1, 'moto buds ', 3999.00, 'images/boat.glb', 'Hi-Res Audio, Large 12.4mm driver, 42 hrs playback & IPx4 rating Bluetooth '),
(2, 'boAt Nirvana Ion wireless ', 2100.00, 'images/boat_nirvana_ion_tws__earbuds.glb', 'Dual EQ Modes, 120 Hours Playback, and Quad Mics to enhance voice .'),
(3, 'Samsung Galaxy Buds FE (Graphite)| ', 5000.00, 'images/green_earbuds_-_realistic_model_high_poly.glb', 'Powerful Active Noise Cancellation | Enriched Bass Sound | Ergonomic Design | 30-Hour Battery Life ... Samsung Galaxy in Ear ...'),
(4, ' Zebronics ZEB-Envy 2', 1500.00, 'images/headphones (1).glb', 'The  headphones allow you to fully enjoy exceptional audio, thanks to the deep bass produced by the dual 40mm powerful speakers.'),
(5, 'boAt Rockerz 450 Pro ', 2999.00, 'images/headphones (2).glb', ' On Ear Headphones with Mic with 70 Hours Battery, 40Mm Drivers, Bluetooth V5.0 Padded Ear Cushions, Easy Access Controls and ...'),
(6, 'Bose QuietComfort ', 7999.00, 'images/headphones.glb', 'Wireless Noise Cancelling Headphones, Bluetooth Over Ear Headphones with Up to 24 Hours of Battery Life, Sandstone - Limited Edition Color.'),
(7, 'JBL Flip 6 Wireless Portable ', 4999.00, 'images/jbl_charge_3_speaker.glb', 'Upto 12 Hours Playtime, IP67 Water & Dustproof, PartyBoost & Personalization App (Without Mic, Black)'),
(8, 'Marshall Emberton ', 11000.00, 'images/portable_bluetooth_speaker.glb', 'IPX7-rated water-resistance rating and a multi-directional ...'),
(9, 'Marshall Emberton II ', 12000.00, 'images/speaker.glb', 'Compact Portable Bluetooth Speaker with 30+ Hours of Playtime, (360° Sound), Dust & Waterproof (IP67) Black & Brass.'),
(10, 'Portronics POR-372 ', 500.00, 'images/keyboard.glb', 'Key2 Combo Wireless Keyboard and Mouse Set, with 2.4 GHz USB Receiver Silent Keystrokes, 1200 DPI Optical Tracking, Multimedia Keys for ...'),
(11, 'Logitech G PRO', 4999.00, 'images/mechanical_keyboard_-_aesthetic.glb', ' Mechanical Gaming Keyboard, Ultra Portable Tenkeyless Design, Detachable Micro USB Cable, 16.8 Million Color LIGHTSYNC RGB Backlit Keys,Black.'),
(12, 'Ninjadog Varna Pro ', 3000.00, 'images/limited_time_offergaming_keyboard.glb', 'Premium Mechanical Wireless Keyboard ; Bluetooth and USB Wireless dongle (Included), Multi Device Support for Upto 3 Hosts (Blue (Clicky) Switch)');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `qr_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'prem', 'prem@gmail.com', '$2y$10$ixwcCmHlnHrf4Fqm7TplEO98JilHKSbbVttAl/.I5EoD0mK7PTC7m'),
(2, 'Bhavesh', 'bhavesh@gmail.com', '$2y$10$U1LzltrzVvmM4l9LuBb8Du7KYQIgLdyxYojHPaPDusrWsr9wuHI/C');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
