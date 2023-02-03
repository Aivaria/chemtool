-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Nov 2021 um 00:34
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `chemtool`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `tag`
--

INSERT INTO `tag` (`id`, `name`, `priority`, `hidden`, `color`) VALUES
(1, 'base', 100, 1, '#C1C1BF'),
(2, 'secret', 200, 1, '#6B6B6B'),
(3, 'medicine', 10, 1, 'pink'),
(4, 'pyrotechnic', 30, 1, '#FF2819'),
(5, 'poison', 40, 1, 'lightgreen'),
(6, 'acid', 80, 1, 'greenyellow'),
(7, 'disease', 60, 1, '#681860'),
(8, 'drug', 50, 1, '#BDBF42'),
(9, 'food', 120, 1, '#BC6F49'),
(10, 'hypospraysafe', 20, 1, '#00FFF6'),
(11, 'hard to get', 500, 1, '#899EBA'),
(12, 'misc', 400, 1, '#B70187');
(13, 'no parent', 1000, 1, null);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_389B7835E237E06` (`name`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
