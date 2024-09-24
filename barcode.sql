-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 24 Eyl 2024, 13:59:13
-- Sunucu sürümü: 8.2.0
-- PHP Sürümü: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `barcode`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `email_verification`
--

DROP TABLE IF EXISTS `email_verification`;
CREATE TABLE IF NOT EXISTS `email_verification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email_id` int NOT NULL,
  `verification_code` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email_id` (`email_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `phone_number` varchar(11) COLLATE utf8mb4_turkish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone_number`, `email`, `password`, `created_at`) VALUES
(1, 'Emir', 'Güllü', '05385533918', 'emirgul231@gmail.com', '$2y$10$WcQjSnjB9LjDpwE4Ixm0D.LN8N5nPUVcEaP48.RU9EwYWU6VVgFA6', '2024-09-23 11:28:21'),
(27, 'Emir', 'Gül', '05385533918', 'emirgul31@gmail.com', '$2y$10$0rKKk4qDQkKH513IW4I2XOrFPCNYZqGwCl1dpbp3sfSZ7i5rTK2w.', '2024-09-24 09:56:04'),
(24, 'Emir', 'Gül', '05385533918', 'emirgul23@gmail.com', '$2y$10$opfUO2yxuaEWphFVAJNzIe50rR3TrvXUt4jssmGe9nUgel7SyNRo2', '2024-09-24 09:42:38'),
(25, 'Emir', 'Gül', '05385533918', 'emirgul23671@gmail.com', '$2y$10$MNgfX7dGrTrZwqWxe41averJd3oi8.SDgGgxBUhc4wuzzrkm1hqay', '2024-09-24 09:46:25');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
