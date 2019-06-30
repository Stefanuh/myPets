-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 37.97.170.92
-- Gegenereerd op: 01 jul 2019 om 01:31
-- Serverversie: 10.1.40-MariaDB-1~stretch
-- PHP-versie: 5.6.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stefanjovanovic_nl_mypets`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `appointment`
--

CREATE TABLE `appointment` (
  `appointmentID` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `petID` int(10) NOT NULL,
  `state` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `appointment_treatment`
--

CREATE TABLE `appointment_treatment` (
  `appointmentID` int(10) NOT NULL,
  `treatmentID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `breed`
--

CREATE TABLE `breed` (
  `breedID` int(10) NOT NULL,
  `breedTypeID` int(1) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `breed`
--

INSERT INTO `breed` (`breedID`, `breedTypeID`, `name`) VALUES
(1, 1, 'Australische herder'),
(2, 1, 'Beagle'),
(3, 1, 'Golden retriever'),
(4, 1, 'Duitse herder'),
(5, 1, 'Mopshond'),
(6, 1, 'Franse poedel'),
(7, 1, 'Chihuahua'),
(8, 1, 'Engelse buldog'),
(9, 1, 'Greyhound'),
(10, 1, 'Labrador retriever'),
(11, 2, 'Pers'),
(12, 2, 'Brits korthaar'),
(13, 2, 'Maine Coon'),
(14, 2, 'Siamees'),
(15, 2, 'Ragdoll'),
(16, 2, 'Sphynx'),
(17, 2, 'Abessijn'),
(18, 2, 'Bengaal'),
(19, 2, 'Blauwe Rus'),
(20, 2, 'Amerikaans korthaar');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `breed_type`
--

CREATE TABLE `breed_type` (
  `breedTypeID` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `breed_type`
--

INSERT INTO `breed_type` (`breedTypeID`, `name`) VALUES
(1, 'Hond'),
(2, 'Kat');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `menu`
--

CREATE TABLE `menu` (
  `menuID` int(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `slug` varchar(20) NOT NULL,
  `secured` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `menu`
--

INSERT INTO `menu` (`menuID`, `name`, `slug`, `secured`) VALUES
(1, 'Log in', 'login', 0),
(2, 'Account aanmaken', 'register', 0),
(3, 'Overzicht', 'dashboard', 1),
(4, 'Afspraken', 'appointment', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pet`
--

CREATE TABLE `pet` (
  `petID` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `breedID` int(10) NOT NULL,
  `birth` date NOT NULL,
  `userID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `treatment`
--

CREATE TABLE `treatment` (
  `treatmentID` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `treatment`
--

INSERT INTO `treatment` (`treatmentID`, `name`, `price`) VALUES
(1, 'Consult', '27.00'),
(2, ' Cocktail controle', '20.00'),
(3, 'Grote cocktail incl. consult', '39.00'),
(4, 'Kleine cocktail incl. consult	', '39.00'),
(5, 'Check & Vaccinatie cocktail', '24.00'),
(6, 'Check & Vaccinatie kennelhoest', '29.00'),
(7, 'Check & Vaccinatie rabiës	', '29.00'),
(8, 'Sterilisatie teef ', '259.00'),
(9, 'Castratie reu ', '129.00'),
(10, 'Sterilisatie poes	', '119.00'),
(11, 'Castratie kater', '55.00'),
(12, 'Nagels knippen / anaalklieren legen', '7.00'),
(13, 'Chippen (excl. registratie)', '32.00'),
(14, 'Chippen (incl. registratie)', '44.00'),
(15, 'Reiniging en polijsten kat', '99.00'),
(16, 'Reiniging en polijsten hond', '119.00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `userID` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `role` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`userID`, `firstName`, `lastName`, `email`, `password`, `phone`, `role`) VALUES
(1, 'Admin', 'Medewerker', 'admin@mypets.nl', '$2y$12$.gHZELY24lRc.V5SKSvqme5fykD/uYOQji42gZ/srML45Oaat28nm', '', 1),
(2, 'Test', 'Klant', 'klant@mypets.nl', '$2y$12$97oWJpRIoJBcWLJN802oRe03btOSNwOkL7KL6MSKQshVwcb6iEkhC', '', 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointmentID`),
  ADD KEY `petID` (`petID`);

--
-- Indexen voor tabel `appointment_treatment`
--
ALTER TABLE `appointment_treatment`
  ADD KEY `appointment_treatment_ibfk_1` (`appointmentID`),
  ADD KEY `appointment_treatment_ibfk_2` (`treatmentID`);

--
-- Indexen voor tabel `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`breedID`),
  ADD KEY `breedTypeID` (`breedTypeID`);

--
-- Indexen voor tabel `breed_type`
--
ALTER TABLE `breed_type`
  ADD PRIMARY KEY (`breedTypeID`);

--
-- Indexen voor tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menuID`);

--
-- Indexen voor tabel `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`petID`),
  ADD KEY `breedID` (`breedID`),
  ADD KEY `userID` (`userID`);

--
-- Indexen voor tabel `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`treatmentID`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointmentID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `breed`
--
ALTER TABLE `breed`
  MODIFY `breedID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT voor een tabel `breed_type`
--
ALTER TABLE `breed_type`
  MODIFY `breedTypeID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `menuID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `pet`
--
ALTER TABLE `pet`
  MODIFY `petID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `treatment`
--
ALTER TABLE `treatment`
  MODIFY `treatmentID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`petID`) REFERENCES `pet` (`petID`);

--
-- Beperkingen voor tabel `appointment_treatment`
--
ALTER TABLE `appointment_treatment`
  ADD CONSTRAINT `appointment_treatment_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`appointmentID`) ON DELETE NO ACTION,
  ADD CONSTRAINT `appointment_treatment_ibfk_2` FOREIGN KEY (`treatmentID`) REFERENCES `treatment` (`treatmentID`) ON DELETE NO ACTION;

--
-- Beperkingen voor tabel `breed`
--
ALTER TABLE `breed`
  ADD CONSTRAINT `breed_ibfk_1` FOREIGN KEY (`breedTypeID`) REFERENCES `breed_type` (`breedTypeID`);

--
-- Beperkingen voor tabel `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`breedID`) REFERENCES `breed` (`breedID`),
  ADD CONSTRAINT `pet_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
