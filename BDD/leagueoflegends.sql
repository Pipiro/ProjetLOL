-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 08 Avril 2017 à 00:22
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `leagueoflegends`
--

-- --------------------------------------------------------

--
-- Structure de la table `apikey`
--

CREATE TABLE IF NOT EXISTS `apikey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `timestamp10s` int(20) NOT NULL DEFAULT '0',
  `number10s` int(11) NOT NULL DEFAULT '0',
  `timestamp10m` int(11) NOT NULL DEFAULT '0',
  `number10m` int(11) NOT NULL DEFAULT '0',
  `actif` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `apikey`
--

INSERT INTO `apikey` (`id`, `name`, `value`, `timestamp10s`, `number10s`, `timestamp10m`, `number10m`, `actif`) VALUES
(1, 'Pipiro', '720315b6-0816-4222-b740-291bc1ae4af9', 1491329310, 6, 1491329310, 452, 1),
(2, 'Pipirox', 'afd770fb-cab4-42cc-b917-4a92a8d90c53', 1491329093, 10, 1491329093, 24, 1),
(3, 'Kaaakaaapipi', '30887bc8-0c93-4867-be51-435e72dbec16', 1491329093, 1, 1491329093, 1, 1),
(5, 'Kaakaapipi', '76496b37-61f0-4d1a-93a5-5ed8371003a7', 1461951034, 10, 1461951034, 10, 1),
(6, 'Xanion', '8fc9f52a-1340-4d22-9268-c5da61403230', 1461951034, 4, 1461951034, 4, 1),
(7, 'Stax', '9cd23d6a-5cf2-411d-9142-8f983997c8fa', 1461747786, 1, 1461747786, 7, 1),
(8, 'smurf stax', 'c69a5e56-386d-4de3-a9aa-c4db274efd8a', 1461747786, 1, 1461747786, 7, 1);

-- --------------------------------------------------------

--
-- Structure de la table `cacheplayers`
--

CREATE TABLE IF NOT EXISTS `cacheplayers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPlayer` int(11) NOT NULL,
  `isRanked` int(11) NOT NULL,
  `updateDate` bigint(20) NOT NULL,
  `idPlayerLol` int(11) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `leagueName` varchar(255) DEFAULT NULL,
  `leaguePoint` varchar(255) DEFAULT NULL,
  `leagueTier` varchar(255) DEFAULT NULL,
  `leagueDivision` varchar(255) DEFAULT NULL,
  `miniSerieProgress` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idPlayer` (`idPlayer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=389 ;

--
-- Contenu de la table `cacheplayers`
--

INSERT INTO `cacheplayers` (`id`, `idPlayer`, `isRanked`, `updateDate`, `idPlayerLol`, `nickname`, `leagueName`, `leaguePoint`, `leagueTier`, `leagueDivision`, `miniSerieProgress`) VALUES
(369, 1, 1, 1491324279, 19441329, 'Pipiroo', 'Hecarim''s Brutes', '29', 'GOLD', 'III', ''),
(370, 2, 1, 1491324279, 125302, 'Xanion', 'Nunu''s Patriots', '0', 'SILVER', 'I', ''),
(372, 4, 0, 1491323640, 23656419, 'IG Dodo', 'Hecarim''s Brutes', '51', 'GOLD', 'IV', ''),
(373, 5, 1, 1491323640, 31757024, 'ImmaFruitDealer', 'Wukong''s Swashbucklers', '0', 'DIAMOND', 'IV', ''),
(378, 13, 1, 1462216433, 82746988, 'TEAM24SEVEN', 'Zyra''s Infiltrators', '1350', 'CHALLENGER', 'I', ''),
(379, 14, 1, 1462216433, 19474104, 'iSatorius', 'Zyra''s Infiltrators', '1206', 'CHALLENGER', 'I', ''),
(380, 15, 1, 1462216433, 21999355, 'Exileh', 'Zyra''s Infiltrators', '1153', 'CHALLENGER', 'I', ''),
(381, 16, 1, 1462216433, 71347673, 'NoXlAK', 'Zyra''s Infiltrators', '1148', 'CHALLENGER', 'I', ''),
(382, 17, 1, 1462216433, 76406887, 'Gilius 1v9', 'Zyra''s Infiltrators', '1051', 'CHALLENGER', 'I', ''),
(383, 3, 0, 1491324279, 27622126, 'Staxboy Q', 'Nunu''s Patriots', '0', 'SILVER', 'III', ''),
(384, 6, 0, 1491323527, 52543274, '', '', '', '', '', ''),
(385, 7, 0, 1491323527, 28960383, '', '', '', '', '', ''),
(386, 8, 0, 1491323527, 38640997, 'OTP Master Yi', 'Xerath''s Executioners', '78', 'BRONZE', 'I', ''),
(387, 9, 1, 1491323527, 19547616, 'FragritÃ´', 'Nocturne''s Legion', '23', 'SILVER', 'IV', ''),
(388, 19, 1, 1491324279, 27459857, 'Takenow', 'Maokai''s Fists', '75', 'PLATINUM', 'II', '');

-- --------------------------------------------------------

--
-- Structure de la table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_roman_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  `idLol` bigint(20) NOT NULL,
  `actif` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `players`
--

INSERT INTO `players` (`id`, `name`, `role`, `idLol`, `actif`) VALUES
(1, 'Pipiroo', 4, 19441329, 1),
(2, 'Xanion', 3, 125302, 1),
(3, 'Staxboy Q', 2, 27622126, 1),
(4, 'IG Dodo', 1, 23656419, 1),
(5, 'ImmaFruitDealer', 2, 31757024, 1),
(6, 'Armageddon42', 1, 52543274, 1),
(7, 'Crypto Xanion', 3, 28960383, 1),
(8, 'Le Bronz&eacute', 4, 38640997, 1),
(9, 'FragritÃ´', 5, 19547616, 1),
(11, 'TEAM24SEVEN', 0, 82746988, 1),
(12, 'iSatorius', 0, 19474104, 1),
(13, 'TEAM24SEVEN', 0, 82746988, 1),
(14, 'iSatorius', 0, 19474104, 1),
(15, 'Exileh', NULL, 21999355, 1),
(16, 'NoXlAK', NULL, 71347673, 1),
(17, 'Gilius 1v9', NULL, 76406887, 1),
(18, 'Carryzer', NULL, 71031429, 0),
(19, 'Takenow', NULL, 27459857, 1);

-- --------------------------------------------------------

--
-- Structure de la table `playerstoteam`
--

CREATE TABLE IF NOT EXISTS `playerstoteam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPlayer` int(11) NOT NULL,
  `idTeam` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTeamIndex` (`idTeam`),
  KEY `idPlayerIndex` (`idPlayer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `playerstoteam`
--

INSERT INTO `playerstoteam` (`id`, `idPlayer`, `idTeam`) VALUES
(6, 1, 2),
(7, 2, 2),
(8, 3, 2),
(9, 4, 2),
(12, 5, 2),
(13, 13, 3),
(14, 14, 3),
(15, 15, 3),
(16, 16, 3),
(17, 17, 3),
(18, 18, 3),
(19, 1, 1),
(20, 2, 1),
(21, 3, 1),
(22, 19, 1);

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `teams`
--

INSERT INTO `teams` (`id`, `name`) VALUES
(1, 'Incredibles Geeks'),
(2, 'Origine'),
(3, 'Pros');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cacheplayers`
--
ALTER TABLE `cacheplayers`
  ADD CONSTRAINT `constraintIdPlayer` FOREIGN KEY (`idPlayer`) REFERENCES `players` (`id`);

--
-- Contraintes pour la table `playerstoteam`
--
ALTER TABLE `playerstoteam`
  ADD CONSTRAINT `idPlayerConstraint` FOREIGN KEY (`idPlayer`) REFERENCES `players` (`id`),
  ADD CONSTRAINT `idTeamConstraint` FOREIGN KEY (`idTeam`) REFERENCES `teams` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
