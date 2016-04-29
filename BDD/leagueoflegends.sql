-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 28 Avril 2016 à 20:14
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
(1, 'Pipiro', '720315b6-0816-4222-b740-291bc1ae4af9', 1461866981, 10, 1461866981, 60, 1),
(2, 'Pipirox', 'afd770fb-cab4-42cc-b917-4a92a8d90c53', 1461866576, 5, 1461866576, 5, 1),
(3, 'Kaaakaaapipi', '30887bc8-0c93-4867-be51-435e72dbec16', 1461865264, 10, 1461865264, 10, 1),
(5, 'Kaakaapipi', '76496b37-61f0-4d1a-93a5-5ed8371003a7', 1461747786, 1, 1461747786, 7, 1),
(6, 'Xanion', '8fc9f52a-1340-4d22-9268-c5da61403230', 1461747786, 1, 1461747786, 7, 1),
(7, 'Stax', '9cd23d6a-5cf2-411d-9142-8f983997c8fa', 1461747786, 1, 1461747786, 7, 1),
(8, 'smurf stax', 'c69a5e56-386d-4de3-a9aa-c4db274efd8a', 1461747786, 1, 1461747786, 7, 1);

-- --------------------------------------------------------

--
-- Structure de la table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `idLol` bigint(20) NOT NULL,
  `actif` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `players`
--

INSERT INTO `players` (`id`, `name`, `idLol`, `actif`) VALUES
(1, 'Pipiro', 19441329, 1),
(2, 'Xanion', 125302, 1),
(3, 'Stax', 27622126, 1),
(4, 'Dodo', 23656419, 1),
(5, 'Fruit', 31757024, 1),
(6, 'Guigui', 52543274, 1),
(7, 'Smurf Xanion', 28960383, 1),
(8, 'Smurf Pipiro', 38640997, 1),
(9, 'Foufoune', 19547616, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `playerstoteam`
--

INSERT INTO `playerstoteam` (`id`, `idPlayer`, `idTeam`) VALUES
(1, 3, 1),
(2, 6, 1),
(3, 7, 1),
(4, 8, 1),
(5, 9, 1),
(6, 1, 2),
(7, 2, 2),
(8, 3, 2),
(9, 4, 2),
(10, 5, 2);

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `teams`
--

INSERT INTO `teams` (`id`, `name`) VALUES
(1, 'Incredibles Geeks'),
(2, 'Origine');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `playerstoteam`
--
ALTER TABLE `playerstoteam`
  ADD CONSTRAINT `idTeamConstraint` FOREIGN KEY (`idTeam`) REFERENCES `teams` (`id`),
  ADD CONSTRAINT `idPlayerConstraint` FOREIGN KEY (`idPlayer`) REFERENCES `players` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
