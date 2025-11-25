-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 
-- 伺服器版本: 10.1.22-MariaDB
-- PHP 版本： 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `checkin_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `checkins`
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
(12, 7, '2025-11-25 06:55:37');

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `residents`
--

INSERT INTO `residents` (`id`, `student_id`, `name`, `room`, `phone`, `email`, `created_at`) VALUES
(1, '413401235', '許小策', '101', '0968635830', NULL, '2025-11-18 05:53:18'),
(2, '413401508', '張底齊', '102', '091234567', NULL, '2025-11-18 06:07:30'),
(3, '413401340', '沉思與', '103', '090000000', NULL, '2025-11-18 07:19:06'),
(4, '413401234', '咪咪', '104', '0987654321', NULL, '2025-11-23 15:46:42'),
(5, '41344321', '林阿信', '105', '0931231653', NULL, '2025-11-24 17:16:51'),
(6, '413404564', '顏振宇', '201', '0987456197', NULL, '2025-11-25 06:03:53'),
(7, '41340136', '王夏威', '202', '200000000000', NULL, '2025-11-25 06:54:33'),
(8, '413401558', '其又伯夷', '203', '65464464448', NULL, '2025-11-25 07:04:49');

-- --------------------------------------------------------

--
-- 資料表結構 `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `violation` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `violations`
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
-- 已匯出資料表的索引
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
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用資料表 AUTO_INCREMENT `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用資料表 AUTO_INCREMENT `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;

--
-- 資料表的 Constraints `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
