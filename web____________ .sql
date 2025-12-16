-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-12-16 05:29:46
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
(7, 5, '2025-11-24 17:19:15'),
(8, 4, '2025-11-25 05:56:31'),
(9, 6, '2025-11-25 06:06:00'),
(10, 5, '2025-11-25 06:06:07'),
(11, 3, '2025-11-25 06:06:11'),
(12, 7, '2025-11-25 06:55:37'),
(13, 1, '2025-12-16 04:27:04'),
(14, 8, '2025-12-16 04:27:07');

-- --------------------------------------------------------

--
-- 資料表結構 `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'student',
  `room` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `residents`
--

INSERT INTO `residents` (`id`, `student_id`, `password`, `name`, `role`, `room`, `phone`, `created_at`) VALUES
(1, '413401235', 'test1234', '許小策', 'student', '101', '0968635830', '2025-11-18 05:53:18'),
(2, '413401508', 'pw', '張底齊', 'student', '102', '091234567', '2025-11-18 06:07:30'),
(3, '413401340', 'pw', '沉思與', 'student', '103', '090000000', '2025-11-18 07:19:06'),
(4, '413401234', 'pw', '咪咪', 'student', '104', '0987654321', '2025-11-23 15:46:42'),
(5, '41344321', 'pw', '林阿信', 'student', '105', '0931231653', '2025-11-24 17:16:51'),
(6, '413404564', 'pw', '顏振宇', 'student', '201', '0987456197', '2025-11-25 06:03:53'),
(7, '41340136', 'pw', '王夏威', 'student', '202', '200000000000', '2025-11-25 06:54:33'),
(8, '413401558', 'pw', '其又伯夷', 'student', '203', '65464464448', '2025-11-25 07:04:49'),
(9, 'admin', 'password', '系統管理員', 'admin', '000', '0900000000', '2025-12-16 04:25:17');

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
(10, 2, '邊騎車邊抽菸彈菸蒂到警察車上引發大火', 999, '2025-11-24 17:42:01'),
(13, 6, '押車壓太低', 999, '2025-11-25 06:04:46'),
(14, 2, '跑去西昌街', 2147483647, '2025-11-25 06:51:54'),
(15, 2, '喊著口號一張卡一個奇跡去拔插頭', 2147483647, '2025-11-25 06:52:39'),
(16, 7, '叫他解正和弦他給我彈C大調', 2147483647, '2025-11-25 06:55:24'),
(17, 7, '叫他去超商他說只想超市', 2147483647, '2025-11-25 06:56:37'),
(18, 8, '整天說要回家其實跑去三仙巷', 2147483647, '2025-11-25 07:05:42'),
(19, 8, '讓別人大噴水', 2147483647, '2025-11-25 07:07:19'),
(20, 5, '騙我說要獵山豬其實去找猴子玩耍', 2147483647, '2025-11-25 07:08:24'),
(22, 2, '妄想當職業牌手殊不知只是個職業搖手 多多慮好喝', 2147483647, '2025-11-25 07:11:32'),
(23, 6, '假裝自己是直屬學長其實偷偷拐學妹真噁', 2147483647, '2025-11-25 07:16:25'),
(24, 6, '報名下賽道結果開CHR過去', 2147483647, '2025-11-25 07:19:48'),
(25, 8, '去夜店假裝很醉去得吃別人', 2147483647, '2025-11-25 07:20:41');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
