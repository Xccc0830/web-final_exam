-- phpMyAdmin SQL Dump
<<<<<<< HEAD
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-11-23 16:40:09
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
=======
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 
-- 伺服器版本: 10.1.22-MariaDB
-- PHP 版本： 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
>>>>>>> 4ad1be0b3b54f38a2f4e6772b64def42f5c0e594
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
<<<<<<< HEAD
-- 資料表結構 `checkins`
--

CREATE TABLE `checkins` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `checkin_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
=======
>>>>>>> 4ad1be0b3b54f38a2f4e6772b64def42f5c0e594
-- 資料表結構 `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `room` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
<<<<<<< HEAD
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `residents`
=======
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `residents`
>>>>>>> 4ad1be0b3b54f38a2f4e6772b64def42f5c0e594
--

INSERT INTO `residents` (`id`, `student_id`, `name`, `room`, `phone`, `email`, `created_at`) VALUES
(1, '413401235', '許小策', '101', '0968635830', NULL, '2025-11-18 05:53:18'),
(2, '413401508', '張底齊', '102', '091234567', NULL, '2025-11-18 06:07:30'),
(3, '413401340', '沉思與', '103', '090000000', NULL, '2025-11-18 07:19:06');

-- --------------------------------------------------------

--
-- 資料表結構 `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `violation` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
<<<<<<< HEAD
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `violations`
=======
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `violations`
>>>>>>> 4ad1be0b3b54f38a2f4e6772b64def42f5c0e594
--

INSERT INTO `violations` (`id`, `resident_id`, `violation`, `points`, `created_at`) VALUES
(1, 2, '在宿舍抽菸引發大火', 999, '2025-11-18 06:41:29'),
(2, 2, '用菸蒂燙牆壁引發大火', 999, '2025-11-18 06:44:01'),
(3, 2, '把菸蒂彈到停車場引發大火', 999, '2025-11-18 06:44:35'),
(4, 2, '邊騎車邊抽菸引發大火', 999, '2025-11-18 07:00:27');

--
<<<<<<< HEAD
-- 已傾印資料表的索引
--

--
-- 資料表索引 `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`);

--
=======
-- 已匯出資料表的索引
--

--
>>>>>>> 4ad1be0b3b54f38a2f4e6772b64def42f5c0e594
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
<<<<<<< HEAD
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
=======
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用資料表 AUTO_INCREMENT `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `violations`
>>>>>>> 4ad1be0b3b54f38a2f4e6772b64def42f5c0e594
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
