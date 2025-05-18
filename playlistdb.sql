-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 18, 2025 at 07:51 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `playlistdb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `album`
--

CREATE TABLE `album` (
  `ID` int(11) NOT NULL,
  `Tytul` varchar(255) NOT NULL,
  `IloscPiosenek` int(11) DEFAULT NULL,
  `WykonawcaID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`ID`, `Tytul`, `IloscPiosenek`, `WykonawcaID`) VALUES
(1, 'Emails I Can’t Send', 13, 1),
(2, 'Happier Than Ever', 16, 2),
(3, 'Think Later', 14, 3),
(4, 'The Tortured Poets Department: The Anthology', 31, 4),
(5, 'Hit Me Hard and Soft', 10, 2),
(7, 'Taylor Swift', 11, 7);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `piosenka`
--

CREATE TABLE `piosenka` (
  `ID` int(11) NOT NULL,
  `Tytul` varchar(255) NOT NULL,
  `CzasTrwania` time NOT NULL,
  `Gatunek` varchar(100) DEFAULT NULL,
  `AlbumID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `piosenka`
--

INSERT INTO `piosenka` (`ID`, `Tytul`, `CzasTrwania`, `Gatunek`, `AlbumID`) VALUES
(1, 'Feather', '03:13:00', 'Pop', 1),
(2, 'Nonsense', '02:43:00', 'Pop', 1),
(3, 'Because I Liked a Boy', '03:14:00', 'Pop', 1),
(4, 'Happier Than Ever', '04:58:00', 'Alternative', 2),
(5, 'Everything I Wanted', '04:05:00', 'Alternative', 2),
(6, 'Therefore I Am', '02:54:00', 'Alternative', 2),
(7, 'Greedy', '02:46:00', 'Pop', 3),
(8, 'Exes', '03:04:00', 'Pop', 3),
(9, 'Run For The Hills', '02:50:00', 'Pop', 3),
(10, 'Fortnight (feat. Post Malone)', '03:48:00', 'Pop', 4),
(11, 'The Tortured Poets Department', '04:53:00', 'Pop', 4),
(12, 'So Long, London', '04:22:00', 'Pop', 4),
(13, 'Clara Bow', '03:36:00', 'Pop', 4),
(14, 'Who’s Afraid of Little Old Me?', '05:34:00', 'Alternative', 4),
(15, 'Lunch', '03:22:00', 'Alternative', 5),
(16, 'Chihiro', '05:00:00', 'Alternative', 5),
(17, 'The Greatest', '05:33:00', 'Alternative', 5),
(18, 'Wildflower', '04:45:00', 'Alternative', 5),
(19, 'L’Amour de Ma Vie', '03:45:00', 'Alternative', 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `playlista`
--

CREATE TABLE `playlista` (
  `ID` int(11) NOT NULL,
  `Nazwa` varchar(255) NOT NULL,
  `DataUtworzenia` date NOT NULL,
  `CzasTrwania` time DEFAULT NULL,
  `LiczbaUtworow` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlista`
--

INSERT INTO `playlista` (`ID`, `Nazwa`, `DataUtworzenia`, `CzasTrwania`, `LiczbaUtworow`) VALUES
(1, 'Pop Hits', '2024-03-10', '09:30:00', 5),
(2, 'Chill Lol', '2024-03-12', '21:59:00', 5),
(3, 'Sad & Deep', '2025-05-15', '00:27:00', 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `playlista_piosenka`
--

CREATE TABLE `playlista_piosenka` (
  `PlaylistaID` int(11) NOT NULL,
  `PiosenkaID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlista_piosenka`
--

INSERT INTO `playlista_piosenka` (`PlaylistaID`, `PiosenkaID`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 7),
(1, 9),
(1, 10),
(1, 11),
(1, 15),
(2, 8),
(2, 13),
(2, 14),
(2, 16),
(2, 18),
(3, 12),
(3, 14),
(3, 17),
(3, 19);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wykonawca`
--

CREATE TABLE `wykonawca` (
  `ID` int(11) NOT NULL,
  `Nazwa` varchar(255) NOT NULL,
  `WytworniaID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wykonawca`
--

INSERT INTO `wykonawca` (`ID`, `Nazwa`, `WytworniaID`) VALUES
(1, 'Sabrina Carpenter', 1),
(2, 'Billie Eilish', 2),
(3, 'Tate McRae', 3),
(4, 'Taylor Swift', 4),
(7, 'Taylor Swift', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wytwornia`
--

CREATE TABLE `wytwornia` (
  `ID` int(11) NOT NULL,
  `Nazwa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wytwornia`
--

INSERT INTO `wytwornia` (`ID`, `Nazwa`) VALUES
(1, 'Island Records'),
(2, 'Darkroom/Interscope Records'),
(3, 'RCA Records'),
(4, 'Republic Records');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `WykonawcaID` (`WykonawcaID`);

--
-- Indeksy dla tabeli `piosenka`
--
ALTER TABLE `piosenka`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AlbumID` (`AlbumID`);

--
-- Indeksy dla tabeli `playlista`
--
ALTER TABLE `playlista`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `playlista_piosenka`
--
ALTER TABLE `playlista_piosenka`
  ADD PRIMARY KEY (`PlaylistaID`,`PiosenkaID`),
  ADD KEY `PiosenkaID` (`PiosenkaID`);

--
-- Indeksy dla tabeli `wykonawca`
--
ALTER TABLE `wykonawca`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `WytworniaID` (`WytworniaID`);

--
-- Indeksy dla tabeli `wytwornia`
--
ALTER TABLE `wytwornia`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `piosenka`
--
ALTER TABLE `piosenka`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `playlista`
--
ALTER TABLE `playlista`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wykonawca`
--
ALTER TABLE `wykonawca`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wytwornia`
--
ALTER TABLE `wytwornia`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`WykonawcaID`) REFERENCES `wykonawca` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `piosenka`
--
ALTER TABLE `piosenka`
  ADD CONSTRAINT `piosenka_ibfk_1` FOREIGN KEY (`AlbumID`) REFERENCES `album` (`ID`) ON DELETE SET NULL;

--
-- Constraints for table `playlista_piosenka`
--
ALTER TABLE `playlista_piosenka`
  ADD CONSTRAINT `playlista_piosenka_ibfk_1` FOREIGN KEY (`PlaylistaID`) REFERENCES `playlista` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `playlista_piosenka_ibfk_2` FOREIGN KEY (`PiosenkaID`) REFERENCES `piosenka` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `wykonawca`
--
ALTER TABLE `wykonawca`
  ADD CONSTRAINT `wykonawca_ibfk_1` FOREIGN KEY (`WytworniaID`) REFERENCES `wytwornia` (`ID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
