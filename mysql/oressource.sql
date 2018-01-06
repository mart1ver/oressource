-- MySQL dump 10.16  Distrib 10.1.29-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: oressource2
-- ------------------------------------------------------
-- Server version	10.1.29-MariaDB-6

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `collectes`
--

DROP TABLE IF EXISTS `collectes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_type_collecte` int(11) NOT NULL,
  `localisation` int(11) NOT NULL,
  `id_point_collecte` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Collectes_TypeCollecte` (`id_type_collecte`),
  KEY `FK_Collectes_PointCollectes` (`id_point_collecte`),
  KEY `FK_Collectes_Localite` (`localisation`),
  KEY `FK_Collectes_Createur` (`id_createur`),
  KEY `FK_Collectes_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_Collectes_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Collectes_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Collectes_Localite` FOREIGN KEY (`localisation`) REFERENCES `localites` (`id`),
  CONSTRAINT `FK_Collectes_PointCollectes` FOREIGN KEY (`id_point_collecte`) REFERENCES `points_collecte` (`id`),
  CONSTRAINT `FK_Collectes_TypeCollecte` FOREIGN KEY (`id_type_collecte`) REFERENCES `type_collecte` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collectes`
--

LOCK TABLES `collectes` WRITE;
/*!40000 ALTER TABLE `collectes` DISABLE KEYS */;
/*!40000 ALTER TABLE `collectes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conventions_sorties`
--

DROP TABLE IF EXISTS `conventions_sorties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conventions_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_ConvSortie_nom` (`nom`(255)),
  KEY `FK_ConvSortie_Createur` (`id_createur`),
  KEY `FK_ConvSortie_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_ConvSortie_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_ConvSortie_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conventions_sorties`
--

LOCK TABLES `conventions_sorties` WRITE;
/*!40000 ALTER TABLE `conventions_sorties` DISABLE KEYS */;
/*!40000 ALTER TABLE `conventions_sorties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `description_structure`
--

DROP TABLE IF EXISTS `description_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `description_structure` (
  `id` int(11) NOT NULL,
  `session_timeout` int(11) NOT NULL,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `description` text NOT NULL,
  `siret` text NOT NULL,
  `telephone` text NOT NULL,
  `mail` text NOT NULL,
  `id_localite` int(11) NOT NULL DEFAULT '1',
  `texte_adhesion` text NOT NULL,
  `taux_tva` decimal(10,0) NOT NULL DEFAULT '0',
  `tva_active` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `cr` int(11) NOT NULL,
  `lot` tinyint(1) NOT NULL DEFAULT '1',
  `viz` tinyint(1) NOT NULL DEFAULT '1',
  `nb_viz` int(11) NOT NULL,
  `saisiec` tinyint(1) NOT NULL DEFAULT '1',
  `affsp` tinyint(1) NOT NULL DEFAULT '1',
  `affss` tinyint(1) NOT NULL DEFAULT '1',
  `affsr` tinyint(1) NOT NULL DEFAULT '1',
  `affsd` tinyint(1) NOT NULL DEFAULT '1',
  `affsde` tinyint(1) NOT NULL DEFAULT '1',
  `pes_vente` tinyint(1) NOT NULL DEFAULT '1',
  `force_pes_vente` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `description_structure`
--

LOCK TABLES `description_structure` WRITE;
/*!40000 ALTER TABLE `description_structure` DISABLE KEYS */;
INSERT INTO `description_structure` VALUES (1,30,'La petite rockette','125 rue du chemin vert','la petite rockette est une asso cool','508 822 475 00010','0155286118','lapetiterockette@gmail.com',1,'',10,0,1,1,'2018-01-03 22:43:44',3216,1,1,30,1,1,1,1,1,1,1,0);
/*!40000 ALTER TABLE `description_structure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filieres_sortie`
--

DROP TABLE IF EXISTS `filieres_sortie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filieres_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `id_type_dechet_evac` text NOT NULL,
  `couleur` text NOT NULL,
  `description` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_FilieresSortie_nom` (`nom`(255)),
  KEY `FK_FiliereSortie_Createur` (`id_createur`),
  KEY `FK_FiliereSortie_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_FiliereSortie_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_FiliereSortie_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filieres_sortie`
--

LOCK TABLES `filieres_sortie` WRITE;
/*!40000 ALTER TABLE `filieres_sortie` DISABLE KEYS */;
/*!40000 ALTER TABLE `filieres_sortie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grille_objets`
--

DROP TABLE IF EXISTS `grille_objets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grille_objets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `prix` decimal(10,0) NOT NULL DEFAULT '0',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_GrilleObjets_nom` (`nom`(255)),
  KEY `FK_GrilleObjet_TypeDechet` (`id_type_dechet`),
  KEY `FK_GrilleObjet_Createur` (`id_createur`),
  KEY `FK_GrilleObjet_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_GrilleObjet_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_GrilleObjet_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_GrilleObjet_TypeDechet` FOREIGN KEY (`id_type_dechet`) REFERENCES `type_dechets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grille_objets`
--

LOCK TABLES `grille_objets` WRITE;
/*!40000 ALTER TABLE `grille_objets` DISABLE KEYS */;
INSERT INTO `grille_objets` VALUES (1,'2018-01-03 22:43:21','T-shirt, haut','T-shirts et hauts en touts genres',3,1,2,1,1,'2018-01-03 22:43:33'),(2,'2018-01-03 22:43:21','tv','televsion',1,1,10,1,1,'2018-01-03 22:43:33'),(3,'2018-01-03 22:43:21','lecteur vhs','lecteur vhs ',1,1,5,1,1,'2018-01-03 22:43:33'),(4,'2018-01-03 22:43:21','lecteur dvd','lecteur dvd ',1,1,5,1,1,'2018-01-03 22:43:33'),(5,'2018-01-03 22:43:21','four micro ondes','four',1,1,5,1,1,'2018-01-03 22:43:33'),(6,'2018-01-03 22:43:21','étagere billy','ikea valeur neuf = ',2,0,5,1,1,'2018-01-03 22:43:33'),(7,'2018-01-03 22:43:21','puzzle','puzzle',4,1,2,1,1,'2018-01-03 22:43:33'),(8,'2018-01-03 22:43:21','clavier','clavier',5,1,2,1,1,'2018-01-03 22:43:33'),(9,'2018-01-03 22:43:21','bol','bol',6,1,1,1,1,'2018-01-03 22:43:33'),(10,'2018-01-03 22:43:21','assiette','assiette',6,1,0,1,1,'2018-01-03 22:43:33'),(11,'2018-01-03 22:43:21','bd','BD',7,1,0,1,1,'2018-01-03 22:43:33'),(12,'2018-01-03 22:43:21','polars','polars',7,1,0,1,1,'2018-01-03 22:43:33'),(13,'2018-01-03 22:43:21','vinyle','vinyle',8,1,0,1,1,'2018-01-03 22:43:33'),(14,'2018-01-03 22:43:21','dvd','dvd',8,1,0,1,1,'2018-01-03 22:43:33'),(15,'2018-01-03 22:43:21','vhs','vhs',8,1,0,1,1,'2018-01-03 22:43:33'),(16,'2018-01-03 22:43:21','K7 audio','K7 audio',8,1,0,1,1,'2018-01-03 22:43:33'),(17,'2018-01-03 22:43:21','cadres','cadres',9,0,1,1,1,'2018-01-03 22:43:33'),(18,'2018-01-03 22:43:21','bouteille de gaz','heuu...',10,1,51,1,1,'2018-01-03 22:43:33'),(19,'2018-01-03 22:43:21','chaise pliante','chaise pliante',2,0,5,1,1,'2018-01-03 22:43:33'),(20,'2018-01-03 22:43:21','pantalon','pantalons toutes marques confondues',3,1,3,1,1,'2018-01-03 22:43:33'),(21,'2018-01-03 22:43:21','grille pain','grille pain',1,1,5,1,1,'2018-01-03 22:43:33'),(22,'2018-01-03 22:43:21','chemise','chemises en tout genre',3,1,3,1,1,'2018-01-03 22:43:33'),(23,'2018-01-03 22:43:21','veste','vestes',3,1,3,1,1,'2018-01-03 22:43:33'),(24,'2018-01-03 22:43:21','debardeur','debardeurs',3,1,1,1,1,'2018-01-03 22:43:33'),(25,'2018-01-03 22:43:21','soutien gorge','soutien gorges',3,1,2,1,1,'2018-01-03 22:43:33'),(26,'2018-01-03 22:43:21','culotte','culottes',3,1,1,1,1,'2018-01-03 22:43:33'),(27,'2018-01-03 22:43:21','manteau','manteaux',3,1,5,1,1,'2018-01-03 22:43:33'),(28,'2018-01-03 22:43:21','vétement enfant','vetements enfants',3,1,2,1,1,'2018-01-03 22:43:33'),(29,'2018-01-03 22:43:21','manteau enfant','manteau enfant',3,1,3,1,1,'2018-01-03 22:43:33'),(30,'2018-01-03 22:43:21','sous vet. enfant','sous vet. enfant',3,1,2,1,1,'2018-01-03 22:43:33'),(31,'2018-01-03 22:43:21','linge de maison','linge de maison en tout genre',3,1,2,1,1,'2018-01-03 22:43:33'),(32,'2018-01-03 22:43:21','sac à  main','sac à main',3,1,3,1,1,'2018-01-03 22:43:33'),(33,'2018-01-03 22:43:21','pochette','pochettes',3,1,2,1,1,'2018-01-03 22:43:33'),(34,'2018-01-03 22:43:21','cravatte','cravattes',3,1,1,1,1,'2018-01-03 22:43:33'),(35,'2018-01-03 22:43:21','echarpe','echarpes ',3,1,1,1,1,'2018-01-03 22:43:33'),(36,'2018-01-03 22:43:21','paire de gants','paires de gants',3,1,1,1,1,'2018-01-03 22:43:33'),(37,'2018-01-03 22:43:21','papeterie ','papeterie',9,0,0,1,1,'2018-01-03 22:43:33'),(38,'2018-01-03 22:43:21','Chaise Collecterie','Chaise Collecterie',10,1,85,1,1,'2018-01-03 22:43:33'),(39,'2018-01-03 22:43:21','Ordinateur Tour','Complet avec clavier & Souris',5,1,80,1,1,'2018-01-03 22:43:33'),(40,'2018-01-03 22:43:21','sudoku électronique ','sudoku électronique ',1,1,0,1,1,'2018-01-03 22:43:33'),(41,'2018-01-03 22:43:21','lot 6 verres','lot 6 verres',6,1,2,1,1,'2018-01-03 22:43:33'),(42,'2018-01-03 22:43:21','Cuilleres','1 lot de 12',9,1,1,1,1,'2018-01-03 22:43:33');
/*!40000 ALTER TABLE `grille_objets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `localites`
--

DROP TABLE IF EXISTS `localites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `couleur` text NOT NULL,
  `relation_openstreetmap` text NOT NULL,
  `commentaire` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_localites_nom` (`nom`(255)),
  KEY `FK_Localite_Createur` (`id_createur`),
  KEY `FK_Localite_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_Localite_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Localite_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `localites`
--

LOCK TABLES `localites` WRITE;
/*!40000 ALTER TABLE `localites` DISABLE KEYS */;
/*!40000 ALTER TABLE `localites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moyens_paiement`
--

DROP TABLE IF EXISTS `moyens_paiement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moyens_paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_MoyensPaiement_nom` (`nom`(255)),
  KEY `FK_MoyensPaiment_Createur` (`id_createur`),
  KEY `FK_MoyensPaiment_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_MoyensPaiment_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_MoyensPaiment_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moyens_paiement`
--

LOCK TABLES `moyens_paiement` WRITE;
/*!40000 ALTER TABLE `moyens_paiement` DISABLE KEYS */;
INSERT INTO `moyens_paiement` VALUES (1,'2018-01-03 22:43:22','Especes','billet et pieces ','#732121',1,1,1,'2018-01-03 22:43:30'),(2,'2018-01-03 22:43:22','Cheque','cheques','#c0c0c0',1,1,1,'2018-01-03 22:43:30'),(3,'2018-01-03 22:43:22','Carte Bleue','Carte Bleue','#0000ff',1,1,1,'2018-01-03 22:43:30');
/*!40000 ALTER TABLE `moyens_paiement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesees_collectes`
--

DROP TABLE IF EXISTS `pesees_collectes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesees_collectes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(11,3) NOT NULL,
  `id_collecte` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_PeseesCollectes_Collectes` (`id_collecte`),
  KEY `FK_PeseesCollectes_TypeDechet` (`id_type_dechet`),
  KEY `FK_PeseesCollectes_Createur` (`id_createur`),
  KEY `FK_PeseesCollectes_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_PeseesCollectes_Collectes` FOREIGN KEY (`id_collecte`) REFERENCES `collectes` (`id`),
  CONSTRAINT `FK_PeseesCollectes_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PeseesCollectes_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PeseesCollectes_TypeDechet` FOREIGN KEY (`id_type_dechet`) REFERENCES `type_dechets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesees_collectes`
--

LOCK TABLES `pesees_collectes` WRITE;
/*!40000 ALTER TABLE `pesees_collectes` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesees_collectes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesees_sorties`
--

DROP TABLE IF EXISTS `pesees_sorties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesees_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(7,3) NOT NULL,
  `id_sortie` int(11) NOT NULL,
  `id_type_dechet` int(11) DEFAULT '0',
  `id_type_poubelle` int(11) DEFAULT '0',
  `id_type_dechet_evac` int(11) DEFAULT '0',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_PeseesSorties_Sorties` (`id_sortie`),
  KEY `FK_PeseesSorties_TypeDechets` (`id_type_dechet`),
  KEY `FK_PeseesSorties_TypePoubelles` (`id_type_poubelle`),
  KEY `FK_PeseesSorties_TypeDechetEvac` (`id_type_dechet_evac`),
  KEY `FK_PeseesSorties_Createur` (`id_createur`),
  KEY `FK_PeseesSorties_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_PeseesSorties_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PeseesSorties_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PeseesSorties_Sorties` FOREIGN KEY (`id_sortie`) REFERENCES `sorties` (`id`),
  CONSTRAINT `FK_PeseesSorties_TypeDechetEvac` FOREIGN KEY (`id_type_dechet_evac`) REFERENCES `type_dechets_evac` (`id`),
  CONSTRAINT `FK_PeseesSorties_TypeDechets` FOREIGN KEY (`id_type_dechet`) REFERENCES `type_dechets` (`id`),
  CONSTRAINT `FK_PeseesSorties_TypePoubelles` FOREIGN KEY (`id_type_poubelle`) REFERENCES `types_poubelles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesees_sorties`
--

LOCK TABLES `pesees_sorties` WRITE;
/*!40000 ALTER TABLE `pesees_sorties` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesees_sorties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesees_vendus`
--

DROP TABLE IF EXISTS `pesees_vendus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesees_vendus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `masse` decimal(11,3) NOT NULL,
  `quantite` int(11) NOT NULL,
  `id_vendu` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_PeseesVendus_Createur` (`id_createur`),
  KEY `FK_PeseesVendus_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_PeseesVendus_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PeseesVendus_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PeseesVendus_Vendus` FOREIGN KEY (`id`) REFERENCES `vendus` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesees_vendus`
--

LOCK TABLES `pesees_vendus` WRITE;
/*!40000 ALTER TABLE `pesees_vendus` DISABLE KEYS */;
/*!40000 ALTER TABLE `pesees_vendus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `points_collecte`
--

DROP TABLE IF EXISTS `points_collecte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `points_collecte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `couleur` text NOT NULL,
  `commentaire` text NOT NULL,
  `pesee_max` decimal(10,0) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_PointsSortie_nom` (`nom`(255)),
  KEY `FK_PointCollecte_Createur` (`id_createur`),
  KEY `FK_PointCollecte_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_PointCollecte_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PointCollecte_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points_collecte`
--

LOCK TABLES `points_collecte` WRITE;
/*!40000 ALTER TABLE `points_collecte` DISABLE KEYS */;
/*!40000 ALTER TABLE `points_collecte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `points_sortie`
--

DROP TABLE IF EXISTS `points_sortie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `points_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `couleur` text NOT NULL,
  `commentaire` text NOT NULL,
  `pesee_max` decimal(10,0) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_PointsCollecte_nom` (`nom`(255)),
  KEY `FK_PointSortie_Createur` (`id_createur`),
  KEY `FK_PointSortie_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_PointSortie_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PointSortie_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points_sortie`
--

LOCK TABLES `points_sortie` WRITE;
/*!40000 ALTER TABLE `points_sortie` DISABLE KEYS */;
/*!40000 ALTER TABLE `points_sortie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `points_vente`
--

DROP TABLE IF EXISTS `points_vente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `points_vente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `adresse` text NOT NULL,
  `couleur` text NOT NULL,
  `commentaire` text NOT NULL,
  `surface_vente` decimal(10,0) NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_PointsVente_nom` (`nom`(255)),
  KEY `FK_PointVente_Createur` (`id_createur`),
  KEY `FK_PointVente_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_PointVente_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_PointVente_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points_vente`
--

LOCK TABLES `points_vente` WRITE;
/*!40000 ALTER TABLE `points_vente` DISABLE KEYS */;
/*!40000 ALTER TABLE `points_vente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sorties`
--

DROP TABLE IF EXISTS `sorties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `classe` text NOT NULL,
  `id_filiere` int(11) DEFAULT '0',
  `id_convention` int(11) DEFAULT '0',
  `id_type_sortie` int(11) DEFAULT '0',
  `id_point_sortie` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Sorties_Filiere` (`id_filiere`),
  KEY `FK_Sorties_Convention` (`id_convention`),
  KEY `FK_Sorties_TypeSortie` (`id_type_sortie`),
  KEY `FK_Sorties_Localites` (`id_point_sortie`),
  KEY `FK_Sorties_Createur` (`id_createur`),
  KEY `FK_Sorties_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_Sorties_Convention` FOREIGN KEY (`id_convention`) REFERENCES `conventions_sorties` (`id`),
  CONSTRAINT `FK_Sorties_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Sorties_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Sorties_Filiere` FOREIGN KEY (`id_filiere`) REFERENCES `filieres_sortie` (`id`),
  CONSTRAINT `FK_Sorties_Localites` FOREIGN KEY (`id_point_sortie`) REFERENCES `localites` (`id`),
  CONSTRAINT `FK_Sorties_TypeSortie` FOREIGN KEY (`id_type_sortie`) REFERENCES `type_sortie` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sorties`
--

LOCK TABLES `sorties` WRITE;
/*!40000 ALTER TABLE `sorties` DISABLE KEYS */;
/*!40000 ALTER TABLE `sorties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_collecte`
--

DROP TABLE IF EXISTS `type_collecte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_collecte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_TypeCollecte_nom` (`nom`(255)),
  KEY `FK_TypesCollecte_Createur` (`id_createur`),
  KEY `FK_TypesCollecte_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_TypesCollecte_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_TypesCollecte_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_collecte`
--

LOCK TABLES `type_collecte` WRITE;
/*!40000 ALTER TABLE `type_collecte` DISABLE KEYS */;
/*!40000 ALTER TABLE `type_collecte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_contenants`
--

DROP TABLE IF EXISTS `type_contenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_contenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `masse` decimal(10,0) NOT NULL DEFAULT '0',
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_TypeContenants_nom` (`nom`(255)),
  KEY `FK_TypesContenants_Createur` (`id_createur`),
  KEY `FK_TypesContenants_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_TypesContenants_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_TypesContenants_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_contenants`
--

LOCK TABLES `type_contenants` WRITE;
/*!40000 ALTER TABLE `type_contenants` DISABLE KEYS */;
INSERT INTO `type_contenants` VALUES (1,'2018-01-03 22:43:26','Roulpratique','Roulpratique en alu.',10,'#019b99',1,1,1,'2018-01-03 22:43:39'),(2,'2018-01-03 22:43:26','diable vert','diable vert ',12,'#dd0c0c',1,1,1,'2018-01-03 22:43:39'),(3,'2018-01-03 22:43:26','roll textille','roll textille',31,'#5037d0',1,1,1,'2018-01-03 22:43:39'),(4,'2018-01-03 22:43:26','caddie','petite cage en fer sur roulettes',11,'#ab26ab',1,1,1,'2018-01-03 22:43:39'),(5,'2018-01-03 22:43:26','cage d3e','cage d3e',39,'#108f99',1,1,1,'2018-01-03 22:43:39'),(6,'2018-01-03 22:43:26','caisse grise','grande caisse grise',3,'#799082',1,1,1,'2018-01-03 22:43:39'),(7,'2018-01-03 22:43:26','petite caisse grise','petite caisse grise',2,'#776161',1,1,1,'2018-01-03 22:43:39');
/*!40000 ALTER TABLE `type_contenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_dechets`
--

DROP TABLE IF EXISTS `type_dechets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_dechets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_TypeDechets_nom` (`nom`(255)),
  KEY `FK_TypesDechets_Createur` (`id_createur`),
  KEY `FK_TypesDechets_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_TypesDechets_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_TypesDechets_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_dechets`
--

LOCK TABLES `type_dechets` WRITE;
/*!40000 ALTER TABLE `type_dechets` DISABLE KEYS */;
INSERT INTO `type_dechets` VALUES (1,'2018-01-03 22:43:26','matériel électrique','matériel électrique','#e87702',1,1,1,'2018-01-03 22:43:40'),(2,'2018-01-03 22:43:26','mobilier','mobilier','#e83c02',1,1,1,'2018-01-03 22:43:40'),(3,'2018-01-03 22:43:26','textiles, accessoires ','textile, accessoires','#db20df',1,1,1,'2018-01-03 22:43:40'),(4,'2018-01-03 22:43:26','jouets','jeux, jouets , comprend aussi les jeux de societés','#80ffff',1,1,1,'2018-01-03 22:43:40'),(5,'2018-01-03 22:43:26','informatique','ordis, ecrans, claviers, autres périphériques info. DEEE. en fait ... ','#e902ff',1,1,1,'2018-01-03 22:43:40'),(6,'2018-01-03 22:43:26','vaisselle','vaisselle, tout états, touts materiaux','#c0c0c0',1,1,1,'2018-01-03 22:43:40'),(7,'2018-01-03 22:43:26','livres','livres magazines journaux ','#3a02e8',1,1,1,'2018-01-03 22:43:40'),(8,'2018-01-03 22:43:26','supports media','cd dvd vinyles cassets minidiscs et consors','#0b28ff',1,1,1,'2018-01-03 22:43:40'),(9,'2018-01-03 22:43:26','bibelots ','bibelots divers objets déco ','#c0c0c0',1,1,1,'2018-01-03 22:43:40'),(10,'2018-01-03 22:43:26','autres','autres','#02d4ff',1,1,1,'2018-01-03 22:43:40'),(11,'2018-01-03 22:43:26','bijoux','bijoux en tout genre, pese peut mais trés bien valorisé','#3fbf9f',1,1,1,'2018-01-03 22:43:40'),(12,'2018-01-03 22:43:26','mobilier en service','mobilier en service','#bb2222',0,1,1,'2018-01-03 22:43:40'),(13,'2018-01-03 22:43:26','mobilier hs','mobilier hs','#bd2d2d',0,1,1,'2018-01-03 22:43:40'),(14,'2018-01-03 22:43:26','la réserve des arts','petit matériel','#00ff40',0,1,1,'2018-01-03 22:43:40'),(15,'2018-01-03 22:43:26','la collecterie','création','#000000',0,1,1,'2018-01-03 22:43:40'),(16,'2018-01-03 22:43:26','de la cave au grenier','création','#000000',0,1,1,'2018-01-03 22:43:40'),(17,'2018-01-03 22:43:26','la ressourcerie créative','création','#000000',0,1,1,'2018-01-03 22:43:40');
/*!40000 ALTER TABLE `type_dechets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_dechets_evac`
--

DROP TABLE IF EXISTS `type_dechets_evac`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_dechets_evac` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_TypeDechetsEvac_nom` (`nom`(255)),
  KEY `FK_TypesDechetsEvac_Createur` (`id_createur`),
  KEY `FK_TypesDechetsEvac_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_TypesDechetsEvac_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_TypesDechetsEvac_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_dechets_evac`
--

LOCK TABLES `type_dechets_evac` WRITE;
/*!40000 ALTER TABLE `type_dechets_evac` DISABLE KEYS */;
INSERT INTO `type_dechets_evac` VALUES (1,'2018-01-03 22:43:27','metaux','férraille alu ect...','#8e33ae',1,1,1,'2018-01-03 22:43:34'),(2,'2018-01-03 22:43:27','deee','déchets d\'équipements électroniques et électroménagers','#000000',1,1,1,'2018-01-03 22:43:34'),(3,'2018-01-03 22:43:27','livres non vendus','livres non vendus en boutique, invendables sur place','#9b4848',1,1,1,'2018-01-03 22:43:34'),(4,'2018-01-03 22:43:27','piles','piles','#925555',1,1,1,'2018-01-03 22:43:34'),(5,'2018-01-03 22:43:27','lampes eco/neons','lampes eco/neons','#000000',1,1,1,'2018-01-03 22:43:34'),(6,'2018-01-03 22:43:27','textile','textile','#846060',1,1,1,'2018-01-03 22:43:34'),(7,'2018-01-03 22:43:27','bois','planches ','#933e3e',1,1,1,'2018-01-03 22:43:34'),(8,'2018-01-03 22:43:27','mobilier hors service','mobilier hors service','#c47878',1,1,1,'2018-01-03 22:43:34'),(9,'2018-01-03 22:43:27','assises','assises','#e98e8e',1,1,1,'2018-01-03 22:43:34'),(10,'2018-01-03 22:43:27','plans de travail','plans de travail','#c02d2d',1,1,1,'2018-01-03 22:43:34'),(11,'2018-01-03 22:43:27','cables','cables','#aa3a3a',1,1,1,'2018-01-03 22:43:34');
/*!40000 ALTER TABLE `type_dechets_evac` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_sortie`
--

DROP TABLE IF EXISTS `type_sortie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `type_sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_TypeSortie_nom` (`nom`(255)),
  KEY `FK_TypeSortie_Createur` (`id_createur`),
  KEY `FK_TypesSortie_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_TypeSortie_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_TypesSortie_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_sortie`
--

LOCK TABLES `type_sortie` WRITE;
/*!40000 ALTER TABLE `type_sortie` DISABLE KEYS */;
INSERT INTO `type_sortie` VALUES (1,'2018-01-03 22:43:27','don à un particulier','don d\'objet invendable en boutique à un particulier','#84a8c6',1,1,1,'2018-01-03 22:43:37'),(2,'2018-01-03 22:43:27','don à une association','don d\'objets à une asso. sans convention','#c6a13d',1,1,1,'2018-01-03 22:43:37'),(3,'2018-01-03 22:43:27','don à un artiste','objets, materiaux donnés à un artiste','#bf6b1f',1,1,1,'2018-01-03 22:43:37'),(4,'2018-01-03 22:43:27','don à un salarié','don à un salarié','#bb3333',1,1,1,'2018-01-03 22:43:37'),(5,'2018-01-03 22:43:27','don à un bénévole','don à un bénévole','#155175',1,1,1,'2018-01-03 22:43:37'),(6,'2018-01-03 22:43:27','aménagement des locaux','aménagement des locaux','#da4747',1,1,1,'2018-01-03 22:43:37'),(7,'2018-01-03 22:43:27','ateliers d\'animation','ateliers d\'animation','#9a15e3',1,1,1,'2018-01-03 22:43:37');
/*!40000 ALTER TABLE `type_sortie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_poubelles`
--

DROP TABLE IF EXISTS `types_poubelles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `types_poubelles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` text NOT NULL,
  `description` text NOT NULL,
  `masse_bac` decimal(10,0) NOT NULL DEFAULT '0',
  `ultime` tinyint(1) NOT NULL DEFAULT '1',
  `couleur` text NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_TypesPoubelles_nom` (`nom`(255)),
  KEY `FK_TypesPoubelle_Createur` (`id_createur`),
  KEY `FK_TypesPoubelle_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_TypesPoubelle_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_TypesPoubelle_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_poubelles`
--

LOCK TABLES `types_poubelles` WRITE;
/*!40000 ALTER TABLE `types_poubelles` DISABLE KEYS */;
INSERT INTO `types_poubelles` VALUES (1,'2018-01-03 22:43:25','petite poubelle verte','tout venant bac 100 litres',22,1,'#00aa55',1,1,1,'2018-01-03 22:43:36'),(2,'2018-01-03 22:43:25','moyenne poubelle verte','moyenne poubelle verte',27,1,'#00aa55',1,1,1,'2018-01-03 22:43:36'),(3,'2018-01-03 22:43:25','grosse poubelle verte','grosse poubelle verte',32,1,'#00aa55',1,1,1,'2018-01-03 22:43:36'),(4,'2018-01-03 22:43:25','petite poubelle jaune','a',19,0,'#ffb506',1,1,1,'2018-01-03 22:43:36'),(5,'2018-01-03 22:43:25','moyenne poubelle jaune','emballages bac de 200 l',27,0,'#ffb506',1,1,1,'2018-01-03 22:43:36'),(6,'2018-01-03 22:43:25','grosse poubelle jaune','Emballages, bac de 400 litres.',32,0,'#ffb506',1,1,1,'2018-01-03 22:43:36'),(7,'2018-01-03 22:43:25','petite poubelle blanche','verre , bac de 100l',20,0,'#c0c0c0',1,1,1,'2018-01-03 22:43:36');
/*!40000 ALTER TABLE `types_poubelles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `niveau` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `mail` text NOT NULL,
  `pass` text NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UN_Utilisateur_mail` (`mail`(255)),
  KEY `FK_Utilisateurs_Createur` (`id_createur`),
  KEY `FK_Utilisateurs_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_Utilisateurs_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Utilisateurs_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (1,'2018-01-03 22:43:28','c1c2c3v1v2v3s1bighljk','admininstrateur','cher expérimentateur','admin@oressource.org','81dc9bdb52d04dc20036dbd8313ed055',1,1,'2018-01-03 22:43:28');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendus`
--

DROP TABLE IF EXISTS `vendus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_vente` int(11) NOT NULL,
  `id_type_dechet` int(11) NOT NULL,
  `id_objet` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(9,2) NOT NULL,
  `remboursement` decimal(9,2) NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Vendus_Ventes` (`id_vente`),
  KEY `FK_Vendus_TypesDechets` (`id_type_dechet`),
  KEY `FK_Vendus_Objet` (`id_objet`),
  KEY `FK_Vendus_Createur` (`id_createur`),
  KEY `FK_Vendus_Editeur` (`id_last_hero`),
  CONSTRAINT `FK_Vendus_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Vendus_Editeur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Vendus_Objet` FOREIGN KEY (`id_objet`) REFERENCES `grille_objets` (`id`),
  CONSTRAINT `FK_Vendus_TypesDechets` FOREIGN KEY (`id_type_dechet`) REFERENCES `type_dechets` (`id`),
  CONSTRAINT `FK_Vendus_Ventes` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendus`
--

LOCK TABLES `vendus` WRITE;
/*!40000 ALTER TABLE `vendus` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventes`
--

DROP TABLE IF EXISTS `ventes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_moyen_paiement` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentaire` text NOT NULL,
  `id_point_vente` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL DEFAULT '0',
  `id_last_hero` int(11) NOT NULL DEFAULT '0',
  `last_hero_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Ventes_MoyenPaiement` (`id_moyen_paiement`),
  KEY `FK_Ventes_PointVente` (`id_point_vente`),
  KEY `FK_Ventes_Createur` (`id_createur`),
  KEY `FK_Ventes_Edicteur` (`id_last_hero`),
  CONSTRAINT `FK_Ventes_Createur` FOREIGN KEY (`id_createur`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Ventes_Edicteur` FOREIGN KEY (`id_last_hero`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_Ventes_MoyenPaiement` FOREIGN KEY (`id_moyen_paiement`) REFERENCES `moyens_paiement` (`id`),
  CONSTRAINT `FK_Ventes_PointVente` FOREIGN KEY (`id_point_vente`) REFERENCES `points_vente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventes`
--

LOCK TABLES `ventes` WRITE;
/*!40000 ALTER TABLE `ventes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-03 23:49:29
