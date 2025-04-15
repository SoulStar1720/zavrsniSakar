-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 09:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `knjiznica`
--

-- --------------------------------------------------------

--
-- Table structure for table `autor`
--

CREATE TABLE `autor` (
  `AutorID` int(11) NOT NULL,
  `ImePrezime` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `autor`
--

INSERT INTO `autor` (`AutorID`, `ImePrezime`) VALUES
(17, 'Agatha Christie'),
(16, 'Alan Moore'),
(4, 'Albert Einstein'),
(11, 'David Crystal'),
(6, 'David Griffiths'),
(1, 'Donald Knuth'),
(12, 'Erich Gamma'),
(7, 'George Orwell'),
(13, 'Ivana Brlić-Mažurani'),
(9, 'J.K. Rowling'),
(10, 'Jane Austen'),
(3, 'John E. Freund'),
(15, 'Multiple Authors'),
(8, 'National Geographic'),
(5, 'Razni autori'),
(2, 'Stephen Hawking'),
(14, 'Walter Isaacson'),
(18, 'Yuval Noah Harari');

-- --------------------------------------------------------

--
-- Table structure for table `clan`
--

CREATE TABLE `clan` (
  `IDClan` int(11) NOT NULL,
  `Ime` varchar(20) NOT NULL,
  `Prezime` varchar(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Lozinka` varchar(255) NOT NULL DEFAULT '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clan`
--

INSERT INTO `clan` (`IDClan`, `Ime`, `Prezime`, `Email`, `Lozinka`, `role`) VALUES
(101, 'Ivan', 'Horvat', 'ivan.horvat101@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(102, 'Ana', 'Matić', 'ana.matić102@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(103, 'Marko', 'Jurić', 'marko.jurić103@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(104, 'Ivana', 'Pavić', 'ivana.pavić104@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(105, 'Petar', 'Ivić', 'petar.ivić105@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(106, 'Maja', 'Kovačić', 'maja.kovačić106@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(107, 'Petra', 'Novak', 'petra.novak107@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(108, 'Luka', 'Kovačić', 'luka.kovačić108@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(109, 'Ivana', 'Milić', 'ivana.milić109@knjiznica.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(1000, 'Dominik', 'Dusek', 'broooooooooo224424@gmail.com', '$2y$10$EEkQnHXKsiu1XAX31x59fuLK5y/r7efOYIx3zK6jEDXQCG/vhIjiK', 'user'),
(1001, 'Lena', 'Sakar', 'sakarlena@gmail.com', '$2y$10$f1A0DozXQ4hB5SfrO3iQReGkLB/JVLFXBjpOQFjvW375VYdgCXLF6', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `izdavac`
--

CREATE TABLE `izdavac` (
  `IzdavacID` int(11) NOT NULL,
  `Naziv` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `izdavac`
--

INSERT INTO `izdavac` (`IzdavacID`, `Naziv`) VALUES
(1, 'Addison-Wesley'),
(4, 'Annalen der Physik'),
(2, 'Bantam Books'),
(6, 'Cambridge University Press'),
(14, 'DC Comics'),
(16, 'Harper'),
(15, 'HarperCollins'),
(10, 'LAGUNA'),
(8, 'National Geographic'),
(13, 'Nature Publishing'),
(3, 'Pearson'),
(9, 'Scholastic'),
(7, 'Secker & Warburg'),
(12, 'Simon & Schuster'),
(11, 'Školska knjiga'),
(5, 'Springer');

-- --------------------------------------------------------

--
-- Table structure for table `posudba`
--

CREATE TABLE `posudba` (
  `PosudbaID` int(11) NOT NULL,
  `ClanID` int(11) NOT NULL,
  `PrimjerakID` int(11) NOT NULL,
  `DatumPosudbe` date DEFAULT curdate(),
  `DatumVracanja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posudba`
--

INSERT INTO `posudba` (`PosudbaID`, `ClanID`, `PrimjerakID`, `DatumPosudbe`, `DatumVracanja`) VALUES
(1, 101, 201, '2024-01-10', NULL),
(2, 103, 202, '2024-01-12', NULL),
(3, 102, 204, '2024-01-05', NULL),
(4, 105, 209, '2024-01-03', NULL),
(5, 104, 210, '2024-01-08', NULL),
(6, 103, 214, '2024-01-15', NULL),
(7, 102, 215, '2024-01-16', NULL),
(8, 101, 220, '2024-03-10', NULL),
(9, 103, 221, '2024-03-11', NULL),
(10, 102, 223, '2024-03-12', NULL),
(11, 104, 225, '2024-03-13', NULL),
(12, 101, 226, '2024-04-01', NULL),
(13, 103, 231, '2024-04-02', NULL),
(14, 105, 235, '2024-04-03', '2024-04-17'),
(15, 107, 240, '2024-04-05', NULL),
(16, 102, 263, '2024-05-01', '2025-04-15'),
(17, 104, 268, '2024-05-03', '2024-05-17'),
(18, 106, 277, '2024-05-05', NULL),
(19, 108, 284, '2024-05-07', NULL);

--
-- Triggers `posudba`
--
DELIMITER $$
CREATE TRIGGER `posudba_after_insert` AFTER INSERT ON `posudba` FOR EACH ROW BEGIN
  UPDATE Primjerak 
  SET Dostupno = 'posuđeno', 
      DatumPosudbe = NEW.DatumPosudbe, 
      ClanID = NEW.ClanID 
  WHERE IDPrimjerak = NEW.PrimjerakID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `primjerak`
--

CREATE TABLE `primjerak` (
  `LiteraturaID` int(11) NOT NULL,
  `IDPrimjerak` int(11) NOT NULL,
  `DatumPosudbe` date DEFAULT NULL,
  `ClanID` int(11) DEFAULT NULL,
  `Dostupno` enum('dostupno','posuđeno') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `primjerak`
--

INSERT INTO `primjerak` (`LiteraturaID`, `IDPrimjerak`, `DatumPosudbe`, `ClanID`, `Dostupno`) VALUES
(1, 201, '2024-01-10', 101, 'posuđeno'),
(1, 202, '2024-01-12', 103, 'posuđeno'),
(1, 203, NULL, NULL, 'dostupno'),
(2, 204, '2024-01-05', 102, 'posuđeno'),
(2, 205, NULL, NULL, 'dostupno'),
(2, 206, NULL, NULL, 'dostupno'),
(2, 207, NULL, NULL, 'dostupno'),
(2, 208, NULL, NULL, 'dostupno'),
(3, 209, '2024-01-03', 105, 'posuđeno'),
(4, 210, '2024-01-08', 104, 'posuđeno'),
(4, 211, NULL, NULL, 'dostupno'),
(4, 212, NULL, NULL, 'dostupno'),
(5, 213, NULL, NULL, 'dostupno'),
(5, 214, '2024-01-15', 103, 'posuđeno'),
(6, 215, '2024-01-16', 102, 'posuđeno'),
(6, 216, NULL, NULL, 'dostupno'),
(6, 217, NULL, NULL, 'dostupno'),
(6, 218, NULL, NULL, 'dostupno'),
(7, 219, NULL, NULL, 'dostupno'),
(7, 220, '2024-03-10', 101, 'posuđeno'),
(7, 221, '2024-03-11', 103, 'posuđeno'),
(8, 222, NULL, NULL, 'dostupno'),
(8, 223, '2024-03-12', 102, 'posuđeno'),
(9, 224, NULL, NULL, 'dostupno'),
(9, 225, '2024-03-13', 104, 'posuđeno'),
(10, 226, NULL, NULL, 'dostupno'),
(10, 227, NULL, NULL, 'dostupno'),
(10, 228, NULL, NULL, 'dostupno'),
(10, 229, NULL, NULL, 'dostupno'),
(11, 230, NULL, NULL, 'dostupno'),
(11, 231, NULL, NULL, 'dostupno'),
(12, 232, NULL, NULL, 'dostupno'),
(12, 233, NULL, NULL, 'dostupno'),
(12, 234, NULL, NULL, 'dostupno'),
(13, 235, NULL, NULL, 'dostupno'),
(13, 236, NULL, NULL, 'dostupno'),
(13, 237, NULL, NULL, 'dostupno'),
(13, 238, NULL, NULL, 'dostupno'),
(13, 239, NULL, NULL, 'dostupno'),
(14, 240, NULL, NULL, 'dostupno'),
(14, 241, NULL, NULL, 'dostupno'),
(15, 242, NULL, NULL, 'dostupno'),
(15, 243, NULL, NULL, 'dostupno'),
(15, 244, NULL, NULL, 'dostupno'),
(16, 245, NULL, NULL, 'dostupno'),
(16, 246, NULL, NULL, 'dostupno'),
(16, 247, NULL, NULL, 'dostupno'),
(16, 248, NULL, NULL, 'dostupno'),
(16, 249, NULL, NULL, 'dostupno'),
(16, 250, NULL, NULL, 'dostupno'),
(17, 251, NULL, NULL, 'dostupno'),
(17, 252, NULL, NULL, 'dostupno'),
(17, 253, NULL, NULL, 'dostupno'),
(17, 254, NULL, NULL, 'dostupno'),
(18, 255, NULL, NULL, 'dostupno'),
(18, 256, NULL, NULL, 'dostupno'),
(18, 257, NULL, NULL, 'dostupno'),
(19, 258, NULL, NULL, 'dostupno'),
(19, 259, NULL, NULL, 'dostupno'),
(19, 260, NULL, NULL, 'dostupno'),
(19, 261, NULL, NULL, 'dostupno'),
(19, 262, NULL, NULL, 'dostupno'),
(20, 263, NULL, NULL, 'dostupno'),
(20, 264, NULL, NULL, 'dostupno'),
(21, 265, NULL, NULL, 'dostupno'),
(21, 266, NULL, NULL, 'dostupno'),
(21, 267, NULL, NULL, 'dostupno'),
(22, 268, NULL, NULL, 'dostupno'),
(22, 269, NULL, NULL, 'dostupno'),
(22, 270, NULL, NULL, 'dostupno'),
(22, 271, NULL, NULL, 'dostupno'),
(23, 272, NULL, NULL, 'dostupno'),
(23, 273, NULL, NULL, 'dostupno'),
(24, 274, NULL, NULL, 'dostupno'),
(24, 275, NULL, NULL, 'dostupno'),
(24, 276, NULL, NULL, 'dostupno'),
(25, 277, NULL, NULL, 'dostupno'),
(25, 278, NULL, NULL, 'dostupno'),
(25, 279, NULL, NULL, 'dostupno'),
(26, 280, NULL, NULL, 'dostupno'),
(26, 281, NULL, NULL, 'dostupno'),
(26, 282, NULL, NULL, 'dostupno'),
(26, 283, NULL, NULL, 'dostupno'),
(27, 284, NULL, NULL, 'dostupno'),
(27, 285, NULL, NULL, 'dostupno'),
(28, 286, NULL, NULL, 'dostupno'),
(28, 287, NULL, NULL, 'dostupno'),
(28, 288, NULL, NULL, 'dostupno'),
(29, 289, NULL, NULL, 'dostupno'),
(29, 290, NULL, NULL, 'dostupno');

-- --------------------------------------------------------

--
-- Table structure for table `vrstaliterature`
--

CREATE TABLE `vrstaliterature` (
  `IDLiteratura` int(11) NOT NULL,
  `vrsta_literature` varchar(50) NOT NULL,
  `naslov` varchar(100) NOT NULL,
  `ISBN_broj` varchar(20) DEFAULT NULL,
  `broj_primjeraka` int(11) NOT NULL,
  `AutorID` int(11) DEFAULT NULL,
  `IzdavacID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vrstaliterature`
--

INSERT INTO `vrstaliterature` (`IDLiteratura`, `vrsta_literature`, `naslov`, `ISBN_broj`, `broj_primjeraka`, `AutorID`, `IzdavacID`) VALUES
(1, 'Fakultativna knjiga', 'The Art of Computer Programming', '978-0-201-03804-2', 3, 1, 1),
(2, 'Fakultativna knjiga', 'A Brief History of Time', '978-0-553-17521-9', 5, 2, 2),
(3, 'Udžbenik', 'Mathematical Statistics with Applications', '9780134080918', 1, 3, 3),
(4, 'Znanstveni časopis', 'On the Electrodynamics of Moving Bodies', NULL, 3, 4, 4),
(5, 'Zbornik radova', 'Proceedings of AI Conference 2023', '978-0-123-45678-9', 2, 5, 5),
(6, 'Priručnik', 'Introduction to Quantum Mechanics', '978-0-521-65842-4', 4, 6, 6),
(7, 'Roman', '1984', '978-0-452-28423-4', 4, 7, 7),
(8, 'Enciklopedija', 'National Geographic Encyclopedia', '978-1-4262-1300-0', 6, 8, 8),
(9, 'Roman', 'Harry Potter and the Sorcerer\'s Stone', '978-0-545-01022-1', 5, 9, 9),
(10, 'Roman', 'Ponos i predrasude', '978-953-7396-48-9', 4, 10, 10),
(11, 'Znanstvena monografija', 'The Universe in a Nutshell', '978-0553802023', 2, 2, 2),
(12, 'Enciklopedija', 'The Cambridge Encyclopedia of Language', '978-0521736503', 3, 11, 6),
(13, 'Priručnik', 'Design Patterns: Elements of Reusable Object-Oriented Software', '978-0201633610', 5, 12, 1),
(14, 'Zbirka poezije', 'Priče iz davnine', '978-953-6747-00-8', 2, 13, 11),
(15, 'Biografija', 'Steve Jobs', '978-1451648539', 3, 14, 12),
(16, 'Znanstveni časopis', 'Nature: International Journal of Science', NULL, 6, 15, 13),
(17, 'Strip', 'Watchmen', '978-0930289232', 4, 16, 14),
(18, 'Kriminalistički roman', 'Murder on the Orient Express', '978-0062073501', 3, 17, 15),
(19, 'Povijesna studija', 'Sapiens: A Brief History of Humankind', '978-0062316097', 5, 18, 16),
(20, 'Roman', 'Black Holes and Baby Universes', '978-0553406635', 2, 2, 2),
(21, 'Znanstvena monografija', 'Relativity: The Special and General Theory', '978-1684226264', 3, 4, 4),
(22, 'Priručnik', 'Concrete Mathematics', '978-0201558029', 4, 1, 1),
(23, 'Biografija', 'Leonardo da Vinci', '978-1501139154', 2, 14, 12),
(24, 'Enciklopedija', 'National Geographic Atlas of the World', '978-1426222193', 3, 8, 8),
(25, 'Roman', 'Death on the Nile', '978-0062073556', 3, 17, 15),
(26, 'Znanstveni časopis', 'Science: Academic Journal', NULL, 4, 15, 13),
(27, 'Strip', 'V for Vendetta', '978-1401207922', 2, 16, 14),
(28, 'Povijesna studija', 'Homo Deus', '978-0062464316', 3, 18, 16),
(29, 'Udžbenik', 'Modern Elementary Statistics', '978-0135934598', 2, 3, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`AutorID`),
  ADD UNIQUE KEY `ImePrezime` (`ImePrezime`);

--
-- Indexes for table `clan`
--
ALTER TABLE `clan`
  ADD PRIMARY KEY (`IDClan`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `izdavac`
--
ALTER TABLE `izdavac`
  ADD PRIMARY KEY (`IzdavacID`),
  ADD UNIQUE KEY `Naziv` (`Naziv`);

--
-- Indexes for table `posudba`
--
ALTER TABLE `posudba`
  ADD PRIMARY KEY (`PosudbaID`),
  ADD KEY `idx_datum_posudbe` (`DatumPosudbe`),
  ADD KEY `idx_clan` (`ClanID`),
  ADD KEY `PrimjerakID` (`PrimjerakID`);

--
-- Indexes for table `primjerak`
--
ALTER TABLE `primjerak`
  ADD PRIMARY KEY (`IDPrimjerak`),
  ADD KEY `LiteraturaID` (`LiteraturaID`),
  ADD KEY `idx_dostupno` (`Dostupno`),
  ADD KEY `ClanID` (`ClanID`);

--
-- Indexes for table `vrstaliterature`
--
ALTER TABLE `vrstaliterature`
  ADD PRIMARY KEY (`IDLiteratura`),
  ADD KEY `idx_autor` (`AutorID`),
  ADD KEY `idx_izdavac` (`IzdavacID`),
  ADD KEY `idx_naslov` (`naslov`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autor`
--
ALTER TABLE `autor`
  MODIFY `AutorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `clan`
--
ALTER TABLE `clan`
  MODIFY `IDClan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `izdavac`
--
ALTER TABLE `izdavac`
  MODIFY `IzdavacID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posudba`
--
ALTER TABLE `posudba`
  MODIFY `PosudbaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000;

--
-- AUTO_INCREMENT for table `primjerak`
--
ALTER TABLE `primjerak`
  MODIFY `IDPrimjerak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `vrstaliterature`
--
ALTER TABLE `vrstaliterature`
  MODIFY `IDLiteratura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posudba`
--
ALTER TABLE `posudba`
  ADD CONSTRAINT `posudba_ibfk_3` FOREIGN KEY (`ClanID`) REFERENCES `clan` (`IDClan`),
  ADD CONSTRAINT `posudba_ibfk_4` FOREIGN KEY (`PrimjerakID`) REFERENCES `primjerak` (`IDPrimjerak`),
  ADD CONSTRAINT `posudba_ibfk_5` FOREIGN KEY (`ClanID`) REFERENCES `clan` (`IDClan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posudba_ibfk_6` FOREIGN KEY (`PrimjerakID`) REFERENCES `primjerak` (`IDPrimjerak`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `primjerak`
--
ALTER TABLE `primjerak`
  ADD CONSTRAINT `primjerak_ibfk_1` FOREIGN KEY (`LiteraturaID`) REFERENCES `vrstaliterature` (`IDLiteratura`),
  ADD CONSTRAINT `primjerak_ibfk_3` FOREIGN KEY (`ClanID`) REFERENCES `clan` (`IDClan`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vrstaliterature`
--
ALTER TABLE `vrstaliterature`
  ADD CONSTRAINT `vrstaliterature_ibfk_1` FOREIGN KEY (`AutorID`) REFERENCES `autor` (`AutorID`),
  ADD CONSTRAINT `vrstaliterature_ibfk_2` FOREIGN KEY (`IzdavacID`) REFERENCES `izdavac` (`IzdavacID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
