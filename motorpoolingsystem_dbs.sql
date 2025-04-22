-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 08:04 AM
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
-- Database: `motorpoolingsystem_dbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `fk_userId` int(11) NOT NULL,
  `fk_vehicleId` int(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `licenseNumber` varchar(100) NOT NULL,
  `contactNumber` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `emergencyContactName` varchar(100) NOT NULL,
  `emergencyContactNumber` int(11) NOT NULL,
  `licenseExpiryDate` date NOT NULL,
  `driverphoto` varchar(100) NOT NULL,
  `driverlicensephoto` varchar(100) NOT NULL,
  `registrationStatus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`fk_userId`, `fk_vehicleId`, `address`, `licenseNumber`, `contactNumber`, `birthdate`, `emergencyContactName`, `emergencyContactNumber`, `licenseExpiryDate`, `driverphoto`, `driverlicensephoto`, `registrationStatus`) VALUES
(110, 44, 'palanas', 'ab124', 2147483647, '2025-07-04', 'Imelda Abapo', 2147483647, '2031-06-26', 'uploads/driver_6783c403b134b.jpg', 'uploads/license_6783c403b160a.jpg', 'approved'),
(111, 45, 'Poblacion', 'AB1312312', 966573803, '2025-01-16', 'Darlene Diacamus', 2147483647, '2025-01-23', 'uploads/driver_6783c5d28bc1d.jpg', 'uploads/license_6783c5d28be8f.png', 'approved'),
(112, 46, 'asd', 'asd', 123, '2025-01-22', 'asda', 1231, '2025-01-30', 'uploads/driver_6783cb77e9aa8.jpg', 'uploads/license_6783cb77e9dae.png', 'approved'),
(115, 48, 'Kalubihan', 'ABC12345', 9665380, '2003-04-07', 'Imelda Abapo', 955572312, '2025-01-02', 'uploads/driver_67845a7bdc707.jpg', 'uploads/license_67845a7bdcac3.png', 'approved'),
(119, 49, 'lk', 'lklk', 2131, '2025-01-20', 'sada', 23, '2025-01-31', 'uploads/driver_6786142cf1bd6.jpg', 'uploads/license_6786142cf2001.jpg', 'approved'),
(122, 50, 'k', 'lklk', 123, '2025-01-29', 'asd', 123, '2025-01-31', 'uploads/driver_678615409468c.jpg', 'uploads/license_6786154094a79.png', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `driverque`
--

CREATE TABLE `driverque` (
  `queId` int(11) NOT NULL,
  `fk_userId` int(11) NOT NULL,
  `dateandtime` datetime NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driverque`
--

INSERT INTO `driverque` (`queId`, `fk_userId`, `dateandtime`, `status`) VALUES
(362, 122, '2025-01-14 08:51:16', 'driving');

-- --------------------------------------------------------

--
-- Table structure for table `riderbooking`
--

CREATE TABLE `riderbooking` (
  `riderbookingID` int(11) NOT NULL,
  `fk_userId` int(11) NOT NULL,
  `pickupPoint` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `contactNumber` int(11) NOT NULL,
  `bookingType` varchar(100) NOT NULL,
  `timeofPickUp` time NOT NULL,
  `numberofPassenger` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `fk_driverId` int(11) DEFAULT NULL,
  `timeArrived` varchar(100) DEFAULT NULL,
  `rideDuration` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riderbooking`
--

INSERT INTO `riderbooking` (`riderbookingID`, `fk_userId`, `pickupPoint`, `destination`, `contactNumber`, `bookingType`, `timeofPickUp`, `numberofPassenger`, `status`, `fk_driverId`, `timeArrived`, `rideDuration`) VALUES
(566, 118, 'lj', 'jk', 123, 'Advance Booking', '12:00:00', 1, 'rebooked', NULL, NULL, ''),
(567, 93, 'kj', 'jkj213', 123, 'Advance Booking', '12:00:00', 1, 'waiting', NULL, NULL, ''),
(568, 118, 'lj', 'jk', 123, 'Advance Booking', '12:00:00', 1, 'rebooked', NULL, NULL, ''),
(569, 118, 'lj', 'jk', 123, 'Advance Booking', '12:00:00', 1, 'completed', 122, '15:46:56', '3h 46m'),
(570, 118, 'kj', 'jk', 123, 'Advance Booking', '12:00:00', 1, 'accepted', 122, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `riderfeedback`
--

CREATE TABLE `riderfeedback` (
  `fk_riderbookingId` int(11) NOT NULL,
  `fk_riderId` int(11) NOT NULL,
  `fk_driverId` int(11) NOT NULL,
  `rate` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riderfeedback`
--

INSERT INTO `riderfeedback` (`fk_riderbookingId`, `fk_riderId`, `fk_driverId`, `rate`, `comment`) VALUES
(569, 118, 122, 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `DriverRegistrationStatus` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `fullname`, `email`, `password`, `role`, `DriverRegistrationStatus`) VALUES
(92, 'rider1', 'rider1@123', '$2y$10$OQQGZDMSxeIsJT/S7uKPweVpUrv9fPCjtYLlP1QCDNQXPeS0F9dYu', 'Rider', NULL),
(93, 'rider2', 'rider2@123', '$2y$10$v8kRg/AmkFd2BAqu4ubYNeGN.swYdDXKJmownQZYH7wo9WVBMtRWi', 'Rider', NULL),
(99, 'rider3', 'rider3@123', '$2y$10$91J.ZrODiM9fpneURn.KnuZKGsYkakxYJbQrbRTpmEAelg5TAmyr2', 'Rider', NULL),
(101, 'admin2', 'admin2@123', '$2y$10$9cnRCHF22pZp36rjEzHFvur1ZhDwzHqOm.NjOV7djzva19VTnMJS.', 'Admin', NULL),
(102, 'admin3', 'admin3@123', '$2y$10$GyfW/CD4lDvQzTSM9ElidObYs/uK3FhPZc.5W4ALoezencPwJp5cm', 'Admin', NULL),
(107, 'admin1', 'admin1@123', '$2y$10$PqoQF6ktTyTg.89oP17QleYK9x3xvURPLNQiaIOCXelyX2UEkz6Vm', 'Admin', NULL),
(110, 'driver1', 'driver1@123', '$2y$10$rc6LPSKHsTxplYdnA8uSQO35uoWjeGBHnwaHLGaK5QACcc4dZZozm', 'Driver', NULL),
(111, 'driver2', 'driver2@123', '$2y$10$yIZXLmLuBfu76MdL8dW4uO7ATibFV/wUi8RROs9tD2qRrJ823w2zC', 'Driver', NULL),
(112, 'driver3', 'driver3@123', '$2y$10$krVJAOhm5psxGaTGlvbBae6BHHsuGm1WVr0v5eQIXLV8QAO3c5/VG', 'Driver', NULL),
(115, 'driver4', 'driver4@123', '$2y$10$1i4wm7Qrai/Ph7zYk5NKBO/9Fg/eXNY8b5pqbV8ILB7KiXsqy9Rmy', 'Driver', NULL),
(116, 'driver5', 'driver5@123', '$2y$10$lLuuk3dfsqc5pZEPyn9wv.eZ1aqXyH2/u/e0sgjPYPcd0sJbkq1P2', 'Driver', NULL),
(117, 'driver6', 'driver6@123', '$2y$10$pzxo5DwjHUZVZMIniSSqGuWbxG/URvjcQtk4f0rwh6RlCbMnIlz.G', 'Driver', NULL),
(118, 'rider10', 'rider10@123', '$2y$10$blQjH8zrzOUNZKfWYvjBeuTE19xiDuXSMKC0je5fdyPBhyM5AzPBK', 'Rider', NULL),
(119, 'driver10', 'driver10@123', '$2y$10$Y8rn3Zfnls7v0pDdm/Ama.f6hEyQAiFIoGTFOO6yaCXZSN.k8UqYO', 'Driver', NULL),
(122, 'driver20', 'driver20@123', '$2y$10$i.YL3Kopl3IgZPIKdiYBUOMT.UOh0i/4MOGXupAlCeo5vLZb7sPOe', 'Driver', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL,
  `vehiclebrand` varchar(100) NOT NULL,
  `vehicleModel` varchar(100) NOT NULL,
  `cylindercapacity` int(11) NOT NULL,
  `vehiclePlateNumber` varchar(100) NOT NULL,
  `vehicleRegistrationPhoto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `vehiclebrand`, `vehicleModel`, `cylindercapacity`, `vehiclePlateNumber`, `vehicleRegistrationPhoto`) VALUES
(35, 'k', 'jk', 998, 'lkl', 'uploads/vehicle_6777df3f95971.jpg'),
(37, 'lkl', '2sad', 123, 'll', 'uploads/vehicle_6777e030665c1.png'),
(38, 'sd', 'sd', 123, 'asd', 'uploads/vehicle_6777e39f8affb.jpg'),
(39, 'asda', 'asd', 123, 'asda', 'uploads/vehicle_6783bc2b75d3a.jpg'),
(40, 'asda', 'asd', 123, 'asda', 'uploads/vehicle_6783bc2c7f297.jpg'),
(41, 'asda', 'asd', 123, 'asda', 'uploads/vehicle_6783bc3067f7c.jpg'),
(42, 'asda', 'asd', 123, 'asda', 'uploads/vehicle_6783bc311523e.jpg'),
(43, 'asda', 'asd', 123, 'asda', 'uploads/vehicle_6783bc38cb89e.jpg'),
(44, 'Rusi', 'Classic 250', 250, 'AGN12323', 'uploads/vehicle_6783c403b1a84.png'),
(45, 'asda', 'asda', 150, 'ABC123', 'uploads/vehicle_6783c5d28c1d4.png'),
(46, 'asd', 'asda', 123, 'asd312', 'uploads/vehicle_6783cb77ea155.png'),
(47, 'asd', 'asda', 123, 'asd312', 'uploads/vehicle_6783cb7c29f42.png'),
(48, 'Rusi', 'Classic 250', 250, 'ABC1234', 'uploads/vehicle_67845a7bdd42d.jpg'),
(49, 'sda', 'asea', 155, 'asda', 'uploads/vehicle_6786142cf23f4.jpg'),
(50, 'asda', 'jk', 123, 'sada', 'uploads/vehicle_6786154094e07.png'),
(51, 'asda', 'jk', 123, 'sada', 'uploads/vehicle_67861541c40e9.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`fk_userId`),
  ADD KEY `fk_vehicleId` (`fk_vehicleId`);

--
-- Indexes for table `driverque`
--
ALTER TABLE `driverque`
  ADD PRIMARY KEY (`queId`),
  ADD KEY `fk_driverId` (`fk_userId`);

--
-- Indexes for table `riderbooking`
--
ALTER TABLE `riderbooking`
  ADD PRIMARY KEY (`riderbookingID`),
  ADD KEY `userId` (`fk_userId`),
  ADD KEY `fk_driverId` (`fk_driverId`);

--
-- Indexes for table `riderfeedback`
--
ALTER TABLE `riderfeedback`
  ADD PRIMARY KEY (`fk_riderbookingId`),
  ADD KEY `fk_riderId` (`fk_riderId`),
  ADD KEY `fk_driverId` (`fk_driverId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `fk_userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `driverque`
--
ALTER TABLE `driverque`
  MODIFY `queId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=363;

--
-- AUTO_INCREMENT for table `riderbooking`
--
ALTER TABLE `riderbooking`
  MODIFY `riderbookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=571;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `driver_ibfk_1` FOREIGN KEY (`fk_userId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `driver_ibfk_2` FOREIGN KEY (`fk_vehicleId`) REFERENCES `vehicle` (`vehicle_id`);

--
-- Constraints for table `driverque`
--
ALTER TABLE `driverque`
  ADD CONSTRAINT `driverque_ibfk_1` FOREIGN KEY (`fk_userId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `riderbooking`
--
ALTER TABLE `riderbooking`
  ADD CONSTRAINT `riderbooking_ibfk_1` FOREIGN KEY (`fk_userId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `riderbooking_ibfk_2` FOREIGN KEY (`fk_driverId`) REFERENCES `user` (`userId`);

--
-- Constraints for table `riderfeedback`
--
ALTER TABLE `riderfeedback`
  ADD CONSTRAINT `riderfeedback_ibfk_1` FOREIGN KEY (`fk_riderId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `riderfeedback_ibfk_2` FOREIGN KEY (`fk_driverId`) REFERENCES `user` (`userId`),
  ADD CONSTRAINT `riderfeedback_ibfk_3` FOREIGN KEY (`fk_riderbookingId`) REFERENCES `riderbooking` (`riderbookingID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
