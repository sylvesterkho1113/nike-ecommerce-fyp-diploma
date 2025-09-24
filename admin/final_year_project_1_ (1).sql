-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2024 at 10:46 AM
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
-- Database: `final_year_project(1)`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(2) NOT NULL,
  `Admin_Username` text NOT NULL,
  `Admin_Password` varchar(255) NOT NULL,
  `Admin_Email` text NOT NULL,
  `Admin_Profile_Image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `Admin_Username`, `Admin_Password`, `Admin_Email`, `Admin_Profile_Image`) VALUES
(1, 'Tee chin yean', '202cb962ac59075b964b07152d234b70', 'admin2@gmail.com', 0x696d672f494d475f32303232303732335f3132313831302e6a7067),
(2, 'admin1', '250cf8b51c773f3f8dc8b4be867a9a02', 'admin1@gmail.com', 0x696d672fe5b18fe5b995e688aae59bbe20323032342d30342d3139203135333033382e706e67),
(4, 'Kwc', '202cb962ac59075b964b07152d234b70', 'admin@gmail.com', 0xe5b18fe5b995e688aae59bbe20323032342d30352d3130203135323031302e706e67);

-- --------------------------------------------------------

--
-- Table structure for table `bill_master`
--

CREATE TABLE `bill_master` (
  `Invoice_ID` int(6) NOT NULL,
  `Customer_ID` int(5) NOT NULL,
  `Invoice_Date` date NOT NULL DEFAULT current_timestamp(),
  `Total_Amount` decimal(7,2) NOT NULL,
  `Delivery_Address` text DEFAULT NULL,
  `Invoice_Status` varchar(10) NOT NULL,
  `Remark` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_master`
--

INSERT INTO `bill_master` (`Invoice_ID`, `Customer_ID`, `Invoice_Date`, `Total_Amount`, `Delivery_Address`, `Invoice_Status`, `Remark`) VALUES
(5, 1, '2024-06-05', 124.00, '55, Jalan Bukt Mertajam 8, Taman RZosmerah, kitkat', 'Delivered', ''),
(6, 1, '2024-06-05', 200.00, '33,Jalan Dedap 1, Taman Johor Jaya, Johor Bahru, Johor', 'Pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `bill_master_transaction`
--

CREATE TABLE `bill_master_transaction` (
  `Item_ID` int(10) NOT NULL,
  `Invoice_ID` int(6) NOT NULL,
  `Product_ID` int(4) NOT NULL,
  `Quantity` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_master_transaction`
--

INSERT INTO `bill_master_transaction` (`Item_ID`, `Invoice_ID`, `Product_ID`, `Quantity`) VALUES
(9, 5, 1, 6),
(10, 5, 16, 2),
(11, 6, 1, 4),
(12, 6, 16, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Cart_ID` int(6) NOT NULL,
  `Customer_ID` int(5) NOT NULL,
  `Total_Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`Cart_ID`, `Customer_ID`, `Total_Price`) VALUES
(7, 1, 146.00);

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `CI_ID` int(7) NOT NULL,
  `Cart_ID` int(6) NOT NULL,
  `Product_ID` int(4) NOT NULL,
  `Quantity` int(5) NOT NULL,
  `CI_Status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`CI_ID`, `Cart_ID`, `Product_ID`, `Quantity`, `CI_Status`) VALUES
(13, 7, 1, 9, 0),
(14, 7, 16, 1, 0),
(15, 7, 2, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(5) NOT NULL,
  `Customer_Username` text DEFAULT NULL,
  `Customer_Email` text NOT NULL,
  `Customer_Password` varchar(50) NOT NULL,
  `Customer_Phone_Number` varchar(11) NOT NULL,
  `Billing_Address_Line1` text DEFAULT NULL,
  `Billing_Address_Line2` text DEFAULT NULL,
  `Billing_Address_Line3` text DEFAULT NULL,
  `Billing_Address_Line4` text DEFAULT NULL,
  `Customer_Register_Time` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `Customer_Delivery_Address_1` text DEFAULT NULL,
  `Customer_Delivery_Address_2` text DEFAULT NULL,
  `Customer_Delivery_Address_3` text DEFAULT NULL,
  `Customer_Profile_Image` longblob DEFAULT NULL,
  `Customer_Status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `Customer_Username`, `Customer_Email`, `Customer_Password`, `Customer_Phone_Number`, `Billing_Address_Line1`, `Billing_Address_Line2`, `Billing_Address_Line3`, `Billing_Address_Line4`, `Customer_Register_Time`, `Customer_Delivery_Address_1`, `Customer_Delivery_Address_2`, `Customer_Delivery_Address_3`, `Customer_Profile_Image`, `Customer_Status`) VALUES
(1, 'Sylvester Kho', '1211211485@student.mmu.edu.my', '321022fddb9224d7d705a7cb1707b2f6', '017-7833558', '33, Jalan Dedap 1', 'Taman Johor Jaya', '81100 Johor Bahru', 'Johor, Malaysia', '2024-04-25 11:48:10.592559', '33,Jalan Dedap 1, Taman Johor Jaya, Johor Bahru, Johor', '27, Jalan tegap 7, Taman Gaya, Johor Bahru, Johor', '55, Jalan Bukt Mertajam 8, Taman RZosmerah, kitkat', NULL, 1),
(2, 'kkk', 'khoweicong100@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '0177833558', NULL, NULL, NULL, NULL, '2024-04-26 09:37:03.908830', NULL, NULL, NULL, NULL, 0),
(3, 'YMHHK', 'cccc211@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '01215798527', NULL, NULL, NULL, NULL, '2024-04-26 16:20:07.344655', NULL, NULL, NULL, NULL, 0),
(4, 'Sylvester Kho', 'admin@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '0177833558', NULL, NULL, NULL, NULL, '2024-04-27 21:50:46.703403', NULL, NULL, NULL, NULL, 0),
(5, 'Sylvester Kho', 'khoweicong100@gmail.co', '321022fddb9224d7d705a7cb1707b2f6', '017-7833558', NULL, NULL, NULL, NULL, '2024-05-14 23:13:00.478824', NULL, NULL, NULL, NULL, 0),
(6, 'll', 'll@gmail', '321022fddb9224d7d705a7cb1707b2f6', '017-7833558', NULL, NULL, NULL, NULL, '2024-05-16 08:52:02.297925', NULL, NULL, NULL, NULL, 0),
(7, 'kkk', '123@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '017-7833558', NULL, NULL, NULL, NULL, '2024-05-27 17:20:40.387594', NULL, NULL, NULL, NULL, 0),
(8, 'Sylvester Kho', 'ahmai859@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '012-7960019', NULL, NULL, NULL, NULL, '2024-05-27 17:25:25.237856', NULL, NULL, NULL, NULL, 0),
(9, 'kkk', '123456@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '017-7833558', NULL, NULL, NULL, NULL, '2024-05-30 00:33:32.334139', NULL, NULL, NULL, NULL, 0),
(10, 'kkk', '1234@gmail.com', '321022fddb9224d7d705a7cb1707b2f6', '017-7833 55', NULL, NULL, NULL, NULL, '2024-05-30 00:51:11.573345', NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int(4) NOT NULL,
  `Size_ID` int(2) NOT NULL,
  `Product_Name` text NOT NULL,
  `Product_Description` text NOT NULL,
  `Product_quantity_available` int(4) NOT NULL,
  `sale_quantity` int(4) NOT NULL,
  `Product_Price` decimal(7,2) NOT NULL,
  `Product_Cost` decimal(7,2) NOT NULL,
  `New_arrive` int(1) NOT NULL,
  `Product_Status` int(1) NOT NULL,
  `PC_ID` int(25) NOT NULL,
  `Product_Image` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `Size_ID`, `Product_Name`, `Product_Description`, `Product_quantity_available`, `sale_quantity`, `Product_Price`, `Product_Cost`, `New_arrive`, `Product_Status`, `PC_ID`, `Product_Image`) VALUES
(1, 1, 't', 'haa', 10, 0, 9.00, 2.00, 1, 0, 3, 'IMG_20220723_121810.jpg'),
(2, 0, 'te', '2', 0, 0, 8.00, 4.00, 0, 0, 0, '20230115_214116.jpg'),
(3, 0, 'test', 'test', 10, 0, 10.00, 1.00, 0, 0, 0, ''),
(4, 0, 'ttt', 'test', 10, 0, 10.00, 1.00, 0, 0, 0, ''),
(5, 0, 'test2', 'test', 10, 0, 10.00, 1.00, 0, 1, 0, ''),
(6, 0, 'test5', 'test', 10, 0, 10.00, 1.00, 0, 0, 0, ''),
(7, 0, '', '', 0, 0, 0.00, 0.00, 0, 0, 0, ''),
(10, 0, 'test update', 'test', 10, 0, 10.00, 1.00, 0, 1, 0, ''),
(11, 0, 'tt', 'w', 2, 0, 22.00, 2.00, 0, 1, 0, ''),
(13, 0, 'product 2', 'kasut pertama', 10, 0, 20.00, 10.00, 0, 0, 0, 'CknA879W5bFP2CRSbJKnch.jpg'),
(14, 0, 'testting 123', '12345', 123, 0, 23.00, 12.00, 0, 0, 0, 't01b42ca72d334c10be.jpg'),
(15, 0, 'testting 123123', '12345', 123, 0, 23.00, 12.00, 0, 1, 0, '319560493_705665844211786_7301884689113764996_n.jpg'),
(16, 0, 'Wei Cong Test', 'Good for Basketball', 14, 0, 32.00, 25.00, 0, 1, 3, 'My timetable.png'),
(0, 17, 'test video2', '2', 46, 0, 12.00, 4.00, 0, 0, 2, '319560493_705665844211786_7301884689113764996_n.jpg'),
(0, 19, 'test video3', '2', 46, 0, 12.00, 4.00, 0, 0, 2, 'IMG_20220723_121810.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `PC_ID` int(2) NOT NULL,
  `Category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`PC_ID`, `Category`) VALUES
(1, 'lifestyle'),
(2, 'Running'),
(3, 'Basketball'),
(4, 'Football');

-- --------------------------------------------------------

--
-- Table structure for table `product_delete`
--

CREATE TABLE `product_delete` (
  `Pd_Id` int(25) NOT NULL,
  `Product_ID` int(25) NOT NULL,
  `Description` text NOT NULL,
  `Date_of_delete` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_delete`
--

INSERT INTO `product_delete` (`Pd_Id`, `Product_ID`, `Description`, `Date_of_delete`) VALUES
(0, 1, 'testing ', '2024-06-07');

-- --------------------------------------------------------

--
-- Table structure for table `product_quantity_change`
--

CREATE TABLE `product_quantity_change` (
  `id` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantity_Change` int(11) DEFAULT NULL,
  `Change_Type` char(1) DEFAULT NULL,
  `Change_Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_quantity_change`
--

INSERT INTO `product_quantity_change` (`id`, `Product_ID`, `Description`, `Quantity_Change`, `Change_Type`, `Change_Date`) VALUES
(2, 12, 'test', 12, '+', '2024-05-30 12:49:54'),
(3, 12, 'test', 13, '+', '2024-05-30 12:51:27'),
(4, 12, 'test', 14, '+', '2024-05-30 12:51:49'),
(5, 12, 'test', 5, '+', '2024-05-31 04:12:18'),
(6, 12, 'test', 7, '+', '2024-05-31 04:14:00'),
(7, 12, 'test', -10, '-', '2024-05-31 04:14:14'),
(8, 1, 'test', 7, '+', '2024-06-07 02:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `record_time`
--

CREATE TABLE `record_time` (
  `Product_ID` int(4) NOT NULL,
  `record_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `record_time`
--

INSERT INTO `record_time` (`Product_ID`, `record_time`) VALUES
(1, '2024-03-19 08:26:34'),
(2, '2023-03-19 08:26:34'),
(16, '2024-04-25 08:06:44'),
(17, '2024-06-07 10:54:56'),
(18, '2024-06-07 10:56:39');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `Size_ID` int(2) NOT NULL,
  `gender` int(1) NOT NULL,
  `Size` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`Size_ID`, `gender`, `Size`) VALUES
(1, 0, 5.5),
(2, 0, 6),
(3, 0, 6.5),
(4, 0, 7),
(5, 0, 7.5),
(6, 0, 8),
(7, 0, 8.5),
(8, 0, 9),
(9, 0, 9.5),
(10, 0, 10),
(11, 0, 10.5),
(12, 0, 11),
(13, 0, 11.5),
(14, 0, 12.5),
(15, 0, 13.5),
(16, 0, 14.5),
(17, 0, 15.5),
(18, 1, 2),
(19, 1, 2.5),
(20, 1, 3),
(21, 1, 3.5),
(22, 1, 4),
(23, 1, 4.5),
(24, 1, 5),
(25, 1, 5.5),
(26, 1, 6),
(27, 1, 6.5),
(28, 1, 7),
(29, 1, 7.5),
(30, 1, 8),
(31, 1, 8.5),
(32, 1, 9),
(33, 1, 9.5),
(34, 1, 10),
(35, 2, 11.5),
(36, 2, 12),
(37, 2, 12.5),
(38, 2, 13),
(39, 2, 14),
(40, 2, 1),
(41, 2, 1.5),
(42, 2, 2),
(43, 2, 2.5),
(44, 2, 3),
(45, 2, 3.5),
(46, 2, 4),
(47, 2, 4.5),
(48, 2, 5),
(49, 2, 5.5),
(50, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `time_record`
--

CREATE TABLE `time_record` (
  `Record_ID` int(3) NOT NULL,
  `Admin_ID` int(2) NOT NULL,
  `Login_Time` datetime NOT NULL,
  `Logout_TIme` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_record`
--

INSERT INTO `time_record` (`Record_ID`, `Admin_ID`, `Login_Time`, `Logout_TIme`) VALUES
(1, 1, '2024-03-14 15:50:27', '2024-03-14 15:50:35'),
(2, 1, '2024-03-14 15:51:05', '2024-03-14 15:51:15'),
(3, 1, '2024-03-14 17:12:03', '0000-00-00 00:00:00'),
(4, 1, '2024-03-14 21:08:52', '0000-00-00 00:00:00'),
(5, 1, '2024-03-15 11:39:18', '0000-00-00 00:00:00'),
(6, 1, '2024-03-15 12:08:16', '0000-00-00 00:00:00'),
(7, 1, '2024-03-15 22:17:03', '0000-00-00 00:00:00'),
(8, 1, '2024-03-18 15:43:23', '0000-00-00 00:00:00'),
(9, 1, '2024-03-19 15:27:02', '0000-00-00 00:00:00'),
(10, 1, '2024-03-19 20:46:28', '0000-00-00 00:00:00'),
(11, 1, '2024-03-20 12:47:57', '0000-00-00 00:00:00'),
(12, 1, '2024-03-21 11:53:35', '0000-00-00 00:00:00'),
(13, 1, '2024-03-21 17:33:11', '0000-00-00 00:00:00'),
(14, 1, '2024-03-22 16:29:29', '0000-00-00 00:00:00'),
(15, 1, '2024-03-25 16:20:24', '0000-00-00 00:00:00'),
(16, 1, '2024-03-26 15:35:43', '0000-00-00 00:00:00'),
(17, 1, '2024-03-29 11:40:20', '2024-03-29 11:42:46'),
(18, 10, '2024-03-29 11:43:57', '0000-00-00 00:00:00'),
(19, 10, '2024-04-01 15:46:27', '0000-00-00 00:00:00'),
(20, 10, '2024-04-02 21:44:48', '0000-00-00 00:00:00'),
(21, 10, '2024-04-04 13:15:17', '2024-04-04 13:16:22'),
(22, 1, '2024-05-20 16:09:32', '2024-05-20 16:24:02'),
(23, 1, '2024-05-20 16:25:02', '2024-05-20 16:38:56'),
(24, 2, '2024-05-20 16:39:04', '0000-00-00 00:00:00'),
(25, 1, '2024-06-06 22:23:41', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`);

--
-- Indexes for table `bill_master`
--
ALTER TABLE `bill_master`
  ADD PRIMARY KEY (`Invoice_ID`);

--
-- Indexes for table `bill_master_transaction`
--
ALTER TABLE `bill_master_transaction`
  ADD PRIMARY KEY (`Item_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_ID`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`CI_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD UNIQUE KEY `unique_customer_email` (`Customer_Email`) USING HASH;

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`PC_ID`);

--
-- Indexes for table `product_quantity_change`
--
ALTER TABLE `product_quantity_change`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `record_time`
--
ALTER TABLE `record_time`
  ADD PRIMARY KEY (`Product_ID`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`Size_ID`);

--
-- Indexes for table `time_record`
--
ALTER TABLE `time_record`
  ADD PRIMARY KEY (`Record_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bill_master`
--
ALTER TABLE `bill_master`
  MODIFY `Invoice_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bill_master_transaction`
--
ALTER TABLE `bill_master_transaction`
  MODIFY `Item_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `Cart_ID` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `CI_ID` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `PC_ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_quantity_change`
--
ALTER TABLE `product_quantity_change`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `record_time`
--
ALTER TABLE `record_time`
  MODIFY `Product_ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `Size_ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `time_record`
--
ALTER TABLE `time_record`
  MODIFY `Record_ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
