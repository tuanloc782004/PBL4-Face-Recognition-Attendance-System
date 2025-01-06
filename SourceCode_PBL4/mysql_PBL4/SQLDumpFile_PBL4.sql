-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 07:49 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pbl4`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `username`, `password`) VALUES
(1, 'admin123', '123123123');

-- --------------------------------------------------------

--
-- Table structure for table `capdo`
--

CREATE TABLE `capdo` (
  `ID` int NOT NULL,
  `TenCapDo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `capdo`
--

INSERT INTO `capdo` (`ID`, `TenCapDo`) VALUES
(1, 'N1'),
(2, 'N2'),
(3, 'N3'),
(4, 'N4'),
(5, 'N5');

-- --------------------------------------------------------

--
-- Table structure for table `diemdanh`
--

CREATE TABLE `diemdanh` (
  `ID` int NOT NULL,
  `ID_QuanLyNgayHoc` int NOT NULL,
  `ID_HocVien` int NOT NULL,
  `ThoiGianDiemDanh` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TrangThai` enum('Có mặt','Vắng') COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giohoc`
--

CREATE TABLE `giohoc` (
  `ID` int NOT NULL,
  `GioBatDau` time NOT NULL,
  `GioKetThuc` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `giohoc`
--

INSERT INTO `giohoc` (`ID`, `GioBatDau`, `GioKetThuc`) VALUES
(1, '07:00:00', '08:50:00'),
(2, '07:00:00', '23:00:00'),
(3, '07:00:00', '23:00:00'),
(4, '15:30:00', '17:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `hocvien`
--

CREATE TABLE `hocvien` (
  `ID` int NOT NULL,
  `Ten` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `NgaySinh` date NOT NULL,
  `GioiTinh` enum('Nam','Nữ') COLLATE utf8mb3_unicode_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `DiaChi` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `Anh` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `ID_Lop` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `hocvien`
--

INSERT INTO `hocvien` (`ID`, `Ten`, `NgaySinh`, `GioiTinh`, `Email`, `DiaChi`, `Anh`, `ID_Lop`) VALUES
(1, 'Lê Văn Tuấn Lộc	', '2004-01-01', 'Nam', 'tuanloc782004@gmail.com	', 'Đà Nẵng', '', 2),
(2, 'Trần Minh Quang	', '2004-01-01', 'Nam', 'minhquangtran1106@gmail.com	', 'Đà Nẵng', '', 2),
(3, 'Đoàn Công Tuấn Vũ	', '2004-01-01', 'Nam', 'supersaza219@gmail.com	', 'Đà Nẵng', '', 2),
(4, 'Trần Thị Thanh Phương	', '2004-01-01', 'Nữ', 'phuongsuga333@gmail.com	', 'Đà Nẵng', '', 2),
(5, 'Lê Đình Toàn	', '2004-01-01', 'Nam', 'toanledinh76@gmail.com	', 'Đà Nẵng', '', 2),
(6, 'Nguyễn Bá Nam	', '2004-01-01', 'Nam', 'namnguyenba148@gmail.com	', 'Đà Nẵng', '', 2),
(7, 'Phạm Phan Thành	', '2004-01-01', 'Nam', 'thanhlongtivip@gmail.com	', 'Quảng Nam', '', 2),
(8, 'Hồ Thanh Huy	', '2004-01-01', 'Nam', 'hothanhhuy24062004@gmail.com	', 'Quảng Nam', '', 3),
(9, 'Nguyễn Văn Chương	', '2004-01-01', 'Nam', 'nguyenvanchuongwind@gmail.com	', 'Quảng Nam', '', 3),
(10, 'Hồ Sỹ Thảo	', '2004-01-01', 'Nam', 'hosithao1622004@gmail.com	', 'Đà Nẵng', '', 3),
(11, 'Võ Lê Quyên	', '2004-01-01', 'Nam', 'volequyen11042005@gmail.com	', 'Đà Nẵng', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `lop`
--

CREATE TABLE `lop` (
  `ID` int NOT NULL,
  `TenLop` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `ID_CapDo` int NOT NULL,
  `ID_GioHoc` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `lop`
--

INSERT INTO `lop` (`ID`, `TenLop`, `ID_CapDo`, `ID_GioHoc`) VALUES
(1, 'N1', 1, 1),
(2, 'N2', 2, 2),
(3, 'N3/1', 3, 3),
(4, 'N3/2', 3, 4),
(5, 'N4/1', 4, 2),
(6, 'N4/2', 4, 3),
(7, 'N5/1', 5, 1),
(8, 'N5/2', 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `ngayhoc`
--

CREATE TABLE `ngayhoc` (
  `ID` int NOT NULL,
  `TenNgay` date NOT NULL,
  `ID_TuanHoc` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `ngayhoc`
--

INSERT INTO `ngayhoc` (`ID`, `TenNgay`, `ID_TuanHoc`) VALUES
(1, '2024-11-04', 1),
(2, '2024-11-05', 1),
(3, '2024-11-06', 1),
(4, '2024-11-07', 1),
(5, '2024-11-08', 1),
(6, '2024-11-11', 2),
(7, '2024-11-12', 2),
(8, '2024-11-13', 2),
(9, '2024-11-14', 2),
(10, '2024-11-15', 2),
(11, '2024-11-18', 3),
(12, '2024-11-19', 3),
(13, '2024-11-20', 3),
(14, '2024-11-21', 3),
(15, '2024-11-22', 3),
(16, '2024-11-25', 4),
(17, '2024-11-26', 4),
(18, '2024-11-27', 4),
(19, '2024-11-28', 4),
(20, '2024-11-29', 4),
(21, '2024-12-02', 5),
(22, '2024-12-03', 5),
(23, '2024-12-04', 5),
(24, '2024-12-05', 5),
(25, '2024-12-06', 5),
(26, '2024-12-09', 6),
(27, '2024-12-10', 6),
(28, '2024-12-11', 6),
(29, '2024-12-12', 6),
(30, '2024-12-13', 6),
(31, '2024-12-16', 7),
(32, '2024-12-17', 7),
(33, '2024-12-18', 7),
(34, '2024-12-19', 7),
(35, '2024-12-20', 7),
(36, '2024-12-23', 8),
(37, '2024-12-24', 8),
(38, '2024-12-25', 8),
(39, '2024-12-26', 8),
(40, '2024-12-27', 8),
(41, '2024-12-30', 9),
(42, '2024-12-31', 9),
(43, '2025-01-01', 9),
(44, '2025-01-02', 9),
(45, '2025-01-03', 9),
(46, '2025-01-06', 10),
(47, '2025-01-07', 10),
(48, '2025-01-08', 10),
(49, '2025-01-09', 10),
(50, '2025-01-10', 10),
(51, '2025-01-13', 11),
(52, '2025-01-14', 11),
(53, '2025-01-15', 11),
(54, '2025-01-16', 11),
(55, '2025-01-17', 11),
(56, '2025-01-20', 12),
(57, '2025-01-21', 12),
(58, '2025-01-22', 12),
(59, '2025-01-23', 12),
(60, '2025-01-24', 12),
(61, '2025-01-27', 13),
(62, '2025-01-28', 13),
(63, '2025-01-29', 13),
(64, '2025-01-30', 13),
(65, '2025-01-31', 13),
(66, '2025-02-03', 14),
(67, '2025-02-04', 14),
(68, '2025-02-05', 14),
(69, '2025-02-06', 14),
(70, '2025-02-07', 14),
(71, '2025-02-10', 15),
(72, '2025-02-11', 15),
(73, '2025-02-12', 15),
(74, '2025-02-13', 15),
(75, '2025-02-14', 15),
(76, '2025-02-17', 16),
(77, '2025-02-18', 16),
(78, '2025-02-19', 16),
(79, '2025-02-20', 16),
(80, '2025-02-21', 16),
(81, '2024-11-09', 1),
(82, '2024-11-10', 1),
(83, '2024-11-16', 2),
(84, '2024-11-17', 2),
(85, '2024-11-23', 3),
(86, '2024-11-24', 3),
(87, '2024-11-30', 4),
(88, '2024-12-01', 4),
(89, '2024-12-07', 5),
(90, '2024-12-08', 5),
(91, '2024-12-14', 6),
(92, '2024-12-15', 6),
(93, '2024-12-21', 7),
(94, '2024-12-22', 7),
(95, '2024-12-28', 8),
(96, '2024-12-29', 8),
(97, '2025-01-04', 9),
(98, '2025-01-05', 9),
(99, '2025-01-11', 10),
(100, '2025-01-12', 10),
(101, '2025-01-18', 11),
(102, '2025-01-19', 11),
(103, '2025-01-25', 12),
(104, '2025-01-26', 12),
(105, '2025-02-01', 13),
(106, '2025-02-02', 13),
(107, '2025-02-08', 14),
(108, '2025-02-09', 14),
(109, '2025-02-15', 15),
(110, '2025-02-16', 15),
(111, '2025-02-22', 16),
(112, '2025-02-23', 16);

-- --------------------------------------------------------

--
-- Table structure for table `quanlyngayhoc`
--

CREATE TABLE `quanlyngayhoc` (
  `ID` int NOT NULL,
  `ID_Lop` int NOT NULL,
  `ID_NgayHoc` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `quanlyngayhoc`
--

INSERT INTO `quanlyngayhoc` (`ID`, `ID_Lop`, `ID_NgayHoc`) VALUES
(1, 1, 1),
(2, 1, 5),
(3, 1, 6),
(4, 1, 10),
(5, 1, 11),
(6, 1, 15),
(7, 1, 16),
(8, 1, 20),
(9, 1, 21),
(10, 1, 25),
(11, 1, 26),
(12, 1, 30),
(13, 1, 31),
(14, 1, 35),
(15, 1, 36),
(16, 1, 40),
(17, 1, 41),
(18, 1, 45),
(19, 1, 46),
(20, 1, 50),
(21, 1, 51),
(22, 1, 55),
(23, 1, 56),
(24, 1, 60),
(25, 1, 61),
(26, 1, 65),
(27, 1, 66),
(28, 1, 70),
(29, 1, 71),
(30, 1, 75),
(31, 1, 76),
(32, 1, 80),
(33, 2, 1),
(34, 2, 4),
(35, 2, 6),
(36, 2, 9),
(37, 2, 11),
(38, 2, 14),
(39, 2, 16),
(40, 2, 19),
(41, 2, 21),
(42, 2, 24),
(43, 2, 26),
(44, 2, 29),
(45, 2, 31),
(46, 2, 34),
(47, 2, 36),
(48, 2, 39),
(49, 2, 41),
(50, 2, 44),
(51, 2, 46),
(52, 2, 49),
(53, 2, 51),
(54, 2, 54),
(55, 2, 56),
(56, 2, 59),
(57, 2, 61),
(58, 2, 64),
(59, 2, 66),
(60, 2, 69),
(61, 2, 71),
(62, 2, 74),
(63, 2, 76),
(64, 2, 79),
(65, 3, 1),
(66, 3, 3),
(67, 3, 6),
(68, 3, 8),
(69, 3, 11),
(70, 3, 13),
(71, 3, 16),
(72, 3, 18),
(73, 3, 21),
(74, 3, 23),
(75, 3, 26),
(76, 3, 28),
(77, 3, 31),
(78, 3, 33),
(79, 3, 36),
(80, 3, 38),
(81, 3, 41),
(82, 3, 43),
(83, 3, 46),
(84, 3, 48),
(85, 3, 51),
(86, 3, 53),
(87, 3, 56),
(88, 3, 58),
(89, 3, 61),
(90, 3, 63),
(91, 3, 66),
(92, 3, 68),
(93, 3, 71),
(94, 3, 73),
(95, 3, 76),
(96, 3, 78),
(97, 4, 1),
(98, 4, 2),
(99, 4, 6),
(100, 4, 7),
(101, 4, 11),
(102, 4, 12),
(103, 4, 16),
(104, 4, 17),
(105, 4, 21),
(106, 4, 22),
(107, 4, 26),
(108, 4, 27),
(109, 4, 31),
(110, 4, 32),
(111, 4, 36),
(112, 4, 37),
(113, 4, 41),
(114, 4, 42),
(115, 4, 46),
(116, 4, 47),
(117, 4, 51),
(118, 4, 52),
(119, 4, 56),
(120, 4, 57),
(121, 4, 61),
(122, 4, 62),
(123, 4, 66),
(124, 4, 67),
(125, 4, 71),
(126, 4, 72),
(127, 4, 76),
(128, 4, 77),
(129, 5, 2),
(130, 5, 5),
(131, 5, 7),
(132, 5, 10),
(133, 5, 12),
(134, 5, 15),
(135, 5, 17),
(136, 5, 20),
(137, 5, 22),
(138, 5, 25),
(139, 5, 27),
(140, 5, 30),
(141, 5, 32),
(142, 5, 35),
(143, 5, 37),
(144, 5, 40),
(145, 5, 42),
(146, 5, 45),
(147, 5, 47),
(148, 5, 50),
(149, 5, 52),
(150, 5, 55),
(151, 5, 57),
(152, 5, 60),
(153, 5, 62),
(154, 5, 65),
(155, 5, 67),
(156, 5, 70),
(157, 5, 72),
(158, 5, 75),
(159, 5, 77),
(160, 5, 80),
(161, 6, 2),
(162, 6, 4),
(163, 6, 7),
(164, 6, 9),
(165, 6, 12),
(166, 6, 14),
(167, 6, 17),
(168, 6, 19),
(169, 6, 22),
(170, 6, 24),
(171, 6, 27),
(172, 6, 29),
(173, 6, 32),
(174, 6, 34),
(175, 6, 37),
(176, 6, 39),
(177, 6, 42),
(178, 6, 44),
(179, 6, 47),
(180, 6, 49),
(181, 6, 52),
(182, 6, 54),
(183, 6, 57),
(184, 6, 59),
(185, 6, 62),
(186, 6, 64),
(187, 6, 67),
(188, 6, 69),
(189, 6, 72),
(190, 6, 74),
(191, 6, 77),
(192, 6, 79),
(193, 7, 2),
(194, 7, 3),
(195, 7, 7),
(196, 7, 8),
(197, 7, 12),
(198, 7, 13),
(199, 7, 17),
(200, 7, 18),
(201, 7, 22),
(202, 7, 23),
(203, 7, 27),
(204, 7, 28),
(205, 7, 32),
(206, 7, 33),
(207, 7, 37),
(208, 7, 38),
(209, 7, 42),
(210, 7, 43),
(211, 7, 47),
(212, 7, 48),
(213, 7, 52),
(214, 7, 53),
(215, 7, 57),
(216, 7, 58),
(217, 7, 62),
(218, 7, 63),
(219, 7, 67),
(220, 7, 68),
(221, 7, 72),
(222, 7, 73),
(223, 7, 77),
(224, 7, 78),
(225, 8, 3),
(226, 8, 5),
(227, 8, 8),
(228, 8, 10),
(229, 8, 13),
(230, 8, 15),
(231, 8, 18),
(232, 8, 20),
(233, 8, 23),
(234, 8, 25),
(235, 8, 28),
(236, 8, 30),
(237, 8, 33),
(238, 8, 35),
(239, 8, 38),
(240, 8, 40),
(241, 8, 43),
(242, 8, 45),
(243, 8, 48),
(244, 8, 50),
(245, 8, 53),
(246, 8, 55),
(247, 8, 58),
(248, 8, 60),
(249, 8, 63),
(250, 8, 65),
(251, 8, 68),
(252, 8, 70),
(253, 8, 73),
(254, 8, 75),
(255, 8, 78),
(256, 8, 80);

-- --------------------------------------------------------

--
-- Table structure for table `tuanhoc`
--

CREATE TABLE `tuanhoc` (
  `ID` int NOT NULL,
  `TenTuan` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `NgayBatDau` date NOT NULL,
  `NgayKetThuc` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `tuanhoc`
--

INSERT INTO `tuanhoc` (`ID`, `TenTuan`, `NgayBatDau`, `NgayKetThuc`) VALUES
(1, 'Tuần 1', '2024-11-04', '2024-11-10'),
(2, 'Tuần 2', '2024-11-11', '2024-11-17'),
(3, 'Tuần 3', '2024-11-18', '2024-11-24'),
(4, 'Tuần 4', '2024-11-25', '2024-12-01'),
(5, 'Tuần 5', '2024-12-02', '2024-12-08'),
(6, 'Tuần 6', '2024-12-09', '2024-12-15'),
(7, 'Tuần 7', '2024-12-16', '2024-12-22'),
(8, 'Tuần 8', '2024-12-23', '2024-12-29'),
(9, 'Tuần 9', '2024-12-30', '2025-01-05'),
(10, 'Tuần 10', '2025-01-06', '2025-01-12'),
(11, 'Tuần 11', '2025-01-13', '2025-01-19'),
(12, 'Tuần 12', '2025-01-20', '2025-01-26'),
(13, 'Tuần 13', '2025-01-27', '2025-02-02'),
(14, 'Tuần 14', '2025-02-03', '2025-02-09'),
(15, 'Tuần 15', '2025-02-10', '2025-02-16'),
(16, 'Tuần 16', '2025-02-17', '2025-02-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `capdo`
--
ALTER TABLE `capdo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_HocVien` (`ID_HocVien`),
  ADD KEY `ID_QuanLyNgayHoc` (`ID_QuanLyNgayHoc`);

--
-- Indexes for table `giohoc`
--
ALTER TABLE `giohoc`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `hocvien`
--
ALTER TABLE `hocvien`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Lop` (`ID_Lop`);

--
-- Indexes for table `lop`
--
ALTER TABLE `lop`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_CapDo` (`ID_CapDo`),
  ADD KEY `ID_GioHoc` (`ID_GioHoc`);

--
-- Indexes for table `ngayhoc`
--
ALTER TABLE `ngayhoc`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_TuanHoc` (`ID_TuanHoc`);

--
-- Indexes for table `quanlyngayhoc`
--
ALTER TABLE `quanlyngayhoc`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Lop` (`ID_Lop`),
  ADD KEY `ID_NgayHoc` (`ID_NgayHoc`);

--
-- Indexes for table `tuanhoc`
--
ALTER TABLE `tuanhoc`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `capdo`
--
ALTER TABLE `capdo`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `diemdanh`
--
ALTER TABLE `diemdanh`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `giohoc`
--
ALTER TABLE `giohoc`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hocvien`
--
ALTER TABLE `hocvien`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `lop`
--
ALTER TABLE `lop`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ngayhoc`
--
ALTER TABLE `ngayhoc`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `quanlyngayhoc`
--
ALTER TABLE `quanlyngayhoc`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `tuanhoc`
--
ALTER TABLE `tuanhoc`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD CONSTRAINT `diemdanh_ibfk_2` FOREIGN KEY (`ID_HocVien`) REFERENCES `hocvien` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `diemdanh_ibfk_3` FOREIGN KEY (`ID_QuanLyNgayHoc`) REFERENCES `quanlyngayhoc` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hocvien`
--
ALTER TABLE `hocvien`
  ADD CONSTRAINT `hocvien_ibfk_2` FOREIGN KEY (`ID_Lop`) REFERENCES `lop` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lop`
--
ALTER TABLE `lop`
  ADD CONSTRAINT `lop_ibfk_1` FOREIGN KEY (`ID_CapDo`) REFERENCES `capdo` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lop_ibfk_2` FOREIGN KEY (`ID_GioHoc`) REFERENCES `giohoc` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ngayhoc`
--
ALTER TABLE `ngayhoc`
  ADD CONSTRAINT `ngayhoc_ibfk_1` FOREIGN KEY (`ID_TuanHoc`) REFERENCES `tuanhoc` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quanlyngayhoc`
--
ALTER TABLE `quanlyngayhoc`
  ADD CONSTRAINT `quanlyngayhoc_ibfk_1` FOREIGN KEY (`ID_Lop`) REFERENCES `lop` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quanlyngayhoc_ibfk_2` FOREIGN KEY (`ID_NgayHoc`) REFERENCES `ngayhoc` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
