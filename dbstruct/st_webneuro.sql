-- MySQL dump 10.11
--
-- Host: localhost    Database: webneuro
-- ------------------------------------------------------
-- Server version	5.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `webneuro`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `webneuro` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;

USE `webneuro`;

--
-- Table structure for table `clientlog`
--

DROP TABLE IF EXISTS `clientlog`;
CREATE TABLE `clientlog` (
  `idlog` int(11) NOT NULL auto_increment,
  `idhagent` smallint(11) default NULL,
  `idclient` int(11) default NULL,
  `idwebclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idlog`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webanimbuild`
--

DROP TABLE IF EXISTS `webanimbuild`;
CREATE TABLE `webanimbuild` (
  `idanimbuild` int(11) NOT NULL auto_increment,
  `idwebanimjob` int(11) NOT NULL default '0',
  `idshop` int(11) default NULL,
  `animdate1` date default '0000-00-00',
  `animactivite` varchar(255) collate latin1_general_ci default NULL,
  `animnombre` tinyint(2) default NULL,
  `metat` int(5) default '0',
  `sexe` char(3) collate latin1_general_ci default NULL,
  `animin1` time default NULL,
  `animout1` time default NULL,
  `animin2` time default NULL,
  `animout2` time default NULL,
  `datecommande` datetime NOT NULL default '0000-00-00 00:00:00',
  `bondecommande` varchar(55) collate latin1_general_ci NOT NULL default '',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimbuild`),
  KEY `idvipjob` (`idwebanimjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webanimjob`
--

DROP TABLE IF EXISTS `webanimjob`;
CREATE TABLE `webanimjob` (
  `idwebanimjob` int(11) NOT NULL auto_increment,
  `idhagent` smallint(11) default NULL,
  `idclient` int(11) default NULL,
  `idwebclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `shopselection` varchar(255) collate latin1_general_ci NOT NULL default '',
  `shopselectionsearch` varchar(255) collate latin1_general_ci NOT NULL default '',
  `shopselectionnew` varchar(255) collate latin1_general_ci NOT NULL default '',
  `shophistorique` varchar(255) collate latin1_general_ci NOT NULL default '',
  `isnew` tinyint(4) NOT NULL default '0',
  `reference` varchar(255) collate latin1_general_ci default NULL,
  `noteprest` mediumtext collate latin1_general_ci NOT NULL,
  `etat` int(5) default '0',
  `datein` date default NULL,
  `dateout` date default NULL,
  `notejob` mediumtext collate latin1_general_ci,
  `datedevis` date default NULL,
  `datecommande` datetime NOT NULL default '0000-00-00 00:00:00',
  `bondecommande` varchar(55) collate latin1_general_ci NOT NULL default '',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `facnum` varchar(15) collate latin1_general_ci default NULL,
  `facnumtemp` varchar(15) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`idwebanimjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webclient`
--

DROP TABLE IF EXISTS `webclient`;
CREATE TABLE `webclient` (
  `idwebclient` int(11) NOT NULL auto_increment,
  `codeclient` int(10) default NULL,
  `societe` varchar(60) collate latin1_general_ci default NULL,
  `qualite` varchar(10) collate latin1_general_ci default NULL,
  `cprenom` varchar(30) collate latin1_general_ci default NULL,
  `cnom` varchar(30) collate latin1_general_ci default NULL,
  `fonction` varchar(30) collate latin1_general_ci default NULL,
  `langue` varchar(10) collate latin1_general_ci default NULL,
  `adresse` varchar(60) collate latin1_general_ci default NULL,
  `cp` varchar(10) collate latin1_general_ci default NULL,
  `ville` varchar(30) collate latin1_general_ci default NULL,
  `pays` varchar(30) collate latin1_general_ci default NULL,
  `email` varchar(50) collate latin1_general_ci default NULL,
  `notes` mediumtext collate latin1_general_ci,
  `tva` varchar(20) collate latin1_general_ci default NULL,
  `astva` char(2) collate latin1_general_ci default '4',
  `codetva` char(3) collate latin1_general_ci default 'BE',
  `codecompta` varchar(15) collate latin1_general_ci default NULL,
  `tel` varchar(15) collate latin1_general_ci default NULL,
  `fax` varchar(15) collate latin1_general_ci default NULL,
  `password` varchar(15) collate latin1_general_ci default NULL,
  `secteur` varchar(10) collate latin1_general_ci NOT NULL default '0',
  `etat` int(4) NOT NULL default '0',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idwebclient`),
  KEY `codeclient` (`codeclient`),
  KEY `societe` (`societe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webmerchbuild`
--

DROP TABLE IF EXISTS `webmerchbuild`;
CREATE TABLE `webmerchbuild` (
  `idanimbuild` int(11) NOT NULL auto_increment,
  `idwebanimjob` int(11) NOT NULL default '0',
  `idshop` int(11) default NULL,
  `animdate1` date default '0000-00-00',
  `animdate2` date default '0000-00-00',
  `animdays` varchar(55) collate latin1_general_ci default NULL,
  `animactivite` varchar(255) collate latin1_general_ci default NULL,
  `animnombre` tinyint(2) default NULL,
  `metat` int(5) default '0',
  `sexe` char(3) collate latin1_general_ci default NULL,
  `animin1` time default NULL,
  `animout1` time default NULL,
  `animin2` time default NULL,
  `animout2` time default NULL,
  `datecommande` datetime NOT NULL default '0000-00-00 00:00:00',
  `bondecommande` varchar(55) collate latin1_general_ci NOT NULL default '',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimbuild`),
  KEY `idvipjob` (`idwebanimjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webmerchjob`
--

DROP TABLE IF EXISTS `webmerchjob`;
CREATE TABLE `webmerchjob` (
  `idwebanimjob` int(11) NOT NULL auto_increment,
  `idhagent` smallint(11) default NULL,
  `idclient` int(11) default NULL,
  `idwebclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `shopselection` varchar(255) collate latin1_general_ci NOT NULL default '',
  `shopselectionsearch` varchar(255) collate latin1_general_ci NOT NULL default '',
  `shopselectionnew` varchar(255) collate latin1_general_ci NOT NULL default '',
  `shophistorique` varchar(255) collate latin1_general_ci NOT NULL default '',
  `isnew` tinyint(4) NOT NULL default '0',
  `reference` varchar(255) collate latin1_general_ci default NULL,
  `etat` int(5) default '0',
  `datein` date default NULL,
  `dateout` date default NULL,
  `notejob` mediumtext collate latin1_general_ci,
  `noteprest` mediumtext collate latin1_general_ci,
  `notedeplac` mediumtext collate latin1_general_ci,
  `noteloca` mediumtext collate latin1_general_ci,
  `notefrais` mediumtext collate latin1_general_ci,
  `briefing` mediumtext collate latin1_general_ci,
  `casting` text collate latin1_general_ci,
  `datedevis` date default NULL,
  `datecommande` datetime NOT NULL default '0000-00-00 00:00:00',
  `bondecommande` varchar(55) collate latin1_general_ci NOT NULL default '',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `facnum` varchar(15) collate latin1_general_ci default NULL,
  `facnumtemp` varchar(15) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`idwebanimjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webnews`
--

DROP TABLE IF EXISTS `webnews`;
CREATE TABLE `webnews` (
  `idwebnews` int(11) NOT NULL auto_increment,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `titrefr` text collate latin1_general_ci NOT NULL,
  `textefr` mediumtext collate latin1_general_ci,
  `titrenl` text collate latin1_general_ci,
  `textenl` mediumtext collate latin1_general_ci,
  `urgent` tinyint(1) NOT NULL default '0',
  `online` varchar(5) collate latin1_general_ci NOT NULL default 'non',
  `datepublic` date default NULL,
  PRIMARY KEY  (`idwebnews`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webnewspeople`
--

DROP TABLE IF EXISTS `webnewspeople`;
CREATE TABLE `webnewspeople` (
  `idwebnewspeople` int(11) NOT NULL auto_increment,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `titrefr` text collate latin1_general_ci NOT NULL,
  `textefr` mediumtext collate latin1_general_ci,
  `titrenl` text collate latin1_general_ci,
  `textenl` mediumtext collate latin1_general_ci,
  `online` varchar(5) collate latin1_general_ci NOT NULL default 'non',
  `urgent` tinyint(1) NOT NULL default '0',
  `datepublic` date default NULL,
  PRIMARY KEY  (`idwebnewspeople`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webpeople`
--

DROP TABLE IF EXISTS `webpeople`;
CREATE TABLE `webpeople` (
  `idwebpeople` int(11) NOT NULL auto_increment,
  `idpeople` varchar(20) collate latin1_general_ci default NULL,
  `codepeople` int(11) default NULL,
  `sexe` varchar(10) collate latin1_general_ci default NULL,
  `pnom` varchar(30) collate latin1_general_ci default NULL,
  `pprenom` varchar(30) collate latin1_general_ci default NULL,
  `adresse1` varchar(50) collate latin1_general_ci default NULL,
  `num1` varchar(10) collate latin1_general_ci default NULL,
  `bte1` varchar(5) collate latin1_general_ci default NULL,
  `cp1` varchar(10) collate latin1_general_ci default NULL,
  `ville1` varchar(30) collate latin1_general_ci default NULL,
  `pays1` varchar(30) collate latin1_general_ci default NULL,
  `adresse2` varchar(50) collate latin1_general_ci default NULL,
  `num2` varchar(10) collate latin1_general_ci default NULL,
  `bte2` varchar(5) collate latin1_general_ci default NULL,
  `cp2` varchar(10) collate latin1_general_ci default NULL,
  `ville2` varchar(30) collate latin1_general_ci default NULL,
  `pays2` varchar(30) collate latin1_general_ci default NULL,
  `photo` varchar(20) collate latin1_general_ci default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `datemodifweb` date NOT NULL default '0000-00-00',
  `tel` varchar(20) collate latin1_general_ci default NULL,
  `fax` varchar(20) collate latin1_general_ci default NULL,
  `gsm` varchar(20) collate latin1_general_ci default NULL,
  `email` varchar(50) collate latin1_general_ci default NULL,
  `categorie` varchar(30) collate latin1_general_ci default NULL,
  `notegenerale` mediumtext collate latin1_general_ci,
  `notemerch` mediumtext collate latin1_general_ci,
  `isout` varchar(5) collate latin1_general_ci default NULL,
  `noteout` mediumtext collate latin1_general_ci,
  `lfr` varchar(5) collate latin1_general_ci default NULL,
  `lnl` varchar(5) collate latin1_general_ci default NULL,
  `len` varchar(5) collate latin1_general_ci default NULL,
  `ldu` varchar(5) collate latin1_general_ci default NULL,
  `lit` varchar(5) collate latin1_general_ci default NULL,
  `lsp` varchar(5) collate latin1_general_ci default NULL,
  `lbase` varchar(5) collate latin1_general_ci default NULL,
  `lbureau` varchar(5) collate latin1_general_ci default NULL,
  `notelangue` mediumtext collate latin1_general_ci,
  `physio` varchar(30) collate latin1_general_ci default NULL,
  `province` varchar(30) collate latin1_general_ci default NULL,
  `ccheveux` varchar(30) collate latin1_general_ci default NULL,
  `lcheveux` varchar(30) collate latin1_general_ci default NULL,
  `taille` varchar(30) collate latin1_general_ci default NULL,
  `tveste` varchar(30) collate latin1_general_ci default NULL,
  `tjupe` varchar(30) collate latin1_general_ci default NULL,
  `pointure` varchar(30) collate latin1_general_ci default NULL,
  `permis` varchar(5) collate latin1_general_ci default NULL,
  `voiture` varchar(5) collate latin1_general_ci default NULL,
  `ndate` date default NULL,
  `ncp` varchar(5) collate latin1_general_ci default NULL,
  `nville` varchar(30) collate latin1_general_ci default NULL,
  `npays` varchar(30) collate latin1_general_ci default NULL,
  `dateentree` date default NULL,
  `datesortie` date default NULL,
  `noteregistre` mediumtext collate latin1_general_ci,
  `catsociale` varchar(5) collate latin1_general_ci NOT NULL default '1',
  `ncidentite` varchar(17) collate latin1_general_ci default NULL,
  `nrnational` varchar(16) collate latin1_general_ci default NULL,
  `nationalite` varchar(30) collate latin1_general_ci default NULL,
  `etatcivil` varchar(5) collate latin1_general_ci default NULL,
  `datemariage` date default NULL,
  `nomconjoint` varchar(30) collate latin1_general_ci default NULL,
  `dateconjoint` date default NULL,
  `jobconjoint` varchar(30) collate latin1_general_ci default NULL,
  `pacharge` varchar(5) collate latin1_general_ci default NULL,
  `eacharge` varchar(5) collate latin1_general_ci default NULL,
  `banque` varchar(30) collate latin1_general_ci default NULL,
  `iban` varchar(35) collate latin1_general_ci default NULL,
  `bic` varchar(12) collate latin1_general_ci default NULL,
  `annif` varchar(8) collate latin1_general_ci default NULL,
  `webpass` varchar(20) collate latin1_general_ci default NULL,
  `webetat` mediumint(4) NOT NULL default '0',
  `webtype` smallint(2) NOT NULL default '0',
  `err` char(1) collate latin1_general_ci NOT NULL default '',
  `modepay` tinyint(4) NOT NULL default '1',
  `menspoi` varchar(5) collate latin1_general_ci default NULL,
  `menstai` varchar(5) collate latin1_general_ci default NULL,
  `menshan` varchar(5) collate latin1_general_ci default NULL,
  `conninformatiq` varchar(100) collate latin1_general_ci default NULL,
  `fume` varchar(5) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`idwebpeople`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webprojet`
--

DROP TABLE IF EXISTS `webprojet`;
CREATE TABLE `webprojet` (
  `idwebprojet` int(11) NOT NULL auto_increment,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `titrefr` text collate latin1_general_ci NOT NULL,
  `textefr` mediumtext collate latin1_general_ci,
  `titrenl` text collate latin1_general_ci,
  `textenl` mediumtext collate latin1_general_ci,
  `section` tinyint(4) default NULL,
  `online` varchar(5) collate latin1_general_ci NOT NULL default 'non',
  `date1` date default NULL,
  `date2` date default NULL,
  PRIMARY KEY  (`idwebprojet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webshop`
--

DROP TABLE IF EXISTS `webshop`;
CREATE TABLE `webshop` (
  `idshop` int(11) NOT NULL auto_increment,
  `codeshop` varchar(10) collate latin1_general_ci default NULL,
  `societe` varchar(30) collate latin1_general_ci default NULL,
  `adresse` varchar(50) collate latin1_general_ci default NULL,
  `cp` varchar(10) collate latin1_general_ci default NULL,
  `ville` varchar(30) collate latin1_general_ci default NULL,
  `pays` varchar(30) collate latin1_general_ci default NULL,
  `tel` varchar(15) collate latin1_general_ci default NULL,
  `fax` varchar(15) collate latin1_general_ci default NULL,
  `web` varchar(50) collate latin1_general_ci default NULL,
  `qualite` varchar(10) collate latin1_general_ci default NULL,
  `sprenom` varchar(30) collate latin1_general_ci default NULL,
  `snom` varchar(30) collate latin1_general_ci default NULL,
  `fonction` varchar(30) collate latin1_general_ci default NULL,
  `slangue` varchar(5) collate latin1_general_ci NOT NULL default 'FR',
  `eassemaine` int(3) default NULL,
  `notes` mediumtext collate latin1_general_ci,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idshop`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webvipbuild`
--

DROP TABLE IF EXISTS `webvipbuild`;
CREATE TABLE `webvipbuild` (
  `idvipbuild` int(11) NOT NULL auto_increment,
  `idwebvipjob` int(11) NOT NULL default '0',
  `vipdate1` date default '0000-00-00',
  `vipdate2` date default '0000-00-00',
  `vipactivite` varchar(255) collate latin1_general_ci default NULL,
  `vipnombre` tinyint(2) default NULL,
  `metat` int(5) default '0',
  `sexe` char(3) collate latin1_general_ci default NULL,
  `vipin` time default NULL,
  `vipout` time default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idvipbuild`),
  KEY `idvipjob` (`idwebvipjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

--
-- Table structure for table `webvipjob`
--

DROP TABLE IF EXISTS `webvipjob`;
CREATE TABLE `webvipjob` (
  `idwebvipjob` int(11) NOT NULL auto_increment,
  `idagent` smallint(11) default NULL,
  `idclient` int(11) default NULL,
  `idwebclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `isnew` tinyint(4) NOT NULL default '0',
  `reference` varchar(255) collate latin1_general_ci default NULL,
  `etat` int(5) default '0',
  `datein` date default NULL,
  `dateout` date default NULL,
  `notejob` mediumtext collate latin1_general_ci,
  `noteprest` mediumtext collate latin1_general_ci,
  `notedeplac` mediumtext collate latin1_general_ci,
  `noteloca` mediumtext collate latin1_general_ci,
  `notefrais` mediumtext collate latin1_general_ci,
  `briefing` mediumtext collate latin1_general_ci,
  `casting` text collate latin1_general_ci,
  `datedevis` date default NULL,
  `datecommande` datetime NOT NULL default '0000-00-00 00:00:00',
  `bondecommande` varchar(55) collate latin1_general_ci NOT NULL default '',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `facnum` varchar(15) collate latin1_general_ci default NULL,
  `facnumtemp` varchar(15) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`idwebvipjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-04-04  8:23:16
