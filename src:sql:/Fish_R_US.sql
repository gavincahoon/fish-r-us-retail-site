-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 08, 2025 at 02:52 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Fish R US`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('fish','supply') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`) VALUES
(1, 'Freshwater Fish', 'fish'),
(2, 'Tropical Fish', 'fish'),
(3, 'Supplies', 'fish');

-- --------------------------------------------------------

--
-- Table structure for table `kpis`
--

CREATE TABLE `kpis` (
  `id` int NOT NULL,
  `metric_name` varchar(100) DEFAULT NULL,
  `value` decimal(12,2) DEFAULT NULL,
  `week` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kpis`
--

INSERT INTO `kpis` (`id`, `metric_name`, `value`, `week`) VALUES
(1, 'Total Sales', 542.00, '2025-06-30'),
(2, 'Items Sold', 38.00, '2025-06-30'),
(3, 'Returns', 2.00, '2025-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Processing',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `discount` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `total_price`, `status`, `created_at`, `discount`, `customer_id`) VALUES
(1, 129.84, 'Cancelled', '2025-07-02 00:00:00', 0, NULL),
(2, 69.42, 'Processing', '2025-07-02 00:00:00', 0, NULL),
(3, 534.76, 'Delivered', '2025-07-02 00:00:00', 0, NULL),
(4, 49.87, 'Delivered', '2025-07-02 00:00:00', 0, NULL),
(5, 74.92, 'Delivered', '2025-07-02 00:00:00', 0, NULL),
(6, 105.38, 'Cancelled', '2025-07-02 00:00:00', 0, NULL),
(9, 56.82, 'Delivered', '2025-07-01 00:00:00', 0, NULL),
(10, 65.93, 'Processing', '2025-07-02 12:15:50', NULL, NULL),
(11, 22.49, 'Processing', '2025-07-07 10:03:28', 0, NULL),
(12, 14.99, 'Processing', '2025-07-07 10:50:05', 0, NULL),
(13, 56.43, 'Processing', '2025-07-07 20:20:40', 0, 1),
(14, 9.99, 'Processing', '2025-07-07 20:25:19', 0, 1),
(17, 22.49, 'Processing', '2025-07-08 09:25:34', 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(5, 13, 1, 1, 9.99),
(6, 13, 5, 1, 6.50),
(7, 13, 13, 1, 24.95),
(8, 13, 12, 1, 14.99),
(9, 14, 1, 1, 9.99),
(13, 17, 1, 1, 9.99),
(14, 17, 2, 1, 12.50);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category_id` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image_url`) VALUES
(1, 'Dwarf gourami', 'A beautiful Dwarf gourami.', 9.99, 2, 'img/whitefish.jpg'),
(2, 'Betta Fish', 'Colorful and easy to care for.', 12.50, 2, 'img/tropical1.jpg'),
(3, 'Aquarium Plant', 'Enhances water oxygen levels.', 5.00, 3, 'img/plant1.webp'),
(4, 'Fish Food Pellets', 'Nutritious daily pellets.', 3.99, 3, 'img/fishfood.jpg'),
(5, 'Gravel Substrate', 'Natural gravel for tanks.', 6.50, 3, 'img/gravel.jpg'),
(6, 'Neon Tetra', 'Small, peaceful schooling fish with vibrant neon blue and red stripes.', 2.49, 2, 'img/neontetra.webp'),
(7, 'White Tigerfish', 'Korean girls love this fish!', 4.99, 1, 'img/tigerfish.jpg'),
(8, 'Corydoras Catfish', 'Bottom-dwelling cleaner fish, great for community aquariums.', 3.99, 1, 'img/corydoras.webp'),
(9, 'Angelfish', 'Graceful freshwater fish with tall fins and striking patterns.', 6.50, 1, 'img/angelfish.webp'),
(10, 'Guppy (Mixed Colors)', 'Hardy and colorful livebearers, great for beginners.', 1.99, 1, 'img/guppy.jpg'),
(11, 'Goldfish', 'Everyone\'s favorite!', 8.99, 1, 'img/goldfish.jpg'),
(12, 'Aquarium Heater (50W)', 'Submersible heater with thermostat for 5–10 gallon tanks.', 14.99, 3, 'img/heater.webp'),
(13, 'Aquarium Filter (Hang-on-Back)', 'Quiet HOB filter with adjustable flow and 3-stage filtration.', 24.95, 3, 'img/filter.jpg'),
(14, 'Java Fern Plant', 'Live aquatic plant that thrives in low light and attaches to driftwood.', 5.99, 3, 'img/javafern.jpg'),
(15, 'Driftwood Decoration (Medium)', 'Natural driftwood for aquascaping and fish hiding spots.', 12.75, 3, 'img/driftwood.jpeg'),
(16, 'Mandarinfish', 'A small, brightly colored member of the dragonet family.', 17.49, 2, 'img/coolfish.jpg'),
(17, 'Aquarium Air Pump (QuietFlow)', 'Oxygenates water and powers air-driven accessories.', 10.99, 3, 'img/pump.jpg'),
(18, 'Aquarium Test Kit', 'All-in-one test kit for pH, ammonia, nitrites, and nitrates.', 19.99, 3, 'img/testkit.jpg'),
(19, 'Fish Net (Small)', 'Gentle mesh net for transferring fish or removing debris.', 2.49, 3, 'img/net.jpg'),
(20, 'Butterfly Koi', 'A type of ornamental fish notable for their elongated finnage.', 6.99, 1, 'img/butterfly-koi.webp');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `username`, `role`) VALUES
(1, 'bsmith', 'admin'),
(2, 'pjones', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_online` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `address`, `is_online`) VALUES
(1, 'Downtown Fish Emporium', '123 Ocean Blvd, Atlantis', 0),
(2, 'Westside Aquatics', '456 Coral Rd, Pacifica', 0),
(3, 'Fish R US Online', 'www.fishrus.com', 1),
(4, 'The Fish Bowl', '321 Aquarium Ave, Seattle, WA 98101', 0),
(5, 'Blue Lagoon Pets', '159 Lakefront Rd, Minneapolis, MN 55401', 0),
(6, 'Bubble & Fins', '951 Tidepool Way, Charleston, SC 29401', 0),
(7, 'Freshwater Finds', '202 Brookside St, Denver, CO 80201', 0),
(8, 'Marine Marvels', '88 Shoreline Blvd, Boston, MA 02101', 0),
(9, 'Aqua City', '741 Aqua Way, Chicago, IL 60601', 0),
(10, 'The Aquatic Corner', '369 Reef Run, Tampa, FL 33601', 0),
(11, 'Fin & Flora', '864 Streamside Ln, Nashville, TN 37201', 0),
(12, 'Tank Topia', '135 Delta Rd, Sacramento, CA 95814', 0),
(13, 'Reef Retreat', '246 Surfside Pkwy, Honolulu, HI 96801', 0),
(14, 'Gill Stop', '1739 Bayou Blvd, New Orleans, LA 70101', 0),
(15, 'The Wet Spot', '111 Rainwater Dr, Austin, TX 73301', 0),
(16, 'Fishy Business', '505 Bubbler St, Atlanta, GA 30301', 0),
(17, 'Aqua Haven', '123 Coral Reef Blvd, Miami, FL 33101', 0),
(18, 'Neptune’s Nook', '456 Oceanview Dr, San Diego, CA 92101', 0),
(19, 'Fin Friends', '789 Riverbend Ln, Portland, OR 97201', 0),
(20, 'Cascade Aquatics', '777 Springwater Rd, Boise, ID 83701', 0);

-- --------------------------------------------------------

--
-- Table structure for table `store_inventory`
--

CREATE TABLE `store_inventory` (
  `store_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `store_inventory`
--

INSERT INTO `store_inventory` (`store_id`, `product_id`, `quantity`) VALUES
(1, 4, 12),
(1, 7, 5),
(2, 4, 8),
(2, 9, 6),
(3, 1, 12),
(3, 5, 7),
(4, 6, 9),
(4, 11, 4),
(5, 2, 10),
(5, 12, 3),
(5, 13, 8),
(6, 8, 6),
(7, 10, 11),
(8, 13, 8),
(9, 14, 10),
(10, 15, 5),
(11, 16, 6),
(12, 17, 7),
(13, 10, 4),
(14, 5, 10),
(14, 19, 6),
(17, 4, 11);

-- --------------------------------------------------------

--
-- Table structure for table `store_visits`
--

CREATE TABLE `store_visits` (
  `user_id` int NOT NULL,
  `store_id` int NOT NULL,
  `visit_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','employee','customer') NOT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `is_active`) VALUES
(1, 'bsmith', '$2b$12$VXmy1P1imgUF34P0SzA1ZuSEL2eDR/BdqQbsNpKkYx6QlLayjXRjS', 'bsmith@gmail.com', 'admin', 1),
(2, 'pjones', '$2b$12$u6mXpHhD98Xv5ah5tj2X2OL7NE3VXPNeXkrq/e56Eu2jMdHIG3Ywa', 'pjones@gmail.com', 'customer', 1),
(5, 'Employee', '$2y$10$.9elALtjrwOewpLzqnVi/.lPHrRnFhTroetnJCQPtgLrp/AWWV5SW', NULL, 'employee', 1),
(6, 'lukef', '$2y$10$amVg5J0kGeOQYiOTFWrADumvdwy6Ur6rerZhzS8Nc2Po3aLfPg78e', NULL, 'admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_info` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `contact_info`) VALUES
(1, 'AquaSupply Inc.', 'aquasupply@example.com | (555) 123-4567'),
(2, 'FishCo Distributors', 'fishco@example.com | (555) 234-5678'),
(3, 'OceanLife Wholesalers', 'oceanlife@example.com | (555) 345-6789'),
(4, 'AquaLife Distributors', 'aquaticlife@example.com | (555) 120-4500'),
(5, 'Marine Wonders Co.', 'sales@marinewonders.com | (555) 330-8877'),
(6, 'Freshwater Friends LLC', 'contact@freshfriends.org | (555) 404-3344'),
(7, 'Tropical Tanks Supply', 'tropicals@tanksupply.net | (555) 823-1277'),
(8, 'GreenScape Aquatics', 'greenscape@aqua.com | (555) 555-1234'),
(9, 'Oceanic Reef Supplies', 'reef@oceanic.com | (555) 726-9933'),
(10, 'Blue Lagoon Wholesale', 'info@bluelagoon.co | (555) 292-8881'),
(11, 'AquaEssentials', 'essentials@aquaessentials.com | (555) 234-9009'),
(12, 'Fish Haven Distribution', 'fhaven@distribute.com | (555) 412-4780'),
(13, 'EcoMarine Goods', 'eco@marinegoods.com | (555) 987-6222'),
(14, 'CoralCraft Suppliers', 'hello@coralcraft.com | (555) 765-4311'),
(15, 'Neptune Pet Supply', 'orders@neptunepet.com | (555) 233-5678'),
(16, 'Crystal Tank Co.', 'support@crystaltank.com | (555) 617-9845'),
(17, 'Amazonian Imports', 'amazonian@importers.com | (555) 321-8765'),
(18, 'Rock & Reef Supply', 'rocks@reefrock.com | (555) 141-9000'),
(19, 'Zen Aquariums Ltd.', 'zen@aquariums.co | (555) 707-8800'),
(20, 'AquaTech Systems', 'tech@aquasystems.com | (555) 651-1122');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_products`
--

CREATE TABLE `vendor_products` (
  `vendor_id` int NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpis`
--
ALTER TABLE `kpis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customer` (`customer_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_inventory`
--
ALTER TABLE `store_inventory`
  ADD PRIMARY KEY (`store_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `store_visits`
--
ALTER TABLE `store_visits`
  ADD PRIMARY KEY (`user_id`,`store_id`,`visit_date`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_products`
--
ALTER TABLE `vendor_products`
  ADD PRIMARY KEY (`vendor_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kpis`
--
ALTER TABLE `kpis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `store_inventory`
--
ALTER TABLE `store_inventory`
  ADD CONSTRAINT `store_inventory_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`),
  ADD CONSTRAINT `store_inventory_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `store_visits`
--
ALTER TABLE `store_visits`
  ADD CONSTRAINT `store_visits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `store_visits_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `vendor_products`
--
ALTER TABLE `vendor_products`
  ADD CONSTRAINT `vendor_products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `vendor_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
