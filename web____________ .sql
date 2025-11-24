-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-11-24 19:12:38
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `web期末專案`
--

-- --------------------------------------------------------

--
-- 資料表結構 `checkins`
--

CREATE TABLE `checkins` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `checkin_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `checkins`
--

INSERT INTO `checkins` (`id`, `resident_id`, `checkin_time`) VALUES
(3, 1, '2025-11-24 16:57:08'),
(4, 4, '2025-11-24 17:08:28'),
(5, 2, '2025-11-24 17:08:34'),
(6, 4, '2025-11-24 17:08:44'),
(7, 5, '2025-11-24 17:19:15');

-- --------------------------------------------------------

--
-- 資料表結構 `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `room` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `residents`
--

INSERT INTO `residents` (`id`, `student_id`, `name`, `room`, `phone`, `email`, `created_at`) VALUES
(1, '413401235', '許小策', '101', '0968635830', NULL, '2025-11-18 05:53:18'),
(2, '413401508', '張底齊', '102', '091234567', NULL, '2025-11-18 06:07:30'),
(3, '413401340', '沉思與', '103', '090000000', NULL, '2025-11-18 07:19:06'),
(4, '413401234', '咪咪', '104', '0987654321', NULL, '2025-11-23 15:46:42'),
(5, '41344321', '林阿信', '105', '0931231653', NULL, '2025-11-24 17:16:51');

-- --------------------------------------------------------

--
-- 資料表結構 `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `violation` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `violations`
--

INSERT INTO `violations` (`id`, `resident_id`, `violation`, `points`, `created_at`) VALUES
(1, 2, '在宿舍抽菸引發大火', 999, '2025-11-18 06:41:29'),
(2, 2, '用菸蒂燙牆壁引發大火', 999, '2025-11-18 06:44:01'),
(3, 2, '把菸蒂彈到停車場引發大火', 999, '2025-11-18 06:44:35'),
(4, 2, '邊騎車邊抽菸引發大火', 999, '2025-11-18 07:00:27'),
(6, 2, '邊騎車邊抽菸彈菸蒂到警察車上引發大火', 999, '2025-11-24 17:11:06'),
(7, 4, '打麻將隕石自摸把桌子打破，造成整棟宿舍的二樓變成一樓', 999, '2025-11-24 17:12:18'),
(9, 1, '太帥', 4, '2025-11-24 17:18:12'),
(10, 2, '邊騎車邊抽菸彈菸蒂到警察車上引發大火', 999, '2025-11-24 17:42:01'),
(11, 4, 'test', 1, '2025-11-24 17:45:07');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- 資料表索引 `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- 資料表的限制式 `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
