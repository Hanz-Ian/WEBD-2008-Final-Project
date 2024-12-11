-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 11, 2024 at 05:10 AM
-- Server version: 8.0.39
-- PHP Version: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vintage_archives`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Shirts'),
(2, 'Windbreakers'),
(3, 'Pants');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `brand` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `style` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `category_id`, `name`, `brand`, `description`, `size`, `price`, `style`, `image`, `created_at`) VALUES
(1, 2, 'Full Zip Windbreaker Lightweight', 'Reebok', 'Reebok Mens Jacket Large Full Zip Windbreaker Lightweight 90s Y2K Retro Vtg', 'Large', 75.00, '90s', 'images.jpg', '2024-12-10 07:08:57'),
(2, 1, 'Oversized Graphic Tee', 'DAZZLZZAD', 'DAZZLZZAD Mens Oversized Graphic Tees Y2k Baggy Shirts Vintage Racing Tee Unisex Streetwear Tshirt Patchwork Polo Tee', 'Medium', 27.00, 'Racing', '6145o5gsEUL._AC_SX679_.jpg', '2024-12-10 07:26:41'),
(3, 3, 'Y2K Jeans Men', 'Y2K Wave', 'Add a touch of vintage to your outfits with our Y2K Jeans Men.\r\nOur Y2K Mens Jeans, made from quality material, feature a mid-waist baggy cut, sewn-on black sparkles, 4 pockets', 'Medium', 70.00, 'Y2K', '71RzaJEFdIL._AC_UY1000_.jpg', '2024-12-11 02:55:02'),
(4, 1, 'Atomic Cup Classic Water', 'FromAnother', 'Vintage Atomic Cup Classic Water Racing T-Shirt', 'Extra Large', 45.00, 'Racing', 'atomiccup.jpg', '2024-12-11 04:57:52'),
(5, 1, 'Thrashed and Built To Take The Heat GM', 'RagStock', 'Vintage Thrashed &quot;Built To Take The Heat&quot; GM Racing T-Shirt', 'Extra Large', 45.00, 'Racing', 'vintage-t-shirts-9511-2__33647.jpg', '2024-12-11 05:02:14'),
(6, 3, 'Balloon Pants', 'ZARA', 'Zaras Balloon Fit Jeans for Men', 'Large', 80.00, 'Baggy', '05575301802-p.jpg', '2024-12-11 05:06:40'),
(7, 3, 'Baggy Fit Jeans', 'ZARA', 'Zaras Baggy Fit Jeans for Men', 'Medium', 80.00, 'Baggy', '08062380800-e2.jpg', '2024-12-11 05:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `item_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `item_id`, `user_id`, `name`, `review`, `created_at`) VALUES
(2, 1, 3, 'hanzsrrc', 'I like this to be honest', '2024-12-11 01:27:05'),
(3, 1, 3, 'hanzsrrc', 'Coming back to see this, it looks nice!', '2024-12-11 01:27:36'),
(4, 1, 3, 'hanzsrrc', 'My third time here, I want to buy this!', '2024-12-11 01:27:49'),
(5, 1, 3, 'hanzsrrc', 'Yeah!', '2024-12-11 01:27:55'),
(6, 1, 4, 'user1', 'Is the price $75?', '2024-12-11 02:00:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `username`, `password`, `role`, `created_at`) VALUES
(3, 'hanzemail@redriver.ca', 'hanzsrrc', '$2y$10$IH7/P2OEIRCys44vDu2NfeFUeGv0.6O645y4Rt39CLe2l1KQDbdLi', 'admin', '2024-12-10 02:08:17'),
(4, 'something@something.com', 'user1', '$2y$10$K0b7Lk11sTt0ev8xkhVVEONG5vH3Il78DoAIHek3gcHvwgbrkG91G', 'user', '2024-12-10 07:18:40'),
(6, 'dansemail@email.ca', 'dandan', '$2y$10$fU2qpCvfdaBCzESE7qVjAufZErjDiwKnfQ9J7zQvz5RKYtMaHGoyG', 'admin', '2024-12-11 03:47:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
