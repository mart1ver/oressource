-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Dim 30 Novembre 2014 à 17:11
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
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Contenu de la table `collectes`
--

INSERT INTO `collectes` (`id`, `timestamp`, `id_type_collecte`, `adherent`, `localisation`, `id_point_collecte`, `commentaire`) VALUES
(1, '2014-09-04 18:42:18', 1, 'non', 1, 1, ''),
(2, '2014-09-04 18:42:24', 2, 'non', 1, 1, ''),
(3, '2014-09-04 18:42:32', 2, 'non', 1, 1, ''),
(4, '2014-09-04 18:45:00', 1, 'non', 1, 2, ''),
(5, '2014-09-04 18:45:47', 1, 'non', 1, 3, ''),
(6, '2014-09-05 14:46:16', 1, 'non', 1, 1, ''),
(7, '2014-09-05 14:46:25', 2, 'oui', 3, 1, ''),
(8, '2014-09-05 16:41:31', 1, 'non', 1, 2, ''),
(9, '2014-09-05 16:41:33', 1, 'non', 1, 2, ''),
(10, '2014-09-08 15:04:49', 1, 'oui', 2, 1, ''),
(11, '2014-09-08 15:22:44', 2, 'non', 1, 1, ''),
(12, '2014-09-08 15:23:33', 3, 'non', 1, 1, ''),
(13, '2014-09-09 13:51:17', 1, 'non', 1, 1, ''),
(14, '2014-09-09 13:51:23', 1, 'non', 1, 1, ''),
(15, '2014-09-09 14:06:26', 1, 'non', 1, 1, ''),
(16, '2014-09-13 18:43:00', 1, 'non', 1, 1, ''),
(17, '2014-09-14 16:22:06', 1, 'non', 1, 1, ''),
(18, '2014-09-24 12:49:41', 1, 'non', 1, 1, ''),
(19, '2014-09-24 13:48:53', 1, 'non', 1, 1, ''),
(20, '2014-09-29 19:49:04', 1, 'non', 1, 1, ''),
(21, '2014-10-01 12:46:52', 1, 'non', 1, 1, ''),
(22, '2014-10-01 12:46:58', 5, 'non', 1, 1, ''),
(23, '2014-10-01 12:47:08', 1, 'non', 1, 1, ''),
(24, '2014-10-01 17:10:56', 1, 'non', 1, 1, ''),
(25, '2014-10-01 17:21:08', 1, 'non', 1, 1, ''),
(26, '2014-10-01 18:56:32', 2, 'non', 2, 1, ''),
(27, '2014-10-01 19:12:36', 1, 'non', 2, 1, ''),
(28, '2014-10-02 12:07:17', 1, 'non', 1, 1, ''),
(29, '2014-10-03 08:31:01', 1, 'non', 1, 1, ''),
(30, '2014-10-08 13:55:33', 1, 'oui', 1, 1, ''),
(31, '2014-10-09 10:03:28', 1, 'non', 1, 1, ''),
(32, '2014-10-16 13:52:22', 1, 'oui', 1, 1, ''),
(33, '2014-10-16 13:53:02', 2, 'oui', 2, 1, ''),
(34, '2014-10-17 15:45:56', 1, 'non', 2, 1, ''),
(35, '2014-10-22 14:03:37', 2, 'non', 1, 1, ''),
(36, '2014-10-27 10:29:20', 1, 'non', 2, 1, ''),
(37, '2014-10-27 10:29:49', 2, 'non', 3, 1, ''),
(38, '2014-10-30 12:56:40', 1, 'non', 1, 1, ''),
(39, '2014-10-30 12:58:10', 2, 'non', 2, 1, 'ordi'),
(40, '2014-10-30 17:59:30', 0, 'non', 0, 1, ''),
(41, '2014-11-02 16:49:53', 1, 'non', 1, 1, ''),
(42, '2014-11-05 17:06:05', 1, 'non', 2, 1, ''),
(43, '2014-11-06 10:04:48', 1, 'non', 1, 1, ''),
(44, '2014-11-06 10:05:13', 1, 'oui', 1, 1, ''),
(45, '2014-11-06 11:38:50', 5, 'non', 3, 1, ''),
(46, '2014-11-09 14:34:07', 1, 'non', 1, 1, ''),
(47, '2014-11-20 14:38:32', 1, 'non', 1, 1, 'JEUNE FEMME GROS SEINS'),
(48, '2014-11-20 15:32:20', 2, 'non', 3, 1, ''),
(49, '2014-11-23 13:37:36', 1, 'non', 2, 1, '');

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
(3, '2014-07-23 11:48:09', 'dÃ©chetterie ', 'porte des lilas', '#89b029', 'non');

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
  `texte_adhesion` text NOT NULL,
  `taux_tva` text NOT NULL,
  `tva_active` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `description_structure`
--

INSERT INTO `description_structure` (`id`, `nom`, `adresse`, `description`, `siret`, `telephone`, `mail`, `id_localite`, `texte_adhesion`, `taux_tva`, `tva_active`) VALUES
(1, 'La petite rockette', '125 rue du chemin vert', 'la petite rockette est une asso', '508 822 475 00010', '0155286118', 'lapetiterockette@gmail.com', '1', '  L''adhÃ©sion Ã  la ressourcerie formalise avant tout votre soutien aux valeurs Ã©cologiques et sociales dÃ©fendues par l''association. (Et peut, par ailleurs, s''avÃ©rer utile pour Ãªtre tenu informÃ© par courriel des diverses activitÃ©s, ponctuelles ou ordinaires, \r\ndÃ©veloppÃ©es la ressourcerie.)\r\n   AdhÃ©rer est donc surtout un geste politique, militant, d''engagement actif dans la lutte contre l''absurditÃ© consumÃ©riste et sa normalisation du gaspillage!!', '5.54', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `filieres_sortie`
--

CREATE TABLE IF NOT EXISTS `filieres_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `id_type_dechet_evac` text NOT NULL,
  `couleur` text NOT NULL,
  `description` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `filieres_sortie`
--

INSERT INTO `filieres_sortie` (`id`, `timestamp`, `nom`, `id_type_dechet_evac`, `couleur`, `description`, `visible`) VALUES
(1, '2014-07-10 13:54:43', 'recycle livre', '3', '#a77a37', 'ramasse livres', 'oui'),
(2, '2014-07-10 13:55:01', 'gebetex', '6', '#f48570', 'ramasse textiles', 'oui'),
(3, '2014-07-10 14:17:52', 'eco-systemes', '2', '#b36cb4', 'ramasse deee', 'oui'),
(4, '2014-07-10 18:51:38', 'screlec', '5', '#e3c451', 'ampoules', 'oui'),
(5, '2014-09-14 13:22:47', 'bijoutek', '1', '#ff9a00', 'recycle les bijoux', 'oui');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Contenu de la table `grille_objets`
--

INSERT INTO `grille_objets` (`id`, `timestamp`, `nom`, `description`, `id_type_dechet`, `visible`, `prix`) VALUES
(1, '2014-08-07 13:49:49', 'T-shirt, haut', 'T-shirts et hauts en touts genres', 3, 'oui', '2'),
(2, '2014-08-07 14:17:10', 'tv', 'televsion', 1, 'oui', '7'),
(3, '2014-08-07 14:49:46', 'lecteur vhs ', 'lecteur vhs ', 1, 'oui', '5'),
(4, '2014-08-07 14:51:28', 'lecteur dvd', 'lecteur dvd ', 1, 'oui', '5'),
(6, '2014-08-07 14:53:58', 'four micro ondes', 'four', 1, 'oui', '5'),
(7, '2014-08-07 14:54:49', 'Ã©tagÃ¨re billy', 'ikea valeur neuf = ', 2, 'oui', '5'),
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
(21, '2014-08-12 13:17:05', 'chaise pliante', 'chaise pliante', 2, 'oui', '5'),
(22, '2014-09-08 15:21:55', 'pantalon', 'pantalons toutes marques confondues', 3, 'oui', '3'),
(23, '2014-09-09 14:25:50', 'grille pain', 'grille pain', 1, 'oui', '5'),
(24, '2014-10-02 12:36:59', 'chemise', 'chemises en tout genre', 3, 'oui', '3'),
(25, '2014-10-02 12:37:11', 'veste', 'vestes', 3, 'oui', '3'),
(26, '2014-10-02 12:37:31', 'debardeur', 'debardeurs', 3, 'oui', '1'),
(27, '2014-10-02 12:38:00', 'soutien gorge', 'soutien gorges', 3, 'oui', '2'),
(28, '2014-10-02 12:38:13', 'culotte', 'culottes', 3, 'oui', '1'),
(29, '2014-10-02 12:38:27', 'manteau', 'manteaux', 3, 'oui', '5'),
(30, '2014-10-02 12:38:47', 'vÃªtement enfant', 'vÃªtements enfants', 3, 'oui', '1.5'),
(31, '2014-10-02 12:39:02', 'manteau enfant', 'manteau enfant', 3, 'oui', '3'),
(32, '2014-10-02 12:39:24', 'sous vet. enfant', 'sous vet. enfant', 3, 'oui', '1.5'),
(33, '2014-10-02 12:39:56', 'linge de maison', 'linge de maison en tout genre', 3, 'oui', '1.5'),
(34, '2014-10-02 12:40:07', 'sac Ã  main', 'sac Ã  main', 3, 'oui', '3'),
(35, '2014-10-02 12:40:21', 'pochette', 'pochettes', 3, 'oui', '1.5'),
(36, '2014-10-02 12:40:35', 'cravatte', 'cravattes', 3, 'oui', '0.5'),
(37, '2014-10-02 12:40:49', 'echarpe', 'echarpes ', 3, 'oui', '1'),
(38, '2014-10-02 12:41:06', 'paire de gants', 'paires de gants', 3, 'oui', '1');

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
-- Structure de la table `moyens_paiement`
--

CREATE TABLE IF NOT EXISTS `moyens_paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `moyens_paiement`
--

INSERT INTO `moyens_paiement` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-11-28 23:14:25', 'Especes', 'billet et pieces ', '#732121', 'oui'),
(2, '2014-11-28 23:16:30', 'Cheque', 'cheques', '#87421f', 'oui');

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
  `masse` decimal(11,3) NOT NULL,
  `id_collecte` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

--
-- Contenu de la table `pesees_collectes`
--

INSERT INTO `pesees_collectes` (`id`, `timestamp`, `masse`, `id_collecte`, `id_type_dechet`) VALUES
(1, '2014-09-04 18:42:18', 11.000, 1, 1),
(2, '2014-09-04 18:42:24', 54.000, 2, 6),
(3, '2014-09-04 18:42:24', 8.000, 2, 11),
(4, '2014-09-04 18:42:32', 99.000, 3, 3),
(5, '2014-09-04 18:45:00', 44.000, 4, 4),
(6, '2014-09-04 18:45:47', 1.000, 5, 7),
(7, '2014-09-05 14:46:16', 1.000, 6, 1),
(8, '2014-09-05 14:46:16', 2.000, 6, 2),
(9, '2014-09-05 14:46:16', 3.000, 6, 3),
(10, '2014-09-05 14:46:16', 66.000, 6, 4),
(11, '2014-09-05 14:46:16', 88.000, 6, 9),
(12, '2014-09-05 14:46:25', 5.000, 7, 7),
(13, '2014-09-05 16:41:31', 11.000, 8, 1),
(14, '2014-09-05 16:41:31', 55.000, 8, 4),
(15, '2014-09-05 16:41:33', 1.000, 9, 1),
(16, '2014-09-08 15:04:49', 15.000, 10, 3),
(17, '2014-09-08 15:04:49', 3.000, 10, 4),
(18, '2014-09-08 15:04:49', 5.000, 10, 6),
(19, '2014-09-08 15:04:49', 2.000, 10, 7),
(20, '2014-09-08 15:22:44', 500.000, 11, 3),
(21, '2014-09-08 15:23:33', 12.000, 12, 2),
(22, '2014-09-08 15:23:33', 12.000, 12, 4),
(23, '2014-09-08 15:23:33', 60.000, 12, 5),
(24, '2014-09-08 15:23:33', 120.000, 12, 6),
(25, '2014-09-09 13:51:17', 11.000, 13, 2),
(26, '2014-09-09 13:51:23', 0.800, 14, 4),
(27, '2014-09-09 13:51:23', 5.000, 14, 8),
(28, '2014-09-09 14:06:26', 12.000, 15, 2),
(29, '2014-09-09 14:06:26', 4.000, 15, 6),
(30, '2014-09-09 14:06:26', 0.900, 15, 11),
(31, '2014-09-13 18:43:00', 11.000, 16, 2),
(32, '2014-09-14 16:22:06', 11.000, 17, 2),
(33, '2014-09-24 12:49:41', 4.000, 18, 1),
(34, '2014-09-24 12:49:41', 11.000, 18, 2),
(35, '2014-09-24 13:48:53', 55.000, 19, 3),
(36, '2014-09-24 13:48:53', 11.000, 19, 4),
(37, '2014-09-29 19:49:04', 19.000, 20, 2),
(38, '2014-09-29 19:49:04', 0.800, 20, 9),
(39, '2014-09-29 19:49:04', 55.000, 20, 10),
(40, '2014-10-01 12:46:52', 11.000, 21, 1),
(41, '2014-10-01 12:46:58', 5.500, 22, 6),
(42, '2014-10-01 12:47:08', 0.500, 23, 5),
(43, '2014-10-01 12:47:08', 0.900, 23, 8),
(44, '2014-10-01 12:47:08', 8.000, 23, 11),
(45, '2014-10-01 17:10:56', 11.000, 24, 2),
(46, '2014-10-01 17:21:08', 11.000, 25, 3),
(47, '2014-10-01 18:56:32', 1.000, 26, 3),
(48, '2014-10-01 18:56:32', 0.900, 26, 6),
(49, '2014-10-02 12:07:17', 33.000, 28, 1),
(50, '2014-10-03 08:31:01', 6.000, 29, 3),
(51, '2014-10-03 08:31:01', 22.000, 29, 4),
(52, '2014-10-03 08:31:01', 3.000, 29, 5),
(53, '2014-10-03 08:31:01', 8.000, 29, 7),
(54, '2014-10-08 13:55:33', 12.000, 30, 1),
(55, '2014-10-08 13:55:33', 3.000, 30, 5),
(56, '2014-10-08 13:55:33', 55.000, 30, 6),
(57, '2014-10-08 13:55:33', 0.400, 30, 11),
(58, '2014-10-09 10:03:28', 85.000, 31, 1),
(59, '2014-10-09 10:03:28', 0.900, 31, 11),
(60, '2014-10-16 13:52:22', 91.000, 32, 3),
(61, '2014-10-16 13:53:02', 11.000, 33, 6),
(62, '2014-10-16 13:53:02', 181.000, 33, 7),
(63, '2014-10-16 13:53:03', 5.000, 33, 10),
(64, '2014-10-17 15:45:56', 22.000, 34, 1),
(65, '2014-10-17 15:45:56', 5.000, 34, 6),
(66, '2014-10-17 15:45:56', 3.000, 34, 13),
(67, '2014-10-22 14:03:37', 22.000, 35, 3),
(68, '2014-10-22 14:03:37', 0.880, 35, 7),
(69, '2014-10-27 10:29:20', 22.000, 36, 5),
(70, '2014-10-27 10:29:49', 0.800, 37, 8),
(71, '2014-10-27 10:29:49', 0.900, 37, 9),
(72, '2014-10-30 12:56:40', 22.000, 38, 1),
(73, '2014-10-30 12:58:10', 111.000, 39, 5),
(74, '2014-10-30 17:59:30', 22.000, 40, 1),
(75, '2014-10-30 17:59:30', 33.000, 40, 5),
(76, '2014-10-30 17:59:30', 0.900, 40, 6),
(77, '2014-11-02 16:49:53', 111.000, 41, 5),
(78, '2014-11-05 17:06:05', 2.200, 42, 11),
(79, '2014-11-06 10:05:13', 128.000, 44, 6),
(80, '2014-11-06 11:38:50', 333.000, 45, 3),
(81, '2014-11-09 14:34:07', 266.000, 46, 1),
(82, '2014-11-09 14:34:07', 122.000, 46, 8),
(83, '2014-11-09 14:34:07', 45.000, 46, 10),
(84, '2014-11-20 14:38:32', 12.000, 47, 1),
(85, '2014-11-20 14:38:32', 68.000, 47, 3),
(86, '2014-11-20 15:32:20', 25.000, 48, 1),
(87, '2014-11-20 15:32:20', 256.000, 48, 3),
(88, '2014-11-20 15:32:20', 248.000, 48, 6),
(89, '2014-11-20 15:32:20', 69.000, 48, 9),
(90, '2014-11-23 13:37:36', 22.000, 49, 1),
(91, '2014-11-23 13:37:36', 3.000, 49, 3),
(92, '2014-11-23 13:37:36', 0.900, 49, 4),
(93, '2014-11-23 13:37:36', 2.000, 49, 5),
(94, '2014-11-23 13:37:36', 8.000, 49, 6),
(95, '2014-11-23 13:37:36', 3.000, 49, 7);

-- --------------------------------------------------------

--
-- Structure de la table `pesees_sorties`
--

CREATE TABLE IF NOT EXISTS `pesees_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(7,3) NOT NULL,
  `id_sortie` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_type_poubelle` int(11) NOT NULL,
  `id_type_dechet_evac` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Contenu de la table `pesees_sorties`
--

INSERT INTO `pesees_sorties` (`id`, `timestamp`, `masse`, `id_sortie`, `id_type_dechet`, `id_type_poubelle`, `id_type_dechet_evac`) VALUES
(1, '2014-09-04 18:46:40', 11.000, 1, 1, 0, 0),
(2, '2014-09-04 18:46:46', 11.000, 2, 1, 0, 0),
(3, '2014-09-04 18:46:52', 55.000, 3, 1, 0, 0),
(4, '2014-09-04 18:46:57', 101.000, 4, 0, 1, 0),
(5, '2014-09-05 14:47:17', 11.000, 5, 1, 0, 0),
(6, '2014-09-05 14:47:23', 11.000, 6, 3, 0, 0),
(7, '2014-09-05 14:47:31', 236.000, 7, 3, 0, 0),
(8, '2014-09-05 14:47:36', 101.000, 8, 0, 1, 0),
(9, '2014-09-05 16:50:35', 11.000, 9, 4, 0, 0),
(10, '2014-09-08 15:07:28', 15.000, 10, 2, 0, 0),
(11, '2014-09-08 15:11:35', 351.500, 11, 2, 0, 0),
(12, '2014-09-08 15:13:33', 170.000, 12, 1, 0, 0),
(13, '2014-09-08 15:16:00', 98.000, 13, 0, 1, 0),
(14, '2014-09-08 15:32:30', 12.000, 14, 4, 0, 0),
(15, '2014-09-09 14:09:57', 12.000, 15, 3, 0, 0),
(16, '2014-09-09 14:12:02', 168.000, 16, 2, 0, 0),
(17, '2014-09-09 14:14:04', 98.000, 17, 0, 1, 0),
(18, '2014-09-13 18:55:16', 11.000, 18, 1, 0, 0),
(19, '2014-09-13 18:55:24', 51.000, 19, 3, 0, 0),
(20, '2014-09-13 18:55:30', 12.000, 20, 1, 0, 0),
(21, '2014-09-13 18:55:35', 89.000, 21, 0, 1, 0),
(22, '2014-09-13 19:06:29', 55.000, 22, 6, 0, 0),
(23, '2014-09-13 19:12:01', 91.000, 23, 0, 7, 0),
(24, '2014-09-13 19:31:30', 280.000, 24, 3, 0, 0),
(25, '2014-09-13 19:31:30', 12.000, 24, 6, 0, 0),
(26, '2014-09-14 13:07:22', 427.000, 25, 1, 0, 0),
(27, '2014-09-14 13:07:31', 111.000, 26, 2, 0, 0),
(28, '2014-09-14 13:08:00', 222.000, 27, 2, 0, 0),
(29, '2014-10-09 10:20:47', 155.000, 28, 1, 0, 0),
(30, '2014-10-09 10:26:32', 1.000, 29, 1, 0, 0),
(31, '2014-10-17 15:42:27', 35.890, 31, 4, 0, 0),
(32, '2014-10-17 15:42:27', 227.000, 31, 11, 0, 0),
(33, '2014-11-05 12:54:11', 123.000, 36, 7, 0, 0),
(34, '2014-11-05 14:21:13', 176.000, 37, 13, 0, 0),
(35, '2014-11-05 14:29:05', 222.000, 38, 5, 0, 0),
(36, '2014-11-05 17:03:45', 146.000, 39, 7, 0, 0),
(37, '2014-11-05 19:14:15', 227.000, 40, 3, 0, 0),
(38, '2014-11-05 19:14:15', 33.600, 40, 8, 0, 0),
(39, '2014-11-05 19:15:25', 47.600, 41, 7, 0, 0),
(40, '2014-11-06 10:40:46', 140.900, 42, 1, 0, 0),
(41, '2014-11-06 10:41:13', 88.000, 43, 7, 0, 0),
(42, '2014-11-20 15:06:01', 45.000, 44, 7, 0, 0),
(43, '2014-11-23 15:22:26', 113.000, 45, 1, 0, 0),
(44, '2014-11-23 15:26:27', 122.000, 46, 3, 0, 0),
(45, '2014-11-23 15:34:39', 33.000, 47, 1, 0, 0),
(46, '2014-11-23 15:34:39', 444.000, 47, 7, 0, 0),
(47, '2014-11-23 15:39:01', 22.000, 48, 5, 0, 0),
(48, '2014-11-23 15:45:05', 124.000, 49, 7, 0, 0),
(49, '2014-11-30 14:26:26', 2.000, 50, 1, 0, 0),
(50, '2014-11-30 14:42:20', 33.000, 52, 0, 0, 1),
(51, '2014-11-30 16:00:17', 33.000, 54, 0, 0, 3);

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
(2, '2014-06-12 13:01:52', 'point de collecte itinerant', 'pas d''adresse fixe!', '#4773a3', 'camion 30 mÃ¨tres cube avec balance! ', '350', 'oui'),
(3, '2014-06-12 14:14:31', 'point de collecte en dechetterie', 'porte des lilas', '#c4b13a', 'oui!', '1000', 'non');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

--
-- Contenu de la table `sorties`
--

INSERT INTO `sorties` (`id`, `timestamp`, `classe`, `adherent`, `id_filiere`, `id_convention`, `id_type_sortie`, `id_point_sortie`, `commentaire`) VALUES
(1, '2014-09-04 18:46:40', 'sorties', 'non', 0, 0, 1, 1, ''),
(2, '2014-09-04 18:46:46', 'sortiesc', '', 0, 1, 0, 1, ''),
(3, '2014-09-04 18:46:52', 'sortiesr', '', 1, 0, 0, 1, ''),
(4, '2014-09-04 18:46:57', 'sortiesp', '', 0, 0, 0, 1, ''),
(5, '2014-09-05 14:47:17', 'sorties', 'non', 0, 0, 1, 1, ''),
(6, '2014-09-05 14:47:23', 'sortiesc', '', 0, 2, 0, 1, ''),
(7, '2014-09-05 14:47:31', 'sortiesr', '', 3, 0, 0, 1, ''),
(8, '2014-09-05 14:47:36', 'sortiesp', '', 0, 0, 0, 1, ''),
(9, '2014-09-05 16:50:35', 'sorties', 'non', 0, 0, 3, 1, ''),
(10, '2014-09-08 15:07:28', 'sorties', 'oui', 0, 0, 1, 1, ''),
(11, '2014-09-08 15:11:35', 'sortiesr', '', 2, 0, 0, 1, ''),
(12, '2014-09-08 15:13:33', 'sortiesr', '', 1, 0, 0, 1, ''),
(13, '2014-09-08 15:16:00', 'sortiesp', '', 0, 0, 0, 1, ''),
(14, '2014-09-08 15:32:30', 'sortiesc', '', 0, 1, 0, 1, ''),
(15, '2014-09-09 14:09:57', 'sorties', 'non', 0, 0, 1, 1, ''),
(16, '2014-09-09 14:12:02', 'sortiesr', '', 2, 0, 0, 1, ''),
(17, '2014-09-09 14:14:04', 'sortiesp', '', 0, 0, 0, 1, ''),
(18, '2014-09-13 18:55:16', 'sorties', 'non', 0, 0, 1, 1, ''),
(19, '2014-09-13 18:55:24', 'sortiesc', '', 0, 1, 0, 1, ''),
(20, '2014-09-13 18:55:30', 'sortiesr', '', 1, 0, 0, 1, ''),
(21, '2014-09-13 18:55:35', 'sortiesp', '', 0, 0, 0, 1, ''),
(22, '2014-09-13 19:06:29', 'sorties', 'non', 0, 0, 3, 1, ''),
(23, '2014-09-13 19:12:01', 'sortiesp', '', 0, 0, 0, 1, ''),
(24, '2014-09-13 19:31:30', 'sortiesc', '', 0, 2, 0, 1, ''),
(25, '2014-09-14 13:07:22', 'sortiesr', '', 1, 0, 0, 1, ''),
(26, '2014-09-14 13:07:30', 'sortiesr', '', 2, 0, 0, 1, ''),
(27, '2014-09-14 13:08:00', 'sortiesr', '', 2, 0, 0, 1, ''),
(28, '2014-10-09 10:20:47', 'sorties', 'non', 0, 0, 3, 1, ''),
(29, '2014-10-09 10:26:32', 'sortiesc', '', 0, 1, 0, 1, ''),
(30, '2014-10-09 10:26:35', 'sortiesc', '', 0, 1, 0, 1, ''),
(31, '2014-10-17 15:42:27', 'sortiesc', '', 0, 1, 0, 1, ''),
(32, '2014-10-17 15:42:40', 'sortiesc', '', 0, 1, 0, 1, ''),
(33, '2014-11-05 11:22:46', 'sortiesc', '', 0, 1, 0, 1, ''),
(34, '2014-11-05 11:26:18', 'sortiesc', '', 0, 1, 0, 1, ''),
(35, '2014-11-05 11:27:02', 'sortiesc', '', 0, 1, 0, 1, ''),
(36, '2014-11-05 12:54:11', 'sortiesr', '', 1, 0, 0, 1, ''),
(37, '2014-11-05 14:21:13', 'sortiesd', '', 0, 0, 0, 1, ''),
(38, '2014-11-05 14:29:05', 'sorties', 'non', 0, 0, 1, 1, ''),
(39, '2014-11-05 17:03:45', 'sortiesr', '', 1, 0, 0, 1, ''),
(40, '2014-11-05 19:14:15', 'sorties', 'non', 0, 0, 1, 1, ''),
(41, '2014-11-05 19:15:25', 'sorties', 'non', 0, 0, 3, 1, ''),
(42, '2014-11-06 10:40:46', 'sortiesr', '', 3, 0, 0, 1, ''),
(43, '2014-11-06 10:41:13', 'sortiesr', '', 1, 0, 0, 1, ''),
(44, '2014-11-20 15:06:01', 'sortiesr', '', 1, 0, 0, 1, ''),
(45, '2014-11-23 15:22:26', 'sorties', 'non', 0, 0, 1, 1, 'mon pote'),
(46, '2014-11-23 15:26:27', 'sortiesc', '', 0, 2, 0, 1, 'il Ã©tai frilleu'),
(47, '2014-11-23 15:34:39', 'sortiesd', '', 0, 0, 0, 1, ''),
(48, '2014-11-23 15:39:01', 'sortiesd', '', 0, 0, 0, 1, 'ta mere en short'),
(49, '2014-11-23 15:45:05', 'sortiesr', '', 1, 0, 0, 1, 'que du livre pas Ã  recycler mais prÃªt Ã  vendre'),
(50, '2014-11-30 14:26:26', 'sortiesc', '', 0, 1, 0, 1, ''),
(51, '2014-11-30 14:33:40', 'sortiesc', '', 0, 2, 0, 1, ''),
(52, '2014-11-30 14:42:20', 'sortiesc', '', 0, 1, 0, 1, ''),
(53, '2014-11-30 16:00:10', 'sortiesr', '', 0, 0, 0, 1, ''),
(54, '2014-11-30 16:00:17', 'sortiesr', '', 1, 0, 0, 1, '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `types_poubelles`
--

INSERT INTO `types_poubelles` (`id`, `timestamp`, `nom`, `description`, `masse_bac`, `ultime`, `couleur`, `visible`) VALUES
(1, '2014-07-17 13:22:03', 'petite poubelle verte', 'tout venant bac 100 litres', '22', 'oui', '#70f71a', 'oui'),
(2, '2014-07-17 13:22:06', 'grande poubelle verte', 'grande poubelle verte', '27', 'oui', '#46de12', 'oui'),
(3, '2014-07-17 15:18:10', 'grosse poubelle verte', 'grosse poubelle verte', '32', 'oui', '#50c02d', 'oui'),
(4, '2014-07-17 15:24:42', 'petite poubelle jaune', 'a', '19', 'non', '#ffec00', 'oui'),
(5, '2014-07-23 11:29:05', 'grande poubelle jaune', 'emballages bac de 200 l', '27', 'non', '#e2e51d', 'oui'),
(6, '2014-07-23 11:29:57', 'grosse poubelle jaune', 'Emballages, bac de 400 litres.', '32', 'non', '#fff10d', 'oui'),
(7, '2014-07-23 11:30:52', 'petite poubelle blanche', 'verre , bac de 100l', '20', 'non', '#e4e295', 'oui');

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
(1, '2014-07-02 13:21:10', 'apport volontaire', 'quand une personne apporte d''elle mÃªme un ou des objets Ã  la boutique', '#cb2323', 'oui'),
(2, '2014-07-02 13:21:40', 'collecte Ã  domicile', 'collecte chez l''habitant en tournÃ©e camion', '#7b1f1f', 'oui'),
(3, '2014-07-02 13:21:53', 'collecte en pied d''immeuble', 'collecte demandÃ©e par un bailleur ', '#934a4a', 'oui'),
(4, '2014-07-02 13:22:48', 'collecte en brocante', 'collecte effectuÃ©e en fin de brocantes', '#a26b6b', 'oui'),
(5, '2014-07-10 18:59:22', 'collecte en hopital psychiatrique ', 'collecte en hopital psychiatrique ^^', '#ff88c9', 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `type_contenants`
--

CREATE TABLE IF NOT EXISTS `type_contenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `masse` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `type_contenants`
--

INSERT INTO `type_contenants` (`id`, `timestamp`, `nom`, `description`, `masse`, `couleur`, `visible`) VALUES
(2, '2014-10-01 16:54:57', 'Roulpratique', 'Roulpratique en alu.', '10', '#019b99', 'oui'),
(3, '2014-10-01 17:06:29', 'diable vert', 'diable vert ', '12', '#dd0c0c', 'oui'),
(4, '2014-10-13 13:28:02', 'roll textille', 'roll textille', '31', '#5037d0', 'oui'),
(5, '2014-11-05 14:25:22', 'caddie', 'petite cage en fer sur roulettes', '11', '#ab26ab', 'oui'),
(6, '2014-11-05 14:25:43', 'cage d3e', 'cage d3e', '39', '#108f99', 'oui'),
(7, '2014-11-05 14:26:11', 'caisse grise', 'grande caisse grise', '3', '#799082', 'oui'),
(8, '2014-11-05 14:27:33', 'petite caisse grise', 'petite caisse grise', '1.5', '#776161', 'oui');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `type_dechets`
--

INSERT INTO `type_dechets` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-04-08 18:21:16', 'matÃ©riel electrique', 'dÃ©chets dâ€™Ã©quipements Ã©lectroniques et Ã©lectromÃ©nagers ', '#e87702', 'oui'),
(2, '2014-04-08 18:21:40', 'mobilier', 'mobilier', '#e83c02', 'non'),
(3, '2014-04-08 18:22:10', 'textile,accessoires ', 'textile,accessoires la masse de bijoux Ã©tant nÃ©gligeable ', '#ff1c0f', 'oui'),
(4, '2014-06-22 20:00:31', 'jouets', 'jeux, jouets , comprend aussi les jeux de societÃ©s', '#e80273', 'oui'),
(5, '2014-06-22 20:34:38', 'informatique', 'ordis, ecrans , claviers , autres pÃ©riphÃ©riques info. DEEE. en fait ... ', '#e902ff', 'oui'),
(6, '2014-07-02 14:00:33', 'vaisselle', 'vaisselle ,tout Ã©tats touts materiaux', '#a71dff', 'oui'),
(7, '2014-07-02 22:04:57', 'livres', 'livres magazines journaux etc', '#3a02e8', 'oui'),
(8, '2014-07-02 22:06:25', 'supports media', 'cd dvd vinyles cassets minidiscs et consors', '#0b28ff', 'oui'),
(9, '2014-07-02 22:07:24', 'bibelots quincaillerie  ', 'bibelots divers objets dÃ©co ', '#026fe8', 'oui'),
(10, '2014-07-02 22:08:36', 'autres', 'autres', '#02d4ff', 'oui'),
(11, '2014-07-10 19:29:38', 'bijoux', 'bijoux en tout genre , pese peut mais trÃ¨s bien valorisÃ©', '#0effc1', 'oui'),
(12, '2014-10-09 10:10:59', 'mobilier en service', 'mobilier en service', '#bb2222', 'non'),
(13, '2014-10-09 10:11:16', 'mobilier hs', 'mobilier hs', '#bd2d2d', 'non');

-- --------------------------------------------------------

--
-- Structure de la table `type_dechets_evac`
--

CREATE TABLE IF NOT EXISTS `type_dechets_evac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `type_dechets_evac`
--

INSERT INTO `type_dechets_evac` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-11-23 16:31:40', 'metaux', 'ferraille alu ect...', '#8e33ae', 'oui'),
(2, '2014-11-30 15:00:35', 'deee', 'dÃ©chets dâ€™Ã©quipements Ã©lectroniques et Ã©lectromÃ©nagers ', '#000000', 'oui'),
(3, '2014-11-30 15:00:57', 'livres Ã  recycler', 'livres Ã  recycler', '#9b4848', 'oui'),
(4, '2014-11-30 15:01:13', 'piles', 'piles', '#925555', 'oui'),
(5, '2014-11-30 15:01:40', 'lampes eco/neons', 'lampes eco/neons', '#000000', 'oui'),
(6, '2014-11-30 15:02:06', 'textile', 'textile', '#846060', 'oui'),
(7, '2014-11-30 15:02:43', 'bois', 'planches ', '#933e3e', 'oui'),
(8, '2014-11-30 15:03:10', 'mobilier hors service', 'mobilier hors service', '#c47878', 'oui');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `timestamp`, `niveau`, `nom`, `prenom`, `mail`, `pass`) VALUES
(1, '2014-06-25 22:58:07', 'c1c2c3v1v2s1abighljkmp', 'martin', 'vert', 'mart1ver@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(3, '2014-10-26 12:57:35', 'c1bigl', 'jean', 'claude', 'jeanclaude@jc.org', '81dc9bdb52d04dc20036dbd8313ed055'),
(4, '2014-10-26 13:01:14', 'c1v1s1abi', 'mickey', 'mouse', 'miky@disney.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(5, '2014-11-09 14:13:33', 'c1c2c3v1v2s1abighljkmp', 'bobel', 'martin', 'martinbobel@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(6, '2014-11-20 14:33:06', 'c1c2c3v1v2s1abighljkmp', 'aurelien ', 'furet', 'aurelien.furet@lapetiterockette.org', '52455f83f349b742ba72e64636e92cba');

-- --------------------------------------------------------

--
-- Structure de la table `vendus`
--

CREATE TABLE IF NOT EXISTS `vendus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vente` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_objet` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(9,3) NOT NULL,
  `remboursement` decimal(9,3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `vendus`
--

INSERT INTO `vendus` (`id`, `id_vente`, `id_type_dechet`, `id_objet`, `quantite`, `prix`, `remboursement`) VALUES
(1, 1, 6, 10, 3, 0.500, 0.000),
(2, 2, 2, 0, 1, 20.000, 0.000),
(3, 2, 3, 1, 1, 1.000, 0.000),
(4, 2, 6, 10, 20, 0.500, 0.000),
(5, 2, 6, 11, 2, 0.250, 0.000),
(6, 3, 6, 10, 2, 0.500, 0.000),
(7, 4, 1, 0, 1, 10.000, 0.000),
(8, 5, 1, 2, 1, 7.000, 0.000),
(9, 6, 1, 2, 1, 0.000, 7.000),
(10, 7, 1, 2, 1, 0.000, 7.000),
(11, 8, 1, 2, 1, 0.000, 7.000),
(12, 9, 1, 2, 1, 0.000, 7.000),
(13, 10, 1, 2, 1, 7.000, 0.000),
(14, 11, 3, 26, 1, 1.500, 0.000),
(15, 11, 3, 22, 1, 3.000, 0.000),
(16, 11, 3, 24, 2, 2.000, 0.000),
(17, 12, 1, 2, 1, 0.000, 7.000),
(18, 13, 3, 1, 20, 2.000, 0.000),
(19, 13, 3, 33, 10, 4.000, 0.000),
(20, 14, 0, 0, 1, 0.000, 220.000);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `ventes`
--

INSERT INTO `ventes` (`id`, `timestamp`, `adherent`, `commentaire`, `id_point_vente`) VALUES
(1, '2014-09-05 16:22:21', 'non', '', 1),
(2, '2014-09-08 15:20:06', 'non', '', 1),
(3, '2014-09-08 15:44:02', 'non', '', 1),
(4, '2014-09-09 14:15:17', 'non', '', 1),
(5, '2014-10-27 16:24:35', 'non', '', 1),
(6, '2014-10-27 16:53:16', 'non', '', 1),
(7, '2014-10-27 16:54:12', 'non', '', 1),
(8, '2014-10-27 16:56:02', 'non', '', 1),
(9, '2014-10-27 16:59:00', 'non', '', 1),
(10, '2014-10-30 12:50:39', 'non', 'une tÃ©lÃ©', 1),
(11, '2014-11-05 17:05:24', 'non', '', 1),
(12, '2014-11-06 11:37:12', 'non', '', 1),
(13, '2014-11-20 15:18:12', 'non', 'ZEFGRE', 1),
(14, '2014-11-20 15:23:53', 'non', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
