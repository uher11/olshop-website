-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 05:19 AM
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
-- Database: `olshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'uher', 'bfbafe31d251ffdf4578c4824a744e36');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_product` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_total` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_product`, `jumlah`, `harga_total`) VALUES
(84, 107, 11, 1, 55),
(85, 108, 2, 1, 50),
(86, 109, 1, 16, 640),
(87, 110, 20, 50, 2250),
(88, 110, 19, 50, 1750),
(89, 110, 1, 34, 1360),
(90, 110, 2, 49, 2450),
(91, 110, 10, 50, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `nomor_hp` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `nomor_hp`, `alamat`) VALUES
(1, 'Emier', '085245468974', 'Bogor'),
(2, 'Rian Abidin', '082123655698', 'Bekasi'),
(3, 'Ujang Hermawan', '082213741911', 'Kp. Cibodas, Rt 01/14, Ds. Suntenjaya, Kec. Lembang, Kab. Bandung Barat'),
(4, 'Evriyantoro', '081365987745', 'Pondok Kelapa');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id_product`, `name`, `description`, `price`, `category`, `stock`) VALUES
(1, 'Heavenly Choco Lava', 'Chocolate cake with melted chocolate inside', 40.00, 'desserts', 50),
(2, 'Caramel Dream Cheesecake', 'Soft cheesecake with caramel topping', 50.00, 'desserts', 50),
(3, 'Matcha Fantasy Pudding', 'Matcha pudding with red bean sauce', 35.00, 'desserts', 100),
(4, 'Tropical Paradise Fruit Bowl', 'Mix of fresh fruits with yogurt', 30.00, 'desserts', 100),
(5, 'Cookies & Cream Delight', 'Cookies & cream ice cream with Oreo toppings', 45.00, 'desserts', 100),
(6, 'Iced Caramel Macchiato', 'Espresso coffee with caramel and cold milk', 40.00, 'drinks', 100),
(7, 'Midnight Mocha Latte', 'Combination of espresso and premium chocolate', 45.00, 'drinks', 100),
(8, 'Blue Ocean Lemonade', 'Refreshing lemonade with a touch of blue syrup', 30.00, 'drinks', 100),
(9, 'Berry Bliss Smoothie', 'Smoothie mix of blueberry, strawberry, and yogurt', 35.00, 'drinks', 100),
(10, 'Matcha Latte Supreme', 'Matcha green tea with milk and soft foam', 40.00, 'drinks', 50),
(11, 'The Mighty Beef Bowl', 'Rice with sliced beef in a special seasoning', 55.00, 'heavy_meals', 100),
(12, 'Royal Chicken Steak', 'Chicken steak with special sauce', 65.00, 'heavy_meals', 100),
(13, 'Ocean Delight Grilled Salmon', 'Grilled salmon with premium spices', 85.00, 'heavy_meals', 100),
(14, 'Ultimate Cheese Burger', 'Beef burger with melted cheese', 50.00, 'heavy_meals', 100),
(15, 'Spicy Samurai Ramen', 'Spicy ramen with grilled chicken topping', 45.00, 'heavy_meals', 100),
(16, 'Golden Crispy Fries', 'Crispy french fries with special sauce', 25.00, 'light_bites', 100),
(17, 'Cheesy Mozzarella Sticks', 'Melted mozzarella sticks with marinara sauce', 30.00, 'light_bites', 100),
(18, 'Crunchy Nacho Fiesta', 'Nachos with cheese, salsa, and guacamole', 40.00, 'light_bites', 100),
(19, 'Chicken Popcorn Madness', 'Crispy fried chicken in small bites', 35.00, 'light_bites', 50),
(20, 'Spicy Wings Blast', 'Spicy chicken wings with special BBQ sauce', 45.00, 'light_bites', 50);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `nama_pelanggan` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tanggal_transaksi` datetime DEFAULT NULL,
  `total_harga` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `nama_pelanggan`, `alamat`, `telepon`, `tanggal_transaksi`, `total_harga`) VALUES
(107, 'Emier', 'Bogor', '085245468974', '2025-03-16 04:40:05', 880000),
(108, 'Rian Abidin', 'Bekasi', '082123655698', '2025-03-16 04:57:29', 800000),
(109, 'Ujang Hermawan', 'Kp. Cibodas, Rt 01/14, Ds. Suntenjaya, Kec. Lembang, Kab. Bandung Barat', '082213741911', '2025-03-16 04:59:49', 10240000),
(110, 'Evriyantoro', 'Pondok Kelapa', '081365987745', '2025-03-16 05:01:40', 156960000);

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `after_insert_transaksi` AFTER INSERT ON `transaksi` FOR EACH ROW BEGIN
    INSERT INTO pelanggan (nama_pelanggan, nomor_hp, alamat)
    VALUES (NEW.nama_pelanggan, NEW.telepon, NEW.alamat)
    ON DUPLICATE KEY UPDATE alamat = NEW.alamat;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `fk_detail_product` (`id_product`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `nama_pelanggan` (`nama_pelanggan`,`nomor_hp`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
