-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 12, 2015 at 01:12 PM
-- Server version: 5.5.34
-- PHP Version: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `oressource`
--

-- --------------------------------------------------------

--
-- Table structure for table `collectes`
--

CREATE TABLE IF NOT EXISTS `collectes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_type_collecte` int(11) NOT NULL,
  `adherent` text NOT NULL,
  `localisation` int(11) NOT NULL,
  `id_point_collecte` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `collectes`
--

INSERT INTO `collectes` (`id`, `timestamp`, `id_type_collecte`, `adherent`, `localisation`, `id_point_collecte`, `commentaire`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-12-13 01:42:54', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(2, '2014-12-13 01:43:20', 1, 'non', 3, 1, '', 1, 0, '0000-00-00 00:00:00'),
(3, '2014-12-13 01:43:47', 4, 'non', 9, 1, '', 1, 1, '2014-12-13 15:11:15'),
(4, '2014-12-13 01:44:15', 3, 'non', 19, 1, '', 1, 0, '0000-00-00 00:00:00'),
(5, '2014-12-13 01:46:42', 2, 'non', 2, 2, '', 1, 0, '0000-00-00 00:00:00'),
(6, '2014-12-13 01:47:57', 3, 'non', 2, 2, '', 1, 0, '0000-00-00 00:00:00'),
(7, '2014-12-13 16:12:52', 1, 'non', 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(8, '2014-12-13 16:43:12', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(9, '2014-12-13 16:55:40', 2, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(10, '2014-12-13 17:26:39', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(11, '2014-12-13 17:27:32', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(12, '2014-12-13 17:35:38', 4, 'non', 12, 1, '', 1, 0, '0000-00-00 00:00:00'),
(13, '2014-12-14 16:49:59', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(14, '2014-12-14 18:56:16', 2, 'non', 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(15, '2014-12-15 00:19:48', 1, 'non', 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(16, '2015-01-25 16:19:05', 1, 'non', 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(17, '2015-01-25 16:19:20', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(18, '2015-02-05 09:30:43', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(19, '2015-02-05 10:11:55', 2, 'non', 2, 1, '', 1, 1, '2015-02-05 10:13:04'),
(20, '2015-02-24 11:08:18', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(21, '2015-02-24 11:08:43', 1, 'non', 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(22, '2015-02-27 00:38:39', 1, 'non', 3, 1, '', 1, 0, '0000-00-00 00:00:00'),
(23, '2015-06-02 11:42:15', 1, 'non', 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(24, '2015-05-07 11:42:39', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(25, '2015-06-02 11:46:40', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(26, '2015-06-12 10:01:37', 1, 'non', 1, 1, '', 1, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `conventions_sorties`
--

CREATE TABLE IF NOT EXISTS `conventions_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `conventions_sorties`
--

INSERT INTO `conventions_sorties` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-07-10 13:28:10', 'la maison de la plage', 'mozaiques', '#828fe4', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-07-10 13:29:44', 'action froid', 'maraudes sdf et autres', '#626ac2', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-07-23 11:48:09', 'dÃ©chetterie ', 'porte des lilas', '#89b029', 'non', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `description_structure`
--

CREATE TABLE IF NOT EXISTS `description_structure` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `description` text NOT NULL,
  `siret` text NOT NULL,
  `telephone` text NOT NULL,
  `mail` text NOT NULL,
  `id_localite` text NOT NULL,
  `texte_adhesion` text NOT NULL,
  `taux_tva` text NOT NULL,
  `tva_active` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cr` int(11) NOT NULL,
  `lot` text NOT NULL,
  `viz` text NOT NULL,
  `nb_viz` int(11) NOT NULL,
  `saisiec` text NOT NULL,
  `affsp` text NOT NULL COMMENT 'Afficher Saisie Poubelles',
  `affss` text NOT NULL COMMENT 'Afficher Saisie Sorties Partenaires', 
  `affsr` text NOT NULL COMMENT 'Afficher Saisie Recyclage',
  `affsd` text NOT NULL COMMENT 'Afficher Saisie Don',
  `affsde` text NOT NULL COMMENT 'Afficher Saisie Dechetterie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `description_structure`
--

INSERT INTO `description_structure` (`id`, `nom`, `adresse`, `description`, `siret`, `telephone`, `mail`, `id_localite`, `texte_adhesion`, `taux_tva`, `tva_active`, `id_createur`, `id_last_hero`, `last_hero_timestamp`, `cr`, `lot`, `viz`, `nb_viz`, `saisiec`) VALUES
(1, 'La petite rockette', '125 rue du chemin vert', 'la petite rockette est une asso cool', '508 822 475 00010', '0155286118', 'lapetiterockette@gmail.com', '1', '  L''adhÃ©sion Ã  la ressourcerie formalise avant tout votre soutien aux valeurs Ã©cologiques et sociales dÃ©fendues par l''association. (Et peut, par ailleurs, s''avÃ©rer utile pour Ãªtre tenu informÃ© par courriel des diverses activitÃ©s, ponctuelles ou ordinaires, \r\ndÃ©veloppÃ©es la ressourcerie.)\r\n   AdhÃ©rer est donc surtout un geste politique, militant, d''engagement actif dans la lutte contre l''absurditÃ© consumÃ©riste et sa normalisation du gaspillage!!', '10', 'non', 0, 0, '2015-06-02 10:58:23', 1234, 'oui', 'oui', 10, 'oui');

-- --------------------------------------------------------

--
-- Table structure for table `filieres_sortie`
--

CREATE TABLE IF NOT EXISTS `filieres_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `id_type_dechet_evac` text NOT NULL,
  `couleur` text NOT NULL,
  `description` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `filieres_sortie`
--

INSERT INTO `filieres_sortie` (`id`, `timestamp`, `nom`, `id_type_dechet_evac`, `couleur`, `description`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-07-10 13:54:43', 'recycle livre', '3', '#a77a37', 'ramasse livres', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-07-10 13:55:01', 'gebetex', '6', '#f48570', 'ramasse textiles', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-07-10 14:17:52', 'eco-systemes', '2', '#b36cb4', 'ramasse deee', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-07-10 18:51:38', 'screlec', '5', '#e3c451', 'ampoules', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-09-14 13:22:47', 'bijoutek', '1', '#ff9a00', 'recycle les bijoux', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `grille_objets`
--

CREATE TABLE IF NOT EXISTS `grille_objets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `visible` text NOT NULL,
  `prix` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `grille_objets`
--

INSERT INTO `grille_objets` (`id`, `timestamp`, `nom`, `description`, `id_type_dechet`, `visible`, `prix`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-08-07 13:49:49', 'T-shirt, haut', 'T-shirts et hauts en touts genres', 3, 'oui', '1.5', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-08-07 14:17:10', 'tv', 'televsion', 1, 'oui', '7', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-08-07 14:49:46', 'lecteur vhs ', 'lecteur vhs ', 1, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-08-07 14:51:28', 'lecteur dvd', 'lecteur dvd ', 1, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(6, '2014-08-07 14:53:58', 'four micro ondes', 'four', 1, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-08-07 14:54:49', 'Ã©tagÃ¨re billy', 'ikea valeur neuf = ', 2, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(8, '2014-08-07 14:55:49', 'puzzle', 'puzzle', 4, 'oui', '1.5', 0, 0, '0000-00-00 00:00:00'),
(9, '2014-08-07 14:57:03', 'clavier', 'clavier', 5, 'oui', '2', 0, 0, '0000-00-00 00:00:00'),
(10, '2014-08-07 14:57:18', 'bol', 'bol', 6, 'oui', '0.5', 0, 0, '0000-00-00 00:00:00'),
(11, '2014-08-07 14:58:51', 'assiette', 'assiette', 6, 'oui', '0.25', 0, 0, '0000-00-00 00:00:00'),
(12, '2014-08-07 15:02:15', 'bd', 'BD', 7, 'oui', '0.1', 0, 0, '0000-00-00 00:00:00'),
(13, '2014-08-07 15:02:40', 'polars', 'polars', 7, 'oui', '0.1', 0, 0, '0000-00-00 00:00:00'),
(14, '2014-08-07 15:03:14', 'vinyle', 'vinyle', 8, 'oui', '0.1', 0, 0, '0000-00-00 00:00:00'),
(15, '2014-08-07 15:03:28', 'dvd', 'dvd', 8, 'oui', '0.1', 0, 0, '0000-00-00 00:00:00'),
(16, '2014-08-07 15:03:38', 'vhs', 'vhs', 8, 'oui', '0.1', 0, 0, '0000-00-00 00:00:00'),
(17, '2014-08-07 15:03:52', 'K7 audio', 'K7 audio', 8, 'oui', '0.1', 0, 0, '0000-00-00 00:00:00'),
(18, '2014-08-07 15:04:07', 'cadres', 'cadres', 9, 'oui', '1', 0, 0, '0000-00-00 00:00:00'),
(19, '2014-08-07 15:04:37', 'bouteille de gaz', 'heuu...', 10, 'oui', '51', 0, 0, '0000-00-00 00:00:00'),
(21, '2014-08-12 13:17:05', 'chaise pliante', 'chaise pliante', 2, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(22, '2014-09-08 15:21:55', 'pantalon', 'pantalons toutes marques confondues', 3, 'oui', '3', 0, 0, '0000-00-00 00:00:00'),
(23, '2014-09-09 14:25:50', 'grille pain', 'grille pain', 1, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(24, '2014-10-02 12:36:59', 'chemise', 'chemises en tout genre', 3, 'oui', '3', 0, 0, '0000-00-00 00:00:00'),
(25, '2014-10-02 12:37:11', 'veste', 'vestes', 3, 'oui', '3', 0, 0, '0000-00-00 00:00:00'),
(26, '2014-10-02 12:37:31', 'debardeur', 'debardeurs', 3, 'oui', '1', 0, 0, '0000-00-00 00:00:00'),
(27, '2014-10-02 12:38:00', 'soutien gorge', 'soutien gorges', 3, 'oui', '2', 0, 0, '0000-00-00 00:00:00'),
(28, '2014-10-02 12:38:13', 'culotte', 'culottes', 3, 'oui', '1', 0, 0, '0000-00-00 00:00:00'),
(29, '2014-10-02 12:38:27', 'manteau', 'manteaux', 3, 'oui', '5', 0, 0, '0000-00-00 00:00:00'),
(30, '2014-10-02 12:38:47', 'vÃªtement enfant', 'vÃªtements enfants', 3, 'oui', '1.5', 0, 0, '0000-00-00 00:00:00'),
(31, '2014-10-02 12:39:02', 'manteau enfant', 'manteau enfant', 3, 'oui', '3', 0, 0, '0000-00-00 00:00:00'),
(32, '2014-10-02 12:39:24', 'sous vet. enfant', 'sous vet. enfant', 3, 'oui', '1.5', 0, 0, '0000-00-00 00:00:00'),
(33, '2014-10-02 12:39:56', 'linge de maison', 'linge de maison en tout genre', 3, 'oui', '1.5', 0, 0, '0000-00-00 00:00:00'),
(34, '2014-10-02 12:40:07', 'sac Ã  main', 'sac Ã  main', 3, 'oui', '3', 0, 0, '0000-00-00 00:00:00'),
(35, '2014-10-02 12:40:21', 'pochette', 'pochettes', 3, 'oui', '1.5', 0, 0, '0000-00-00 00:00:00'),
(36, '2014-10-02 12:40:35', 'cravatte', 'cravattes', 3, 'oui', '0.5', 0, 0, '0000-00-00 00:00:00'),
(37, '2014-10-02 12:40:49', 'echarpe', 'echarpes ', 3, 'oui', '1', 0, 0, '0000-00-00 00:00:00'),
(38, '2014-10-02 12:41:06', 'paire de gants', 'paires de gants', 3, 'oui', '1', 0, 0, '0000-00-00 00:00:00'),
(39, '2015-02-05 09:36:20', 'cafetiere', 'cafetiere', 1, 'oui', '7', 0, 0, '0000-00-00 00:00:00'),
(40, '2015-02-26 11:44:08', 'papeterie ', 'papeterie', 9, 'oui', '0', 0, 0, '0000-00-00 00:00:00'),
(41, '2015-03-09 09:50:55', 'Chaise Collecterie', 'Chaise Collecterie', 10, 'oui', '85', 0, 0, '0000-00-00 00:00:00'),
(42, '2015-03-09 13:46:35', 'Ordinateur Tour', 'Complet avec clavier & Souris', 5, 'oui', '80', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `localites`
--

CREATE TABLE IF NOT EXISTS `localites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `couleur` text NOT NULL,
  `relation_openstreetmap` text NOT NULL,
  `commentaire` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `localites`
--

INSERT INTO `localites` (`id`, `timestamp`, `nom`, `couleur`, `relation_openstreetmap`, `commentaire`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-06-15 16:56:47', 'Quartier', '#89cc58', 'http://www.openstreetmap.org/relation/9533', 'quartier rockette chemin vert', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-06-15 17:00:58', '11Â° arr.', '#43d163', 'http://www.openstreetmap.org/relation/9529', '11 eme arrondissement de paris', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-07-02 21:53:51', '1er arr.', '#086c0d', 'http://toto.com', 'premier arrondissement de paris', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-12-13 00:00:51', '2Â° arr.', '#8a2f2f', 'http://www.google.fr', 'deuxiemme arrondissement de paris', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-12-13 00:01:22', '3Â° arr.', '#1e8199', '', 'deuxiemme arrondissement de paris', 'oui', 0, 0, '0000-00-00 00:00:00'),
(6, '2014-12-13 00:01:50', '4Â° arr.', '#0f8e90', '', 'quatrieme arrondissement de paris', 'oui', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-12-13 00:02:29', '5Â° arr.', '#b63c3c', 'http://www.google.fr', 'cinquieme arrondissement de paris', 'oui', 0, 0, '0000-00-00 00:00:00'),
(8, '2014-12-13 00:04:11', '6Â° arr.', '#000000', '', '6Â° arr.', 'oui', 0, 0, '0000-00-00 00:00:00'),
(9, '2014-12-13 00:04:21', '7Â° arr.', '#000000', '', '7Â° arr.', 'oui', 0, 0, '0000-00-00 00:00:00'),
(10, '2014-12-13 00:04:29', '8Â° arr.', '#000000', '', '8Â° arr.', 'oui', 0, 0, '0000-00-00 00:00:00'),
(11, '2014-12-13 00:04:38', '9Â° arr.', '#000000', '', '9Â° arr.', 'oui', 0, 0, '0000-00-00 00:00:00'),
(12, '2014-12-13 00:04:50', '10Â° arr.', '#000000', '', '10Â° arr.', 'oui', 0, 0, '0000-00-00 00:00:00'),
(13, '2014-12-13 00:05:06', '12Â° arr.', '#000000', '', '12Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(14, '2014-12-13 00:05:37', '13Â° arr.', '#000000', '', '13Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(15, '2014-12-13 00:06:51', '14Â° arr.', '#000000', '', '14Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(16, '2014-12-13 00:07:02', '15Â° arr.', '#000000', '', '15Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(17, '2014-12-13 00:07:10', '16Â° arr.', '#000000', '', '16Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(18, '2014-12-13 00:07:19', '17Â° arr.', '#000000', '', '17Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(19, '2014-12-13 00:07:27', '18Â° arr.', '#000000', '', '18Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(20, '2014-12-13 00:07:36', '19Â° arr.', '#000000', '', '19Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00'),
(21, '2014-12-13 00:07:46', '20Â° arr.', '#000000', '', '20Â° arrondissement de paris ', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `moyens_paiement`
--

CREATE TABLE IF NOT EXISTS `moyens_paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `moyens_paiement`
--

INSERT INTO `moyens_paiement` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-11-28 23:14:25', 'Especes', 'billet et pieces ', '#732121', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-11-28 23:16:30', 'Cheque', 'cheques', '#c0c0c0', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2015-03-09 13:44:13', 'Carte Bleue', 'Carte Bleue', '#0000ff', 'non', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pesees_collectes`
--

CREATE TABLE IF NOT EXISTS `pesees_collectes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(11,3) NOT NULL,
  `id_collecte` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `pesees_collectes`
--

INSERT INTO `pesees_collectes` (`id`, `timestamp`, `masse`, `id_collecte`, `id_type_dechet`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-12-13 01:42:54', 33.000, 1, 1, 1, 0, '0000-00-00 00:00:00'),
(2, '2014-12-13 01:43:20', 33.000, 2, 5, 1, 0, '0000-00-00 00:00:00'),
(3, '2014-12-13 01:43:20', 55.000, 2, 7, 1, 0, '0000-00-00 00:00:00'),
(4, '2014-12-13 01:43:20', 22.000, 2, 8, 1, 0, '0000-00-00 00:00:00'),
(5, '2014-12-13 01:43:47', 33.000, 3, 3, 1, 0, '0000-00-00 00:00:00'),
(6, '2014-12-13 01:43:47', 11.000, 3, 4, 1, 0, '0000-00-00 00:00:00'),
(7, '2014-12-13 01:43:47', 55.000, 3, 5, 1, 0, '0000-00-00 00:00:00'),
(8, '2014-12-13 01:43:47', 88.000, 3, 8, 1, 0, '0000-00-00 00:00:00'),
(9, '2014-12-13 01:44:15', 133.000, 4, 3, 1, 0, '0000-00-00 00:00:00'),
(10, '2014-12-13 01:44:15', 6.000, 4, 6, 1, 0, '0000-00-00 00:00:00'),
(11, '2014-12-13 01:46:42', 55.000, 5, 1, 1, 0, '0000-00-00 00:00:00'),
(12, '2014-12-13 01:47:57', 2.000, 6, 1, 1, 0, '0000-00-00 00:00:00'),
(13, '2014-12-13 01:47:57', 55.000, 6, 4, 1, 0, '0000-00-00 00:00:00'),
(14, '2014-12-13 16:12:52', 333.000, 7, 1, 1, 0, '0000-00-00 00:00:00'),
(15, '2014-12-13 16:12:52', 55.000, 7, 3, 1, 0, '0000-00-00 00:00:00'),
(16, '2014-12-13 16:43:12', 3.000, 8, 1, 1, 0, '0000-00-00 00:00:00'),
(17, '2014-12-13 16:55:40', 33.000, 9, 11, 1, 0, '0000-00-00 00:00:00'),
(18, '2014-12-13 17:26:39', 33.000, 10, 1, 1, 0, '0000-00-00 00:00:00'),
(19, '2014-12-13 17:27:32', 3.000, 11, 1, 1, 0, '0000-00-00 00:00:00'),
(20, '2014-12-13 17:27:32', 5.000, 11, 3, 1, 0, '0000-00-00 00:00:00'),
(21, '2014-12-13 17:27:32', 9.000, 11, 7, 1, 0, '0000-00-00 00:00:00'),
(22, '2014-12-13 17:35:38', 30.000, 12, 1, 1, 0, '0000-00-00 00:00:00'),
(23, '2014-12-13 17:35:38', 108.000, 12, 3, 1, 0, '0000-00-00 00:00:00'),
(24, '2014-12-13 17:35:38', 52.000, 12, 6, 1, 0, '0000-00-00 00:00:00'),
(25, '2014-12-14 16:49:59', 33.000, 13, 1, 1, 0, '0000-00-00 00:00:00'),
(26, '2014-12-14 16:49:59', 5.000, 13, 3, 1, 0, '0000-00-00 00:00:00'),
(27, '2014-12-14 16:49:59', 9.000, 13, 8, 1, 0, '0000-00-00 00:00:00'),
(28, '2014-12-14 18:56:16', 33.000, 14, 1, 1, 0, '0000-00-00 00:00:00'),
(29, '2014-12-14 18:56:16', 55.000, 14, 6, 1, 0, '0000-00-00 00:00:00'),
(30, '2014-12-15 00:19:48', 22.000, 15, 1, 1, 0, '0000-00-00 00:00:00'),
(31, '2015-01-25 16:19:05', 92.000, 16, 1, 1, 0, '0000-00-00 00:00:00'),
(32, '2015-01-25 16:19:05', 55.000, 16, 6, 1, 0, '0000-00-00 00:00:00'),
(33, '2015-01-25 16:19:20', 22.000, 17, 1, 1, 0, '0000-00-00 00:00:00'),
(34, '2015-01-25 16:19:20', 33.000, 17, 6, 1, 0, '0000-00-00 00:00:00'),
(35, '2015-02-05 09:30:43', 22.000, 18, 1, 1, 0, '0000-00-00 00:00:00'),
(36, '2015-02-05 10:11:55', 3.000, 19, 4, 1, 0, '0000-00-00 00:00:00'),
(37, '2015-02-05 10:11:55', 5.000, 19, 5, 1, 0, '0000-00-00 00:00:00'),
(38, '2015-02-05 10:11:55', 22.000, 19, 9, 1, 0, '0000-00-00 00:00:00'),
(39, '2015-02-24 11:08:18', 33.000, 20, 1, 1, 0, '0000-00-00 00:00:00'),
(40, '2015-02-24 11:08:43', 24.000, 21, 3, 1, 0, '0000-00-00 00:00:00'),
(41, '2015-02-24 11:08:43', 3.000, 21, 10, 1, 0, '0000-00-00 00:00:00'),
(42, '2015-02-24 11:08:43', 1.000, 21, 11, 1, 0, '0000-00-00 00:00:00'),
(43, '2015-02-27 00:38:39', 183.000, 22, 1, 1, 0, '0000-00-00 00:00:00'),
(44, '2015-06-02 11:42:15', 22.000, 23, 1, 1, 0, '0000-00-00 00:00:00'),
(45, '2015-05-07 11:42:39', 222.000, 24, 5, 1, 0, '0000-00-00 00:00:00'),
(46, '2015-06-02 11:46:40', 222.000, 25, 7, 1, 0, '0000-00-00 00:00:00'),
(47, '2015-06-12 10:01:37', 22.000, 26, 4, 1, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pesees_sorties`
--

CREATE TABLE IF NOT EXISTS `pesees_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(7,3) NOT NULL,
  `id_sortie` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_type_poubelle` int(11) NOT NULL,
  `id_type_dechet_evac` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

--
-- Dumping data for table `pesees_sorties`
--

INSERT INTO `pesees_sorties` (`id`, `timestamp`, `masse`, `id_sortie`, `id_type_dechet`, `id_type_poubelle`, `id_type_dechet_evac`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-12-09 21:18:54', 101.000, 1, 0, 1, 0, 1, 0, '0000-00-00 00:00:00'),
(2, '2014-12-09 21:18:54', 67.000, 1, 0, 6, 0, 1, 0, '0000-00-00 00:00:00'),
(3, '2014-12-09 21:19:10', 12.000, 2, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(4, '2014-12-09 21:19:10', 55.000, 2, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(5, '2014-12-09 21:19:18', 222.000, 3, 0, 0, 6, 1, 0, '0000-00-00 00:00:00'),
(6, '2014-12-09 21:19:34', 33.000, 4, 3, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(7, '2014-12-09 21:19:34', 2.000, 4, 11, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(8, '2014-12-09 21:19:47', 123.000, 5, 0, 0, 7, 1, 0, '0000-00-00 00:00:00'),
(9, '2014-12-09 21:19:47', 22.000, 5, 0, 0, 8, 1, 0, '0000-00-00 00:00:00'),
(10, '2014-12-09 22:38:35', 33.000, 6, 4, 0, 0, 1, 1, '2014-12-10 13:22:24'),
(11, '2014-12-09 22:38:35', 33.000, 6, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(12, '2014-12-10 11:33:19', 22.000, 7, 6, 0, 0, 1, 1, '2014-12-10 12:53:22'),
(13, '2014-12-10 11:33:19', 330.000, 7, 0, 0, 2, 1, 1, '2014-12-10 12:49:36'),
(14, '2014-12-10 12:53:59', 11.000, 8, 0, 1, 0, 1, 0, '0000-00-00 00:00:00'),
(15, '2014-12-10 12:53:59', 36.000, 8, 0, 4, 0, 1, 0, '0000-00-00 00:00:00'),
(16, '2014-12-10 12:54:14', 550.000, 9, 0, 0, 0, 1, 1, '2014-12-11 01:39:21'),
(17, '2014-12-10 12:54:24', 321.000, 10, 0, 0, 7, 1, 0, '0000-00-00 00:00:00'),
(18, '2014-12-10 12:55:06', 22.900, 11, 2, 0, 0, 1, 1, '2014-12-10 15:37:33'),
(19, '2014-12-10 14:00:35', 33.000, 12, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(20, '2014-12-10 14:00:35', 66.000, 12, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(21, '2014-12-10 14:31:41', 222.000, 13, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(22, '2014-12-11 01:27:03', 33.000, 14, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(23, '2014-12-11 11:37:34', 33.000, 15, 0, 0, 2, 1, 0, '0000-00-00 00:00:00'),
(24, '2014-12-11 11:37:43', 22.000, 16, 0, 0, 6, 1, 1, '2014-12-11 11:47:04'),
(25, '2014-12-11 11:38:12', 333.000, 17, 0, 0, 1, 1, 1, '2014-12-11 12:59:36'),
(26, '2014-12-11 11:49:51', 5.000, 18, 7, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(27, '2014-12-11 11:49:51', 0.900, 18, 0, 0, 4, 1, 0, '0000-00-00 00:00:00'),
(28, '2014-12-11 11:50:03', 78.000, 19, 0, 5, 0, 1, 1, '2014-12-11 12:58:12'),
(29, '2014-12-12 14:53:11', 30.000, 20, 0, 1, 0, 1, 0, '0000-00-00 00:00:00'),
(30, '2014-12-12 14:53:30', 22.000, 21, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(31, '2014-12-12 14:53:30', 55.000, 21, 0, 0, 7, 1, 0, '0000-00-00 00:00:00'),
(32, '2014-12-12 14:53:59', 43.000, 22, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(33, '2014-12-12 14:54:09', 5.000, 23, 4, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(34, '2014-12-12 14:54:17', 555.000, 24, 0, 0, 7, 1, 1, '2014-12-12 17:06:28'),
(35, '2014-12-13 13:17:07', 126.000, 25, 0, 3, 0, 1, 0, '0000-00-00 00:00:00'),
(36, '2014-12-13 13:17:07', 36.000, 25, 0, 4, 0, 1, 0, '0000-00-00 00:00:00'),
(37, '2014-12-13 13:17:23', 55.600, 26, 0, 0, 7, 1, 0, '0000-00-00 00:00:00'),
(38, '2014-12-13 13:17:47', 259.000, 27, 0, 0, 6, 1, 0, '0000-00-00 00:00:00'),
(39, '2014-12-13 13:18:03', 2.000, 28, 3, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(40, '2014-12-13 13:18:16', 262.000, 29, 0, 0, 7, 1, 0, '0000-00-00 00:00:00'),
(41, '2014-12-13 16:35:36', 22.000, 30, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(42, '2014-12-13 16:35:36', 555.000, 30, 0, 0, 7, 1, 0, '0000-00-00 00:00:00'),
(43, '2014-12-13 17:38:05', 34.000, 32, 0, 1, 0, 1, 0, '0000-00-00 00:00:00'),
(44, '2014-12-13 17:38:05', 71.000, 32, 0, 2, 0, 1, 0, '0000-00-00 00:00:00'),
(45, '2014-12-13 17:38:05', 28.000, 32, 0, 5, 0, 1, 0, '0000-00-00 00:00:00'),
(46, '2014-12-13 17:39:15', 84.000, 33, 0, 5, 0, 1, 0, '0000-00-00 00:00:00'),
(47, '2014-12-13 17:40:38', 33.000, 34, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(48, '2014-12-13 17:40:38', 66.000, 34, 0, 0, 2, 1, 0, '0000-00-00 00:00:00'),
(49, '2014-12-13 17:42:38', 222.000, 35, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(50, '2014-12-13 17:43:40', 333.000, 36, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(51, '2014-12-13 17:45:16', 666.000, 37, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(52, '2014-12-13 17:46:28', 33.000, 38, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(53, '2014-12-13 17:46:28', 66.000, 38, 0, 0, 6, 1, 0, '0000-00-00 00:00:00'),
(54, '2014-12-13 19:07:44', 22.000, 39, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(55, '2014-12-14 16:50:08', 33.000, 40, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(56, '2014-12-14 22:28:19', 33.000, 41, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(57, '2015-01-25 16:21:33', 190.000, 42, 0, 3, 0, 1, 0, '0000-00-00 00:00:00'),
(58, '2015-01-25 16:21:33', 91.000, 42, 0, 6, 0, 1, 0, '0000-00-00 00:00:00'),
(59, '2015-01-25 16:21:56', 12.000, 43, 1, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(60, '2015-01-25 16:21:56', 55.000, 43, 0, 0, 2, 1, 0, '0000-00-00 00:00:00'),
(61, '2015-01-25 16:22:03', 222.000, 44, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(62, '2015-01-25 16:22:10', 2.000, 45, 5, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(63, '2015-01-25 16:22:18', 22.000, 46, 0, 0, 5, 1, 0, '0000-00-00 00:00:00'),
(64, '2015-05-21 12:05:37', 55.000, 47, 7, 0, 0, 1, 0, '0000-00-00 00:00:00'),
(65, '2015-06-01 12:09:23', 111.000, 48, 0, 0, 1, 1, 0, '0000-00-00 00:00:00'),
(66, '2015-06-01 12:13:57', 222.000, 49, 0, 0, 2, 1, 0, '0000-00-00 00:00:00'),
(67, '2015-06-01 12:15:37', 33.000, 50, 0, 1, 0, 1, 0, '0000-00-00 00:00:00'),
(68, '2015-06-02 12:18:12', 210.000, 51, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(69, '2015-06-01 12:18:27', 333.000, 52, 0, 0, 3, 1, 0, '0000-00-00 00:00:00'),
(70, '2015-06-02 12:21:16', 222.000, 53, 0, 0, 2, 1, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `points_collecte`
--

CREATE TABLE IF NOT EXISTS `points_collecte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `couleur` text NOT NULL,
  `commentaire` text NOT NULL,
  `pesee_max` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `points_collecte`
--

INSERT INTO `points_collecte` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `pesee_max`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-04-08 18:06:36', 'Point de collecte en boutique ', '125 rue du chemin vert', '#d13232', 'point de collecte de la roro', '700', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-06-12 13:01:52', 'Point de collecte itinÃ©rant', 'pas d''adresse fixe!', '#4773a3', 'camion 30 mÃ¨tres cube avec balance! ', '350', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-06-12 14:14:31', 'Point de collecte en dÃ©chetterie', 'porte des lilas', '#c4b13a', 'oui!', '1000', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `points_sortie`
--

CREATE TABLE IF NOT EXISTS `points_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `couleur` text NOT NULL,
  `commentaire` text NOT NULL,
  `pesee_max` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `points_sortie`
--

INSERT INTO `points_sortie` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `pesee_max`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-04-08 18:19:28', 'Sorties hors-boutique', '125 rue du chemin vert', '#aa1d7c', 'point de sortie hors boutique principale de la ressourcerie de la petite rockette', '700', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `points_vente`
--

CREATE TABLE IF NOT EXISTS `points_vente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `couleur` text NOT NULL,
  `commentaire` text NOT NULL,
  `surface_vente` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `points_vente`
--

INSERT INTO `points_vente` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `surface_vente`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-04-08 18:17:25', 'Boutique de La Petite Rockette', '125 rue du chemin vert', '#a4e06e', 'la caisse principale de la ressourcerie de la petite rockette              ', '280', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-07-10 17:08:03', 'La Toute Petite Rockette', 'rue de la folie mericourt ', '#509a64', 'vente de vÃªtements et accessoires de mode femme et enfants ', '27', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2015-03-09 13:53:13', 'EvÃ©nement', 'Espace des Blancs-Manteaux', '#00ff40', '4 & 5 Avril 2015', '1000', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sorties`
--

CREATE TABLE IF NOT EXISTS `sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `classe` text NOT NULL,
  `adherent` text NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_convention` int(11) NOT NULL,
  `id_type_sortie` int(11) NOT NULL,
  `id_point_sortie` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `sorties`
--

INSERT INTO `sorties` (`id`, `timestamp`, `classe`, `adherent`, `id_filiere`, `id_convention`, `id_type_sortie`, `id_point_sortie`, `commentaire`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-12-09 21:18:54', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(2, '2014-12-09 21:19:10', 'sortiesc', '', 0, 1, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(3, '2014-12-09 21:19:18', 'sortiesr', '', 2, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(4, '2014-12-09 21:19:34', 'sorties', 'non', 0, 0, 2, 1, '', 1, 1, '2014-12-09 22:23:21'),
(5, '2014-12-09 21:19:47', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(6, '2014-12-09 22:38:35', 'sorties', 'non', 0, 0, 2, 1, '', 1, 1, '2014-12-10 13:22:24'),
(7, '2014-12-10 11:33:19', 'sorties', 'non', 0, 0, 2, 1, '', 1, 1, '2014-12-10 12:53:22'),
(8, '2014-12-10 12:53:59', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(9, '2014-12-10 12:54:14', 'sortiesr', '', 1, 0, 3, 1, '', 1, 1, '2014-12-11 01:35:36'),
(10, '2014-12-10 12:54:24', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(11, '2014-12-10 12:55:06', 'sortiesc', '', 0, 1, 0, 1, '', 1, 1, '2014-12-10 15:37:33'),
(12, '2014-12-10 14:00:35', 'sortiesc', '', 0, 1, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(13, '2014-12-10 14:31:41', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(14, '2014-12-11 01:27:03', 'sortiesc', '', 0, 1, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(15, '2014-12-11 11:37:34', 'sortiesr', '', 3, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(16, '2014-12-11 11:37:43', 'sortiesr', '', 2, 0, 0, 1, '', 1, 1, '2014-12-11 11:47:04'),
(17, '2014-12-11 11:38:12', 'sortiesd', '', 0, 0, 0, 1, '', 1, 1, '2014-12-11 12:59:36'),
(18, '2014-12-11 11:49:51', 'sorties', 'non', 0, 0, 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(19, '2014-12-11 11:50:03', 'sortiesp', '', 0, 0, 0, 1, '', 1, 1, '2014-12-11 12:58:12'),
(20, '2014-12-12 14:53:11', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(21, '2014-12-12 14:53:30', 'sortiesc', '', 0, 2, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(22, '2014-12-12 14:53:59', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(23, '2014-12-12 14:54:09', 'sorties', 'non', 0, 0, 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(24, '2014-12-12 14:54:17', 'sortiesd', '', 0, 0, 0, 1, '', 1, 1, '2014-12-12 17:06:28'),
(25, '2014-12-13 13:17:07', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(26, '2014-12-13 13:17:23', 'sortiesc', '', 0, 1, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(27, '2014-12-13 13:17:47', 'sortiesr', '', 2, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(28, '2014-12-13 13:18:03', 'sorties', 'non', 0, 0, 5, 1, '', 1, 0, '0000-00-00 00:00:00'),
(29, '2014-12-13 13:18:16', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(30, '2014-12-13 16:35:36', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(31, '2014-12-13 16:39:34', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(32, '2014-12-13 17:38:05', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(33, '2014-12-13 17:39:15', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(34, '2014-12-13 17:40:38', 'sortiesc', '', 0, 2, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(35, '2014-12-13 17:42:38', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(36, '2014-12-13 17:43:40', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(37, '2014-12-13 17:45:16', 'sorties', 'non', 0, 0, 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(38, '2014-12-13 17:46:28', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(39, '2014-12-13 19:07:44', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(40, '2014-12-14 16:50:08', 'sortiesc', '', 0, 2, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(41, '2014-12-14 22:28:19', 'sorties', 'non', 0, 0, 2, 1, '', 1, 0, '0000-00-00 00:00:00'),
(42, '2015-01-25 16:21:33', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(43, '2015-01-25 16:21:56', 'sortiesc', '', 0, 1, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(44, '2015-01-25 16:22:03', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(45, '2015-01-25 16:22:10', 'sorties', 'non', 0, 0, 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(46, '2015-01-25 16:22:18', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(47, '2015-05-21 12:05:37', 'sorties', 'non', 0, 0, 1, 1, '', 1, 0, '0000-00-00 00:00:00'),
(48, '2015-06-01 12:09:23', 'sortiesc', '', 0, 1, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(49, '2015-06-01 12:13:57', 'sortiesd', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(50, '2015-06-01 12:15:37', 'sortiesp', '', 0, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(51, '2015-06-02 12:18:12', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(52, '2015-06-01 12:18:27', 'sortiesr', '', 1, 0, 0, 1, '', 1, 0, '0000-00-00 00:00:00'),
(53, '2015-06-01 12:21:16', 'sorties', 'non', 0, 0, 1, 1, '', 1, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `types_poubelles`
--

CREATE TABLE IF NOT EXISTS `types_poubelles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `masse_bac` text NOT NULL,
  `ultime` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `types_poubelles`
--

INSERT INTO `types_poubelles` (`id`, `timestamp`, `nom`, `description`, `masse_bac`, `ultime`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-07-17 13:22:03', 'petite poubelle verte', 'tout venant bac 100 litres', '22', 'oui', '#70f71a', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-07-17 13:22:06', 'grande poubelle verte', 'grande poubelle verte', '27', 'oui', '#46de12', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-07-17 15:18:10', 'grosse poubelle verte', 'grosse poubelle verte', '32', 'oui', '#50c02d', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-07-17 15:24:42', 'petite poubelle jaune', 'a', '19', 'non', '#ffec00', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-07-23 11:29:05', 'grande poubelle jaune', 'emballages bac de 200 l', '27', 'non', '#e2e51d', 'oui', 0, 0, '0000-00-00 00:00:00'),
(6, '2014-07-23 11:29:57', 'grosse poubelle jaune', 'Emballages, bac de 400 litres.', '32', 'non', '#fff10d', 'oui', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-07-23 11:30:52', 'petite poubelle blanche', 'verre , bac de 100l', '20', 'non', '#e4e295', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `type_collecte`
--

CREATE TABLE IF NOT EXISTS `type_collecte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `type_collecte`
--

INSERT INTO `type_collecte` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-07-02 13:21:10', 'apport volontaire', 'quand une personne apporte d''elle mÃªme un ou des objets Ã  la boutique', '#cb2323', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-07-02 13:21:40', 'collecte Ã  domicile', 'collecte chez l''habitant en tournÃ©e camion', '#7b1f1f', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-07-02 13:21:53', 'collecte en pied d''immeuble', 'collecte demandÃ©e par un bailleur ', '#934a4a', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-07-02 13:22:48', 'collecte en brocante', 'collecte effectuÃ©e en fin de brocantes', '#a26b6b', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-07-10 18:59:22', 'collecte en hopital psychiatrique ', 'collecte en hopital psychiatrique ^^', '#ff88c9', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `type_contenants`
--

CREATE TABLE IF NOT EXISTS `type_contenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `masse` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `type_contenants`
--

INSERT INTO `type_contenants` (`id`, `timestamp`, `nom`, `description`, `masse`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(2, '2014-10-01 16:54:57', 'Roulpratique', 'Roulpratique en alu.', '10', '#019b99', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-10-01 17:06:29', 'diable vert', 'diable vert ', '12', '#dd0c0c', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-10-13 13:28:02', 'roll textille', 'roll textille', '31', '#5037d0', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-11-05 14:25:22', 'caddie', 'petite cage en fer sur roulettes', '11', '#ab26ab', 'oui', 0, 0, '0000-00-00 00:00:00'),
(6, '2014-11-05 14:25:43', 'cage d3e', 'cage d3e', '39', '#108f99', 'oui', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-11-05 14:26:11', 'caisse grise', 'grande caisse grise', '3', '#799082', 'oui', 0, 0, '0000-00-00 00:00:00'),
(8, '2014-11-05 14:27:33', 'petite caisse grise', 'petite caisse grise', '1.5', '#776161', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `type_dechets`
--

CREATE TABLE IF NOT EXISTS `type_dechets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `type_dechets`
--

INSERT INTO `type_dechets` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-04-08 18:21:16', 'matÃ©riel Ã©lectrique', 'dÃ©chets dâ€™Ã©quipements Ã©lectroniques et Ã©lectromÃ©nagers ', '#e87702', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-04-08 18:21:40', 'mobilier', 'mobilier', '#e83c02', 'non', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-04-08 18:22:10', 'textiles, accessoires ', 'textile,accessoires la masse de bijoux Ã©tant nÃ©gligeable ', '#ff1c0f', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-06-22 20:00:31', 'jouets', 'jeux, jouets , comprend aussi les jeux de societÃ©s', '#e80273', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-06-22 20:34:38', 'informatique', 'ordis, ecrans , claviers , autres pÃ©riphÃ©riques info. DEEE. en fait ... ', '#e902ff', 'oui', 0, 0, '0000-00-00 00:00:00'),
(6, '2014-07-02 14:00:33', 'vaisselle', 'vaisselle ,tout Ã©tats touts materiaux', '#a71dff', 'oui', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-07-02 22:04:57', 'livres', 'livres magazines journaux etc', '#3a02e8', 'oui', 0, 0, '0000-00-00 00:00:00'),
(8, '2014-07-02 22:06:25', 'supports media', 'cd dvd vinyles cassets minidiscs et consors', '#0b28ff', 'oui', 0, 0, '0000-00-00 00:00:00'),
(9, '2014-07-02 22:07:24', 'bibelots quincaillerie  ', 'bibelots divers objets dÃ©co ', '#026fe8', 'oui', 0, 0, '0000-00-00 00:00:00'),
(10, '2014-07-02 22:08:36', 'autres', 'autres', '#02d4ff', 'oui', 0, 0, '0000-00-00 00:00:00'),
(11, '2014-07-10 19:29:38', 'bijoux', 'bijoux en tout genre , pese peut mais trÃ¨s bien valorisÃ©', '#3fbf9f', 'oui', 0, 0, '0000-00-00 00:00:00'),
(12, '2014-10-09 10:10:59', 'mobilier en service', 'mobilier en service', '#bb2222', 'non', 0, 0, '0000-00-00 00:00:00'),
(13, '2014-10-09 10:11:16', 'mobilier hs', 'mobilier hs', '#bd2d2d', 'non', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `type_dechets_evac`
--

CREATE TABLE IF NOT EXISTS `type_dechets_evac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `type_dechets_evac`
--

INSERT INTO `type_dechets_evac` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-11-23 16:31:40', 'metaux', 'ferraille alu ect...', '#8e33ae', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-11-30 15:00:35', 'deee', 'dÃ©chets dâ€™Ã©quipements Ã©lectroniques et Ã©lectromÃ©nagers ', '#000000', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-11-30 15:00:57', 'livres non vendus', 'livres non vendus en boutique, invendables sur place', '#9b4848', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-11-30 15:01:13', 'piles', 'piles', '#925555', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-11-30 15:01:40', 'lampes eco/neons', 'lampes eco/neons', '#000000', 'oui', 0, 0, '0000-00-00 00:00:00'),
(6, '2014-11-30 15:02:06', 'textile', 'textile', '#846060', 'oui', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-11-30 15:02:43', 'bois', 'planches ', '#933e3e', 'oui', 0, 0, '0000-00-00 00:00:00'),
(8, '2014-11-30 15:03:10', 'mobilier hors service', 'mobilier hors service', '#c47878', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `type_sortie`
--

CREATE TABLE IF NOT EXISTS `type_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `type_sortie`
--

INSERT INTO `type_sortie` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-07-09 12:10:42', 'don Ã  un particulier', 'don d''un objet invendable en boutique Ã  un particulier', '#84a8c6', 'oui', 0, 0, '0000-00-00 00:00:00'),
(2, '2014-07-09 12:13:45', 'don Ã  une association', 'don d''objets Ã  une asso. sans convention', '#c6a13d', 'oui', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-07-09 12:14:35', 'don Ã  un artiste', 'objets, materiaux donnÃ©s Ã  un artiste', '#bf6b1f', 'oui', 0, 0, '0000-00-00 00:00:00'),
(4, '2014-08-06 16:51:49', 'don Ã  un salariÃ©', 'don Ã  un salariÃ©', '#bb3333', 'oui', 0, 0, '0000-00-00 00:00:00'),
(5, '2014-08-06 16:52:10', 'don Ã  un bÃ©nÃ©vole', 'don Ã  un bÃ©nÃ©vole', '#155175', 'oui', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `niveau` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `mail` text NOT NULL,
  `pass` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `timestamp`, `niveau`, `nom`, `prenom`, `mail`, `pass`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2014-06-25 22:58:07', 'c1c2c3v1v2v3s1bighljk', 'vert', 'martin', 'mart1ver@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 0, 0, '0000-00-00 00:00:00'),
(3, '2014-10-26 12:57:35', 'c1gl', 'jean', 'claude', 'jeanclaude@jc.org', '81dc9bdb52d04dc20036dbd8313ed055', 0, 0, '0000-00-00 00:00:00'),
(7, '2014-12-08 20:48:33', 'c1c2c3v1v2bighl', 'gla', 'bli', 'gter@gloubi.com', '380e406ab5ba1b6659ea00c4513cfc13', 0, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vendus`
--

CREATE TABLE IF NOT EXISTS `vendus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_vente` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_objet` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(9,2) NOT NULL,
  `remboursement` decimal(9,2) NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `vendus`
--

INSERT INTO `vendus` (`id`, `timestamp`, `id_vente`, `id_type_dechet`, `id_objet`, `quantite`, `prix`, `remboursement`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, '2015-03-10 10:55:51', 1, 1, 6, 1, 5.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(2, '2015-03-10 10:55:59', 2, 3, 24, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(3, '2015-03-10 10:58:06', 3, 4, 0, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(4, '2015-03-10 10:58:13', 4, 1, 39, 1, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(5, '2015-03-10 10:58:20', 5, 3, 36, 1, 0.50, 0.00, 1, 0, '0000-00-00 00:00:00'),
(6, '2015-03-10 10:58:36', 6, 4, 8, 1, 1.50, 0.00, 1, 0, '0000-00-00 00:00:00'),
(7, '2015-03-10 10:58:36', 6, 4, 8, 1, 1.50, 0.00, 1, 0, '0000-00-00 00:00:00'),
(8, '2015-03-10 10:58:36', 6, 3, 24, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(9, '2015-03-10 11:00:10', 7, 1, 3, 1, 0.00, 5.00, 1, 0, '0000-00-00 00:00:00'),
(10, '2015-03-10 11:09:23', 8, 10, 41, 1, 85.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(11, '2015-03-10 12:52:12', 9, 3, 0, 1, 2.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(12, '2015-03-28 11:15:42', 10, 3, 24, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(13, '2015-03-28 11:16:05', 11, 1, 39, 1, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(14, '2015-03-28 11:16:34', 12, 4, 8, 1, 1.50, 0.00, 1, 0, '0000-00-00 00:00:00'),
(15, '2015-03-28 11:17:28', 13, 1, 23, 1, 5.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(16, '2015-03-28 11:18:10', 14, 1, 4, 1, 5.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(17, '2015-04-16 14:49:25', 15, 1, 39, 10, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(18, '2015-04-16 14:49:25', 15, 1, 39, 10, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(19, '2015-04-16 15:16:01', 16, 1, 39, 12, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(20, '2015-04-16 15:16:01', 16, 1, 39, 12, 0.58, 0.00, 1, 0, '0000-00-00 00:00:00'),
(21, '2015-04-28 13:32:29', 17, 1, 39, 1, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(22, '2015-04-28 14:53:07', 18, 3, 24, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(23, '2015-04-28 14:53:33', 19, 5, 9, 1, 2.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(24, '2015-04-28 14:54:01', 20, 6, 11, 1, 0.25, 0.00, 1, 0, '0000-00-00 00:00:00'),
(25, '2015-04-28 14:54:05', 21, 7, 12, 1, 0.10, 0.00, 1, 0, '0000-00-00 00:00:00'),
(26, '2015-04-28 14:54:09', 22, 3, 24, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(27, '2015-04-28 14:54:13', 23, 6, 11, 1, 0.25, 0.00, 1, 0, '0000-00-00 00:00:00'),
(28, '2015-04-28 14:54:15', 24, 6, 11, 1, 0.25, 0.00, 1, 0, '0000-00-00 00:00:00'),
(29, '2015-04-28 14:54:20', 25, 6, 11, 1, 0.25, 0.00, 1, 0, '0000-00-00 00:00:00'),
(30, '2015-04-28 14:54:45', 26, 1, 6, 1, 5.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(31, '2015-04-28 14:54:49', 27, 5, 9, 1, 2.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(32, '2015-04-28 14:54:53', 28, 6, 10, 1, 0.50, 0.00, 1, 0, '0000-00-00 00:00:00'),
(33, '2015-04-28 15:01:33', 29, 5, 42, 1, 80.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(34, '2015-04-28 15:35:05', 30, 1, 39, 1, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(35, '2015-04-28 15:35:05', 30, 5, 9, 1, 2.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(36, '2015-04-28 15:40:59', 31, 1, 2, 1, 0.00, 7.00, 1, 0, '0000-00-00 00:00:00'),
(37, '2015-04-28 20:39:41', 32, 1, 39, 3, 2.33, 0.00, 1, 0, '0000-00-00 00:00:00'),
(38, '2015-05-15 09:52:02', 33, 5, 0, 3, 2.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(39, '2015-05-18 14:20:24', 34, 1, 39, 1, 7.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(40, '2015-05-18 14:22:05', 35, 3, 24, 1, 3.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(41, '2015-05-15 10:17:10', 36, 1, 6, 1, 5.00, 0.00, 1, 0, '0000-00-00 00:00:00'),
(42, '2015-06-02 10:51:28', 37, 3, 28, 1, 1.00, 0.00, 1, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ventes`
--

CREATE TABLE IF NOT EXISTS `ventes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_moyen_paiement` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `adherent` text NOT NULL,
  `commentaire` text NOT NULL,
  `id_point_vente` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_last_hero` int(11) NOT NULL,
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `ventes`
--

INSERT INTO `ventes` (`id`, `id_moyen_paiement`, `timestamp`, `adherent`, `commentaire`, `id_point_vente`, `id_createur`, `id_last_hero`, `last_hero_timestamp`) VALUES
(1, 1, '2015-03-10 10:55:51', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(2, 1, '2015-03-10 10:55:59', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(3, 1, '2015-03-10 10:58:06', 'non', '', 2, 1, 0, '0000-00-00 00:00:00'),
(4, 1, '2015-03-10 10:58:13', 'non', '', 3, 1, 0, '0000-00-00 00:00:00'),
(5, 1, '2015-03-10 10:58:20', 'non', '', 3, 1, 0, '0000-00-00 00:00:00'),
(6, 1, '2015-03-10 10:58:36', 'non', '', 3, 1, 0, '0000-00-00 00:00:00'),
(7, 1, '2015-03-10 11:00:10', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(8, 1, '2015-03-10 11:09:23', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(9, 1, '2015-03-10 12:52:12', 'non', '', 2, 1, 0, '0000-00-00 00:00:00'),
(10, 1, '2015-03-28 11:15:42', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(11, 1, '2015-03-28 11:16:05', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(12, 1, '2015-03-28 11:16:34', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(13, 1, '2015-03-28 11:17:28', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(14, 1, '2015-03-28 11:18:10', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(15, 1, '2015-04-16 14:49:25', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(16, 1, '2015-04-16 15:16:01', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(17, 1, '2015-04-28 13:32:29', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(18, 1, '2015-04-28 14:53:07', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(19, 1, '2015-04-28 14:53:33', 'non', 'toto', 1, 1, 0, '0000-00-00 00:00:00'),
(20, 1, '2015-04-28 14:54:01', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(21, 1, '2015-04-28 14:54:05', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(22, 1, '2015-04-28 14:54:09', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(23, 1, '2015-04-28 14:54:13', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(24, 1, '2015-04-28 14:54:15', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(25, 1, '2015-04-28 14:54:20', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(26, 1, '2015-04-28 14:54:45', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(27, 1, '2015-04-28 14:54:49', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(28, 1, '2015-04-28 14:54:53', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(29, 1, '2015-04-28 15:01:33', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(30, 1, '2015-04-28 15:35:05', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(31, 1, '2015-04-28 15:40:59', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(32, 2, '2015-04-28 20:39:41', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(33, 1, '2015-05-15 09:52:02', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(34, 1, '2015-05-18 14:20:24', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(35, 1, '2015-05-18 14:22:05', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(36, 1, '2015-05-15 10:17:10', 'non', '', 1, 1, 0, '0000-00-00 00:00:00'),
(37, 1, '2015-06-02 10:51:28', 'non', '', 1, 1, 0, '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
