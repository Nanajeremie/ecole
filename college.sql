-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 02 oct. 2021 à 13:55
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `college`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnements`
--

DROP TABLE IF EXISTS `abonnements`;
CREATE TABLE IF NOT EXISTS `abonnements` (
  `ID_ABONNEMENT` int(11) NOT NULL AUTO_INCREMENT,
  `ID_MOIS` int(11) NOT NULL,
  `MATRICULE` varchar(50) NOT NULL,
  `ID_ANNEE` int(11) NOT NULL,
  `DATE_ABONNEMENT` datetime NOT NULL,
  `DATE_DEBUT_ABONNEMENT` datetime DEFAULT NULL,
  `DATE_FIN_ABONNEMENT` datetime DEFAULT NULL,
  `NUMBER_DAYS` int(11) NOT NULL,
  `COST_FEES` decimal(10,0) NOT NULL,
  `STATUT` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_ABONNEMENT`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `abonnements`
--

INSERT INTO `abonnements` (`ID_ABONNEMENT`, `ID_MOIS`, `MATRICULE`, `ID_ANNEE`, `DATE_ABONNEMENT`, `DATE_DEBUT_ABONNEMENT`, `DATE_FIN_ABONNEMENT`, `NUMBER_DAYS`, `COST_FEES`, `STATUT`) VALUES
(1, 5, 'bs00002', 1, '2021-04-27 01:31:56', '2021-05-05 18:00:00', '2021-05-30 18:00:00', 25, '6250', 'actif'),
(2, 6, 'bs00002', 1, '2021-04-27 01:33:28', '2021-06-05 18:00:00', '2021-06-25 18:00:00', 20, '5000', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `administration`
--

DROP TABLE IF EXISTS `administration`;
CREATE TABLE IF NOT EXISTS `administration` (
  `ID_ADMINISTRATION` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(11) NOT NULL,
  `NOM` varchar(50) NOT NULL,
  `PRENOM` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PHONE` varchar(50) NOT NULL,
  `FUNCTION` varchar(200) NOT NULL,
  PRIMARY KEY (`ID_ADMINISTRATION`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `annee_scolaire`
--

DROP TABLE IF EXISTS `annee_scolaire`;
CREATE TABLE IF NOT EXISTS `annee_scolaire` (
  `ID_ANNEE` int(11) NOT NULL AUTO_INCREMENT,
  `DATE_INI` date NOT NULL,
  `DATE_FIN` date DEFAULT NULL,
  `DATE_FIN_INSCRIPTION` date NOT NULL,
  `FIN_VERSEMENT_1` date NOT NULL,
  `FIN_VERSEMENT_2` date NOT NULL,
  `FIN_VERSEMENT_3` date NOT NULL,
  PRIMARY KEY (`ID_ANNEE`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annee_scolaire`
--

INSERT INTO `annee_scolaire` (`ID_ANNEE`, `DATE_INI`, `DATE_FIN`, `DATE_FIN_INSCRIPTION`, `FIN_VERSEMENT_1`, `FIN_VERSEMENT_2`, `FIN_VERSEMENT_3`) VALUES
(4, '2021-09-28', NULL, '2021-11-30', '2021-11-30', '2021-12-30', '2022-01-30');

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `ID_ANSWER` int(30) NOT NULL AUTO_INCREMENT,
  `SURVEY_ID` int(30) NOT NULL,
  `ID_USER` int(30) NOT NULL,
  `ANSWER` text NOT NULL,
  `QUESTION_ID` int(30) NOT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_ANSWER`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`ID_ANSWER`, `SURVEY_ID`, `ID_USER`, `ANSWER`, `QUESTION_ID`, `DATE_CREATED`, `ID_ANNEE`) VALUES
(1, 2, 9, 'The course was very sexi', 1, '2021-04-27 07:07:56', 1),
(2, 2, 9, 'bugCL', 2, '2021-04-27 07:07:56', 1),
(3, 2, 9, 'It is good', 3, '2021-04-27 07:07:57', 1);

-- --------------------------------------------------------

--
-- Structure de la table `bourse`
--

DROP TABLE IF EXISTS `bourse`;
CREATE TABLE IF NOT EXISTS `bourse` (
  `ID_BOURSE` int(11) NOT NULL AUTO_INCREMENT,
  `TAUX` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`ID_BOURSE`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `bourse`
--

INSERT INTO `bourse` (`ID_BOURSE`, `TAUX`) VALUES
(1, '25'),
(2, '50'),
(3, '75'),
(4, '100'),
(6, '0');

-- --------------------------------------------------------

--
-- Structure de la table `cantine`
--

DROP TABLE IF EXISTS `cantine`;
CREATE TABLE IF NOT EXISTS `cantine` (
  `ID_MOIS` int(11) NOT NULL AUTO_INCREMENT,
  `MOIS` varchar(50) DEFAULT NULL,
  `PRIX` decimal(10,0) DEFAULT NULL,
  `ANNEE_SCOLAIRE` varchar(20) DEFAULT NULL,
  `DATE_CREATION` datetime NOT NULL,
  `DATE_LIMITE_PAIEMENT` datetime DEFAULT NULL,
  `COST_PER_DAY` decimal(10,0) NOT NULL,
  PRIMARY KEY (`ID_MOIS`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `cantine`
--

INSERT INTO `cantine` (`ID_MOIS`, `MOIS`, `PRIX`, `ANNEE_SCOLAIRE`, `DATE_CREATION`, `DATE_LIMITE_PAIEMENT`, `COST_PER_DAY`) VALUES
(1, 'January', '6000', '1', '2021-04-27 00:56:07', '0000-00-00 00:00:00', '250'),
(2, 'February', '3250', '1', '2021-04-27 00:56:07', '2021-02-05 18:00:00', '250'),
(3, 'March', '6250', '1', '2021-04-27 00:56:07', '2021-03-05 18:00:00', '250'),
(4, 'April', '6250', '1', '2021-04-27 00:56:07', '2021-04-05 18:00:00', '250'),
(5, 'May', '6250', '1', '2021-04-27 00:56:07', '2021-05-05 18:00:00', '250'),
(6, 'June', '6500', '1', '2021-04-27 00:56:07', '2021-06-05 18:00:00', '250'),
(7, 'July', '0', '1', '2021-04-27 00:56:07', '2021-07-05 18:00:00', '250'),
(8, 'August', '0', '1', '2021-04-27 00:56:07', '2021-08-05 18:00:00', '250'),
(9, 'September', '0', '1', '2021-04-27 00:56:07', '2021-09-05 18:00:00', '250'),
(10, 'October', '4750', '1', '2021-04-27 00:56:07', '2021-10-05 18:00:00', '250'),
(11, 'November', '6250', '1', '2021-04-27 00:56:07', '2021-11-05 18:00:00', '250'),
(12, 'December', '5000', '1', '2021-04-27 00:56:07', '2021-12-05 18:00:00', '250'),
(13, 'January', '555', '2', '2021-09-27 16:24:23', '2021-09-27 16:26:00', '300'),
(14, 'February', '555', '2', '2021-09-27 16:24:23', '2021-09-17 16:24:00', '300'),
(15, 'March', '55', '2', '2021-09-27 16:24:23', '2021-09-01 16:23:00', '300'),
(16, 'April', '555', '2', '2021-09-27 16:24:23', '2021-09-01 16:28:00', '300'),
(17, 'May', '55', '2', '2021-09-27 16:24:23', '2021-09-10 16:24:00', '300'),
(18, 'June', '55', '2', '2021-09-27 16:24:23', '2021-09-16 16:23:00', '300'),
(19, 'July', '565', '2', '2021-09-27 16:24:23', '2021-09-03 16:24:00', '300'),
(20, 'August', '5', '2', '2021-09-27 16:24:23', '2021-09-02 16:24:00', '300'),
(21, 'September', '55', '2', '2021-09-27 16:24:23', '2021-09-30 16:23:00', '300'),
(22, 'October', '566', '2', '2021-09-27 16:24:23', '2021-09-17 16:23:00', '300'),
(23, 'November', '55', '2', '2021-09-27 16:24:23', '2021-09-23 16:23:00', '300'),
(24, 'December', '55', '2', '2021-09-27 16:24:23', '2021-09-23 16:23:00', '300');

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `ID_CLASSE` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_CLASSE` varchar(50) DEFAULT NULL,
  `MONTANT_SCOLARITE` int(200) DEFAULT NULL,
  `ID_NIVEAU` int(11) NOT NULL,
  PRIMARY KEY (`ID_CLASSE`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`ID_CLASSE`, `NOM_CLASSE`, `MONTANT_SCOLARITE`, `ID_NIVEAU`) VALUES
(8, '5ieme', 50000, 2);

-- --------------------------------------------------------

--
-- Structure de la table `data_bank`
--

DROP TABLE IF EXISTS `data_bank`;
CREATE TABLE IF NOT EXISTS `data_bank` (
  `ID_DATA_BANK` int(11) NOT NULL AUTO_INCREMENT,
  `MATRICULE` varchar(100) DEFAULT NULL,
  `MODULE` varchar(200) DEFAULT NULL,
  `TITRE` varchar(100) DEFAULT NULL,
  `FILE_NAME` varchar(200) DEFAULT NULL,
  `DESCRIPTION` text,
  `CREATE_DATE` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID_DATA_BANK`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `data_bank`
--

INSERT INTO `data_bank` (`ID_DATA_BANK`, `MATRICULE`, `MODULE`, `TITRE`, `FILE_NAME`, `DESCRIPTION`, `CREATE_DATE`) VALUES
(1, 'bs00002', 'Programming C', 'bjvghghc', 'logo.png', 'mklmklmnknjkl', '2021-09-10 16:51:58');

-- --------------------------------------------------------

--
-- Structure de la table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `ID_DEPARTEMENT` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_DEPARTEMENT` varchar(50) DEFAULT NULL,
  `CHEF_DEPARTEMENT` varchar(50) DEFAULT NULL,
  `DESCRIPTION` text,
  PRIMARY KEY (`ID_DEPARTEMENT`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `department`
--

INSERT INTO `department` (`ID_DEPARTEMENT`, `NOM_DEPARTEMENT`, `CHEF_DEPARTEMENT`, `DESCRIPTION`) VALUES
(1, 'Computer Science', 'KAMBOU Sie Yannick', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your document, click it and a button for layout options appears next to it. When you work on a table, click where you want to add a row or a column, and then click the plus sign. Reading is easier, too, in the new Reading view. You can collapse parts of the document and focus on the text you want. If you need to stop reading before you reach the end, Word remembers where you left off - even on another device.'),
(2, 'Electrical Engineering', 'VALEA', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your document, click it and a button for layout options appears next to it. When you work on a table, click where you want to add a row or a column, and then click the plus sign. Reading is easier, too, in the new Reading view. You can collapse parts of the document and focus on the text you want. If you need to stop reading before you reach the end, Word remembers where you left off - even on another device.');

-- --------------------------------------------------------

--
-- Structure de la table `devoirs`
--

DROP TABLE IF EXISTS `devoirs`;
CREATE TABLE IF NOT EXISTS `devoirs` (
  `ID_DEVOIR` int(11) NOT NULL AUTO_INCREMENT,
  `ID_MODULE` int(11) DEFAULT NULL,
  `DATE_UPLOAD` timestamp NULL DEFAULT NULL,
  `DATE_DEV` datetime DEFAULT NULL,
  `DEVOIR` varchar(250) DEFAULT NULL,
  `POURCENTAGE` decimal(10,0) DEFAULT NULL,
  `STATUT` varchar(50) DEFAULT NULL,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_DEVOIR`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `devoirs`
--

INSERT INTO `devoirs` (`ID_DEVOIR`, `ID_MODULE`, `DATE_UPLOAD`, `DATE_DEV`, `DEVOIR`, `POURCENTAGE`, `STATUT`, `ID_ANNEE`) VALUES
(2, 1, '2021-04-27 06:16:23', '2021-04-20 09:18:00', 'Untitled-7.pdf', '25', 'Active', 1),
(3, 1, '2021-04-27 06:17:22', '2021-04-25 10:19:00', 'Untitled-5 [Recovered].pdf', '40', 'Active', 1),
(4, 1, '2021-04-27 06:29:50', '2021-04-26 08:32:00', 'Untitled-5 [Recovered].pdf', '25', 'Active', 1);

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `CODE_LIVRE` varchar(250) NOT NULL,
  `TITRE` varchar(50) DEFAULT NULL,
  `AUTHEUR` varchar(50) DEFAULT NULL,
  `DATE_EDITION` date DEFAULT NULL,
  `DEPARTMENT` int(11) NOT NULL,
  `BOOK_STATUS` varchar(20) DEFAULT NULL,
  `PICTURE` varchar(200) DEFAULT NULL,
  `DESCRIPTION` text NOT NULL,
  `STATUT` varchar(50) NOT NULL,
  PRIMARY KEY (`CODE_LIVRE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`CODE_LIVRE`, `TITRE`, `AUTHEUR`, `DATE_EDITION`, `DEPARTMENT`, `BOOK_STATUS`, `PICTURE`, `DESCRIPTION`, `STATUT`) VALUES
('20202000514', 'Machivdfne Learning', 'wert', '2021-04-27', 2, 'good', 'IMG_20210328_153400_9.jpg', '', 'free'),
('202020005146', 'Machine Learning', 'Lougoudoro', '2021-04-27', 0, 'new', 'amp.png', '', 'free'),
('frert', 'rererw', 'dffde', '2021-04-01', 1, '', 'IMG_4487.JPG', '', 'free');

-- --------------------------------------------------------

--
-- Structure de la table `enseigner`
--

DROP TABLE IF EXISTS `enseigner`;
CREATE TABLE IF NOT EXISTS `enseigner` (
  `ID_ENSEIGNER` int(11) NOT NULL AUTO_INCREMENT,
  `ID_MODULE` int(11) NOT NULL,
  `ID_PROFESSEUR` int(11) NOT NULL,
  `ANNEE` int(4) DEFAULT NULL,
  PRIMARY KEY (`ID_ENSEIGNER`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `enseigner`
--

INSERT INTO `enseigner` (`ID_ENSEIGNER`, `ID_MODULE`, `ID_PROFESSEUR`, `ANNEE`) VALUES
(1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `entretien`
--

DROP TABLE IF EXISTS `entretien`;
CREATE TABLE IF NOT EXISTS `entretien` (
  `ID_ENTRETIEN` int(11) NOT NULL AUTO_INCREMENT,
  `ID_NEW_ETUDIANT` int(11) NOT NULL,
  `DATE_ENTRETIEN` date NOT NULL,
  `HEURE` time NOT NULL,
  PRIMARY KEY (`ID_ENTRETIEN`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `entretien`
--

INSERT INTO `entretien` (`ID_ENTRETIEN`, `ID_NEW_ETUDIANT`, `DATE_ENTRETIEN`, `HEURE`) VALUES
(1, 4, '2021-05-06', '12:00:00'),
(2, 3, '2021-04-30', '15:00:00'),
(3, 5, '2021-04-28', '08:00:00'),
(4, 2, '2021-05-06', '18:00:00'),
(5, 6, '2021-05-08', '18:00:00'),
(6, 7, '2021-09-30', '21:02:00');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `MATRICULE` varchar(25) NOT NULL,
  `PRENOM` varchar(100) DEFAULT NULL,
  `NOM` varchar(50) NOT NULL,
  `SEXE` varchar(20) DEFAULT NULL,
  `DATE_NAISSANCE` date DEFAULT NULL,
  `CNIB` varchar(25) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `NUM_URGENCE` varchar(20) DEFAULT NULL,
  `NOM_PERE` varchar(150) DEFAULT NULL,
  `PROFESSION_PERE` varchar(250) DEFAULT NULL,
  `NOM_MERE` varchar(150) DEFAULT NULL,
  `PROFESSION_MERE` varchar(250) DEFAULT NULL,
  `STATUT` varchar(50) NOT NULL DEFAULT '1',
  PRIMARY KEY (`MATRICULE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`MATRICULE`, `PRENOM`, `NOM`, `SEXE`, `DATE_NAISSANCE`, `CNIB`, `TELEPHONE`, `EMAIL`, `NUM_URGENCE`, `NOM_PERE`, `PROFESSION_PERE`, `NOM_MERE`, `PROFESSION_MERE`, `STATUT`) VALUES
('CSCI00001', 'f2ewf32f', 'ewfe3w', 'Masculin', '2021-09-23', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '0'),
('CSCI00002', 'f2ewf32f', 'ewfe3w', 'Masculin', '2021-09-23', 'f344gfrrrrrrr', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'cssc', 'zzz', '0'),
('CSCI00003', 'f2ewf32f', 'ewfe3w', 'Masculin', '2021-09-29', '', '', '', '', 'xxascs', 'cscscs', 'zzz', 'cssc', '0'),
('CSCI00004', 'f2ewf32f', 'ewfe3w', 'Masculin', '2021-09-15', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'oooo', 'zzz', '1'),
('CSCI00005', 'f2ewf32f', 'ewfe3ww', 'Masculin', '2021-09-29', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '1'),
('CSCI00006', 'Jeremie', 'Nana', 'Masculin', '2021-09-24', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '0'),
('CSCI00007', 'f2ewf32f', 'ewfe3w', 'Masculin', '2021-09-03', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '1'),
('CSCI00008', 'f2ewf32f', 'ewfe3w', 'Masculin', '2021-09-03', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '1'),
('CSCI00009', 'f2ewf32f', 'ewfe3wt', 'Feminin', '2021-09-29', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '1'),
('CSCI00010', 'f2ewf32f', 'ewfe3wttg', 'Feminin', '2021-09-29', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'zzz', 'cssc', '1'),
('CSCI00011', 'f2ewf32frgr', 'ewfe3ww', 'Masculin', '2021-09-22', 'f344gf', '4272', 'nan@gmail.com', '2771', 'xxascs', 'cscscs', 'cssc', 'cssc', '1'),
('CSCI00012', 'f2ewf32frgr', 'ewfe3www', 'Masculin', '2021-09-22', 'f344gf', '4272', 'nan@gmail.com', 'cssc', 'cssc', 'cscscs', 'cssc', 'cssc', '1');

-- --------------------------------------------------------

--
-- Structure de la table `filieres`
--

DROP TABLE IF EXISTS `filieres`;
CREATE TABLE IF NOT EXISTS `filieres` (
  `ID_FILIERE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DEPARTEMENT` int(11) NOT NULL,
  `NOM_FILIERE` varchar(50) DEFAULT NULL,
  `DESCRIPTION` text,
  PRIMARY KEY (`ID_FILIERE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `historique_bourse`
--

DROP TABLE IF EXISTS `historique_bourse`;
CREATE TABLE IF NOT EXISTS `historique_bourse` (
  `ID_HISTORIQUE_BOURSE` int(11) NOT NULL AUTO_INCREMENT,
  `MATRICULE` varchar(25) NOT NULL,
  `ID_BOURSE` int(11) NOT NULL,
  `MOTIVATION` text NOT NULL,
  `OBSERVATION` text NOT NULL,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_HISTORIQUE_BOURSE`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `historique_bourse`
--

INSERT INTO `historique_bourse` (`ID_HISTORIQUE_BOURSE`, `MATRICULE`, `ID_BOURSE`, `MOTIVATION`, `OBSERVATION`, `ID_ANNEE`) VALUES
(1, 'bs00001', 2, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 1),
(2, 'bs00002', 1, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 1),
(3, 'bs00003', 3, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 1),
(4, 'bs00004', 2, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your documen', 1),
(5, 'bs00005', 4, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your document', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your document', 2);

-- --------------------------------------------------------

--
-- Structure de la table `historique_payement`
--

DROP TABLE IF EXISTS `historique_payement`;
CREATE TABLE IF NOT EXISTS `historique_payement` (
  `ID_PAYEMENT` int(11) NOT NULL AUTO_INCREMENT,
  `ID_INSCRIPTION` int(11) NOT NULL,
  `MONTANT` decimal(10,2) NOT NULL,
  `DATE_PAYEMENT` datetime NOT NULL,
  `USER` int(11) NOT NULL,
  PRIMARY KEY (`ID_PAYEMENT`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `historique_payement`
--

INSERT INTO `historique_payement` (`ID_PAYEMENT`, `ID_INSCRIPTION`, `MONTANT`, `DATE_PAYEMENT`, `USER`) VALUES
(1, 1, '250000.00', '2021-04-27 00:36:07', 3),
(2, 1, '20000.00', '2021-04-27 00:44:42', 3),
(3, 2, '412500.00', '2021-04-27 01:26:37', 3),
(4, 3, '120000.00', '2021-04-27 01:28:11', 3),
(5, 4, '300000.00', '2021-04-27 01:28:52', 3),
(6, 5, '29999.00', '2021-04-27 07:32:49', 3),
(7, 6, '125000.00', '2021-09-27 16:19:17', 3),
(8, 5, '245001.00', '2021-09-27 16:50:56', 3),
(9, 6, '0.00', '2021-09-27 16:52:53', 3),
(10, 7, '100000.00', '2021-09-27 16:58:34', 3),
(11, 8, '50000.00', '2021-09-27 16:59:27', 3);

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
  `ID_INSCRIPTION` int(11) NOT NULL AUTO_INCREMENT,
  `ID_BOURSE` int(11) NOT NULL,
  `MATRICULE` varchar(25) NOT NULL,
  `ID_CLASSE` int(11) NOT NULL,
  `DATE_INSCRIPTION` timestamp NULL DEFAULT NULL,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_INSCRIPTION`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`ID_INSCRIPTION`, `ID_BOURSE`, `MATRICULE`, `ID_CLASSE`, `DATE_INSCRIPTION`, `ID_ANNEE`) VALUES
(9, 1, 'CSCI00006', 8, '2021-09-29 15:25:49', 4),
(10, 1, 'CSCI00001', 8, '2021-09-29 15:27:40', 4),
(11, 1, 'CSCI00002', 8, '2021-09-29 15:31:14', 4),
(12, 1, 'CSCI00003', 8, '2021-09-29 15:44:11', 4),
(13, 1, 'CSCI00004', 8, '2021-09-29 16:00:21', 4),
(14, 1, 'CSCI00005', 8, '2021-09-29 16:01:43', 4),
(15, 1, 'CSCI00006', 8, '2021-09-29 16:18:51', 4),
(16, 1, 'CSCI00007', 8, '2021-09-29 16:18:55', 4),
(17, 1, 'CSCI00008', 8, '2021-09-29 16:18:59', 4),
(18, 1, 'CSCI00009', 8, '2021-09-29 16:29:43', 4),
(19, 1, 'CSCI00010', 8, '2021-09-29 16:31:13', 4),
(20, 1, 'CSCI00011', 8, '2021-09-29 20:02:15', 4),
(21, 1, 'CSCI00012', 8, '2021-09-29 20:02:59', 4);

-- --------------------------------------------------------

--
-- Structure de la table `louer`
--

DROP TABLE IF EXISTS `louer`;
CREATE TABLE IF NOT EXISTS `louer` (
  `ID_LOUER` int(11) NOT NULL AUTO_INCREMENT,
  `MATRICULE` varchar(50) NOT NULL,
  `CODE_LIVRE` varchar(250) NOT NULL,
  `DATE_EMPRUNT` date DEFAULT NULL,
  `DATE_REMISE` date DEFAULT NULL,
  `ETAT_EMPRUNT` varchar(50) DEFAULT NULL,
  `ETAT_RETOUR` varchar(50) DEFAULT NULL,
  `STATUT` varchar(50) NOT NULL,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_LOUER`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `louer`
--

INSERT INTO `louer` (`ID_LOUER`, `MATRICULE`, `CODE_LIVRE`, `DATE_EMPRUNT`, `DATE_REMISE`, `ETAT_EMPRUNT`, `ETAT_RETOUR`, `STATUT`, `ID_ANNEE`) VALUES
(1, 'bs00001', '20202000514', '2021-04-27', '2021-05-11', 'New', NULL, 'active', 0),
(2, 'bs00002', 'frert', '2021-04-27', '2021-05-11', 'good', '', 'active', 1);

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `ID_MODULE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CLASSE` int(11) NOT NULL,
  `ID_SEMESTRE` int(11) NOT NULL,
  `NOM_MODULE` varchar(50) DEFAULT NULL,
  `VOLUME_HORAIRE` int(11) DEFAULT NULL,
  `DESCRIPTION` text,
  PRIMARY KEY (`ID_MODULE`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`ID_MODULE`, `ID_CLASSE`, `ID_SEMESTRE`, `NOM_MODULE`, `VOLUME_HORAIRE`, `DESCRIPTION`) VALUES
(1, 1, 7, 'Programming C', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them.'),
(2, 1, 7, 'English', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them.'),
(3, 2, 7, 'Python', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them.'),
(4, 2, 7, 'Algorithm', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them.'),
(5, 1, 2, 'Data Structure', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them.'),
(6, 1, 2, 'DataBase', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your document, click it and a button for layout options appears next to it. When you work on a table, click where you want to add a row or a column, and then click the plus sign. Reading is easier, too, in the new Reading view. You can collapse parts of the document and focus on the text you want. If you need to stop reading before you reach the end, Word remembers where you left off - even on another device.'),
(8, 2, 2, 'Programming arduin', 6, 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme. When you apply styles, your headings change to match the new theme. Save time in Word with new buttons that show up where you need them. To change the way a picture fits in your document, click it and a button for layout options appears next to it. When you work on a table, click where you want to add a row or a column, and then click the plus sign. Reading is easier, too, in the new Reading view. You can collapse parts of the document and focus on the text you want. If you need to stop reading before you reach the end, Word remembers where you left off - even on another device.');

-- --------------------------------------------------------

--
-- Structure de la table `newetudiant`
--

DROP TABLE IF EXISTS `newetudiant`;
CREATE TABLE IF NOT EXISTS `newetudiant` (
  `ID_NEW_ETUDIANT` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(50) NOT NULL,
  `PRENOM` varchar(100) DEFAULT NULL,
  `SEXE` varchar(20) DEFAULT NULL,
  `DATE_NAISSANCE` date DEFAULT NULL,
  `NOM_PERE` varchar(250) DEFAULT NULL,
  `PERE_PROFESSION` varchar(250) DEFAULT NULL,
  `NOM_MERE` varchar(250) DEFAULT NULL,
  `MERE_PROFESSION` varchar(250) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `CLASSE` int(11) DEFAULT NULL,
  `BOURSE` int(11) DEFAULT NULL,
  `CNIB` varchar(25) DEFAULT NULL,
  `TELEPHONE` varchar(20) DEFAULT NULL,
  `NUM_URGENCE` varchar(20) DEFAULT NULL,
  `ECOLE_ORIGINE` varchar(150) NOT NULL,
  `DIPLOME` varchar(50) NOT NULL,
  `MOYENNE` decimal(10,3) NOT NULL,
  `MOTIVATION` text,
  `RECOMMENDATION` varchar(50) DEFAULT NULL,
  `OBSERVATION` text,
  `D_INSCRIPTION` datetime DEFAULT NULL,
  `STATUT` varchar(20) DEFAULT 'in_progress',
  PRIMARY KEY (`ID_NEW_ETUDIANT`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `newetudiant`
--

INSERT INTO `newetudiant` (`ID_NEW_ETUDIANT`, `NOM`, `PRENOM`, `SEXE`, `DATE_NAISSANCE`, `NOM_PERE`, `PERE_PROFESSION`, `NOM_MERE`, `MERE_PROFESSION`, `EMAIL`, `CLASSE`, `BOURSE`, `CNIB`, `TELEPHONE`, `NUM_URGENCE`, `ECOLE_ORIGINE`, `DIPLOME`, `MOYENNE`, `MOTIVATION`, `RECOMMENDATION`, `OBSERVATION`, `D_INSCRIPTION`, `STATUT`) VALUES
(7, 'ewfe3w', 'f2ewf32f', 'Male', '2005-09-17', NULL, NULL, NULL, NULL, 'nan@gmail.com', 3, NULL, 'f344gf', '4272', '2771', 'g455gh5', 'Bac D', '12.000', NULL, NULL, NULL, '2021-09-27 17:02:49', 'in_meeting');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

DROP TABLE IF EXISTS `niveau`;
CREATE TABLE IF NOT EXISTS `niveau` (
  `ID_NIVEAU` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_NIVEAU` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_NIVEAU`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`ID_NIVEAU`, `NOM_NIVEAU`) VALUES
(1, 'Primaire'),
(2, 'Secondaire');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `ID_NOTE` int(11) NOT NULL AUTO_INCREMENT,
  `MATRICULE` varchar(25) NOT NULL,
  `ID_MODULE` int(11) NOT NULL,
  `ANNEE_SCOLAIRE` varchar(20) DEFAULT NULL,
  `NOTE1` decimal(10,0) DEFAULT NULL,
  `NOTE2` decimal(10,0) DEFAULT NULL,
  `NOTE3` decimal(10,0) DEFAULT NULL,
  `NOTE_ADMINISTRATION` decimal(10,0) DEFAULT NULL,
  `NOTE_PARTICIPATION` decimal(10,0) DEFAULT NULL,
  `SANCTION` decimal(10,2) DEFAULT NULL,
  `MOYENNE` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`ID_NOTE`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `notes`
--

INSERT INTO `notes` (`ID_NOTE`, `MATRICULE`, `ID_MODULE`, `ANNEE_SCOLAIRE`, `NOTE1`, `NOTE2`, `NOTE3`, `NOTE_ADMINISTRATION`, `NOTE_PARTICIPATION`, `SANCTION`, `MOYENNE`) VALUES
(1, 'bs00002', 1, '1', '12', '10', '9', '10', '20', '5.00', NULL),
(2, 'bs00004', 1, '1', '17', '18', '19', '8', '20', '1.00', NULL),
(3, 'bs00002', 1, '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
  `ID_PROFESSEUR` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(11) NOT NULL,
  `NOM_PROF` varchar(50) DEFAULT NULL,
  `PRENOM_PROF` varchar(100) DEFAULT NULL,
  `SEXE_PROF` varchar(20) DEFAULT NULL,
  `DATE_NAISSANCE_PROF` date DEFAULT NULL,
  `CNIB_PROF` varchar(25) DEFAULT NULL,
  `TELEPHONE_PROF` varchar(20) DEFAULT NULL,
  `EMAIL_PROF` varchar(100) DEFAULT NULL,
  `GRADE` varchar(100) DEFAULT NULL,
  `SPECIALITE` text,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_PROFESSEUR`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`ID_PROFESSEUR`, `ID_USER`, `NOM_PROF`, `PRENOM_PROF`, `SEXE_PROF`, `DATE_NAISSANCE_PROF`, `CNIB_PROF`, `TELEPHONE_PROF`, `EMAIL_PROF`, `GRADE`, `SPECIALITE`, `ID_ANNEE`) VALUES
(1, 12, 'KABORE', 'boukary', 'Male', '1995-07-27', '12121212', '33333333', 'boukary@gmail.com', 'master 2', 'click it and a button for layout options appears next to it. When you work on a table, click where you want to add a row or a column, and then click the plus sign. Reading is easier, too, in the new Reading view. You can collapse parts of the document and focus on the text you want. If you need to stop reading before you reach the end, Word remembers where you left off - even on another device.', 0);

-- --------------------------------------------------------

--
-- Structure de la table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
CREATE TABLE IF NOT EXISTS `programmes` (
  `ID_PROGRAMME` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CLASSE` int(11) NOT NULL,
  `DATE_UPLOAD` timestamp NULL DEFAULT NULL,
  `PROGRAMME` varchar(250) DEFAULT NULL,
  `NUM_SEMAINE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_PROGRAMME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `ID_QUESTIONS` int(30) NOT NULL AUTO_INCREMENT,
  `QUESTION` text NOT NULL,
  `FRM_OPTION` text NOT NULL,
  `TYPE` varchar(50) NOT NULL,
  `ORDER_BY` int(11) NOT NULL,
  `SURVEY_ID` int(30) NOT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_QUESTIONS`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`ID_QUESTIONS`, `QUESTION`, `FRM_OPTION`, `TYPE`, `ORDER_BY`, `SURVEY_ID`, `DATE_CREATED`, `ID_ANNEE`) VALUES
(1, 'How was the course ?', '', 'textfield_s', 0, 2, '2021-04-27 07:02:27', 1),
(2, 'Your level of comprehension ?', '{\"AoMYC\":\"Low\",\"bugCL\":\"High\",\"VIpfF\":\"Middle\"}', 'radio_opt', 0, 2, '2021-04-27 07:03:45', 1),
(3, 'What do you think about the teacher?', '', 'textfield_s', 0, 2, '2021-04-27 07:05:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `savecode`
--

DROP TABLE IF EXISTS `savecode`;
CREATE TABLE IF NOT EXISTS `savecode` (
  `ID_SAVECODE` int(11) NOT NULL AUTO_INCREMENT,
  `CODE` int(11) NOT NULL,
  PRIMARY KEY (`ID_SAVECODE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `scolarite`
--

DROP TABLE IF EXISTS `scolarite`;
CREATE TABLE IF NOT EXISTS `scolarite` (
  `ID_SCOLARITE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_INSCRIPTION` int(11) NOT NULL,
  `MONTANT_TOTAL` int(11) DEFAULT NULL,
  `MONTANT_PAYE` int(11) DEFAULT NULL,
  `DATE_LIMITE` date DEFAULT NULL,
  PRIMARY KEY (`ID_SCOLARITE`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `scolarite`
--

INSERT INTO `scolarite` (`ID_SCOLARITE`, `ID_INSCRIPTION`, `MONTANT_TOTAL`, `MONTANT_PAYE`, `DATE_LIMITE`) VALUES
(1, 1, 275000, 275000, '2021-04-27'),
(2, 2, 412500, 412500, '2021-04-27'),
(3, 3, 137500, 120000, '2021-04-27'),
(4, 4, 275000, 250000, '2021-04-27'),
(5, 5, 275000, 275000, '2021-04-27'),
(6, 6, 412500, 125000, '2021-09-27'),
(7, 8, 275000, 150000, '2021-09-27');

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

DROP TABLE IF EXISTS `semestre`;
CREATE TABLE IF NOT EXISTS `semestre` (
  `ID_SEMESTRE` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_SEMESTRE` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_SEMESTRE`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `semestre`
--

INSERT INTO `semestre` (`ID_SEMESTRE`, `NOM_SEMESTRE`) VALUES
(2, 'Semester II'),
(3, 'Semester III'),
(4, 'Semester IV'),
(5, 'Semester V'),
(6, 'Semester VI'),
(7, 'Semester I');

-- --------------------------------------------------------

--
-- Structure de la table `survey_set`
--

DROP TABLE IF EXISTS `survey_set`;
CREATE TABLE IF NOT EXISTS `survey_set` (
  `ID_SURVEYS` int(30) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(200) NOT NULL,
  `SURVEY_DESCRIPTION` text NOT NULL,
  `ID_MODULE` int(11) NOT NULL,
  `ID_USER` int(30) NOT NULL,
  `START_DATE` date NOT NULL,
  `END_DATE` date NOT NULL,
  `DATE_CREATED` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_ANNEE` int(11) NOT NULL,
  PRIMARY KEY (`ID_SURVEYS`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `survey_set`
--

INSERT INTO `survey_set` (`ID_SURVEYS`, `TITLE`, `SURVEY_DESCRIPTION`, `ID_MODULE`, `ID_USER`, `START_DATE`, `END_DATE`, `DATE_CREATED`, `ID_ANNEE`) VALUES
(1, '', 'It is just a test checking that everything runs well', 1, 0, '2021-04-27', '2021-04-30', '2021-04-27 00:07:04', 1),
(2, '', 'Click Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme.', 1, 0, '2021-04-26', '2021-05-13', '2021-04-27 06:52:20', 1),
(3, '', 'erye4tr59e84yClick Insert and then choose the elements you want from the different galleries. Themes and styles also help keep your document coordinated. When you click Design and choose a new Theme, the pictures, charts, and SmartArt graphics change to match your new theme.', 1, 0, '2021-04-28', '2021-05-07', '2021-04-27 06:55:08', 1);

-- --------------------------------------------------------

--
-- Structure de la table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
CREATE TABLE IF NOT EXISTS `timetable` (
  `ID_TIMETABLE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_CLASSE` int(11) NOT NULL,
  `WEEK_NUMBER` int(11) NOT NULL,
  `START_DATE` date NOT NULL,
  `TIMETABLE_FILE` text NOT NULL,
  `ID_ANNEE` int(11) NOT NULL,
  `DATE_INSERTION` date NOT NULL,
  PRIMARY KEY (`ID_TIMETABLE`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `timetable`
--

INSERT INTO `timetable` (`ID_TIMETABLE`, `ID_CLASSE`, `WEEK_NUMBER`, `START_DATE`, `TIMETABLE_FILE`, `ID_ANNEE`, `DATE_INSERTION`) VALUES
(1, 3, 1, '2021-04-27', 'Firefox.pdf', 1, '2021-04-27'),
(2, 1, 3, '2021-04-27', '3-SysML.pdf', 1, '2021-04-27');

-- --------------------------------------------------------

--
-- Structure de la table `type_bac`
--

DROP TABLE IF EXISTS `type_bac`;
CREATE TABLE IF NOT EXISTS `type_bac` (
  `ID_TYPE_BAC` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_TYPE_BAC` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_TYPE_BAC`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `type_bac`
--

INSERT INTO `type_bac` (`ID_TYPE_BAC`, `NOM_TYPE_BAC`) VALUES
(1, 'Bac C'),
(2, 'Bac D'),
(3, 'Bac F'),
(4, 'Bac G4');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ID_USER` int(11) NOT NULL AUTO_INCREMENT,
  `PASSWORD` varchar(50) DEFAULT NULL,
  `USERNAME` varchar(25) DEFAULT NULL,
  `DROITS` varchar(20) DEFAULT NULL,
  `PROFILE_PIC` text NOT NULL,
  `USER_STATUS` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_USER`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`ID_USER`, `PASSWORD`, `USERNAME`, `DROITS`, `PROFILE_PIC`, `USER_STATUS`) VALUES
(1, 'admin', 'admin', 'admin', 'user_24px.png', 'enable'),
(3, 'Aissatou', 'Aissatou', 'secretary', 'WIN_20201018_17_04_16_Pro.jpg', 'enable'),
(4, 'Kambou', 'Kambou', 'chef_department', 'baground.png', 'disabled4'),
(5, 'Kambou', 'Yannick', 'chef_department', 'WIN_20210216_10_52_26_Pro.jpg', 'enable'),
(6, 'Yaro', 'Yaro', 'chef_scolarite', 'WIN_20210301_22_47_49_Pro.jpg', 'enable'),
(7, 'Rodrigue', 'Rodrigue', 'dean', '16063267528593072509695918339773.jpeg', 'enable'),
(8, 'bs00001', 'bs00001', 'student', 'user_24px.png', ''),
(9, 'bs00002', 'bs00002', 'student', 'user_24px.png', 'enable'),
(10, 'bs00003', 'bs00003', 'student', 'user_24px.png', 'enable'),
(11, 'bs00004', 'bs00004', 'student', 'user_24px.png', 'enable'),
(12, 'KABOREboukary', 'KABOREboukary', 'teacher', 'IMG_4467.JPG', ''),
(13, 'bs00005', 'bs00005', 'student', 'user_24px.png', 'enable');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
