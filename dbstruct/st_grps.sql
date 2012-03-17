-- MySQL dump 10.11
--
-- Host: localhost    Database: grps
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
-- Current Database: `grps`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `grps` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `grps`;

--
-- Table structure for table `custom008`
--

DROP TABLE IF EXISTS `custom008`;
CREATE TABLE `custom008` (
  `id008` int(11) NOT NULL default '0',
  `idptg` int(11) NOT NULL default '0',
  `reg` int(11) NOT NULL default '0',
  `codeinfo` char(3) NOT NULL default '',
  `valinfo` varchar(200) default NULL,
  PRIMARY KEY  (`id008`),
  KEY `idptg` (`idptg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `custom009`
--

DROP TABLE IF EXISTS `custom009`;
CREATE TABLE `custom009` (
  `id009` int(11) NOT NULL auto_increment,
  `idptg` int(11) NOT NULL default '0',
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `modh` decimal(7,4) NOT NULL default '0.0000',
  `modh150` decimal(7,4) NOT NULL default '0.0000',
  `modh200` decimal(7,4) NOT NULL default '0.0000',
  `mod433` decimal(7,4) NOT NULL default '0.0000',
  `mod437` decimal(7,4) NOT NULL default '0.0000',
  `mod441` decimal(7,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`id009`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

--
-- Table structure for table `custom011`
--

DROP TABLE IF EXISTS `custom011`;
CREATE TABLE `custom011` (
  `id011` int(11) NOT NULL auto_increment,
  `idptg` int(11) NOT NULL default '0',
  `reg` int(11) NOT NULL default '0',
  `code` char(3) NOT NULL default '',
  `montant` decimal(7,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`id011`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `customptg`
--

DROP TABLE IF EXISTS `customptg`;
CREATE TABLE `customptg` (
  `idptg` int(11) NOT NULL auto_increment,
  `nomptg` varchar(100) default NULL,
  `datein` date NOT NULL default '0000-00-00',
  `dateout` date NOT NULL default '0000-00-00',
  `datesend` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idptg`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `gsdb`
--

DROP TABLE IF EXISTS `gsdb`;
CREATE TABLE `gsdb` (
  `idgroupes` int(11) NOT NULL auto_increment,
  `Z00` varchar(12) NOT NULL default '',
  `NVF` int(6) NOT NULL default '0',
  `NOM` varchar(36) default NULL,
  `PRE` varchar(36) default NULL,
  `RUE` varchar(36) default NULL,
  `NUR` varchar(5) default NULL,
  `NUB` char(3) default NULL,
  `CPP` char(2) default NULL,
  `CPO` varchar(7) default NULL,
  `LOC` varchar(36) default NULL,
  `Z80` varchar(15) default NULL,
  `Z81` varchar(15) default NULL,
  `Z82` varchar(15) default NULL,
  `Z83` varchar(120) default NULL,
  `Z10` varchar(10) default NULL,
  `LGE` char(2) default NULL,
  `SEX` char(1) default NULL,
  `NAT` char(1) default NULL,
  `ETC` char(1) default NULL,
  `DTN` varchar(10) default NULL,
  `CPN` varchar(7) default NULL,
  `LON` varchar(36) default NULL,
  `NDI` char(1) default NULL,
  `NUI` varchar(12) default NULL,
  `RNA` varchar(11) default NULL,
  `NOC` varchar(36) default NULL,
  `DNC` varchar(10) default NULL,
  `QPC` char(1) default NULL,
  `DTM` varchar(10) default NULL,
  `RPE` varchar(6) default NULL,
  `Z05` date default NULL,
  `CPE` char(1) default NULL,
  `PRO` varchar(48) default NULL,
  `MRB` varchar(11) default NULL,
  `NCF` varchar(12) default NULL,
  `APC` char(2) default NULL,
  `idptg` int(11) default NULL,
  `DTE` varchar(10) default NULL,
  `CSC` char(1) default NULL,
  `Z36` char(1) default NULL,
  `CCC` char(1) default NULL,
  `PCC` char(2) default NULL,
  `CBR` char(1) default NULL,
  `CBN` char(1) default NULL,
  `Z32` char(1) default NULL,
  `CMP` char(1) default NULL,
  `Z75` varchar(74) NOT NULL default '',
  `RLG` char(2) default NULL,
  `PRC` char(2) default NULL,
  `Z85` char(2) default NULL,
  `PFO` char(3) default NULL,
  `CCT` varchar(4) default NULL,
  `PAR` varchar(11) default NULL,
  `CSO` char(1) default NULL,
  `NEC` char(2) default NULL,
  `SAI` char(1) default NULL,
  PRIMARY KEY  (`idgroupes`),
  KEY `Z00` (`Z00`)
) ENGINE=MyISAM AUTO_INCREMENT=1422 DEFAULT CHARSET=latin1;

--
-- Table structure for table `gsdb0512`
--

DROP TABLE IF EXISTS `gsdb0512`;
CREATE TABLE `gsdb0512` (
  `idgroupes` int(11) NOT NULL auto_increment,
  `Z00` varchar(12) NOT NULL default '',
  `NVF` int(6) NOT NULL default '0',
  `NOM` varchar(36) default NULL,
  `PRE` varchar(36) default NULL,
  `RUE` varchar(36) default NULL,
  `NUR` varchar(5) default NULL,
  `NUB` char(3) default NULL,
  `CPP` char(2) default NULL,
  `CPO` varchar(7) default NULL,
  `LOC` varchar(36) default NULL,
  `Z80` varchar(15) default NULL,
  `Z81` varchar(15) default NULL,
  `Z82` varchar(15) default NULL,
  `Z83` varchar(120) default NULL,
  `Z10` varchar(10) default NULL,
  `LGE` char(2) default NULL,
  `SEX` char(1) default NULL,
  `NAT` char(1) default NULL,
  `ETC` char(1) default NULL,
  `DTN` varchar(10) default NULL,
  `CPN` varchar(7) default NULL,
  `LON` varchar(36) default NULL,
  `NDI` char(1) default NULL,
  `NUI` varchar(12) default NULL,
  `RNA` varchar(11) default NULL,
  `NOC` varchar(36) default NULL,
  `DNC` varchar(10) default NULL,
  `QPC` char(1) default NULL,
  `DTM` varchar(10) default NULL,
  `RPE` varchar(6) default NULL,
  `Z05` date default NULL,
  `CPE` char(1) default NULL,
  `PRO` varchar(48) default NULL,
  `MRB` varchar(11) default NULL,
  `NCF` varchar(12) default NULL,
  `APC` char(2) default NULL,
  `idptg` int(11) default NULL,
  `DTE` varchar(10) default NULL,
  `CSC` char(1) default NULL,
  `Z36` char(1) default NULL,
  `CCC` char(1) default NULL,
  `PCC` char(2) default NULL,
  `CBR` char(1) default NULL,
  `CBN` char(1) default NULL,
  `Z32` char(1) default NULL,
  `CMP` char(1) default NULL,
  `Z75` varchar(74) NOT NULL default '',
  `RLG` char(2) default NULL,
  `PRC` char(2) default NULL,
  `Z85` char(2) default NULL,
  `PFO` char(3) default NULL,
  `CCT` varchar(4) default NULL,
  `PAR` varchar(11) default NULL,
  `CSO` char(1) default NULL,
  `NEC` char(2) default NULL,
  `SAI` char(1) default NULL,
  `Z62` varchar(5) NOT NULL default '',
  `CTP` char(2) NOT NULL default '',
  PRIMARY KEY  (`idgroupes`),
  KEY `Z00` (`Z00`)
) ENGINE=MyISAM AUTO_INCREMENT=1866 DEFAULT CHARSET=latin1;

--
-- Table structure for table `gsdb0601`
--

DROP TABLE IF EXISTS `gsdb0601`;
CREATE TABLE `gsdb0601` (
  `idgroupes` int(11) NOT NULL auto_increment,
  `Z00` varchar(12) NOT NULL default '',
  `NVF` int(6) NOT NULL default '0',
  `NOM` varchar(36) default NULL,
  `PRE` varchar(36) default NULL,
  `RUE` varchar(36) default NULL,
  `NUR` varchar(5) default NULL,
  `NUB` char(3) default NULL,
  `CPP` char(2) default NULL,
  `CPO` varchar(7) default NULL,
  `LOC` varchar(36) default NULL,
  `Z80` varchar(15) default NULL,
  `Z81` varchar(15) default NULL,
  `Z82` varchar(15) default NULL,
  `Z83` varchar(120) default NULL,
  `Z10` varchar(10) default NULL,
  `LGE` char(2) default NULL,
  `SEX` char(1) default NULL,
  `NAT` char(1) default NULL,
  `ETC` char(1) default NULL,
  `DTN` varchar(10) default NULL,
  `CPN` varchar(7) default NULL,
  `LON` varchar(36) default NULL,
  `NDI` char(1) default NULL,
  `NUI` varchar(12) default NULL,
  `RNA` varchar(11) default NULL,
  `NOC` varchar(36) default NULL,
  `DNC` varchar(10) default NULL,
  `QPC` char(1) default NULL,
  `DTM` varchar(10) default NULL,
  `RPE` varchar(6) default NULL,
  `Z05` date default NULL,
  `CPE` char(1) default NULL,
  `PRO` varchar(48) default NULL,
  `MRB` varchar(11) default NULL,
  `NCF` varchar(12) default NULL,
  `APC` char(2) default NULL,
  `idptg` int(11) default NULL,
  `DTE` varchar(10) default NULL,
  `CSC` char(1) default NULL,
  `Z36` char(1) default NULL,
  `CCC` char(1) default NULL,
  `PCC` char(2) default NULL,
  `CBR` char(1) default NULL,
  `CBN` char(1) default NULL,
  `Z32` char(1) default NULL,
  `CMP` char(1) default NULL,
  `Z75` varchar(74) NOT NULL default '',
  `RLG` char(2) default NULL,
  `PRC` char(2) default NULL,
  `Z85` char(2) default NULL,
  `PFO` char(3) default NULL,
  `CCT` varchar(4) default NULL,
  `PAR` varchar(11) default NULL,
  `CSO` char(1) default NULL,
  `NEC` char(2) default NULL,
  `SAI` char(1) default NULL,
  PRIMARY KEY  (`idgroupes`),
  KEY `Z00` (`Z00`)
) ENGINE=MyISAM AUTO_INCREMENT=1945 DEFAULT CHARSET=latin1;

--
-- Table structure for table `gsdb0603`
--

DROP TABLE IF EXISTS `gsdb0603`;
CREATE TABLE `gsdb0603` (
  `idgroupes` int(11) NOT NULL auto_increment,
  `Z00` varchar(12) NOT NULL default '',
  `NVF` int(6) NOT NULL default '0',
  `NOM` varchar(36) default NULL,
  `PRE` varchar(36) default NULL,
  `RUE` varchar(36) default NULL,
  `NUR` varchar(5) default NULL,
  `NUB` char(3) default NULL,
  `CPP` char(2) default NULL,
  `CPO` varchar(7) default NULL,
  `LOC` varchar(36) default NULL,
  `Z80` varchar(15) default NULL,
  `Z81` varchar(15) default NULL,
  `Z82` varchar(15) default NULL,
  `Z83` varchar(120) default NULL,
  `Z10` varchar(10) default NULL,
  `LGE` char(2) default NULL,
  `SEX` char(1) default NULL,
  `NAT` char(1) default NULL,
  `ETC` char(1) default NULL,
  `DTN` varchar(10) default NULL,
  `CPN` varchar(7) default NULL,
  `LON` varchar(36) default NULL,
  `NDI` char(1) default NULL,
  `NUI` varchar(12) default NULL,
  `RNA` varchar(11) default NULL,
  `NOC` varchar(36) default NULL,
  `DNC` varchar(10) default NULL,
  `QPC` char(1) default NULL,
  `DTM` varchar(10) default NULL,
  `RPE` varchar(6) default NULL,
  `Z05` date default NULL,
  `CPE` char(1) default NULL,
  `PRO` varchar(48) default NULL,
  `MRB` varchar(11) default NULL,
  `NCF` varchar(12) default NULL,
  `APC` char(2) default NULL,
  `idptg` int(11) default NULL,
  `DTE` varchar(10) default NULL,
  `CSC` char(1) default NULL,
  `Z36` char(1) default NULL,
  `CCC` char(1) default NULL,
  `PCC` char(2) default NULL,
  `CBR` char(1) default NULL,
  `CBN` char(1) default NULL,
  `Z32` char(1) default NULL,
  `CMP` char(1) default NULL,
  `Z75` varchar(74) NOT NULL default '',
  `RLG` char(2) default NULL,
  `PRC` char(2) default NULL,
  `Z85` char(2) default NULL,
  `PFO` char(3) default NULL,
  `CCT` varchar(4) default NULL,
  `PAR` varchar(11) default NULL,
  `CSO` char(1) default NULL,
  `NEC` char(2) default NULL,
  `SAI` char(1) default NULL,
  PRIMARY KEY  (`idgroupes`),
  KEY `Z00` (`Z00`)
) ENGINE=MyISAM AUTO_INCREMENT=324 DEFAULT CHARSET=latin1;

--
-- Table structure for table `gsdb0604`
--

DROP TABLE IF EXISTS `gsdb0604`;
CREATE TABLE `gsdb0604` (
  `idgroupes` int(11) NOT NULL auto_increment,
  `Z00` varchar(12) NOT NULL default '',
  `NVF` int(6) NOT NULL default '0',
  `NOM` varchar(36) default NULL,
  `PRE` varchar(36) default NULL,
  `RUE` varchar(36) default NULL,
  `NUR` varchar(5) default NULL,
  `NUB` char(3) default NULL,
  `CPP` char(2) default NULL,
  `CPO` varchar(7) default NULL,
  `LOC` varchar(36) default NULL,
  `Z80` varchar(15) default NULL,
  `Z81` varchar(15) default NULL,
  `Z82` varchar(15) default NULL,
  `Z83` varchar(120) default NULL,
  `Z10` varchar(10) default NULL,
  `LGE` char(2) default NULL,
  `SEX` char(1) default NULL,
  `NAT` char(1) default NULL,
  `ETC` char(1) default NULL,
  `DTN` varchar(10) default NULL,
  `CPN` varchar(7) default NULL,
  `LON` varchar(36) default NULL,
  `NDI` char(1) default NULL,
  `NUI` varchar(12) default NULL,
  `RNA` varchar(11) default NULL,
  `NOC` varchar(36) default NULL,
  `DNC` varchar(10) default NULL,
  `QPC` char(1) default NULL,
  `DTM` varchar(10) default NULL,
  `RPE` varchar(6) default NULL,
  `Z05` date default NULL,
  `CPE` char(1) default NULL,
  `PRO` varchar(48) default NULL,
  `MRB` varchar(11) default NULL,
  `NCF` varchar(12) default NULL,
  `APC` char(2) default NULL,
  `idptg` int(11) default NULL,
  `DTE` varchar(10) default NULL,
  `CSC` char(1) default NULL,
  `Z36` char(1) default NULL,
  `CCC` char(1) default NULL,
  `PCC` char(2) default NULL,
  `CBR` char(1) default NULL,
  `CBN` char(1) default NULL,
  `Z32` char(1) default NULL,
  `CMP` char(1) default NULL,
  `Z75` varchar(74) NOT NULL default '',
  `RLG` char(2) default NULL,
  `PRC` char(2) default NULL,
  `Z85` char(2) default NULL,
  `PFO` char(3) default NULL,
  `CCT` varchar(4) default NULL,
  `PAR` varchar(11) default NULL,
  `CSO` char(1) default NULL,
  `NEC` char(2) default NULL,
  `SAI` char(1) default NULL,
  PRIMARY KEY  (`idgroupes`),
  KEY `Z00` (`Z00`)
) ENGINE=MyISAM AUTO_INCREMENT=606 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0309`
--

DROP TABLE IF EXISTS `salaires0309`;
CREATE TABLE `salaires0309` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2074 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0310`
--

DROP TABLE IF EXISTS `salaires0310`;
CREATE TABLE `salaires0310` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=13239 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0311`
--

DROP TABLE IF EXISTS `salaires0311`;
CREATE TABLE `salaires0311` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=4079 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0312`
--

DROP TABLE IF EXISTS `salaires0312`;
CREATE TABLE `salaires0312` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=3559 DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Table structure for table `salaires0401`
--

DROP TABLE IF EXISTS `salaires0401`;
CREATE TABLE `salaires0401` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2811 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0402`
--

DROP TABLE IF EXISTS `salaires0402`;
CREATE TABLE `salaires0402` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1182 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0403`
--

DROP TABLE IF EXISTS `salaires0403`;
CREATE TABLE `salaires0403` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1629 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0404`
--

DROP TABLE IF EXISTS `salaires0404`;
CREATE TABLE `salaires0404` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1462 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0405`
--

DROP TABLE IF EXISTS `salaires0405`;
CREATE TABLE `salaires0405` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=4127 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0406`
--

DROP TABLE IF EXISTS `salaires0406`;
CREATE TABLE `salaires0406` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1499 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0407`
--

DROP TABLE IF EXISTS `salaires0407`;
CREATE TABLE `salaires0407` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1491 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0408`
--

DROP TABLE IF EXISTS `salaires0408`;
CREATE TABLE `salaires0408` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1439 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0409`
--

DROP TABLE IF EXISTS `salaires0409`;
CREATE TABLE `salaires0409` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1732 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0410`
--

DROP TABLE IF EXISTS `salaires0410`;
CREATE TABLE `salaires0410` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2117 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0411`
--

DROP TABLE IF EXISTS `salaires0411`;
CREATE TABLE `salaires0411` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=4286 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0412`
--

DROP TABLE IF EXISTS `salaires0412`;
CREATE TABLE `salaires0412` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1674 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0501`
--

DROP TABLE IF EXISTS `salaires0501`;
CREATE TABLE `salaires0501` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2014 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0502`
--

DROP TABLE IF EXISTS `salaires0502`;
CREATE TABLE `salaires0502` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1346 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0503`
--

DROP TABLE IF EXISTS `salaires0503`;
CREATE TABLE `salaires0503` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1741 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0504`
--

DROP TABLE IF EXISTS `salaires0504`;
CREATE TABLE `salaires0504` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1882 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0505`
--

DROP TABLE IF EXISTS `salaires0505`;
CREATE TABLE `salaires0505` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1641 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0506`
--

DROP TABLE IF EXISTS `salaires0506`;
CREATE TABLE `salaires0506` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2076 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0507`
--

DROP TABLE IF EXISTS `salaires0507`;
CREATE TABLE `salaires0507` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1578 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0508`
--

DROP TABLE IF EXISTS `salaires0508`;
CREATE TABLE `salaires0508` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1546 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0509`
--

DROP TABLE IF EXISTS `salaires0509`;
CREATE TABLE `salaires0509` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1942 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0510`
--

DROP TABLE IF EXISTS `salaires0510`;
CREATE TABLE `salaires0510` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1919 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0511`
--

DROP TABLE IF EXISTS `salaires0511`;
CREATE TABLE `salaires0511` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2568 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0512`
--

DROP TABLE IF EXISTS `salaires0512`;
CREATE TABLE `salaires0512` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2047 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0601`
--

DROP TABLE IF EXISTS `salaires0601`;
CREATE TABLE `salaires0601` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2333 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0602`
--

DROP TABLE IF EXISTS `salaires0602`;
CREATE TABLE `salaires0602` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=3387 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0603`
--

DROP TABLE IF EXISTS `salaires0603`;
CREATE TABLE `salaires0603` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=3418 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0604`
--

DROP TABLE IF EXISTS `salaires0604`;
CREATE TABLE `salaires0604` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1800 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0605`
--

DROP TABLE IF EXISTS `salaires0605`;
CREATE TABLE `salaires0605` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2036 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0606`
--

DROP TABLE IF EXISTS `salaires0606`;
CREATE TABLE `salaires0606` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1930 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0607`
--

DROP TABLE IF EXISTS `salaires0607`;
CREATE TABLE `salaires0607` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1550 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0608`
--

DROP TABLE IF EXISTS `salaires0608`;
CREATE TABLE `salaires0608` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1509 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0609`
--

DROP TABLE IF EXISTS `salaires0609`;
CREATE TABLE `salaires0609` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2020 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0610`
--

DROP TABLE IF EXISTS `salaires0610`;
CREATE TABLE `salaires0610` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1905 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0611`
--

DROP TABLE IF EXISTS `salaires0611`;
CREATE TABLE `salaires0611` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2679 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0612`
--

DROP TABLE IF EXISTS `salaires0612`;
CREATE TABLE `salaires0612` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2500 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salaires0701`
--

DROP TABLE IF EXISTS `salaires0701`;
CREATE TABLE `salaires0701` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=4105 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0401`
--

DROP TABLE IF EXISTS `salairesbis0401`;
CREATE TABLE `salairesbis0401` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0402`
--

DROP TABLE IF EXISTS `salairesbis0402`;
CREATE TABLE `salairesbis0402` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0407`
--

DROP TABLE IF EXISTS `salairesbis0407`;
CREATE TABLE `salairesbis0407` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0410`
--

DROP TABLE IF EXISTS `salairesbis0410`;
CREATE TABLE `salairesbis0410` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0411`
--

DROP TABLE IF EXISTS `salairesbis0411`;
CREATE TABLE `salairesbis0411` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0501`
--

DROP TABLE IF EXISTS `salairesbis0501`;
CREATE TABLE `salairesbis0501` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0503`
--

DROP TABLE IF EXISTS `salairesbis0503`;
CREATE TABLE `salairesbis0503` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0504`
--

DROP TABLE IF EXISTS `salairesbis0504`;
CREATE TABLE `salairesbis0504` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0505`
--

DROP TABLE IF EXISTS `salairesbis0505`;
CREATE TABLE `salairesbis0505` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0506`
--

DROP TABLE IF EXISTS `salairesbis0506`;
CREATE TABLE `salairesbis0506` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0507`
--

DROP TABLE IF EXISTS `salairesbis0507`;
CREATE TABLE `salairesbis0507` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0509`
--

DROP TABLE IF EXISTS `salairesbis0509`;
CREATE TABLE `salairesbis0509` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0510`
--

DROP TABLE IF EXISTS `salairesbis0510`;
CREATE TABLE `salairesbis0510` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0511`
--

DROP TABLE IF EXISTS `salairesbis0511`;
CREATE TABLE `salairesbis0511` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0512`
--

DROP TABLE IF EXISTS `salairesbis0512`;
CREATE TABLE `salairesbis0512` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0606`
--

DROP TABLE IF EXISTS `salairesbis0606`;
CREATE TABLE `salairesbis0606` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0608`
--

DROP TABLE IF EXISTS `salairesbis0608`;
CREATE TABLE `salairesbis0608` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0609`
--

DROP TABLE IF EXISTS `salairesbis0609`;
CREATE TABLE `salairesbis0609` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2056 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0610`
--

DROP TABLE IF EXISTS `salairesbis0610`;
CREATE TABLE `salairesbis0610` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=1930 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0611`
--

DROP TABLE IF EXISTS `salairesbis0611`;
CREATE TABLE `salairesbis0611` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairesbis0612`
--

DROP TABLE IF EXISTS `salairesbis0612`;
CREATE TABLE `salairesbis0612` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0407`
--

DROP TABLE IF EXISTS `salairester0407`;
CREATE TABLE `salairester0407` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0411`
--

DROP TABLE IF EXISTS `salairester0411`;
CREATE TABLE `salairester0411` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0501`
--

DROP TABLE IF EXISTS `salairester0501`;
CREATE TABLE `salairester0501` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0504`
--

DROP TABLE IF EXISTS `salairester0504`;
CREATE TABLE `salairester0504` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0505`
--

DROP TABLE IF EXISTS `salairester0505`;
CREATE TABLE `salairester0505` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0506`
--

DROP TABLE IF EXISTS `salairester0506`;
CREATE TABLE `salairester0506` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0509`
--

DROP TABLE IF EXISTS `salairester0509`;
CREATE TABLE `salairester0509` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0510`
--

DROP TABLE IF EXISTS `salairester0510`;
CREATE TABLE `salairester0510` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0609`
--

DROP TABLE IF EXISTS `salairester0609`;
CREATE TABLE `salairester0609` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Table structure for table `salairester0610`
--

DROP TABLE IF EXISTS `salairester0610`;
CREATE TABLE `salairester0610` (
  `idsalaire` int(11) NOT NULL auto_increment,
  `idpeople` int(11) NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `mod433` decimal(8,4) default NULL,
  `mod437` decimal(8,4) default NULL,
  `mod441` decimal(8,4) default NULL,
  `modh` decimal(8,4) default NULL,
  `modh150` decimal(8,4) default NULL,
  `modh200` decimal(8,4) default NULL,
  PRIMARY KEY  (`idsalaire`),
  KEY `idpeople` (`idpeople`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-04-04  8:22:52
