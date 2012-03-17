-- MySQL dump 10.11
--
-- Host: localhost    Database: dimona
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
-- Current Database: `dimona`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dimona` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dimona`;

--
-- Table structure for table `declarations`
--

DROP TABLE IF EXISTS `declarations`;
CREATE TABLE `declarations` (
  `iddecl` int(11) NOT NULL auto_increment,
  `idfile` int(11) NOT NULL default '0',
  `typedecl` enum('entree','sortie','modification','annulation') NOT NULL default 'entree',
  `rna` char(11) NOT NULL default '',
  `datein` date NOT NULL default '0000-00-00',
  `dateout` date NOT NULL default '0000-00-00',
  `numcartesis` char(10) NOT NULL default '',
  `indicatifdecl` char(14) NOT NULL default '',
  `numaccuse` char(12) NOT NULL default '',
  `numdimona` char(12) NOT NULL default '',
  PRIMARY KEY  (`iddecl`),
  KEY `rna` (`rna`,`datein`,`dateout`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='fichiers DIMONA';

--
-- Table structure for table `fichiers`
--

DROP TABLE IF EXISTS `fichiers`;
CREATE TABLE `fichiers` (
  `idfile` int(11) NOT NULL auto_increment,
  `ftype` enum('accuse','declaration','avis') NOT NULL default 'accuse',
  `fname` char(34) NOT NULL default '',
  `filesend` char(34) NOT NULL default '',
  `datesend` datetime NOT NULL default '0000-00-00 00:00:00',
  `fnum` char(10) NOT NULL default '',
  `version` char(3) NOT NULL default '',
  `nbrenregistrements` int(11) NOT NULL default '0',
  `nbredecl` int(11) NOT NULL default '0',
  `dateprocessed` datetime NOT NULL default '0000-00-00 00:00:00',
  `valid` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`idfile`),
  KEY `ftype` (`ftype`,`valid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='fichiers DIMONA';
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-04-04  8:22:50
