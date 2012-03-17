-- MySQL dump 10.11
--
-- Host: localhost    Database: task
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
-- Current Database: `task`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `task` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `task`;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `ID` int(11) NOT NULL auto_increment,
  `parent` int(11) default NULL,
  `title` text,
  `notes` text,
  `status` int(11) default NULL,
  `priority` int(11) default '3',
  `URL_1` text,
  `URL_2` text,
  `URL_3` text,
  `date_due` date default NULL,
  `date_modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `date_entered` timestamp NOT NULL default '0000-00-00 00:00:00',
  `obsolete` smallint(6) default '0',
  `container` smallint(6) default '0',
  `type` smallint(6) NOT NULL default '0',
  `user_date_1` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_date_2` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_text_1` text NOT NULL,
  `user_text_2` text NOT NULL,
  `user_int_1` int(11) NOT NULL default '0',
  `user_int_2` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-04-04  8:23:16
