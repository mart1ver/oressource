-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Sam 16 Août 2014 à 12:30
-- Version du serveur: 5.5.34
-- Version de PHP: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `oressource`
--

-- --------------------------------------------------------

--
-- Structure de la table `adherents`
--

CREATE TABLE IF NOT EXISTS `adherents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse` text NOT NULL,
  `mail` text NOT NULL,
  `telephone` text NOT NULL,
  `localisation` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `collectes`
--

CREATE TABLE IF NOT EXISTS `collectes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_type_collecte` int(11) NOT NULL,
  `adherent` text NOT NULL,
  `localisation` int(11) NOT NULL,
  `id_point_collecte` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

--
-- Contenu de la table `collectes`
--

INSERT INTO `collectes` (`id`, `timestamp`, `id_type_collecte`, `adherent`, `localisation`, `id_point_collecte`) VALUES
(73, '2014-07-24 15:00:02', 1, 'non', 1, 1),
(74, '2014-07-24 15:00:16', 1, 'non', 1, 1),
(75, '2014-07-24 15:00:22', 1, 'non', 1, 1),
(76, '2014-07-24 15:35:27', 3, 'oui', 3, 1),
(77, '2014-07-24 15:56:55', 1, 'non', 1, 1),
(78, '2014-07-24 16:49:11', 1, 'non', 1, 1),
(79, '2014-07-24 20:26:28', 2, 'non', 2, 1),
(80, '2014-07-24 20:27:30', 1, 'non', 1, 1),
(81, '2014-07-24 20:27:59', 1, 'non', 1, 1),
(82, '2014-07-24 21:30:12', 1, 'non', 1, 1),
(83, '2014-07-24 22:02:28', 1, 'non', 1, 1),
(84, '2014-07-25 09:15:57', 1, 'non', 1, 1),
(85, '2014-07-25 13:20:24', 1, 'non', 1, 1),
(86, '2014-07-25 13:20:34', 1, 'non', 1, 1),
(87, '2014-07-25 13:20:36', 1, 'non', 1, 1),
(88, '2014-08-02 23:06:11', 1, 'non', 1, 1),
(89, '2014-08-03 17:43:09', 1, 'non', 1, 1),
(90, '2014-08-03 20:04:42', 1, 'non', 1, 1),
(91, '2014-08-04 21:32:15', 1, 'non', 1, 1),
(92, '2014-08-06 10:02:44', 1, 'non', 1, 1),
(93, '2014-08-06 12:10:48', 1, 'non', 1, 1),
(94, '2014-08-06 12:11:54', 1, 'non', 1, 1),
(95, '2014-08-06 12:13:30', 1, 'non', 1, 1),
(96, '2014-08-06 16:47:25', 1, 'non', 1, 1),
(97, '2014-08-07 11:22:12', 1, 'non', 1, 1),
(98, '2014-08-07 12:15:23', 2, 'non', 1, 1),
(99, '2014-08-08 11:34:18', 1, 'non', 1, 1),
(100, '2014-08-12 12:51:37', 1, 'non', 1, 1),
(101, '2014-08-16 09:56:59', 1, 'non', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `conventions_sorties`
--

CREATE TABLE IF NOT EXISTS `conventions_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `conventions_sorties`
--

INSERT INTO `conventions_sorties` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-07-10 13:28:10', 'la maison de la plage', 'mozaiques', '#828fe4', 'oui'),
(2, '2014-07-10 13:29:44', 'action froid', 'maraudes sdf et autres', '#626ac2', 'oui'),
(3, '2014-07-23 11:48:09', 'dÃ©chetterie ', 'porte des lilas', '#89b029', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `description_structure`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `description_structure`
--

INSERT INTO `description_structure` (`id`, `nom`, `adresse`, `description`, `siret`, `telephone`, `mail`, `id_localite`) VALUES
(1, 'La petitte rockette', '125 rue du chemin vert', 'la petite rockette est un asso', 'chÃ©po', '0155286118', 'lapetiterockette@gmail.com', '1');

-- --------------------------------------------------------

--
-- Structure de la table `filieres_sortie`
--

CREATE TABLE IF NOT EXISTS `filieres_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `id_type_dechet` text NOT NULL,
  `couleur` text NOT NULL,
  `description` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `filieres_sortie`
--

INSERT INTO `filieres_sortie` (`id`, `timestamp`, `nom`, `id_type_dechet`, `couleur`, `description`, `visible`) VALUES
(1, '2014-07-10 13:54:43', 'recycle livre', '7', '#a77a37', 'ramasse livres', 'oui'),
(2, '2014-07-10 13:55:01', 'gebetex', '3', '#f48570', 'ramasse textiles', 'oui'),
(3, '2014-07-10 14:17:52', 'eco-systemes', '1', '#b36cb4', 'ramasse deee', 'oui'),
(4, '2014-07-10 18:51:38', 'screlec', '1', '#e3c451', 'ampoules', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `grille_objets`
--

CREATE TABLE IF NOT EXISTS `grille_objets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `visible` text NOT NULL,
  `prix` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `grille_objets`
--

INSERT INTO `grille_objets` (`id`, `timestamp`, `nom`, `description`, `id_type_dechet`, `visible`, `prix`) VALUES
(1, '2014-08-07 13:49:49', 'chaussettes', 'chaussettes', 3, 'oui', '1'),
(2, '2014-08-07 14:17:10', 'tv', 'televsion', 1, 'oui', '7'),
(3, '2014-08-07 14:49:46', 'lecteur vhs ', 'lecteur vhs ', 1, 'oui', '5'),
(4, '2014-08-07 14:51:28', 'lecteur dvd', 'lecteur dvd ', 1, 'oui', '5'),
(6, '2014-08-07 14:53:58', 'four micro ondes', 'four', 1, 'oui', '5'),
(7, '2014-08-07 14:54:49', 'Ã©tagÃ¨re "billy"', 'ikea valeur neuf = ', 2, 'oui', '5'),
(8, '2014-08-07 14:55:49', 'puzzle', 'puzzle', 4, 'oui', '1.5'),
(9, '2014-08-07 14:57:03', 'clavier', 'clavier', 5, 'oui', '2'),
(10, '2014-08-07 14:57:18', 'bol', 'bol', 6, 'oui', '0.5'),
(11, '2014-08-07 14:58:51', 'assiette', 'assiette', 6, 'oui', '0.25'),
(12, '2014-08-07 15:02:15', 'bd', 'BD', 7, 'oui', '0'),
(13, '2014-08-07 15:02:40', 'polars', 'polars', 7, 'oui', '0'),
(14, '2014-08-07 15:03:14', 'vinyle', 'vinyle', 8, 'oui', '0'),
(15, '2014-08-07 15:03:28', 'dvd', 'dvd', 8, 'oui', '0'),
(16, '2014-08-07 15:03:38', 'vhs', 'vhs', 8, 'oui', '0'),
(17, '2014-08-07 15:03:52', 'K7 audio', 'K7 audio', 8, 'oui', '0'),
(18, '2014-08-07 15:04:07', 'cadres', 'cadres', 9, 'oui', '1'),
(19, '2014-08-07 15:04:37', 'bouteille de gaz', 'heuu...', 10, 'oui', '51'),
(20, '2014-08-07 15:05:21', 'montre swatch hs', 'montre swatch hs', 11, 'oui', '1.25'),
(21, '2014-08-12 13:17:05', 'chaise pliante', 'chaise pliante', 2, 'oui', '5');

-- --------------------------------------------------------

--
-- Structure de la table `localites`
--

CREATE TABLE IF NOT EXISTS `localites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `couleur` text NOT NULL,
  `relation_openstreetmap` text NOT NULL,
  `commentaire` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `localites`
--

INSERT INTO `localites` (`id`, `timestamp`, `nom`, `couleur`, `relation_openstreetmap`, `commentaire`, `visible`) VALUES
(1, '2014-06-15 16:56:47', '11Â° arr', '#89cc58', 'http://www.openstreetmap.org/relation/9533', '11Â° arrondissement de paris ', 'oui'),
(2, '2014-06-15 17:00:58', '20Â° arr.', '#43d163', 'http://www.openstreetmap.org/relation/9529', '20 eme arrondissement de paris', 'oui'),
(3, '2014-07-02 21:53:51', '1er arr.', '#086c0d', 'http://toto.com', 'premier arrondissement de paris', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `objets_en_pret`
--

CREATE TABLE IF NOT EXISTS `objets_en_pret` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  `ressource` text NOT NULL,
  `numero_facture` text NOT NULL,
  `valeur_remboursement` text NOT NULL,
  `numero_serie` text NOT NULL,
  `photo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `pesees_collectes`
--

CREATE TABLE IF NOT EXISTS `pesees_collectes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(4,3) NOT NULL,
  `id_collecte` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Contenu de la table `pesees_collectes`
--

INSERT INTO `pesees_collectes` (`id`, `timestamp`, `masse`, `id_collecte`, `id_type_dechet`) VALUES
(1, '2014-07-22 15:00:02', 9.999, 73, 1),
(2, '2014-07-24 15:00:16', 9.999, 74, 1),
(3, '2014-07-24 15:00:16', 9.999, 74, 2),
(4, '2014-07-24 15:00:22', 9.999, 75, 1),
(5, '2014-07-24 15:35:28', 1.000, 76, 1),
(6, '2014-07-24 15:35:28', 9.999, 76, 2),
(7, '2014-07-24 15:35:28', 9.999, 76, 3),
(8, '2014-07-24 15:35:28', 9.999, 76, 7),
(9, '2014-07-24 15:35:28', 8.000, 76, 11),
(10, '2014-07-24 15:56:55', 9.999, 77, 6),
(11, '2014-07-24 16:49:11', 9.999, 78, 7),
(12, '2014-07-24 20:26:28', 9.999, 79, 1),
(13, '2014-07-24 20:26:28', 9.999, 79, 2),
(14, '2014-07-24 20:26:28', 9.999, 79, 3),
(15, '2014-07-24 20:26:28', 9.999, 79, 7),
(16, '2014-07-24 20:26:28', 1.000, 79, 10),
(17, '2014-07-24 20:27:30', 9.999, 80, 3),
(18, '2014-07-24 20:27:59', 9.999, 81, 1),
(19, '2014-07-24 22:02:28', 9.999, 83, 1),
(20, '2014-07-24 22:02:28', 9.999, 83, 2),
(21, '2014-07-24 22:02:28', 5.000, 83, 11),
(22, '2014-07-25 09:15:57', 9.999, 84, 3),
(23, '2014-07-25 09:15:57', 5.000, 84, 7),
(24, '2014-07-25 09:15:57', 8.000, 84, 8),
(25, '2014-07-25 13:20:24', 9.999, 85, 3),
(26, '2014-07-25 13:20:24', 9.999, 85, 6),
(27, '2014-08-02 23:06:11', 9.999, 88, 6),
(28, '2014-08-03 17:43:09', 9.999, 89, 1),
(29, '2014-08-03 17:43:09', 9.999, 89, 8),
(30, '2014-08-03 17:43:09', 7.000, 89, 11),
(31, '2014-08-03 20:04:42', 9.999, 90, 3),
(32, '2014-08-04 21:32:15', 9.999, 91, 4),
(33, '2014-08-04 21:32:15', 8.000, 91, 6),
(34, '2014-08-04 21:32:15', 6.000, 91, 8),
(35, '2014-08-04 21:32:15', 9.999, 91, 10),
(36, '2014-08-06 10:02:44', 1.000, 92, 1),
(37, '2014-08-06 10:02:44', 2.000, 92, 2),
(38, '2014-08-06 10:02:44', 3.000, 92, 3),
(39, '2014-08-06 10:02:44', 4.000, 92, 4),
(40, '2014-08-06 10:02:44', 6.000, 92, 6),
(41, '2014-08-06 10:02:44', 5.000, 92, 7),
(42, '2014-08-06 10:02:44', 8.000, 92, 8),
(43, '2014-08-06 10:02:44', 7.000, 92, 9),
(44, '2014-08-06 10:02:44', 9.000, 92, 11),
(45, '2014-08-06 12:10:48', 3.000, 93, 1),
(46, '2014-08-06 12:11:54', 6.000, 94, 1),
(47, '2014-08-06 12:13:30', 6.000, 95, 1),
(48, '2014-08-06 16:47:25', 9.999, 96, 1),
(49, '2014-08-07 11:22:12', 9.999, 97, 1),
(50, '2014-08-07 12:15:23', 9.999, 98, 3),
(51, '2014-08-07 12:15:23', 1.000, 98, 7),
(52, '2014-08-08 11:34:18', 9.999, 99, 1),
(53, '2014-08-08 11:34:18', 9.999, 99, 2),
(54, '2014-08-08 11:34:18', 1.000, 99, 4),
(55, '2014-08-12 12:51:37', 9.999, 100, 2),
(56, '2014-08-12 12:51:37', 1.000, 100, 4),
(57, '2014-08-16 09:56:59', 9.999, 101, 2),
(58, '2014-08-16 09:56:59', 5.000, 101, 3),
(59, '2014-08-16 09:56:59', 9.999, 101, 4),
(60, '2014-08-16 09:56:59', 9.000, 101, 8);

-- --------------------------------------------------------

--
-- Structure de la table `pesees_sorties`
--

CREATE TABLE IF NOT EXISTS `pesees_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` text NOT NULL,
  `id_sortie` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_type_poubelle` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `pesees_sorties`
--

INSERT INTO `pesees_sorties` (`id`, `timestamp`, `masse`, `id_sortie`, `id_type_dechet`, `id_type_poubelle`) VALUES
(1, '2014-08-06 12:07:08', '19', 1, 1, 0),
(2, '2014-08-06 12:07:08', '5', 1, 7, 0),
(3, '2014-08-06 12:08:15', '6', 2, 7, 0),
(4, '2014-08-06 12:09:26', '6', 3, 6, 0),
(5, '2014-08-06 12:10:01', '6', 4, 4, 0),
(6, '2014-08-06 12:15:30', '22', 5, 2, 0),
(7, '2014-08-06 12:15:30', '55.5', 5, 6, 0),
(8, '2014-08-06 12:25:30', '11.9', 6, 1, 0),
(9, '2014-08-06 12:26:27', '11', 7, 1, 0),
(10, '2014-08-06 12:36:50', '21.9', 0, 1, 0),
(11, '2014-08-06 12:36:50', '105.35', 0, 2, 0),
(12, '2014-08-06 12:37:55', '12.9', 0, 1, 0),
(13, '2014-08-06 12:37:55', '555.8', 0, 2, 0),
(14, '2014-08-06 12:40:22', '154.8', 0, 2, 0),
(15, '2014-08-06 12:40:22', '55.9', 0, 3, 0),
(16, '2014-08-06 12:43:18', '32.45', 0, 2, 0),
(17, '2014-08-06 12:45:57', '1', 8, 7, 0),
(18, '2014-08-06 12:46:24', '1', 9, 1, 0),
(19, '2014-08-06 12:47:50', '1', 10, 1, 0),
(20, '2014-08-06 13:27:01', '55', 11, 3, 0),
(21, '2014-08-06 13:33:11', '11', 0, 1, 0),
(22, '2014-08-06 15:01:33', '21', 12, 4, 0),
(23, '2014-08-06 15:01:33', '33', 12, 6, 0),
(24, '2014-08-06 15:30:16', '555', 13, 3, 0),
(25, '2014-08-06 15:35:05', '178.7', 14, 7, 0),
(26, '2014-08-06 15:38:26', '56.9', 15, 7, 0),
(27, '2014-08-06 16:09:47', '84', 16, 2, 0),
(28, '2014-08-06 16:09:47', '84', 16, 5, 0),
(29, '2014-08-06 16:12:23', '84', 17, 0, 2),
(30, '2014-08-06 16:12:23', '79', 17, 0, 6),
(31, '2014-08-06 16:15:48', '84', 18, 0, 2),
(32, '2014-08-06 16:15:55', '111', 19, 7, 0),
(33, '2014-08-06 16:16:02', '555', 20, 6, 0),
(34, '2014-08-06 16:48:09', '1.9', 22, 1, 0),
(35, '2014-08-06 21:33:12', '89', 23, 0, 1),
(36, '2014-08-07 11:26:45', '11', 25, 1, 0),
(37, '2014-08-07 11:28:08', '270', 26, 3, 0),
(38, '2014-08-07 11:29:45', '89', 27, 0, 1),
(39, '2014-08-08 11:36:50', '340', 28, 3, 0),
(40, '2014-08-08 11:38:55', '178', 29, 0, 1),
(41, '2014-08-08 11:38:55', '79', 29, 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `points_collecte`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `points_collecte`
--

INSERT INTO `points_collecte` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `pesee_max`, `visible`) VALUES
(1, '2014-04-08 18:06:36', 'Point de collecte en boutique ', '125 rue du chemin vert', '#d13232', 'point de collecte de la roro', '700', 'oui'),
(2, '2014-06-12 13:01:52', 'point de collecte itinerant', 'pas d''adresse fixe!', '#4773a3', 'camion 30 mÃ¨tres cube avec balance! ', '700', 'non'),
(3, '2014-06-12 14:14:31', 'point de collecte en dechetterie', 'porte des lilas', '#c4b13a', 'oui!', '700', 'non');

-- --------------------------------------------------------

--
-- Structure de la table `points_sortie`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `points_sortie`
--

INSERT INTO `points_sortie` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `pesee_max`, `visible`) VALUES
(1, '2014-04-08 18:19:28', 'Sorties hors boutique', '125 rue du chemin vert', '#aa1d7c', 'point de sortie hors boutique principale de la ressourcerie de la petite rockette', '700', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `points_vente`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `points_vente`
--

INSERT INTO `points_vente` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `surface_vente`, `visible`) VALUES
(1, '2014-04-08 18:17:25', 'Boutique de La Petite Rockette', '125 rue du chemin vert', '#a4e06e', 'la caisse principale de la ressourcerie de la petite rockette              ', '280', 'oui'),
(2, '2014-07-10 17:08:03', 'La toute petite rockette', 'rue de la folie mericourt ', '#509a64', 'vente de vÃªtements et accessoires de mode femme et enfants ', '27', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `prets`
--

CREATE TABLE IF NOT EXISTS `prets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_objet_en_pret` text NOT NULL,
  `date_pret` text NOT NULL,
  `date_retour` text NOT NULL,
  `id_adherent` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `adresse` text NOT NULL,
  `mail` text NOT NULL,
  `date_depot_caution` text NOT NULL,
  `date_rendu_caution` text NOT NULL,
  `montant_caution` text NOT NULL,
  `id_utilisateur` text NOT NULL,
  `commentaire` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `recettes_points_sorties`
--

CREATE TABLE IF NOT EXISTS `recettes_points_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_filiere` int(11) NOT NULL,
  `recette` int(11) NOT NULL,
  `commentaire` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `sorties`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Contenu de la table `sorties`
--

INSERT INTO `sorties` (`id`, `timestamp`, `classe`, `adherent`, `id_filiere`, `id_convention`, `id_type_sortie`, `id_point_sortie`, `commentaire`) VALUES
(1, '2014-08-06 12:07:08', 'sorties', 'oui', 0, 0, 2, 1, ''),
(2, '2014-08-06 12:08:15', 'sorties', 'non', 0, 0, 1, 0, ''),
(3, '2014-08-06 12:09:26', 'sorties', 'non', 0, 0, 1, 0, ''),
(4, '2014-08-06 12:10:01', 'sorties', 'non', 0, 0, 1, 0, ''),
(5, '2014-08-06 12:15:30', 'sorties', 'oui', 0, 0, 3, 1, ''),
(6, '2014-08-06 12:25:30', 'sorties', 'non', 0, 0, 1, 1, ''),
(7, '2014-08-06 12:26:27', 'sorties', 'non', 0, 0, 1, 1, ''),
(8, '2014-08-06 12:45:57', 'sorties', 'oui', 0, 0, 2, 1, ''),
(9, '2014-08-06 12:46:24', 'sorties', 'oui', 0, 0, 1, 1, ''),
(10, '2014-08-06 12:47:50', 'sortiesc', '', 0, 3, 0, 1, ''),
(11, '2014-08-06 13:27:00', 'sortiesc', '', 0, 2, 0, 1, ''),
(12, '2014-08-06 15:01:33', 'sortiesc', '', 0, 1, 0, 1, ''),
(13, '2014-08-06 15:30:16', 'sortiesr', '', 3, 0, 0, 1, ''),
(14, '2014-08-06 15:35:05', 'sortiesr', '', 7, 0, 0, 1, ''),
(15, '2014-08-06 15:38:26', 'sortiesr', '', 7, 0, 0, 1, ''),
(16, '2014-08-06 16:09:47', 'sortiesp', '', 0, 0, 0, 0, ''),
(17, '2014-08-06 16:12:23', 'sortiesp', '', 0, 0, 0, 0, ''),
(18, '2014-08-06 16:15:47', 'sortiesp', '', 0, 0, 0, 1, ''),
(19, '2014-08-06 16:15:55', 'sortiesr', '', 7, 0, 0, 1, ''),
(20, '2014-08-06 16:16:02', 'sortiesc', '', 0, 1, 0, 1, ''),
(21, '2014-08-06 16:16:08', 'sorties', 'oui', 0, 0, 1, 1, ''),
(22, '2014-08-06 16:48:09', 'sorties', 'non', 0, 0, 1, 1, ''),
(23, '2014-08-06 21:33:12', 'sortiesp', '', 0, 0, 0, 1, ''),
(24, '2014-08-07 11:26:38', 'sorties', 'non', 0, 0, 1, 1, ''),
(25, '2014-08-07 11:26:45', 'sorties', 'non', 0, 0, 1, 1, ''),
(26, '2014-08-07 11:28:08', 'sortiesr', '', 3, 0, 0, 1, ''),
(27, '2014-08-07 11:29:45', 'sortiesp', '', 0, 0, 0, 1, ''),
(28, '2014-08-08 11:36:50', 'sortiesr', '', 3, 0, 0, 1, ''),
(29, '2014-08-08 11:38:55', 'sortiesp', '', 0, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `types_poubelles`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `types_poubelles`
--

INSERT INTO `types_poubelles` (`id`, `timestamp`, `nom`, `description`, `masse_bac`, `ultime`, `couleur`, `visible`) VALUES
(1, '2014-07-17 13:22:03', 'petite poubelle verte', 'tout venant bac 100 litres', '22', 'oui', '#70f71a', 'oui'),
(2, '2014-07-17 13:22:06', 'grande poubelle verte', 'grande poubelle verte', '27', 'oui', '#46de12', 'oui'),
(3, '2014-07-17 15:18:10', 'grosse poubelle verte', 'grosse poubelle verte', '32', 'oui', '#50c02d', 'oui'),
(4, '2014-07-17 15:24:42', 'petite poubelle jaune', 'a', '19', 'oui', '#ffec00', 'oui'),
(5, '2014-07-23 11:29:05', 'grande poubelle jaune', 'emballages bac de 200 l', '27', 'oui', '#e2e51d', 'oui'),
(6, '2014-07-23 11:29:57', 'grosse poubelle jaune', 'Emballages, bac de 400 litres.', '32', 'oui', '#fff10d', 'oui'),
(7, '2014-07-23 11:30:52', 'petite poubelle blanche', 'verre , bac de 100l', '20', 'oui', '#e4e295', 'oui'),
(8, '2014-08-04 22:04:50', 'hack', 'fdfd', '22', 'oui', '#ff0000', 'non');

-- --------------------------------------------------------

--
-- Structure de la table `type_collecte`
--

CREATE TABLE IF NOT EXISTS `type_collecte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `type_collecte`
--

INSERT INTO `type_collecte` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-07-02 13:21:10', 'apport vollontaire', 'quand une personne apporte d''elle mÃªme un ou des objets Ã  la boutique', '#cb2323', 'oui'),
(2, '2014-07-02 13:21:40', 'collecte Ã  domicile', 'collecte chez l''habitant en tournÃ©e camion', '#7b1f1f', 'oui'),
(3, '2014-07-02 13:21:53', 'collecte en pied d''immeule', 'collecte demandÃ©e par un bailleur ', '#934a4a', 'oui'),
(4, '2014-07-02 13:22:48', 'collecte en brocante', 'collecte effectuÃ©e en fin de brocantes', '#a26b6b', 'oui'),
(5, '2014-07-10 18:59:22', 'collecte en hopital psychiatrique ', 'collecte en hopital psychiatrique ^^', '#ff88c9', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `type_dechets`
--

CREATE TABLE IF NOT EXISTS `type_dechets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `type_dechets`
--

INSERT INTO `type_dechets` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-04-08 18:21:16', 'deee', 'dÃ©chets dâ€™Ã©quipements Ã©lectroniques et Ã©lectromÃ©nagers ', '#e87702', 'oui'),
(2, '2014-04-08 18:21:40', 'mobilier', 'mobilier', '#e83c02', 'oui'),
(3, '2014-04-08 18:22:10', 'textile,accessoires ', 'textile,accessoires la masse de bijoux Ã©tant nÃ©gligeable ', '#ff1c0f', 'oui'),
(4, '2014-06-22 20:00:31', 'jouets', 'jeux, jouets , comprend aussi les jeux de societÃ©s', '#e80273', 'oui'),
(5, '2014-06-22 20:34:38', 'informatique', 'ordis, ecrans , claviers , autres pÃ©riphÃ©riques info. DEEE. en fait ... ', '#e902ff', 'non'),
(6, '2014-07-02 14:00:33', 'vaisselle', 'vaisselle ,tout Ã©tats touts materiaux', '#a71dff', 'oui'),
(7, '2014-07-02 22:04:57', 'livres', 'livres magazines journaux etc', '#3a02e8', 'oui'),
(8, '2014-07-02 22:06:25', 'supports media', 'cd dvd vinyles cassets minidiscs et consors', '#0b28ff', 'oui'),
(9, '2014-07-02 22:07:24', 'bibelots quincaillerie  ', 'bibelots divers objets dÃ©co ', '#026fe8', 'oui'),
(10, '2014-07-02 22:08:36', 'autres', 'autres', '#02d4ff', 'oui'),
(11, '2014-07-10 19:29:38', 'bijoux', 'bijoux en tout genre , pese peut mais trÃ¨s bien valorisÃ©', '#0effc1', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `type_sortie`
--

CREATE TABLE IF NOT EXISTS `type_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `type_sortie`
--

INSERT INTO `type_sortie` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-07-09 12:10:42', 'don Ã  un particulier', 'don d''un objet invendable en boutique', '#83a6c3', 'oui'),
(2, '2014-07-09 12:13:45', 'don Ã  une association', 'don d''objets Ã  une asso. sans convention', '#c6a13d', 'oui'),
(3, '2014-07-09 12:14:35', 'don Ã  un artiste', 'objets, materiaux donnÃ©s Ã  un artiste', '#bf6b1f', 'oui'),
(4, '2014-08-06 16:51:49', 'don Ã  un salariÃ©', 'don Ã  un salariÃ©', '#bb3333', 'oui'),
(5, '2014-08-06 16:52:10', 'don Ã  un bÃ©nÃ©vole', 'don Ã  un bÃ©nÃ©vole', '#155175', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `niveau` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `mail` text NOT NULL,
  `pass` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `timestamp`, `niveau`, `nom`, `prenom`, `mail`, `pass`) VALUES
(1, '2014-06-25 22:58:07', 'c1c2c3v1v2s1abigmp', 'martin', 'vert', 'mart1ver@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(2, '2014-07-24 15:29:20', 'c1v1s1abig', 'dom', 'dominique', 'dom@dom.dom', '81dc9bdb52d04dc20036dbd8313ed055');

-- --------------------------------------------------------

--
-- Structure de la table `vendus`
--

CREATE TABLE IF NOT EXISTS `vendus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vente` int(11) NOT NULL,
  `id_type_objet` int(11) NOT NULL,
  `prix` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

CREATE TABLE IF NOT EXISTS `ventes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `adherent` text NOT NULL,
  `commentaire` text NOT NULL,
  `id_point_vente` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
