-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 01:32 PM
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
(1001, 'Lena', 'Sakar', 'sakarlena@gmail.com', '$2y$10$f1A0DozXQ4hB5SfrO3iQReGkLB/JVLFXBjpOQFjvW375VYdgCXLF6', 'admin'),
(1003, 'Adrijana', 'Potužak', 'adre044@gmail.com', '$2y$10$ANUl/NoAtTTXAOSIlvO91OrY8xaZBY.Tf0Ili6t8wOBevQEU7nsKq', 'user');

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
-- Table structure for table `knjige`
--

CREATE TABLE `knjige` (
  `IDKnjiga` int(11) NOT NULL,
  `naslov` varchar(100) NOT NULL,
  `AutorID` int(11) NOT NULL,
  `IzdavacID` int(11) NOT NULL,
  `VrstaID` int(11) NOT NULL,
  `ISBN_broj` varchar(20) DEFAULT NULL,
  `broj_primjeraka` int(11) NOT NULL DEFAULT 1,
  `naslovnica` varchar(255) DEFAULT NULL,
  `godina_izdanja` int(4) DEFAULT NULL,
  `opis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knjige`
--

INSERT INTO `knjige` (`IDKnjiga`, `naslov`, `AutorID`, `IzdavacID`, `VrstaID`, `ISBN_broj`, `broj_primjeraka`, `naslovnica`, `godina_izdanja`, `opis`) VALUES
(1, 'The Art of Computer Programming', 1, 1, 1, '978-0-201-03804-2', 3, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/TheArtofComputerProgramming.png', 1968, 'Fundamentalno djelo o računalnom programiranju'),
(2, 'A Brief History of Time', 2, 2, 1, '978-0-553-17521-9', 5, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/ABriefHistoryofTime.png', 1988, 'Popularno-znanstveno djelo o kozmologiji'),
(3, 'Mathematical Statistics', 3, 3, 2, '9780134080918', 1, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/MathematicalStatistics.jpg', 2015, 'Udžbenik matematičke statistike'),
(4, '1984', 7, 7, 6, '978-0-452-28423-4', 4, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/1984.jpg', 1949, 'Distopijski klasik Georgea Orwella'),
(5, 'Harry Potter and the Sorcerer\'s Stone', 9, 9, 6, '978-0-545-01022-1', 5, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/HarryPotterandPhilosoferStone.jpg', 1997, 'Prvi dio serijala o Harryju Potteru'),
(6, 'Ponos i predrasude', 10, 10, 6, '978-953-7396-48-9', 4, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/PonosiiPredrasude.jpeg', 1813, 'Klasični roman Jane Austen'),
(7, 'Murder on the Orient Express', 17, 15, 10, '978-0062073501', 3, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/MurderontheOrientExpress.jpg', 1934, 'Kriminalistički roman Agathe Christie'),
(8, 'Sapiens: A Brief History of Humankind', 18, 16, 11, '978-0062316097', 5, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/SapiensABriefHistoryofHumankind.png', 2011, 'Povijest ljudske vrste'),
(9, 'Design Patterns: Elements of Reusable Object-Oriented Software', 12, 1, 8, '978-0201633610', 5, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/design_patterns.jpg', 1994, 'Klasično djelo o softverskim dizajn uzorcima'),
(10, 'National Geographic Encyclopedia', 8, 8, 7, '978-1-4262-1300-0', 6, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/natgeo_encyclopedia.jpg', 2013, 'Opsežna enciklopedija National Geographica'),
(11, 'Priče iz davnine', 13, 11, 9, '978-953-6747-00-8', 2, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/price_iz_davnine.jpg', 1916, 'Zbirka hrvatskih narodnih pripovijedaka'),
(12, 'The Cambridge Encyclopedia of Language', 11, 6, 7, '978-0521736503', 3, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/cambridge_language.jpg', 2010, 'Opsežna enciklopedija o jeziku i lingvistici'),
(13, 'Watchmen', 16, 14, 12, '978-0930289232', 4, 'C:/xampp/htdocs/zavrsniSakar/naslovnice/Watchmen.jpg', 1986, 'Kultni strip o superherojima s dubokom filozofskom podlogom');

-- --------------------------------------------------------

--
-- Table structure for table `posudba`
--

CREATE TABLE `posudba` (
  `IDPosudba` int(11) NOT NULL,
  `PrimjerakID` int(11) NOT NULL,
  `ClanID` int(11) NOT NULL,
  `DatumPosudbe` datetime NOT NULL DEFAULT current_timestamp(),
  `DatumVracanja` datetime DEFAULT NULL,
  `RokVracanja` datetime DEFAULT NULL,
  `status` enum('aktivna','vraćeno','kasni','otkazano') NOT NULL DEFAULT 'aktivna',
  `napomena` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posudba`
--

INSERT INTO `posudba` (`IDPosudba`, `PrimjerakID`, `ClanID`, `DatumPosudbe`, `DatumVracanja`, `RokVracanja`, `status`, `napomena`) VALUES
(1, 1, 101, '2024-01-10 10:00:00', NULL, '2024-02-09 10:00:00', 'aktivna', NULL),
(2, 2, 103, '2024-01-12 11:30:00', NULL, '2024-02-11 11:30:00', 'aktivna', NULL),
(3, 4, 102, '2024-01-05 14:15:00', NULL, '2024-02-04 14:15:00', 'aktivna', NULL),
(4, 7, 105, '2024-01-03 09:45:00', NULL, '2024-02-02 09:45:00', 'vraćeno', NULL),
(5, 8, 101, '2024-03-10 16:20:00', NULL, '2024-04-09 16:20:00', 'aktivna', NULL),
(6, 9, 103, '2024-03-11 13:10:00', NULL, '2024-04-10 13:10:00', 'aktivna', NULL),
(7, 11, 103, '2024-01-15 10:30:00', NULL, '2024-02-14 10:30:00', 'aktivna', NULL),
(8, 12, 102, '2024-01-16 15:45:00', NULL, '2024-02-15 15:45:00', 'aktivna', NULL),
(9, 14, 106, '2024-05-05 12:00:00', NULL, '2024-06-04 12:00:00', 'aktivna', NULL),
(10, 15, 108, '2024-05-07 14:30:00', NULL, '2024-06-06 14:30:00', 'aktivna', NULL),
(11, 16, 104, '2024-06-01 10:00:00', NULL, '2024-07-01 10:00:00', 'aktivna', NULL),
(12, 24, 107, '2024-05-15 14:30:00', NULL, '2024-06-14 14:30:00', 'aktivna', NULL),
(13, 27, 108, '2024-05-20 11:15:00', NULL, '2024-06-19 11:15:00', 'aktivna', NULL),
(14, 28, 102, '2024-05-22 16:45:00', NULL, '2024-06-21 16:45:00', 'aktivna', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `primjerak`
--

CREATE TABLE `primjerak` (
  `IDPrimjerak` int(11) NOT NULL,
  `KnjigaID` int(11) NOT NULL,
  `inventarni_broj` varchar(20) NOT NULL,
  `DatumPosudbe` date DEFAULT NULL,
  `DatumVracanja` date DEFAULT NULL,
  `ClanID` int(11) DEFAULT NULL,
  `status` enum('dostupno','posuđeno','rezervirano','na popravku','izgubljeno') DEFAULT 'dostupno',
  `napomena` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `primjerak`
--

INSERT INTO `primjerak` (`IDPrimjerak`, `KnjigaID`, `inventarni_broj`, `DatumPosudbe`, `DatumVracanja`, `ClanID`, `status`, `napomena`) VALUES
(1, 1, 'INV-001', '2024-01-10', NULL, 101, 'posuđeno', NULL),
(2, 1, 'INV-002', '2024-01-12', NULL, 103, 'posuđeno', NULL),
(3, 1, 'INV-003', NULL, NULL, NULL, 'dostupno', NULL),
(4, 2, 'INV-004', '2024-01-05', NULL, 102, 'posuđeno', NULL),
(5, 2, 'INV-005', NULL, NULL, NULL, 'dostupno', NULL),
(6, 2, 'INV-006', NULL, NULL, NULL, 'dostupno', NULL),
(7, 3, 'INV-007', '2024-01-03', NULL, 105, 'posuđeno', NULL),
(8, 4, 'INV-008', '2024-03-10', NULL, 101, 'posuđeno', NULL),
(9, 4, 'INV-009', '2024-03-11', NULL, 103, 'posuđeno', NULL),
(10, 4, 'INV-010', NULL, NULL, NULL, 'dostupno', NULL),
(11, 5, 'INV-011', '2024-01-15', NULL, 103, 'posuđeno', NULL),
(12, 6, 'INV-012', '2024-01-16', NULL, 102, 'posuđeno', NULL),
(13, 6, 'INV-013', NULL, NULL, NULL, 'dostupno', NULL),
(14, 7, 'INV-014', '2024-05-05', NULL, 106, 'posuđeno', NULL),
(15, 8, 'INV-015', '2024-05-07', NULL, 108, 'posuđeno', NULL),
(16, 9, 'INV-016', NULL, NULL, NULL, 'dostupno', NULL),
(17, 9, 'INV-017', NULL, NULL, NULL, 'dostupno', NULL),
(18, 9, 'INV-018', NULL, NULL, NULL, 'dostupno', NULL),
(19, 9, 'INV-019', NULL, NULL, NULL, 'dostupno', NULL),
(20, 9, 'INV-020', NULL, NULL, NULL, 'dostupno', NULL),
(21, 10, 'INV-021', '2024-06-01', NULL, 104, 'posuđeno', NULL),
(22, 10, 'INV-022', NULL, NULL, NULL, 'dostupno', NULL),
(23, 10, 'INV-023', NULL, NULL, NULL, 'dostupno', NULL),
(24, 10, 'INV-024', NULL, NULL, NULL, 'dostupno', NULL),
(25, 10, 'INV-025', NULL, NULL, NULL, 'dostupno', NULL),
(26, 10, 'INV-026', NULL, NULL, NULL, 'dostupno', NULL),
(27, 11, 'INV-027', NULL, NULL, NULL, 'dostupno', NULL),
(28, 11, 'INV-028', NULL, NULL, NULL, 'dostupno', NULL),
(29, 12, 'INV-029', '2024-05-15', NULL, 107, 'posuđeno', NULL),
(30, 12, 'INV-030', NULL, NULL, NULL, 'dostupno', NULL),
(31, 12, 'INV-031', NULL, NULL, NULL, 'dostupno', NULL),
(32, 13, 'INV-032', '2024-05-20', NULL, 108, 'posuđeno', NULL),
(33, 13, 'INV-033', '2024-05-22', NULL, 102, 'posuđeno', NULL),
(34, 13, 'INV-034', NULL, NULL, NULL, 'dostupno', NULL),
(35, 13, 'INV-035', NULL, NULL, NULL, 'dostupno', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rezervacija`
--

CREATE TABLE `rezervacija` (
  `IDRezervacija` int(11) NOT NULL,
  `KnjigaID` int(11) NOT NULL,
  `ClanID` int(11) NOT NULL,
  `DatumRezervacije` datetime NOT NULL DEFAULT current_timestamp(),
  `RokPreuzimanja` datetime DEFAULT NULL,
  `status` enum('aktivna','isporučeno','otkazano','isteklo') NOT NULL DEFAULT 'aktivna',
  `napomena` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezervacija`
--

INSERT INTO `rezervacija` (`IDRezervacija`, `KnjigaID`, `ClanID`, `DatumRezervacije`, `RokPreuzimanja`, `status`, `napomena`) VALUES
(1, 1, 104, '2024-06-01 10:00:00', '2024-06-08 10:00:00', 'aktivna', NULL),
(2, 3, 107, '2024-06-02 11:30:00', '2024-06-09 11:30:00', 'aktivna', NULL),
(3, 5, 109, '2024-05-28 14:15:00', '2024-06-04 14:15:00', 'isteklo', NULL),
(4, 2, 101, '2024-06-03 09:20:00', '2024-06-10 09:20:00', 'aktivna', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vrsta`
--

CREATE TABLE `vrsta` (
  `IDVrsta` int(11) NOT NULL,
  `naziv` varchar(50) NOT NULL,
  `opis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vrsta`
--

INSERT INTO `vrsta` (`IDVrsta`, `naziv`, `opis`) VALUES
(1, 'Fakultativna knjiga', 'Dodatna literatura za studij'),
(2, 'Udžbenik', 'Osnovna nastavna literatura'),
(3, 'Znanstveni časopis', 'Stručni i znanstveni časopisi'),
(4, 'Zbornik radova', 'Zbornici sa znanstvenih skupova'),
(5, 'Priručnik', 'Stručni priručnici'),
(6, 'Roman', 'Književna djela - romani'),
(7, 'Enciklopedija', 'Enciklopedijska izdanja'),
(8, 'Znanstvena monografija', 'Znanstvene monografije'),
(9, 'Biografija', 'Biografska djela'),
(10, 'Kriminalistički roman', 'Kriminalistički romani'),
(11, 'Povijesna studija', 'Povijesne analize i studije'),
(12, 'Strip', 'Strip izdanja');

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
-- Indexes for table `knjige`
--
ALTER TABLE `knjige`
  ADD PRIMARY KEY (`IDKnjiga`),
  ADD KEY `AutorID` (`AutorID`),
  ADD KEY `IzdavacID` (`IzdavacID`),
  ADD KEY `VrstaID` (`VrstaID`);

--
-- Indexes for table `posudba`
--
ALTER TABLE `posudba`
  ADD PRIMARY KEY (`IDPosudba`),
  ADD KEY `PrimjerakID` (`PrimjerakID`),
  ADD KEY `ClanID` (`ClanID`);

--
-- Indexes for table `primjerak`
--
ALTER TABLE `primjerak`
  ADD PRIMARY KEY (`IDPrimjerak`),
  ADD UNIQUE KEY `inventarni_broj` (`inventarni_broj`),
  ADD KEY `KnjigaID` (`KnjigaID`),
  ADD KEY `ClanID` (`ClanID`);

--
-- Indexes for table `rezervacija`
--
ALTER TABLE `rezervacija`
  ADD PRIMARY KEY (`IDRezervacija`),
  ADD KEY `KnjigaID` (`KnjigaID`),
  ADD KEY `ClanID` (`ClanID`);

--
-- Indexes for table `vrsta`
--
ALTER TABLE `vrsta`
  ADD PRIMARY KEY (`IDVrsta`),
  ADD UNIQUE KEY `naziv` (`naziv`);

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
  MODIFY `IDClan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT for table `izdavac`
--
ALTER TABLE `izdavac`
  MODIFY `IzdavacID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `knjige`
--
ALTER TABLE `knjige`
  MODIFY `IDKnjiga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posudba`
--
ALTER TABLE `posudba`
  MODIFY `IDPosudba` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `primjerak`
--
ALTER TABLE `primjerak`
  MODIFY `IDPrimjerak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `rezervacija`
--
ALTER TABLE `rezervacija`
  MODIFY `IDRezervacija` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vrsta`
--
ALTER TABLE `vrsta`
  MODIFY `IDVrsta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `knjige`
--
ALTER TABLE `knjige`
  ADD CONSTRAINT `knjige_ibfk_1` FOREIGN KEY (`AutorID`) REFERENCES `autor` (`AutorID`),
  ADD CONSTRAINT `knjige_ibfk_2` FOREIGN KEY (`IzdavacID`) REFERENCES `izdavac` (`IzdavacID`),
  ADD CONSTRAINT `knjige_ibfk_3` FOREIGN KEY (`VrstaID`) REFERENCES `vrsta` (`IDVrsta`);

--
-- Constraints for table `rezervacija`
--
ALTER TABLE `rezervacija`
  ADD CONSTRAINT `rezervacija_ibfk_1` FOREIGN KEY (`KnjigaID`) REFERENCES `knjige` (`IDKnjiga`),
  ADD CONSTRAINT `rezervacija_ibfk_2` FOREIGN KEY (`ClanID`) REFERENCES `clan` (`IDClan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
