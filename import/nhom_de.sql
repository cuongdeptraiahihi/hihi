-- phpMyAdmin SQL Dump
-- version 4.6.5.1
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Dim 08 Janvier 2017 à 11:05
-- Version du serveur :  5.5.49
-- Version de PHP :  5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `admin_bgoedu`
--

-- --------------------------------------------------------

--
-- Structure de la table `nhom_de`
--

CREATE TABLE `nhom_de` (
  `ID_N` int(10) UNSIGNED NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL,
  `ID_LM` int(3) UNSIGNED NOT NULL,
  `public` int(1) UNSIGNED NOT NULL,
  `date_open` datetime NOT NULL,
  `date_close` date NOT NULL,
  `is_sai` int(1) UNSIGNED NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `object` int(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `nhom_de`
--

INSERT INTO `nhom_de` (`ID_N`, `code`, `datetime`, `ID_LM`, `public`, `date_open`, `date_close`, `is_sai`, `type`, `object`) VALUES
(4, 'L70KSL', '2016-11-12 22:50:33', 1, 2, '2016-11-29 09:16:30', '0000-00-00', 0, 'chuyen-de', 0),
(6, 'L3RT2', '2016-11-15 21:46:30', 1, 2, '2016-11-29 09:19:08', '0000-00-00', 0, 'chuyen-de', 0),
(7, 'U9B17G', '2016-11-18 12:07:38', 1, 2, '2016-11-18 19:51:33', '0000-00-00', 1, 'chuyen-de', 0),
(8, 'RIXXBJ', '2016-11-20 15:08:19', 1, 0, '2016-11-29 09:19:31', '0000-00-00', 0, 'chuyen-de', 0),
(9, 'YM2UJM', '2016-11-25 11:23:22', 1, 2, '2016-11-25 11:39:57', '0000-00-00', 0, 'chuyen-de', 0),
(12, 'WM0EHN', '2016-11-26 18:59:09', 1, 2, '2016-11-28 21:42:22', '0000-00-00', 0, 'chuyen-de', 0),
(15, 'HXB7YL', '2016-11-28 20:57:58', 1, 2, '2016-11-28 17:25:36', '0000-00-00', 1, 'chuyen-de', 0),
(18, 'JON3TB', '2016-11-29 17:03:37', 1, 2, '2016-11-30 17:32:25', '0000-00-00', 0, 'chuyen-de', 0),
(19, 'NTYDCZ', '2016-12-02 17:43:10', 1, 2, '2016-12-06 22:49:30', '2016-12-09', 0, 'kiem-tra', 101),
(24, 'PW3SYT', '2016-12-03 15:11:43', 2, 2, '2016-12-06 22:55:41', '2016-12-09', 0, 'kiem-tra', 101),
(30, 'BEEE52', '2016-12-09 16:06:43', 2, 2, '2016-12-17 23:09:32', '2016-12-14', 0, 'kiem-tra', 103),
(31, '9O07RI', '2016-12-10 15:42:30', 1, 2, '2016-12-17 22:21:32', '2016-12-14', 0, 'kiem-tra', 103),
(33, 'TUPT58', '2016-12-12 17:21:30', 1, 2, '2016-12-13 14:48:04', '0000-00-00', 0, 'chuyen-de', 0),
(40, '4F03J9', '2016-12-15 16:24:06', 2, 2, '2016-12-21 17:35:15', '0000-00-00', 0, 'kiem-tra', 106),
(42, 'VQS6MM', '2016-12-17 16:28:14', 1, 2, '2016-12-21 17:29:49', '0000-00-00', 0, 'kiem-tra', 106),
(43, '8H87Y6', '2016-12-18 12:47:54', 1, 2, '2016-12-25 08:04:30', '0000-00-00', 0, 'chuyen-de', 0),
(47, 'KGZD3C', '2016-12-21 16:38:44', 2, 2, '2016-12-28 17:44:32', '0000-00-00', 0, 'kiem-tra', 108),
(49, 'MA1WP1', '2016-12-23 17:05:52', 1, 1, '2016-12-29 22:21:23', '0000-00-00', 0, 'kiem-tra', 108),
(50, '1PG26M', '2016-12-29 08:51:02', 2, 2, '2016-12-29 09:11:02', '0000-00-00', 0, 'chuyen-de', 0),
(52, 'RBA0B4', '2016-12-29 11:12:35', 1, 2, '2016-12-29 11:16:57', '0000-00-00', 0, 'chuyen-de', 0),
(54, 'A8GO1B', '2016-12-31 10:51:16', 1, 2, '2016-12-31 10:55:39', '0000-00-00', 0, 'chuyen-de', 0),
(56, 'Q4BNCH', '2017-01-05 08:43:52', 2, 2, '0000-00-00 00:00:00', '0000-00-00', 0, 'kiem-tra', 111),
(58, 'QBLTO8', '2017-01-06 21:04:56', 1, 2, '0000-00-00 00:00:00', '0000-00-00', 0, 'kiem-tra', 111),
(60, 'PTGPBT', '2017-01-06 21:07:25', 1, 2, '0000-00-00 00:00:00', '0000-00-00', 0, 'kiem-tra', 111),
(61, '6ZXBNR', '2017-01-06 21:20:18', 2, 2, '0000-00-00 00:00:00', '0000-00-00', 0, 'kiem-tra', 111),
(62, 'UZ4OUS', '2017-01-06 21:21:37', 2, 2, '0000-00-00 00:00:00', '0000-00-00', 0, 'kiem-tra', 111);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `nhom_de`
--
ALTER TABLE `nhom_de`
  ADD PRIMARY KEY (`ID_N`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `nhom_de`
--
ALTER TABLE `nhom_de`
  MODIFY `ID_N` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
