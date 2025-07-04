-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2025 年 7 月 04 日 07:58
-- サーバのバージョン： 10.11.11-MariaDB-0ubuntu0.24.04.2
-- PHP のバージョン: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `mydb`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `lecture`
--

DROP TABLE IF EXISTS `lecture`;
CREATE TABLE `lecture` (
  `id` int(11) NOT NULL,
  `lecture_name` varchar(50) NOT NULL,
  `lecture_content` varchar(3000) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `lecture`
--

INSERT INTO `lecture` (`id`, `lecture_name`, `lecture_content`, `created_at`, `teacher_id`) VALUES
(1, 'プロジェクトマネジメント演習', 'プロジェクトマネジメント学科開講科目の内容を深く理解するために、実践的な講義および演習を通じて，より具体的な知識を養成する．演習の多くの場面で，プロジェクト型演習の形式を取り入れることで，単なる知識の獲得・整理に留まらず，プロジェクトの計画，運用に関わる具体的な問題解決手法の教授についても考慮する．', '2025-07-01 06:21:43', 1),
(2, '試し', '楽しい授業です。', '2025-07-02 12:21:44', 1),
(3, 'XXXXXXXX実験', 'XXXXXXXXXの内容を深く理解するために、実践的な講義および演習を通じて，より具体的な知識を養成する．', '2025-07-04 03:19:42', 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `rating_clarity` int(11) NOT NULL,
  `rating_homework` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `review`
--

INSERT INTO `review` (`id`, `lecture_id`, `rating_clarity`, `rating_homework`, `comment`, `created_at`) VALUES
(1, 1, 4, 2, 'いい', '2025-07-01 07:07:59'),
(3, 1, 1, 1, '最高', '2025-07-02 10:01:05'),
(4, 1, 5, 5, 'んｎ', '2025-07-02 10:01:43'),
(5, 1, 5, 4, 'あ', '2025-07-02 10:02:53'),
(6, 1, 2, 2, 'いけ', '2025-07-02 10:09:05'),
(7, 1, 5, 2, 'かなり分かりやすくていい感じなんだけどちょっと課題多いかなー。まあでもためになるからとってもいいと思うよ(必修w)', '2025-07-02 11:50:42'),
(8, 1, 2, 3, '？？', '2025-07-02 11:51:17'),
(9, 1, 4, 3, 'ｋｋ', '2025-07-02 11:54:32'),
(10, 1, 3, 4, 'DOう？', '2025-07-02 12:01:02'),
(11, 1, 3, 4, 'DOう？', '2025-07-02 12:02:48'),
(13, 2, 1, 1, 'saitei', '2025-07-02 12:22:11'),
(14, 3, 5, 5, '神授業！！！！', '2025-07-04 03:22:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `laboratory` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `faculty`, `department`, `laboratory`, `photo`) VALUES
(1, ' 千葉太郎', '社会システム科学部', 'PM学科', '津田沼校舎　〇号館〇階　〇〇〇〇室', '匿名顔1.png'),
(2, '千葉次郎', '社会システム科学部', 'PM学科', '津田沼校舎　X号館X階　XXXX室', 'user_2.png');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, '12345', '$2y$10$UFkt.R0Lp1wLWGolR.TfGeIsfzSi0wq0OaeUN1Zs.ZPbHVn.ywHVS', '2025-07-04 01:44:01'),
(4, '00000', '$2y$10$qiGKOilBNDxO8sbNwn2DMuTC1FKrfsiCfgyuhD2vnuy.HmqnwlZ/C', '2025-07-04 07:33:27');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `lecture`
--
ALTER TABLE `lecture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lecture_teacher` (`teacher_id`);

--
-- テーブルのインデックス `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecture_id` (`lecture_id`);

--
-- テーブルのインデックス `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `lecture`
--
ALTER TABLE `lecture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- テーブルの AUTO_INCREMENT `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `fk_lecture_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`lecture_id`) REFERENCES `lecture` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
