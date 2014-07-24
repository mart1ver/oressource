-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Jeu 24 Juillet 2014 à 22:52
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

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
(81, '2014-07-24 20:27:59', 1, 'non', 1, 1);

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
(1, '2014-07-10 13:28:10', 'la maison de la plage', 'mozaiques', '#5260c2', 'oui'),
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
  `masse` int(11) NOT NULL,
  `id_collecte` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `pesees_collectes`
--

INSERT INTO `pesees_collectes` (`id`, `timestamp`, `masse`, `id_collecte`, `id_type_dechet`) VALUES
(1, '2014-07-22 15:00:02', 111, 73, 1),
(2, '2014-07-24 15:00:16', 55, 74, 1),
(3, '2014-07-24 15:00:16', 66, 74, 2),
(4, '2014-07-24 15:00:22', 11, 75, 1),
(5, '2014-07-24 15:35:28', 1, 76, 1),
(6, '2014-07-24 15:35:28', 111, 76, 2),
(7, '2014-07-24 15:35:28', 32, 76, 3),
(8, '2014-07-24 15:35:28', 55, 76, 7),
(9, '2014-07-24 15:35:28', 8, 76, 11),
(10, '2014-07-24 15:56:55', 44, 77, 6),
(11, '2014-07-24 16:49:11', 15, 78, 7),
(12, '2014-07-24 20:26:28', 24, 79, 1),
(13, '2014-07-24 20:26:28', 88, 79, 2),
(14, '2014-07-24 20:26:28', 56, 79, 3),
(15, '2014-07-24 20:26:28', 45, 79, 7),
(16, '2014-07-24 20:26:28', 1, 79, 10),
(17, '2014-07-24 20:27:30', 43, 80, 3),
(18, '2014-07-24 20:27:59', 11, 81, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pesees_sorties`
--

CREATE TABLE IF NOT EXISTS `pesees_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` int(11) NOT NULL,
  `id_sortie` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `points_collecte`
--

INSERT INTO `points_collecte` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `visible`) VALUES
(1, '2014-04-08 18:06:36', 'Point de collecte en boutique ', '125 rue du chemin vert', '#d13232', 'point de collecte de la roro', 'oui'),
(2, '2014-06-12 13:01:52', 'point de collecte itinerant', 'pas d''adresse fixe!', '#4773a3', 'camion 30 mÃ¨tres cube avec balance! ', 'non'),
(3, '2014-06-12 14:14:31', 'point de collecte en dechetterie', 'porte des lilas', '#c4b13a', 'oui!', 'non');

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
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `points_sortie`
--

INSERT INTO `points_sortie` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `visible`) VALUES
(1, '2014-04-08 18:19:28', 'Sorties hors boutique', '125 rue du chemin vert', '#aa1d7c', 'point de sortie hors boutique principale de la ressourcerie de la petite rockette', 'oui');

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
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `points_vente`
--

INSERT INTO `points_vente` (`id`, `timestamp`, `nom`, `adresse`, `couleur`, `commentaire`, `visible`) VALUES
(1, '2014-04-08 18:17:25', 'Boutique de La Petite Rockette', '125 rue du chemin vert', '#a4e06e', 'la caisse principale de la ressourcerie de la petite rockette              ', 'oui'),
(2, '2014-07-10 17:08:03', 'La toute petite rockette', 'rue de la folie mericourt ', '#509a64', 'vente de vÃªtements et accessoires de mode femme et enfants ', 'oui');

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
  `id_filiere` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `id_point_sortie` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(1, '2014-07-17 13:22:03', 'petite poubelle verte', 'tout venant bac 100 litres', '20', 'oui', '#70f71a', 'oui'),
(2, '2014-07-17 13:22:06', 'grande poubelle verte', 'grande poubelle verte', '27', 'oui', '#46de12', 'oui'),
(3, '2014-07-17 15:18:10', 'grosse poubelle verte', 'grosse poubelle verte', '32', 'oui', '#50c02d', 'oui'),
(4, '2014-07-17 15:24:42', 'petite poubelle verte', 'a', '19', 'oui', '#ffec00', 'oui'),
(5, '2014-07-23 11:29:05', 'grande poubelle jaune', 'emballages bac de 200 l', '27', 'oui', '#e2e51d', 'oui'),
(6, '2014-07-23 11:29:57', 'grosse poubelle jaune', 'Emballages, bac de 400 litres.', '32', 'oui', '#fff10d', 'oui'),
(7, '2014-07-23 11:30:52', 'petite poubelle blanche', 'verre , bac de 100l', '20', 'oui', '#e4e295', 'oui');

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
-- Structure de la table `type_objets`
--

CREATE TABLE IF NOT EXISTS `type_objets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `desciption` text NOT NULL,
  `couleur` text NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `visible` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `type_sortie`
--

INSERT INTO `type_sortie` (`id`, `timestamp`, `nom`, `description`, `couleur`, `visible`) VALUES
(1, '2014-07-09 12:10:42', 'don Ã  un particulier', 'don d''un objet invendable en boutique', '#9d4a4a', 'oui'),
(2, '2014-07-09 12:13:45', 'don Ã  une association', 'don d''objets Ã  une asso. sans convention', '#c6a13d', 'oui'),
(3, '2014-07-09 12:14:35', 'don Ã  un artiste', 'objets, materiaux donnÃ©s Ã  un artiste', '#bf6b1f', 'oui');

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
(1, '2014-06-25 22:58:07', 'c1c5c6v1v2s1abigmp', 'martin', 'vert', 'mart1ver@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(2, '2014-07-24 15:29:20', 'c1v1s1abi', 'dom', 'dominique', 'dom@dom.dom', '81dc9bdb52d04dc20036dbd8313ed055');

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
