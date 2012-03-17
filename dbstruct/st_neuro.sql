-- MySQL dump 10.11
--
-- Host: localhost    Database: neuro
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
-- Current Database: `neuro`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `neuro` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `neuro`;

--
-- Table structure for table `agent`
--

DROP TABLE IF EXISTS `agent`;
CREATE TABLE `agent` (
  `idagent` int(11) NOT NULL auto_increment,
  `nom` varchar(30) default NULL,
  `prenom` varchar(30) default NULL,
  `login` varchar(20) NOT NULL default '',
  `pass` varchar(20) NOT NULL default '',
  `email` varchar(40) NOT NULL default '',
  `secteur` enum('Anim','Hotes','Merch') default NULL,
  `adlevel` enum('admin','devel','user') NOT NULL default 'user',
  `agentmodif` int(11) NOT NULL default '0',
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  `atel` varchar(20) default NULL,
  `agsm` varchar(20) default NULL,
  `idas` char(3) default NULL,
  `serial` varchar(12) NOT NULL default '',
  `isout` enum('N','Y') NOT NULL default 'N',
  PRIMARY KEY  (`idagent`),
  UNIQUE KEY `idas` (`idas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `animation`
--

DROP TABLE IF EXISTS `animation`;
CREATE TABLE `animation` (
  `idanimation` int(11) NOT NULL auto_increment,
  `idanimjob` int(11) NOT NULL default '0',
  `idhagent` int(11) default NULL,
  `idshop` int(11) default NULL,
  `idpeople` int(11) default NULL,
  `antype` varchar(50) default NULL,
  `reference` varchar(100) default NULL,
  `produit` varchar(255) default NULL,
  `datem` date default NULL,
  `weekm` int(3) default NULL,
  `yearm` int(4) NOT NULL default '2005',
  `hin1` time default NULL,
  `hout1` time default NULL,
  `hin2` time default NULL,
  `hout2` time default NULL,
  `ferie` float default '100',
  `kmpaye` mediumint(10) default '0',
  `kmfacture` mediumint(10) default '0',
  `frais` decimal(6,2) default '0.00',
  `fraisfacture` decimal(6,2) default '0.00',
  `livraisonpaye` decimal(6,2) default NULL,
  `livraisonfacture` decimal(6,2) default NULL,
  `typebriefing` tinyint(4) NOT NULL default '0',
  `briefing` decimal(6,2) default '0.00',
  `noteanim` mediumtext,
  `genre` varchar(55) default NULL,
  `datecontrat` date default NULL,
  `peopleonline` enum('0','1','2') NOT NULL default '0',
  `standqualite` tinyint(4) default NULL,
  `standnote` text,
  `autreanim` tinyint(4) default NULL,
  `autreanimnote` text,
  `peoplenote` text,
  `shopnote` text,
  `facturedirect` tinyint(4) default NULL,
  `facturation` tinyint(4) NOT NULL default '1',
  `facnumtemp` varchar(12) default NULL,
  `facnum` varchar(15) default NULL,
  `facnum06` int(11) NOT NULL default '0',
  `webdoc` varchar(10) NOT NULL default 'yes',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `hhcode` varchar(24) default '0',
  `peoplehome` char(2) NOT NULL default '',
  `kmauto` enum('Y','N') NOT NULL default 'Y',
  `ccheck` enum('Y','N') NOT NULL default 'N',
  `tchkdate` date NOT NULL default '0000-00-00',
  `tchkcomment` text NOT NULL,
  PRIMARY KEY  (`idanimation`),
  KEY `facnum` (`facnum`),
  KEY `facnumtemp` (`facnumtemp`),
  KEY `idpeople` (`idpeople`),
  KEY `datem` (`datem`),
  KEY `checked` (`ccheck`),
  KEY `idanimjob` (`idanimjob`),
  KEY `idshop` (`idshop`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `animationdevis`
--

DROP TABLE IF EXISTS `animationdevis`;
CREATE TABLE `animationdevis` (
  `idanimation` int(11) NOT NULL auto_increment,
  `idanimjob` int(11) NOT NULL default '0',
  `idhagent` int(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `idpeople` int(11) default NULL,
  `antype` varchar(50) default NULL,
  `reference` varchar(100) default NULL,
  `produit` varchar(255) default NULL,
  `boncommande` varchar(55) default NULL,
  `datem` date default NULL,
  `weekm` int(3) default NULL,
  `yearm` int(4) NOT NULL default '2004',
  `hin1` time default NULL,
  `hout1` time default NULL,
  `hin2` time default NULL,
  `hout2` time default NULL,
  `ferie` float default '100',
  `kmpaye` mediumint(10) default '0',
  `kmfacture` mediumint(10) default '0',
  `frais` decimal(6,2) default '0.00',
  `fraisfacture` decimal(6,2) default '0.00',
  `livraisonpaye` decimal(6,2) default NULL,
  `livraisonfacture` decimal(6,2) default NULL,
  `briefing` decimal(6,2) default '0.00',
  `noteanim` mediumtext,
  `genre` varchar(55) default NULL,
  `datecontrat` date default NULL,
  `standqualite` tinyint(4) default NULL,
  `standnote` text,
  `autreanim` tinyint(4) default NULL,
  `autreanimnote` text,
  `peoplenote` text,
  `shopnote` text,
  `facturedirect` tinyint(4) default NULL,
  `facturation` tinyint(4) default NULL,
  `facnumtemp` varchar(12) default NULL,
  `facnum` varchar(15) default NULL,
  `webdoc` varchar(10) NOT NULL default 'yes',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `hhcode` varchar(24) default '0',
  PRIMARY KEY  (`idanimation`),
  KEY `facnum` (`facnum`),
  KEY `facnumtemp` (`facnumtemp`),
  KEY `idclient` (`idclient`),
  KEY `idpeople` (`idpeople`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `animbuild`
--

DROP TABLE IF EXISTS `animbuild`;
CREATE TABLE `animbuild` (
  `idanimbuild` int(11) NOT NULL auto_increment,
  `idanimjob` int(11) NOT NULL default '0',
  `animdate1` date default '0000-00-00',
  `animdate2` date default '0000-00-00',
  `animnombre` tinyint(2) default NULL,
  `metat` int(5) default '0',
  `animin1` time default NULL,
  `animout1` time default NULL,
  `animin2` time default NULL,
  `animout2` time default NULL,
  `idshop` int(11) NOT NULL default '0',
  `kmpaye` mediumint(10) default NULL,
  `kmfacture` mediumint(10) default NULL,
  `frais` decimal(6,2) default NULL,
  `fraisfacture` decimal(6,2) default NULL,
  `livraisonpaye` decimal(6,2) default NULL,
  `livraisonfacture` decimal(6,2) default NULL,
  `stand` tinyint(4) default NULL,
  `promo` varchar(60) NOT NULL default '',
  `shopselectionbuild` text,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimbuild`),
  KEY `idvipjob` (`idanimjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `animbuildproduit`
--

DROP TABLE IF EXISTS `animbuildproduit`;
CREATE TABLE `animbuildproduit` (
  `idanimbuildproduit` int(11) NOT NULL auto_increment,
  `idanimjob` int(11) NOT NULL default '0',
  `types` varchar(55) default NULL,
  `prix` decimal(4,2) default NULL,
  `produitin` decimal(4,2) default NULL,
  `unite` varchar(55) default NULL,
  `produitend` decimal(4,2) default NULL,
  `ventes` decimal(4,2) default NULL,
  `produitno` varchar(55) default NULL,
  `degustation` varchar(55) default NULL,
  `promoin` decimal(4,2) default NULL,
  `promoout` decimal(4,2) default NULL,
  `promoend` decimal(4,2) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimbuildproduit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `animjob`
--

DROP TABLE IF EXISTS `animjob`;
CREATE TABLE `animjob` (
  `idanimjob` int(11) NOT NULL auto_increment,
  `idagent` int(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `shopselection` text NOT NULL,
  `shopselectionsearch` text NOT NULL,
  `shopselectionnew` text NOT NULL,
  `shophistorique` text NOT NULL,
  `genre` varchar(55) default NULL,
  `reference` varchar(255) default NULL,
  `etat` int(5) default '0',
  `statutarchive` enum('open','closed','canceled') NOT NULL default 'open',
  `boncommande` varchar(55) default NULL,
  `datecommande` date default NULL,
  `datein` date default NULL,
  `dateout` date default NULL,
  `notejob` mediumtext,
  `noteprest` mediumtext,
  `notedeplac` mediumtext,
  `noteloca` mediumtext,
  `notefrais` mediumtext,
  `briefing` mediumtext,
  `casting` text,
  `datedevis` date default NULL,
  `planningweb` varchar(5) NOT NULL default 'yes',
  `webdoc` varchar(10) NOT NULL default 'yes',
  `offreweb` varchar(5) NOT NULL default 'yes',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `facturation` tinyint(4) NOT NULL default '1',
  `facnum` varchar(15) default NULL,
  `facnum06` int(11) NOT NULL default '0',
  `facnumtemp` varchar(15) default NULL,
  PRIMARY KEY  (`idanimjob`),
  KEY `idclient` (`idclient`),
  KEY `idshop` (`idshop`),
  KEY `idcofficer` (`idcofficer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `animmateriel`
--

DROP TABLE IF EXISTS `animmateriel`;
CREATE TABLE `animmateriel` (
  `idanimmateriel` int(11) NOT NULL auto_increment,
  `idanimation` int(11) NOT NULL default '0',
  `stand` float(4,2) default NULL,
  `livraison` float(4,2) default NULL,
  `gobelet` float(4,2) default NULL,
  `serviette` float(4,2) default NULL,
  `four` float(4,2) default NULL,
  `curedent` float(4,2) default NULL,
  `cuillere` float(4,2) default NULL,
  `rechaud` float(4,2) default NULL,
  `autre` float(4,2) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimmateriel`),
  KEY `idanimation` (`idanimation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `animproduit`
--

DROP TABLE IF EXISTS `animproduit`;
CREATE TABLE `animproduit` (
  `idanimproduit` int(11) NOT NULL auto_increment,
  `idanimation` int(11) NOT NULL default '0',
  `types` varchar(55) default NULL,
  `prix` decimal(4,2) default NULL,
  `produitin` decimal(4,2) default NULL,
  `unite` varchar(55) default NULL,
  `produitend` decimal(6,2) default NULL,
  `ventes` decimal(6,2) default NULL,
  `produitno` varchar(55) default NULL,
  `degustation` varchar(55) default NULL,
  `promoin` decimal(4,2) default NULL,
  `promoout` decimal(4,2) default NULL,
  `promoend` decimal(4,2) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimproduit`),
  KEY `idanimation` (`idanimation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `barsalaires`
--

DROP TABLE IF EXISTS `barsalaires`;
CREATE TABLE `barsalaires` (
  `idbareme` int(11) NOT NULL auto_increment,
  `anneenaissance` int(5) NOT NULL default '0',
  `tarifbrut` decimal(8,4) NOT NULL default '0.0000',
  `age` int(11) NOT NULL default '0',
  PRIMARY KEY  (`idbareme`),
  KEY `anneenaissance` (`anneenaissance`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `barsalaires2006`
--

DROP TABLE IF EXISTS `barsalaires2006`;
CREATE TABLE `barsalaires2006` (
  `idbareme` int(11) NOT NULL auto_increment,
  `anneenaissance` int(5) NOT NULL default '0',
  `tarifbrut` decimal(8,4) NOT NULL default '0.0000',
  `age` int(11) NOT NULL default '0',
  PRIMARY KEY  (`idbareme`),
  KEY `anneenaissance` (`anneenaissance`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `idclient` int(11) NOT NULL auto_increment,
  `codeclient` int(10) default NULL,
  `societe` varchar(60) default NULL,
  `secteur` varchar(4) NOT NULL default '0',
  `qualite` varchar(10) default NULL,
  `cprenom` varchar(30) default NULL,
  `cnom` varchar(30) default NULL,
  `fonction` varchar(30) default NULL,
  `langue` varchar(10) default NULL,
  `adresse` varchar(60) default NULL,
  `cp` varchar(10) default NULL,
  `ville` varchar(30) default NULL,
  `pays` varchar(30) default NULL,
  `email` varchar(50) default NULL,
  `notes` mediumtext,
  `notelarge` longtext,
  `tva` varchar(20) default NULL,
  `astva` char(2) default '4',
  `codetva` char(3) default 'BE',
  `logo` varchar(20) default NULL,
  `codecompta` varchar(15) default NULL,
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `login` varchar(15) default NULL,
  `password` varchar(15) default NULL,
  `facturation` tinyint(2) default '1',
  `factureofficer` tinyint(4) NOT NULL default '1',
  `hforfait` tinyint(2) NOT NULL default '1',
  `htable` tinyint(2) NOT NULL default '2',
  `taheure` decimal(6,2) NOT NULL default '16.85',
  `takm` decimal(6,2) NOT NULL default '0.35',
  `taforfait` decimal(6,2) NOT NULL default '0.00',
  `taforfaitkm` decimal(6,2) default NULL,
  `tastand` decimal(6,2) NOT NULL default '0.00',
  `tabriefing` decimal(6,2) NOT NULL default '36.00',
  `tmheure` decimal(6,2) NOT NULL default '16.50',
  `tmkm` decimal(6,2) NOT NULL default '0.35',
  `tmforfait` decimal(6,2) default NULL,
  `tvheure05` decimal(6,2) default '18.50',
  `tvheure6` decimal(6,2) default '17.50',
  `tvnight` decimal(6,2) default '26.25',
  `tvkm` decimal(6,2) default '0.35',
  `tvforfait` decimal(6,2) NOT NULL default '0.00',
  `etat` int(4) NOT NULL default '5',
  `anim` char(2) NOT NULL default '0',
  `merch` char(2) NOT NULL default '0',
  `vip` char(2) NOT NULL default '0',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  `tv150` decimal(6,2) default '26.25',
  `ta150` decimal(6,2) NOT NULL default '26.25',
  `tm150` decimal(6,2) NOT NULL default '26.25',
  `delai` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`idclient`),
  KEY `codeclient` (`codeclient`),
  KEY `societe` (`societe`),
  KEY `codecompta` (`codecompta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `codepost`
--

DROP TABLE IF EXISTS `codepost`;
CREATE TABLE `codepost` (
  `idcpb` int(11) NOT NULL auto_increment,
  `cpbcode` varchar(4) NOT NULL default '',
  `cpblocalite` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`idcpb`),
  KEY `cpbcode` (`cpbcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `cofficer`
--

DROP TABLE IF EXISTS `cofficer`;
CREATE TABLE `cofficer` (
  `idcofficer` int(11) NOT NULL auto_increment,
  `idclient` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `qualite` varchar(20) default NULL,
  `onom` varchar(30) default NULL,
  `oprenom` varchar(30) default NULL,
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `gsm` varchar(15) default NULL,
  `email` varchar(50) default NULL,
  `departement` varchar(55) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `oldidclient` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`idcofficer`),
  KEY `idclient` (`idclient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `idconfig` int(11) NOT NULL auto_increment,
  `vnom` varchar(20) NOT NULL default '',
  `vvaleur` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`idconfig`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contient toutes les variables du projet';

--
-- Table structure for table `credit`
--

DROP TABLE IF EXISTS `credit`;
CREATE TABLE `credit` (
  `idfac` int(11) NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `etat` tinyint(4) default '1',
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `horizon` char(2) default NULL,
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `facref` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`idfac`),
  KEY `horizon` (`horizon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Notes de credit 2003 a 2006';

--
-- Table structure for table `credit06`
--

DROP TABLE IF EXISTS `credit06`;
CREATE TABLE `credit06` (
  `idfac` int(11) NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `etat` tinyint(4) default '1',
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `horizon` char(2) default NULL,
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `facref` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`idfac`),
  KEY `horizon` (`horizon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='Notes de credit 2003 a 2006';

--
-- Table structure for table `creditdetail`
--

DROP TABLE IF EXISTS `creditdetail`;
CREATE TABLE `creditdetail` (
  `idman` int(11) NOT NULL auto_increment,
  `idfac` int(11) NOT NULL default '0',
  `montant` decimal(10,4) default NULL,
  `description` varchar(250) default NULL,
  `poste` varchar(6) default NULL,
  `agentmodif` int(11) NOT NULL default '0',
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idman`),
  KEY `idfac` (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detail notes credit 2003 a 2006';

--
-- Table structure for table `creditdetail06`
--

DROP TABLE IF EXISTS `creditdetail06`;
CREATE TABLE `creditdetail06` (
  `idman` int(11) NOT NULL auto_increment,
  `idfac` int(11) NOT NULL default '0',
  `montant` decimal(10,4) default NULL,
  `description` varchar(250) default NULL,
  `poste` varchar(6) default NULL,
  `agentmodif` int(11) NOT NULL default '0',
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idman`),
  KEY `idfac` (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detail notes credit 2003 a 2006';

--
-- Table structure for table `disponib`
--

DROP TABLE IF EXISTS `disponib`;
CREATE TABLE `disponib` (
  `iddispo` int(11) NOT NULL auto_increment,
  `idboy` int(11) NOT NULL default '0',
  `annee` int(11) NOT NULL default '0',
  `mois` int(11) NOT NULL default '0',
  `d00` tinyint(4) NOT NULL default '0',
  `d01` tinyint(4) NOT NULL default '0',
  `d02` tinyint(4) NOT NULL default '0',
  `d03` tinyint(4) NOT NULL default '0',
  `d04` tinyint(4) NOT NULL default '0',
  `d05` tinyint(4) NOT NULL default '0',
  `d06` tinyint(4) NOT NULL default '0',
  `d07` tinyint(4) NOT NULL default '0',
  `d08` tinyint(4) NOT NULL default '0',
  `d09` tinyint(4) NOT NULL default '0',
  `d10` tinyint(4) NOT NULL default '0',
  `d11` tinyint(4) NOT NULL default '0',
  `d12` tinyint(4) NOT NULL default '0',
  `d13` tinyint(4) NOT NULL default '0',
  `d14` tinyint(4) NOT NULL default '0',
  `d15` tinyint(4) NOT NULL default '0',
  `d16` tinyint(4) NOT NULL default '0',
  `d17` tinyint(4) NOT NULL default '0',
  `d18` tinyint(4) NOT NULL default '0',
  `d19` tinyint(4) NOT NULL default '0',
  `d20` tinyint(4) NOT NULL default '0',
  `d21` tinyint(4) NOT NULL default '0',
  `d22` tinyint(4) NOT NULL default '0',
  `d23` tinyint(4) NOT NULL default '0',
  `d24` tinyint(4) NOT NULL default '0',
  `d25` tinyint(4) NOT NULL default '0',
  `d26` tinyint(4) NOT NULL default '0',
  `d27` tinyint(4) NOT NULL default '0',
  `d28` tinyint(4) NOT NULL default '0',
  `d29` tinyint(4) NOT NULL default '0',
  `d30` tinyint(4) NOT NULL default '0',
  `d31` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`iddispo`),
  KEY `annee` (`annee`),
  KEY `mois` (`mois`),
  KEY `idboy` (`idboy`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `echeancier`
--

DROP TABLE IF EXISTS `echeancier`;
CREATE TABLE `echeancier` (
  `idecheancier` int(11) NOT NULL auto_increment,
  `idhorizon` varchar(20) NOT NULL default '',
  `journal` varchar(5) NOT NULL default '',
  `annee` year(4) NOT NULL default '0000',
  `idfac` int(11) NOT NULL default '0',
  `dateecheance` date NOT NULL default '0000-00-00',
  `debitcredit` enum('D','C') NOT NULL default 'D',
  `montantdu` decimal(8,2) NOT NULL default '0.00',
  `totalfac` decimal(8,2) NOT NULL default '0.00',
  `dejapaye` decimal(8,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idecheancier`)
) ENGINE=MyISAM AUTO_INCREMENT=712 DEFAULT CHARSET=latin1;

--
-- Table structure for table `facmanuel`
--

DROP TABLE IF EXISTS `facmanuel`;
CREATE TABLE `facmanuel` (
  `idman` int(11) NOT NULL auto_increment,
  `idfac` int(11) NOT NULL default '0',
  `montant` decimal(10,4) default NULL,
  `description` varchar(250) default NULL,
  `poste` varchar(6) default NULL,
  `agentmodif` int(11) NOT NULL default '0',
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idman`),
  KEY `idfac` (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detail manuel des fac de 2003 a 2006';

--
-- Table structure for table `facmanuel06`
--

DROP TABLE IF EXISTS `facmanuel06`;
CREATE TABLE `facmanuel06` (
  `idman` int(11) NOT NULL auto_increment,
  `idfac` int(11) NOT NULL default '0',
  `montant` decimal(10,4) default NULL,
  `description` varchar(250) default NULL,
  `poste` varchar(6) default NULL,
  `agentmodif` int(11) NOT NULL default '0',
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idman`),
  KEY `idfac` (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detail manuel des fac de 2003 a 2006';

--
-- Table structure for table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE `facture` (
  `idfac` int(11) NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `etat` tinyint(4) default '1',
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `horizon` enum('','Y') default NULL,
  `modefac` enum('A','M') NOT NULL default 'A',
  `tarif01` decimal(4,2) default '0.00',
  `tarif02` decimal(4,2) default '0.00',
  `tarif03` decimal(4,2) default '0.00',
  `tarif04` decimal(4,2) default '0.00',
  `tarif05` decimal(4,2) default '0.00',
  `tarif06` decimal(4,2) default '0.00',
  `tarif07` decimal(3,2) NOT NULL default '0.00',
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `note1` text NOT NULL,
  `note2` text NOT NULL,
  `note3` text NOT NULL,
  `note4` text NOT NULL,
  `note5` text NOT NULL,
  `note6` text NOT NULL,
  `note7` text NOT NULL,
  `note8` text NOT NULL,
  PRIMARY KEY  (`idfac`),
  KEY `horizon` (`horizon`),
  KEY `modefac` (`modefac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='factures de 2003 a 2006';

--
-- Table structure for table `facture06`
--

DROP TABLE IF EXISTS `facture06`;
CREATE TABLE `facture06` (
  `idfac` int(11) NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `etat` tinyint(4) default '1',
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `horizon` enum('','Y') default NULL,
  `modefac` enum('A','M') NOT NULL default 'A',
  `tarif01` decimal(4,2) default '0.00',
  `tarif02` decimal(4,2) default '0.00',
  `tarif03` decimal(4,2) default '0.00',
  `tarif04` decimal(4,2) default '0.00',
  `tarif05` decimal(4,2) default '0.00',
  `tarif06` decimal(4,2) default '0.00',
  `tarif07` decimal(3,2) NOT NULL default '0.00',
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `note1` text NOT NULL,
  `note2` text NOT NULL,
  `note3` text NOT NULL,
  `note4` text NOT NULL,
  `note5` text NOT NULL,
  `note6` text NOT NULL,
  `note7` text NOT NULL,
  `note8` text NOT NULL,
  PRIMARY KEY  (`idfac`),
  KEY `horizon` (`horizon`),
  KEY `modefac` (`modefac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0 COMMENT='factures de 2003 a 2006';

--
-- Table structure for table `facturetest`
--

DROP TABLE IF EXISTS `facturetest`;
CREATE TABLE `facturetest` (
  `idfac` int(11) NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `etat` tinyint(4) default '1',
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `horizon` char(2) default NULL,
  `modefac` char(1) NOT NULL default 'A',
  `tarif01` decimal(4,2) default '0.00',
  `tarif02` decimal(4,2) default '0.00',
  `tarif03` decimal(4,2) default '0.00',
  `tarif04` decimal(4,2) default '0.00',
  `tarif05` decimal(4,2) default '0.00',
  `tarif06` decimal(4,2) default '0.00',
  `tarif07` decimal(3,2) NOT NULL default '0.00',
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  PRIMARY KEY  (`idfac`),
  KEY `horizon` (`horizon`),
  KEY `modefac` (`modefac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `jobcontact`
--

DROP TABLE IF EXISTS `jobcontact`;
CREATE TABLE `jobcontact` (
  `idjobcontact` int(11) NOT NULL auto_increment,
  `idvipjob` int(11) NOT NULL default '0',
  `idanimation` int(11) NOT NULL default '0',
  `idmerch` int(11) NOT NULL default '0',
  `idagent` smallint(11) default NULL,
  `idpeople` int(11) default NULL,
  `etatcontact` int(5) default '0',
  `notecontact` mediumtext,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idjobcontact`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins` (
  `idlog` int(11) NOT NULL auto_increment,
  `idagent` int(11) NOT NULL default '0',
  `idmachine` int(11) NOT NULL default '0',
  `logdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `logip` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`idlog`)
) ENGINE=MyISAM AUTO_INCREMENT=13827 DEFAULT CHARSET=latin1;

--
-- Table structure for table `machine`
--

DROP TABLE IF EXISTS `machine`;
CREATE TABLE `machine` (
  `idmachine` int(11) NOT NULL auto_increment,
  `serial` varchar(15) NOT NULL default '',
  `description` mediumtext NOT NULL,
  `iptype` varchar(5) NOT NULL default '',
  `lastip` varchar(15) NOT NULL default '',
  `lastlog` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idmachine`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `matos`
--

DROP TABLE IF EXISTS `matos`;
CREATE TABLE `matos` (
  `idmatos` int(11) NOT NULL auto_increment,
  `idstockm` int(11) NOT NULL default '0',
  `idstockf` int(11) NOT NULL default '0',
  `codematos` varchar(55) default NULL,
  `mnom` varchar(255) default NULL,
  `dateout` date default NULL,
  `idpeople` int(11) NOT NULL default '0',
  `idvip` int(11) NOT NULL default '0',
  `idanimation` int(11) NOT NULL default '0',
  `idmerch` int(11) NOT NULL default '0',
  `autre` varchar(255) default NULL,
  `situation` enum('in','out','supplier','going','coming') NOT NULL default 'in',
  `complet` enum('0','1','2') NOT NULL default '0',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idmatos`),
  UNIQUE KEY `codematos` (`codematos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `merch`
--

DROP TABLE IF EXISTS `merch`;
CREATE TABLE `merch` (
  `idmerch` int(11) NOT NULL auto_increment,
  `idagent` int(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `idpeople` int(11) default NULL,
  `mtype` varchar(50) default NULL,
  `produit` tinytext,
  `boncommande` varchar(55) default NULL,
  `datem` date default NULL,
  `recurrence` tinyint(2) NOT NULL default '1',
  `easremplac` tinyint(2) NOT NULL default '0',
  `weekm` int(3) default NULL,
  `yearm` int(4) NOT NULL default '2007',
  `hin1` time default NULL,
  `hout1` time default NULL,
  `hin2` time default NULL,
  `hout2` time default NULL,
  `ferie` float default '100',
  `kmpaye` mediumint(10) default NULL,
  `kmfacture` mediumint(10) default NULL,
  `frais` decimal(6,2) default NULL,
  `fraisfacture` decimal(6,2) default NULL,
  `livraison` decimal(6,2) NOT NULL default '0.00',
  `diversfrais` decimal(6,2) NOT NULL default '0.00',
  `note` mediumtext,
  `genre` varchar(55) default NULL,
  `datecontrat` date default NULL,
  `contratencode` varchar(5) NOT NULL default '0',
  `rapportencode` varchar(5) NOT NULL default '0',
  `facturedirect` tinyint(4) default NULL,
  `facturation` tinyint(4) default NULL,
  `facnumtemp` int(12) default NULL,
  `facnum` int(15) default NULL,
  `facnum06` int(11) NOT NULL default '0',
  `webdoc` varchar(10) NOT NULL default 'yes',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `hhcode` varchar(24) default '0',
  PRIMARY KEY  (`idmerch`),
  KEY `idpeople` (`idpeople`),
  KEY `yearm` (`yearm`),
  KEY `weekm` (`weekm`),
  KEY `genre` (`genre`),
  KEY `datem` (`datem`),
  KEY `fanum06` (`facnum06`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `merchduplic`
--

DROP TABLE IF EXISTS `merchduplic`;
CREATE TABLE `merchduplic` (
  `idmerch` int(11) NOT NULL auto_increment,
  `idhagent` int(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `idpeople` int(11) default NULL,
  `mtype` varchar(50) default NULL,
  `produit` tinytext,
  `datem` date default NULL,
  `recurrence` tinyint(2) NOT NULL default '1',
  `easremplac` tinyint(2) NOT NULL default '0',
  `weekm` int(3) default NULL,
  `hin1` time default NULL,
  `hout1` time default NULL,
  `hin2` time default NULL,
  `hout2` time default NULL,
  `ferie` float default '100',
  `kmpaye` mediumint(10) default NULL,
  `kmfacture` mediumint(10) default NULL,
  `frais` decimal(6,2) default NULL,
  `fraisfacture` decimal(6,2) default NULL,
  `livraison` decimal(6,2) NOT NULL default '0.00',
  `diversfrais` decimal(6,2) NOT NULL default '0.00',
  `note` mediumtext,
  `genre` varchar(55) default NULL,
  `datecontrat` date default NULL,
  `contratencode` varchar(5) NOT NULL default '0',
  `facturedirect` tinyint(4) default NULL,
  `facturation` tinyint(4) default NULL,
  `facnumtemp` varchar(12) default NULL,
  `facnum` varchar(15) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `hhcode` varchar(24) default '0',
  PRIMARY KEY  (`idmerch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `mercheasmois`
--

DROP TABLE IF EXISTS `mercheasmois`;
CREATE TABLE `mercheasmois` (
  `ideasmois` int(11) NOT NULL auto_increment,
  `easannee` int(4) NOT NULL default '0',
  `easmois` int(2) NOT NULL default '0',
  `mfo1a` int(4) NOT NULL default '0',
  `mfo2a` int(4) NOT NULL default '0',
  `mfo3a` int(4) NOT NULL default '0',
  `mfo4a` int(4) NOT NULL default '0',
  `mpa1a` int(4) NOT NULL default '0',
  `mpa2a` int(4) NOT NULL default '0',
  `mpa3a` int(4) NOT NULL default '0',
  `mpa4a` int(4) NOT NULL default '0',
  `mpa5a` int(4) NOT NULL default '0',
  `mpa6a` int(4) NOT NULL default '0',
  `mpa7a` int(4) NOT NULL default '0',
  `mpa8a` int(4) NOT NULL default '0',
  `mpa9a` int(4) NOT NULL default '0',
  `mpa10a` int(4) NOT NULL default '0',
  `mte1a` int(4) NOT NULL default '0',
  `mte2a` int(4) NOT NULL default '0',
  `mte3a` int(4) NOT NULL default '0',
  `mte4a` int(4) NOT NULL default '0',
  `mte5a` int(4) NOT NULL default '0',
  `mte6a` int(4) NOT NULL default '0',
  `mte7a` int(4) NOT NULL default '0',
  `mte8a` int(4) NOT NULL default '0',
  `mep1a` int(4) NOT NULL default '0',
  `mep2a` int(4) NOT NULL default '0',
  `mep3a` int(4) NOT NULL default '0',
  `mep4a` int(4) NOT NULL default '0',
  `mep5a` int(4) NOT NULL default '0',
  `mba1a` int(4) NOT NULL default '0',
  `mba2a` int(4) NOT NULL default '0',
  `mba3a` int(4) NOT NULL default '0',
  `mba4a` int(4) NOT NULL default '0',
  `mba5a` int(4) NOT NULL default '0',
  `mba6a` int(4) NOT NULL default '0',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`ideasmois`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `mercheasproduit`
--

DROP TABLE IF EXISTS `mercheasproduit`;
CREATE TABLE `mercheasproduit` (
  `ideasprod` int(11) NOT NULL auto_increment,
  `idmerch` int(11) default NULL,
  `plus` tinyint(2) NOT NULL default '0',
  `weekm` int(11) NOT NULL default '0',
  `idshop` int(11) NOT NULL default '0',
  `fo1a` int(4) NOT NULL default '0',
  `fo1b` int(4) NOT NULL default '0',
  `fo1c` int(4) NOT NULL default '0',
  `fo2a` int(4) NOT NULL default '0',
  `fo2b` int(4) NOT NULL default '0',
  `fo2c` int(4) NOT NULL default '0',
  `fo3a` int(4) NOT NULL default '0',
  `fo3b` int(4) NOT NULL default '0',
  `fo3c` int(4) NOT NULL default '0',
  `fo4a` int(4) NOT NULL default '0',
  `fo4b` int(4) NOT NULL default '0',
  `fo4c` int(4) NOT NULL default '0',
  `pa1a` int(4) NOT NULL default '0',
  `pa1b` int(4) NOT NULL default '0',
  `pa1c` int(4) NOT NULL default '0',
  `pa2a` int(4) NOT NULL default '0',
  `pa2b` int(4) NOT NULL default '0',
  `pa2c` int(4) NOT NULL default '0',
  `pa3a` int(4) NOT NULL default '0',
  `pa3b` int(4) NOT NULL default '0',
  `pa3c` int(4) NOT NULL default '0',
  `pa4a` int(4) NOT NULL default '0',
  `pa4b` int(4) NOT NULL default '0',
  `pa4c` int(4) NOT NULL default '0',
  `pa5a` int(4) NOT NULL default '0',
  `pa5b` int(4) NOT NULL default '0',
  `pa5c` int(4) NOT NULL default '0',
  `pa6a` int(4) NOT NULL default '0',
  `pa6b` int(4) NOT NULL default '0',
  `pa6c` int(4) NOT NULL default '0',
  `pa7a` int(4) NOT NULL default '0',
  `pa7b` int(4) NOT NULL default '0',
  `pa7c` int(4) NOT NULL default '0',
  `pa8a` int(4) NOT NULL default '0',
  `pa8b` int(4) NOT NULL default '0',
  `pa8c` int(4) NOT NULL default '0',
  `pa9a` int(4) NOT NULL default '0',
  `pa9b` int(4) NOT NULL default '0',
  `pa9c` int(4) NOT NULL default '0',
  `pa10a` int(4) NOT NULL default '0',
  `pa10b` int(4) NOT NULL default '0',
  `pa10c` int(4) NOT NULL default '0',
  `te1a` int(4) NOT NULL default '0',
  `te1b` int(4) NOT NULL default '0',
  `te1c` int(4) NOT NULL default '0',
  `te2a` int(4) NOT NULL default '0',
  `te2b` int(4) NOT NULL default '0',
  `te2c` int(4) NOT NULL default '0',
  `te3a` int(4) NOT NULL default '0',
  `te3b` int(4) NOT NULL default '0',
  `te3c` int(4) NOT NULL default '0',
  `te4a` int(4) NOT NULL default '0',
  `te4b` int(4) NOT NULL default '0',
  `te4c` int(4) NOT NULL default '0',
  `te5a` int(4) NOT NULL default '0',
  `te5b` int(4) NOT NULL default '0',
  `te5c` int(4) NOT NULL default '0',
  `te6a` int(4) NOT NULL default '0',
  `te6b` int(4) NOT NULL default '0',
  `te6c` int(4) NOT NULL default '0',
  `te7a` int(4) NOT NULL default '0',
  `te7b` int(4) NOT NULL default '0',
  `te7c` int(4) NOT NULL default '0',
  `te8a` int(4) NOT NULL default '0',
  `te8b` int(4) NOT NULL default '0',
  `te8c` int(4) NOT NULL default '0',
  `ep1a` int(4) NOT NULL default '0',
  `ep1b` int(4) NOT NULL default '0',
  `ep1c` int(4) NOT NULL default '0',
  `ep2a` int(4) NOT NULL default '0',
  `ep2b` int(4) NOT NULL default '0',
  `ep2c` int(4) NOT NULL default '0',
  `ep3a` int(4) NOT NULL default '0',
  `ep3b` int(4) NOT NULL default '0',
  `ep3c` int(4) NOT NULL default '0',
  `ep4a` int(4) NOT NULL default '0',
  `ep4b` int(4) NOT NULL default '0',
  `ep4c` int(4) NOT NULL default '0',
  `ep5a` int(4) NOT NULL default '0',
  `ep5b` int(4) NOT NULL default '0',
  `ep5c` int(4) NOT NULL default '0',
  `ba1a` int(4) NOT NULL default '0',
  `ba1b` int(4) NOT NULL default '0',
  `ba1c` int(4) NOT NULL default '0',
  `ba2a` int(4) NOT NULL default '0',
  `ba2b` int(4) NOT NULL default '0',
  `ba2c` int(4) NOT NULL default '0',
  `ba3a` int(4) NOT NULL default '0',
  `ba3b` int(4) NOT NULL default '0',
  `ba3c` int(4) NOT NULL default '0',
  `ba4a` int(4) NOT NULL default '0',
  `ba4b` int(4) NOT NULL default '0',
  `ba4c` int(4) NOT NULL default '0',
  `ba5a` int(4) NOT NULL default '0',
  `ba5b` int(4) NOT NULL default '0',
  `ba5c` int(4) NOT NULL default '0',
  `ba6a` int(4) NOT NULL default '0',
  `ba6b` int(4) NOT NULL default '0',
  `ba6c` int(4) NOT NULL default '0',
  `au1a` int(4) NOT NULL default '0',
  `au1b` int(4) NOT NULL default '0',
  `au1c` int(4) NOT NULL default '0',
  `au2a` int(4) NOT NULL default '0',
  `au2b` int(4) NOT NULL default '0',
  `au2c` int(4) NOT NULL default '0',
  `au3a` int(4) NOT NULL default '0',
  `au3b` int(4) NOT NULL default '0',
  `au3c` int(4) NOT NULL default '0',
  `au4a` int(4) NOT NULL default '0',
  `au4b` int(4) NOT NULL default '0',
  `au4c` int(4) NOT NULL default '0',
  `au5a` int(4) NOT NULL default '0',
  `au5b` int(4) NOT NULL default '0',
  `au5c` int(4) NOT NULL default '0',
  `au1n` varchar(50) default NULL,
  `au2n` varchar(50) default NULL,
  `au3n` varchar(50) default NULL,
  `au4n` varchar(50) default NULL,
  `au5n` varchar(50) default NULL,
  `caisse` int(3) NOT NULL default '0',
  `remarque` mediumtext,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `badcaisses` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ideasprod`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `agentmodif` int(11) NOT NULL default '0',
  `ndate` date NOT NULL default '0000-00-00',
  `description` mediumtext NOT NULL,
  `newspage` varchar(100) default NULL,
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
CREATE TABLE `people` (
  `idpeople` int(11) NOT NULL auto_increment,
  `codepeople` int(11) default NULL,
  `sexe` varchar(10) default NULL,
  `beaute` tinyint(4) NOT NULL default '0',
  `charme` tinyint(4) NOT NULL default '0',
  `dynamisme` tinyint(4) NOT NULL default '0',
  `pnom` varchar(30) default NULL,
  `pprenom` varchar(30) default NULL,
  `adresse1` varchar(50) default NULL,
  `num1` varchar(10) default NULL,
  `bte1` varchar(5) default NULL,
  `cp1` varchar(10) default NULL,
  `ville1` varchar(30) default NULL,
  `pays1` varchar(30) default NULL,
  `glat` decimal(8,5) NOT NULL default '0.00000',
  `glong` decimal(8,5) NOT NULL default '0.00000',
  `adresse2` varchar(50) default NULL,
  `num2` varchar(10) default NULL,
  `bte2` varchar(5) default NULL,
  `cp2` varchar(10) default NULL,
  `ville2` varchar(30) default NULL,
  `pays2` varchar(30) default NULL,
  `glat2` decimal(8,5) NOT NULL default '0.00000',
  `glong2` decimal(8,5) NOT NULL default '0.00000',
  `peoplehome` enum('1','2') NOT NULL default '1',
  `photo` varchar(20) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `datemodifweb` date NOT NULL default '0000-00-00',
  `dateinscription` date NOT NULL default '2003-08-01',
  `tel` varchar(20) default NULL,
  `fax` varchar(20) default NULL,
  `gsm` varchar(20) default NULL,
  `email` varchar(50) default NULL,
  `categorie` varchar(30) default NULL,
  `notegenerale` mediumtext,
  `notemerch` mediumtext,
  `isout` varchar(5) default NULL,
  `noteout` mediumtext,
  `lfr` varchar(5) default NULL,
  `lnl` varchar(5) default NULL,
  `len` varchar(5) default NULL,
  `ldu` varchar(5) default NULL,
  `lit` varchar(5) default NULL,
  `lsp` varchar(5) default NULL,
  `lbase` varchar(5) default NULL,
  `lbureau` varchar(5) default NULL,
  `notelangue` mediumtext,
  `physio` varchar(30) default NULL,
  `province` varchar(30) default NULL,
  `ccheveux` varchar(30) default NULL,
  `lcheveux` varchar(30) default NULL,
  `taille` varchar(30) default NULL,
  `tveste` varchar(30) default NULL,
  `tjupe` varchar(30) default NULL,
  `pointure` varchar(30) default NULL,
  `permis` varchar(5) default NULL,
  `voiture` varchar(5) default NULL,
  `ndate` date default NULL,
  `ncp` varchar(5) default NULL,
  `nville` varchar(30) default NULL,
  `npays` varchar(30) default NULL,
  `dateentree` date default NULL,
  `datesortie` date default NULL,
  `noteregistre` mediumtext,
  `catsociale` varchar(5) NOT NULL default '1',
  `ncidentite` varchar(17) default NULL,
  `nrnational` varchar(16) default NULL,
  `nationalite` varchar(30) default NULL,
  `etatcivil` varchar(5) default NULL,
  `datemariage` date default NULL,
  `nomconjoint` varchar(30) default NULL,
  `dateconjoint` date default NULL,
  `jobconjoint` varchar(30) default NULL,
  `pacharge` varchar(5) default NULL,
  `eacharge` varchar(5) default NULL,
  `banque` varchar(30) default NULL,
  `iban` varchar(35) default NULL,
  `bic` varchar(12) default NULL,
  `annif` varchar(8) default NULL,
  `webpass` varchar(20) default NULL,
  `webetat` mediumint(4) NOT NULL default '0',
  `webdoc` varchar(10) NOT NULL default 'yes',
  `err` char(1) NOT NULL default '',
  `modepay` tinyint(4) NOT NULL default '1',
  `salaire` int(11) NOT NULL default '0',
  `ddmodif` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ddinsc` timestamp NOT NULL default '0000-00-00 00:00:00',
  `profession` varchar(40) default NULL,
  `pouid` int(11) NOT NULL default '0',
  `menspoi` varchar(5) default NULL,
  `menstai` varchar(5) default NULL,
  `menshan` varchar(5) default NULL,
  `conninformatiq` varchar(100) default NULL,
  `fume` varchar(5) default NULL,
  `chomeur` char(3) NOT NULL default 'non',
  PRIMARY KEY  (`idpeople`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `peoplemission`
--

DROP TABLE IF EXISTS `peoplemission`;
CREATE TABLE `peoplemission` (
  `idpeoplemission` int(11) NOT NULL auto_increment,
  `dateout` date default NULL,
  `idpeople` int(11) NOT NULL default '0',
  `idvip` int(11) NOT NULL default '0',
  `idanimation` int(11) NOT NULL default '0',
  `idmerch` int(11) NOT NULL default '0',
  `note` tinytext,
  `motif` enum('1','2','3','4','5') NOT NULL default '1',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idpeoplemission`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `shop`
--

DROP TABLE IF EXISTS `shop`;
CREATE TABLE `shop` (
  `idshop` int(11) NOT NULL auto_increment,
  `codeshop` varchar(10) default NULL,
  `societe` varchar(30) default NULL,
  `adresse` varchar(50) default NULL,
  `cp` varchar(10) default NULL,
  `ville` varchar(30) default NULL,
  `pays` varchar(30) default NULL,
  `glat` decimal(8,5) NOT NULL default '0.00000',
  `glong` decimal(8,5) NOT NULL default '0.00000',
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `web` varchar(50) default NULL,
  `qualite` varchar(10) default NULL,
  `sprenom` varchar(30) default NULL,
  `snom` varchar(30) default NULL,
  `fonction` varchar(30) default NULL,
  `slangue` varchar(5) NOT NULL default 'FR',
  `eassemaine` int(3) default NULL,
  `notes` mediumtext,
  `newweb` enum('0','1') NOT NULL default '0',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idshop`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `sofficer`
--

DROP TABLE IF EXISTS `sofficer`;
CREATE TABLE `sofficer` (
  `idsofficer` int(11) NOT NULL auto_increment,
  `idsupplier` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `qualite` varchar(20) default NULL,
  `onom` varchar(30) default NULL,
  `oprenom` varchar(30) default NULL,
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `gsm` varchar(15) default NULL,
  `email` varchar(50) default NULL,
  `departement` varchar(55) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `oldidclient` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`idsofficer`),
  KEY `idclient` (`idsupplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `stockf`
--

DROP TABLE IF EXISTS `stockf`;
CREATE TABLE `stockf` (
  `idstockf` int(11) NOT NULL auto_increment,
  `reference` varchar(55) default NULL,
  `description` tinytext,
  `stype` enum('pack','unit') NOT NULL default 'unit',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idstockf`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `stockm`
--

DROP TABLE IF EXISTS `stockm`;
CREATE TABLE `stockm` (
  `idstockm` int(11) NOT NULL auto_increment,
  `idstockf` int(11) NOT NULL default '0',
  `reference` varchar(55) default NULL,
  `description` tinytext,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idstockm`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `stockticket`
--

DROP TABLE IF EXISTS `stockticket`;
CREATE TABLE `stockticket` (
  `idticket` int(11) NOT NULL auto_increment,
  `idmatos` int(11) NOT NULL default '0',
  `idstockf` int(11) NOT NULL default '0',
  `idstockm` int(11) NOT NULL default '0',
  `idanimation` int(11) NOT NULL default '0',
  `idvip` int(11) NOT NULL default '0',
  `idvipjob` int(11) NOT NULL default '0',
  `idmerch` int(11) NOT NULL default '0',
  `idpeople` int(11) NOT NULL default '0',
  `jobstatut` enum('job','mission','debooking') NOT NULL default 'mission',
  `dateticket` date NOT NULL default '0000-00-00',
  `stockout` date NOT NULL default '0000-00-00',
  `stockin` date NOT NULL default '2199-12-31',
  `nombre` tinyint(4) NOT NULL default '0',
  `sex` enum('m','f','x') NOT NULL default 'f',
  `suser` enum('mission','supplier','client','stock','people') default NULL,
  `inuse` tinyint(4) NOT NULL default '0',
  `note` tinytext,
  `sview` enum('0','1') NOT NULL default '0',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idticket`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `idsupplier` int(11) NOT NULL auto_increment,
  `codesupplier` int(10) default NULL,
  `societe` varchar(60) default NULL,
  `secteur` varchar(4) NOT NULL default '0',
  `qualite` varchar(10) default NULL,
  `cprenom` varchar(30) default NULL,
  `cnom` varchar(30) default NULL,
  `fonction` varchar(30) default NULL,
  `langue` varchar(10) default NULL,
  `adresse` varchar(60) default NULL,
  `cp` varchar(10) default NULL,
  `ville` varchar(30) default NULL,
  `pays` varchar(30) default NULL,
  `email` varchar(50) default NULL,
  `notes` mediumtext,
  `notelarge` longtext,
  `tva` varchar(20) default NULL,
  `astva` char(2) default '4',
  `codetva` char(3) default 'BE',
  `logo` varchar(20) default NULL,
  `codecompta` varchar(15) default NULL,
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `login` varchar(15) default NULL,
  `password` varchar(15) default NULL,
  `etat` int(4) NOT NULL default '5',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsupplier`),
  KEY `codeclient` (`codesupplier`),
  KEY `societe` (`societe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `tempfactureanim`
--

DROP TABLE IF EXISTS `tempfactureanim`;
CREATE TABLE `tempfactureanim` (
  `idfac` int(10) unsigned NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `etat` tinyint(4) default '1',
  `tarif01` decimal(4,2) default '0.00',
  `tarif02` decimal(4,2) default '0.00',
  `tarif03` decimal(4,2) default '0.00',
  `tarif04` decimal(4,2) default '0.00',
  `tarif05` decimal(4,2) default '0.00',
  `tarif06` decimal(4,2) default '0.00',
  `tarif07` decimal(3,2) NOT NULL default '0.00',
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `modefac` char(3) NOT NULL default 'A',
  `horizon` char(2) NOT NULL default '',
  PRIMARY KEY  (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `tempfacturemerch`
--

DROP TABLE IF EXISTS `tempfacturemerch`;
CREATE TABLE `tempfacturemerch` (
  `idfac` int(10) unsigned NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `etat` tinyint(4) default '1',
  `tarif01` decimal(4,2) default '0.00',
  `tarif02` decimal(4,2) default '0.00',
  `tarif03` decimal(4,2) default '0.00',
  `tarif04` decimal(4,2) default '0.00',
  `tarif05` decimal(4,2) default '0.00',
  `tarif06` decimal(4,2) default '0.00',
  `tarif07` decimal(3,2) NOT NULL default '0.00',
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `modefac` char(3) NOT NULL default 'A',
  `horizon` char(2) NOT NULL default '',
  PRIMARY KEY  (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `tempfacturevip`
--

DROP TABLE IF EXISTS `tempfacturevip`;
CREATE TABLE `tempfacturevip` (
  `idfac` int(10) unsigned NOT NULL auto_increment,
  `datefac` date NOT NULL default '0000-00-00',
  `secteur` tinyint(4) default NULL,
  `datepay` date default NULL,
  `idclient` int(11) default NULL,
  `langue` varchar(10) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `idcofficer` int(11) NOT NULL default '0',
  `etat` tinyint(4) default '1',
  `tarif01` decimal(4,2) default '0.00',
  `tarif02` decimal(4,2) default '0.00',
  `tarif03` decimal(4,2) default '0.00',
  `tarif04` decimal(4,2) default '0.00',
  `tarif05` decimal(4,2) default '0.00',
  `tarif06` decimal(4,2) default '0.00',
  `tarif07` decimal(3,2) NOT NULL default '0.00',
  `montant` decimal(8,2) default NULL,
  `intitule` varchar(50) default NULL,
  `modefac` char(3) NOT NULL default 'A',
  `horizon` char(2) NOT NULL default '',
  PRIMARY KEY  (`idfac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `vipbuild`
--

DROP TABLE IF EXISTS `vipbuild`;
CREATE TABLE `vipbuild` (
  `idvipbuild` int(11) NOT NULL auto_increment,
  `idvipjob` int(11) NOT NULL default '0',
  `vipdate1` date default '0000-00-00',
  `vipdate2` date default '0000-00-00',
  `vipactivite` varchar(255) default NULL,
  `tvipactivite` varchar(55) NOT NULL default '',
  `vipnombre` tinyint(2) default NULL,
  `metat` int(5) default '0',
  `sexe` char(3) default NULL,
  `vipin` time default NULL,
  `vipout` time default NULL,
  `idshop` int(11) NOT NULL default '0',
  `brk` decimal(3,2) NOT NULL default '0.00',
  `night` decimal(3,2) NOT NULL default '0.00',
  `h150` decimal(3,2) NOT NULL default '0.00',
  `ts` decimal(4,2) NOT NULL default '0.00',
  `fts` decimal(3,2) NOT NULL default '0.00',
  `km` mediumint(3) NOT NULL default '0',
  `fkm` decimal(4,2) NOT NULL default '0.00',
  `unif` decimal(4,2) NOT NULL default '0.00',
  `loc1` decimal(6,2) NOT NULL default '0.00',
  `cat` decimal(4,2) NOT NULL default '0.00',
  `disp` decimal(4,2) NOT NULL default '0.00',
  `fr1` decimal(4,2) NOT NULL default '0.00',
  `fr2` decimal(4,2) NOT NULL default '0.00',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idvipbuild`),
  KEY `idvipjob` (`idvipjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `vipdelete`
--

DROP TABLE IF EXISTS `vipdelete`;
CREATE TABLE `vipdelete` (
  `idvip` int(11) NOT NULL default '0',
  `idvipjob` int(11) NOT NULL default '0',
  `idshop` int(11) default '0',
  `idpeople` int(11) default NULL,
  `vipdate` date default NULL,
  `vipactivite` varchar(255) default NULL,
  `sexe` char(3) default NULL,
  `vipin` time default NULL,
  `vipout` time default NULL,
  `brk` decimal(3,2) default NULL,
  `ajust` decimal(3,2) default NULL,
  `night` decimal(3,2) default NULL,
  `ts` decimal(4,2) default NULL,
  `fts` decimal(3,2) default NULL,
  `km` mediumint(3) default NULL,
  `fkm` decimal(4,2) default NULL,
  `vkm` mediumint(3) default NULL,
  `vfkm` decimal(4,2) default NULL,
  `unif` decimal(4,2) default NULL,
  `net` decimal(4,2) default NULL,
  `loc1` decimal(4,2) default NULL,
  `loc2` decimal(4,2) default NULL,
  `cat` decimal(4,2) default NULL,
  `disp` decimal(4,2) default NULL,
  `fr1` decimal(4,2) default NULL,
  `fr2` decimal(4,2) default NULL,
  `vcat` decimal(4,2) default NULL,
  `vdisp` decimal(4,2) default NULL,
  `vfr1` decimal(4,2) default NULL,
  `vfr2` decimal(4,2) default NULL,
  `vfrpeople` decimal(4,2) default NULL,
  `notefrpeople` text,
  `notes` text,
  `notedelete` text,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `datecontrat` date default NULL,
  `h150` decimal(3,2) default NULL,
  `h200` decimal(3,2) default NULL,
  `vnight` decimal(3,2) default NULL,
  PRIMARY KEY  (`idvip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `vipdevis`
--

DROP TABLE IF EXISTS `vipdevis`;
CREATE TABLE `vipdevis` (
  `idvip` int(11) NOT NULL auto_increment,
  `idvipjob` int(11) NOT NULL default '0',
  `idshop` int(11) default '0',
  `idpeople` int(11) default NULL,
  `vipdate` date default NULL,
  `vipactivite` varchar(255) default NULL,
  `sexe` char(3) default NULL,
  `vipin` time default NULL,
  `vipout` time default NULL,
  `brk` decimal(3,2) default NULL,
  `ajust` decimal(3,2) default NULL,
  `night` decimal(3,2) default NULL,
  `ts` decimal(4,2) default NULL,
  `fts` decimal(3,2) default NULL,
  `km` mediumint(3) default NULL,
  `fkm` decimal(4,2) default NULL,
  `vkm` mediumint(3) default NULL,
  `vfkm` decimal(4,2) default NULL,
  `unif` decimal(4,2) default NULL,
  `net` decimal(4,2) default NULL,
  `loc1` decimal(6,2) default NULL,
  `loc2` decimal(6,2) default NULL,
  `cat` decimal(4,2) default NULL,
  `disp` decimal(4,2) default NULL,
  `fr1` decimal(4,2) default NULL,
  `fr2` decimal(4,2) default NULL,
  `vcat` decimal(4,2) default NULL,
  `vdisp` decimal(4,2) default NULL,
  `vfr1` decimal(4,2) default NULL,
  `vfr2` decimal(4,2) default NULL,
  `vfrpeople` decimal(4,2) default NULL,
  `notefrpeople` text,
  `notes` text,
  `notedelete` text,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `datecontrat` date default NULL,
  `h150` decimal(3,2) default NULL,
  `h200` decimal(3,2) default NULL,
  `vnight` decimal(3,2) default NULL,
  PRIMARY KEY  (`idvip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `vipjob`
--

DROP TABLE IF EXISTS `vipjob`;
CREATE TABLE `vipjob` (
  `idvipjob` int(11) NOT NULL auto_increment,
  `idagent` smallint(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `reference` varchar(255) default NULL,
  `datecommande` datetime NOT NULL default '0000-00-00 00:00:00',
  `bondecommande` varchar(55) NOT NULL default '',
  `etat` int(5) default '0',
  `datein` date default NULL,
  `dateout` date default NULL,
  `notejob` mediumtext,
  `noteprest` mediumtext,
  `notedeplac` mediumtext,
  `noteloca` mediumtext,
  `notefrais` mediumtext,
  `briefing` mediumtext,
  `casting` text,
  `datedevis` date default NULL,
  `stock` enum('oui','non') NOT NULL default 'non',
  `planningweb` varchar(5) NOT NULL default 'no',
  `webdoc` varchar(10) NOT NULL default 'yes',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `facnum` varchar(15) default NULL,
  `facnum06` int(11) NOT NULL default '0',
  `facnumtemp` varchar(15) default NULL,
  `forfait` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`idvipjob`),
  KEY `idagent` (`idagent`),
  KEY `idclient` (`idclient`),
  KEY `idcofficer` (`idcofficer`),
  KEY `idshop` (`idshop`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `vipmission`
--

DROP TABLE IF EXISTS `vipmission`;
CREATE TABLE `vipmission` (
  `idvip` int(11) NOT NULL auto_increment,
  `idvipjob` int(11) NOT NULL default '0',
  `idshop` int(11) default '0',
  `idpeople` int(11) NOT NULL default '0',
  `matospeople` enum('0','1','2') NOT NULL default '0',
  `vipdate` date default NULL,
  `vipactivite` varchar(255) default NULL,
  `metat` int(5) default '0',
  `sexe` char(3) default NULL,
  `vipin` time default NULL,
  `vipout` time default NULL,
  `brk` decimal(3,2) default NULL,
  `ajust` decimal(3,2) default NULL,
  `night` decimal(3,2) default NULL,
  `ts` decimal(4,2) default NULL,
  `fts` decimal(3,2) default NULL,
  `km` mediumint(3) default NULL,
  `fkm` decimal(4,2) default NULL,
  `vkm` mediumint(3) default NULL,
  `vfkm` decimal(4,2) default NULL,
  `peoplehome` enum('1','2') NOT NULL default '1',
  `unif` decimal(4,2) default NULL,
  `net` decimal(4,2) default NULL,
  `loc1` decimal(6,2) default NULL,
  `loc2` decimal(6,2) default NULL,
  `cat` decimal(4,2) default NULL,
  `disp` decimal(4,2) default NULL,
  `fr1` decimal(4,2) default NULL,
  `fr2` decimal(4,2) default NULL,
  `vcat` decimal(4,2) default NULL,
  `vdisp` decimal(4,2) default NULL,
  `vfr1` decimal(4,2) default NULL,
  `vfr2` decimal(4,2) default NULL,
  `vfrpeople` decimal(4,2) default NULL,
  `notefrpeople` text,
  `notes` text,
  `notedelete` text,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `datecontrat` date default NULL,
  `facnum` int(11) NOT NULL default '0',
  `facnum06` int(11) NOT NULL default '0',
  `facnumtemp` varchar(15) default NULL,
  `h150` decimal(4,2) default NULL,
  `h200` decimal(4,2) default NULL,
  `vnight` decimal(3,2) default NULL,
  `hhcode` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`idvip`),
  KEY `idvipjob` (`idvipjob`),
  KEY `idpeople` (`idpeople`),
  KEY `idshop` (`idshop`),
  KEY `facnum` (`facnum`),
  KEY `vipdate` (`vipdate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `zoutanimation`
--

DROP TABLE IF EXISTS `zoutanimation`;
CREATE TABLE `zoutanimation` (
  `idanimation` int(11) NOT NULL default '0',
  `idanimjob` int(11) NOT NULL default '0',
  `idhagent` int(11) default NULL,
  `idshop` int(11) default NULL,
  `idpeople` int(11) default NULL,
  `antype` varchar(50) default NULL,
  `reference` varchar(100) default NULL,
  `produit` varchar(255) default NULL,
  `datem` date default NULL,
  `hin1` time default NULL,
  `hout1` time default NULL,
  `hin2` time default NULL,
  `hout2` time default NULL,
  `ferie` float default '100',
  `kmpaye` mediumint(10) default NULL,
  `kmfacture` mediumint(10) default NULL,
  `frais` decimal(6,2) default NULL,
  `fraisfacture` decimal(6,2) default NULL,
  `briefing` decimal(6,2) default NULL,
  `noteanim` mediumtext,
  `genre` varchar(55) default NULL,
  `datecontrat` date default NULL,
  `standqualite` tinyint(4) default NULL,
  `standnote` text,
  `autreanim` tinyint(4) default NULL,
  `autreanimnote` text,
  `peoplenote` text,
  `shopnote` text,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `kmauto` enum('Y','N') default NULL,
  PRIMARY KEY  (`idanimation`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `zoutanimjob`
--

DROP TABLE IF EXISTS `zoutanimjob`;
CREATE TABLE `zoutanimjob` (
  `idanimjob` int(11) NOT NULL auto_increment,
  `idagent` int(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `genre` varchar(55) default NULL,
  `reference` varchar(255) default NULL,
  `etat` int(5) default '0',
  `boncommande` varchar(55) default NULL,
  `datecommande` date default NULL,
  `datein` date default NULL,
  `dateout` date default NULL,
  `notejob` mediumtext,
  `noteprest` mediumtext,
  `notedeplac` mediumtext,
  `noteloca` mediumtext,
  `notefrais` mediumtext,
  `briefing` mediumtext,
  `casting` text,
  `datedevis` date default NULL,
  `planningweb` varchar(5) NOT NULL default 'no',
  `webdoc` varchar(10) NOT NULL default 'yes',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `facnum` varchar(15) default NULL,
  `facnumtemp` varchar(15) default NULL,
  PRIMARY KEY  (`idanimjob`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `zoutanimmateriel`
--

DROP TABLE IF EXISTS `zoutanimmateriel`;
CREATE TABLE `zoutanimmateriel` (
  `idanimmateriel` int(11) NOT NULL default '0',
  `idanimation` int(11) NOT NULL default '0',
  `stand` float(4,2) default NULL,
  `livraison` float(4,2) default NULL,
  `gobelet` float(4,2) default NULL,
  `serviette` float(4,2) default NULL,
  `four` float(4,2) default NULL,
  `curedent` float(4,2) default NULL,
  `cuillere` float(4,2) default NULL,
  `rechaud` float(4,2) default NULL,
  `autre` float(4,2) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimmateriel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `zoutanimproduit`
--

DROP TABLE IF EXISTS `zoutanimproduit`;
CREATE TABLE `zoutanimproduit` (
  `idanimproduit` int(11) NOT NULL default '0',
  `idanimation` int(11) NOT NULL default '0',
  `types` varchar(55) default NULL,
  `prix` decimal(4,2) default NULL,
  `produitin` decimal(4,2) default NULL,
  `unite` varchar(55) default NULL,
  `produitend` decimal(4,2) default NULL,
  `ventes` decimal(4,2) default NULL,
  `produitno` varchar(55) default NULL,
  `degustation` varchar(55) default NULL,
  `promoin` decimal(4,2) default NULL,
  `promoout` decimal(4,2) default NULL,
  `promoend` decimal(4,2) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  PRIMARY KEY  (`idanimproduit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `zoutclient`
--

DROP TABLE IF EXISTS `zoutclient`;
CREATE TABLE `zoutclient` (
  `idzoutclient` int(11) NOT NULL auto_increment,
  `idclient` int(11) NOT NULL default '0',
  `codeclient` int(10) default NULL,
  `societe` varchar(60) default NULL,
  `qualite` varchar(10) default NULL,
  `cprenom` varchar(30) default NULL,
  `cnom` varchar(30) default NULL,
  `fonction` varchar(30) default NULL,
  `langue` varchar(10) default NULL,
  `adresse` varchar(60) default NULL,
  `cp` varchar(10) default NULL,
  `ville` varchar(30) default NULL,
  `pays` varchar(30) default NULL,
  `email` varchar(50) default NULL,
  `notes` mediumtext,
  `notelarge` longtext,
  `tva` varchar(20) default NULL,
  `astva` char(2) default '4',
  `codetva` char(3) default 'BE',
  `logo` varchar(20) default NULL,
  `codecompta` varchar(15) default NULL,
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `login` varchar(15) default NULL,
  `password` varchar(15) default NULL,
  `facturation` tinyint(2) default NULL,
  `hforfait` tinyint(2) default NULL,
  `htable` tinyint(2) default NULL,
  `taheure` decimal(6,2) NOT NULL default '0.00',
  `takm` decimal(6,2) NOT NULL default '0.00',
  `taforfait` decimal(6,2) NOT NULL default '0.00',
  `taforfaitkm` decimal(6,2) default NULL,
  `tastand` decimal(6,2) NOT NULL default '0.00',
  `tabriefing` decimal(6,2) NOT NULL default '0.00',
  `tmheure` decimal(6,2) default NULL,
  `tmkm` decimal(6,2) default NULL,
  `tmforfait` decimal(6,2) default NULL,
  `tvheure05` decimal(6,2) default '18.00',
  `tvheure6` decimal(6,2) default '17.00',
  `tvnight` decimal(6,2) default '23.00',
  `tvkm` decimal(6,2) default '0.31',
  `agentmodif` int(11) default NULL,
  `datemodif` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idzoutclient`),
  KEY `codeclient` (`codeclient`),
  KEY `societe` (`societe`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `zoutcofficer`
--

DROP TABLE IF EXISTS `zoutcofficer`;
CREATE TABLE `zoutcofficer` (
  `idzoutcofficer` int(11) NOT NULL auto_increment,
  `idcofficer` int(11) NOT NULL default '0',
  `idclient` int(11) NOT NULL default '0',
  `langue` varchar(10) default NULL,
  `qualite` varchar(10) default NULL,
  `onom` varchar(30) default NULL,
  `oprenom` varchar(30) default NULL,
  `tel` varchar(15) default NULL,
  `fax` varchar(15) default NULL,
  `gsm` varchar(15) default NULL,
  `email` varchar(50) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `oldidclient` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`idzoutcofficer`),
  KEY `idclient` (`idclient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `zoutmerch`
--

DROP TABLE IF EXISTS `zoutmerch`;
CREATE TABLE `zoutmerch` (
  `idmerch` int(11) NOT NULL default '0',
  `idhagent` int(11) default NULL,
  `idclient` int(11) default NULL,
  `idcofficer` int(11) default NULL,
  `idshop` int(11) default NULL,
  `idpeople` int(11) default NULL,
  `mtype` varchar(50) default NULL,
  `produit` tinytext,
  `boncommande` varchar(55) default NULL,
  `datem` date default NULL,
  `recurrence` tinyint(2) NOT NULL default '1',
  `easremplac` tinyint(2) NOT NULL default '0',
  `weekm` int(3) default NULL,
  `hin1` time default NULL,
  `hout1` time default NULL,
  `hin2` time default NULL,
  `hout2` time default NULL,
  `ferie` float default '100',
  `kmpaye` mediumint(10) default NULL,
  `kmfacture` mediumint(10) default NULL,
  `frais` decimal(6,2) default NULL,
  `fraisfacture` decimal(6,2) default NULL,
  `livraison` decimal(6,2) NOT NULL default '0.00',
  `diversfrais` decimal(6,2) NOT NULL default '0.00',
  `note` mediumtext,
  `genre` varchar(55) default NULL,
  `datecontrat` date default NULL,
  `contratencode` varchar(5) NOT NULL default '0',
  `facturedirect` tinyint(4) default NULL,
  `facturation` tinyint(4) default NULL,
  `facnumtemp` varchar(12) default NULL,
  `facnum` varchar(15) default NULL,
  `agentmodif` int(11) default NULL,
  `datemodif` datetime default NULL,
  `hhcode` varchar(24) default '0',
  PRIMARY KEY  (`idmerch`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-04-04  8:22:58
