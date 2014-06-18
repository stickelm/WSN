-- MySQL dump 10.11
--
-- Host: localhost    Database: 
-- ------------------------------------------------------
-- Server version	5.0.51a-24+lenny3

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
-- Current Database: `MeshliumDB`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `MeshliumDB` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `MeshliumDB`;

--
-- Table structure for table `bluetoothData`
--

DROP TABLE IF EXISTS `bluetoothData`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `bluetoothData` (
  `ID_frame` int(11) NOT NULL auto_increment,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `MAC` varchar(17) collate utf8_unicode_ci NOT NULL,
  `ID` varchar(20) collate utf8_unicode_ci NOT NULL,
  `RSSI` varchar(3) collate utf8_unicode_ci NOT NULL,
  `Vendor` varchar(20) collate utf8_unicode_ci NOT NULL,
  `cod` varchar(20) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ID_frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `currentSensors`
--

DROP TABLE IF EXISTS `currentSensors`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `currentSensors` (
  `OBJECTID` int(11) NOT NULL,
  `waspmoteid` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `sensorReading` varchar(45) NOT NULL,
  `sensorType` varchar(45) NOT NULL,
  `sensorValue` double default NULL,
  `extendedValue` varchar(45) default NULL,
  `units` varchar(45) NOT NULL,
  `timestamp` varchar(45) NOT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  PRIMARY KEY  (`OBJECTID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `encryptionData`
--

DROP TABLE IF EXISTS `encryptionData`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `encryptionData` (
  `id` int(100) NOT NULL auto_increment,
  `waspmote_id` varchar(100) character set utf8 collate utf8_unicode_ci NOT NULL,
  `private_aes` varchar(100) character set utf8 collate utf8_unicode_ci default NULL,
  `public_rsa` varchar(130) character set utf8 collate utf8_unicode_ci default NULL,
  `modulus_rsa` varchar(130) character set utf8 collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `gpsData`
--

DROP TABLE IF EXISTS `gpsData`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `gpsData` (
  `ID_frame` int(11) NOT NULL auto_increment,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `date` datetime NOT NULL,
  `longitude` text collate utf8_unicode_ci NOT NULL,
  `latitude` text collate utf8_unicode_ci NOT NULL,
  `altitude` text collate utf8_unicode_ci NOT NULL,
  `satellites` int(11) NOT NULL,
  `speed` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ID_frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `meshlium`
--

DROP TABLE IF EXISTS `meshlium`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `meshlium` (
  `objectid` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `description` varchar(100) NOT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  `spatialReference` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sensorParser`
--

DROP TABLE IF EXISTS `sensorParser`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sensorParser` (
  `id` int(11) NOT NULL auto_increment,
  `id_wasp` VARCHAR(10) NOT NULL,
  `id_secret` text character set utf8 collate utf8_unicode_ci,
  `frame_type` int(11) default NULL,
  `frame_number` int(11) default NULL,
  `sensor` VARCHAR(10) NOT NULL,
  `value` text character set utf8 collate utf8_unicode_ci,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `sync` int(1) NOT NULL default '0',
  `raw` text character set utf8 collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=78053 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `sensors`
--

DROP TABLE IF EXISTS `sensors`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sensors` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `id_ascii` varchar(45) NOT NULL,
  `units` varchar(45) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tokens` (
  `idtokens` int(11) NOT NULL auto_increment,
  `token` varchar(45) NOT NULL,
  `expires` varchar(45) NOT NULL,
  `referer` varchar(45) default NULL,
  `ip` varchar(45) default NULL,
  PRIMARY KEY  (`idtokens`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `idusers` int(11) NOT NULL auto_increment,
  `user` varchar(45) NOT NULL,
  `passwd` varchar(45) NOT NULL,
  PRIMARY KEY  (`idusers`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `waspmote`
--

DROP TABLE IF EXISTS `waspmote`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `waspmote` (
  `OBJECTID` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  `spatialReference` double NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sensorCount` int(11) NOT NULL,
  `meshliumid` varchar(45) NOT NULL,
  PRIMARY KEY  (`OBJECTID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wifiScan`
--

DROP TABLE IF EXISTS `wifiScan`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `wifiScan` (
  `ID_frame` int(11) NOT NULL auto_increment,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `MAC` varchar(17) character set utf8 collate utf8_unicode_ci NOT NULL,
  `AP` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `RSSI` varchar(3) character set utf8 collate utf8_unicode_ci NOT NULL,
  `Vendor` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  KEY `ID_frame` (`ID_frame`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `zigbeeData`
--

DROP TABLE IF EXISTS `zigbeeData`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `zigbeeData` (
  `ID_frame` int(11) NOT NULL auto_increment,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `mac` varchar(16) collate utf8_unicode_ci NOT NULL,
  `x` varchar(16) collate utf8_unicode_ci NOT NULL,
  `y` varchar(16) collate utf8_unicode_ci NOT NULL,
  `z` varchar(16) collate utf8_unicode_ci NOT NULL,
  `temp` varchar(16) collate utf8_unicode_ci NOT NULL,
  `bat` varchar(16) collate utf8_unicode_ci NOT NULL,
  `frame` varchar(200) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ID_frame`)
) ENGINE=MyISAM AUTO_INCREMENT=37668 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Current Database: `macaddress`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `macaddress` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `macaddress`;

--
-- Table structure for table `ArpWatchData`
--

DROP TABLE IF EXISTS `ArpWatchData`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ArpWatchData` (
  `time_detected` datetime NOT NULL,
  `ip_address` varchar(32) NOT NULL,
  `mac` varchar(32) NOT NULL,
  `name` varchar(128) NOT NULL,
  UNIQUE KEY `my_arp_key` (`time_detected`,`ip_address`,`mac`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `OUIData`
--

DROP TABLE IF EXISTS `OUIData`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `OUIData` (
  `hex_mac` varchar(16) NOT NULL,
  `oui` varchar(512) NOT NULL,
  PRIMARY KEY  (`hex_mac`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Current Database: `mysql`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mysql` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `mysql`;

--
-- Table structure for table `columns_priv`
--

DROP TABLE IF EXISTS `columns_priv`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `columns_priv` (
  `Host` char(60) collate utf8_bin NOT NULL default '',
  `Db` char(64) collate utf8_bin NOT NULL default '',
  `User` char(16) collate utf8_bin NOT NULL default '',
  `Table_name` char(64) collate utf8_bin NOT NULL default '',
  `Column_name` char(64) collate utf8_bin NOT NULL default '',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Column_priv` set('Select','Insert','Update','References') character set utf8 NOT NULL default '',
  PRIMARY KEY  (`Host`,`Db`,`User`,`Table_name`,`Column_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column privileges';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `db`
--

DROP TABLE IF EXISTS `db`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `db` (
  `Host` char(60) collate utf8_bin NOT NULL default '',
  `Db` char(64) collate utf8_bin NOT NULL default '',
  `User` char(16) collate utf8_bin NOT NULL default '',
  `Select_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Insert_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Update_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Delete_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Drop_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Grant_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `References_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Index_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Alter_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_tmp_table_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Lock_tables_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_view_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Show_view_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_routine_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Alter_routine_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Execute_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  PRIMARY KEY  (`Host`,`Db`,`User`),
  KEY `User` (`User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database privileges';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `func`
--

DROP TABLE IF EXISTS `func`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `func` (
  `name` char(64) collate utf8_bin NOT NULL default '',
  `ret` tinyint(1) NOT NULL default '0',
  `dl` char(128) collate utf8_bin NOT NULL default '',
  `type` enum('function','aggregate') character set utf8 NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User defined functions';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `help_category`
--

DROP TABLE IF EXISTS `help_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `help_category` (
  `help_category_id` smallint(5) unsigned NOT NULL,
  `name` char(64) NOT NULL,
  `parent_category_id` smallint(5) unsigned default NULL,
  `url` char(128) NOT NULL,
  PRIMARY KEY  (`help_category_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='help categories';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `help_keyword`
--

DROP TABLE IF EXISTS `help_keyword`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `help_keyword` (
  `help_keyword_id` int(10) unsigned NOT NULL,
  `name` char(64) NOT NULL,
  PRIMARY KEY  (`help_keyword_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='help keywords';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `help_relation`
--

DROP TABLE IF EXISTS `help_relation`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `help_relation` (
  `help_topic_id` int(10) unsigned NOT NULL,
  `help_keyword_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`help_keyword_id`,`help_topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='keyword-topic relation';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `help_topic`
--

DROP TABLE IF EXISTS `help_topic`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `help_topic` (
  `help_topic_id` int(10) unsigned NOT NULL,
  `name` char(64) NOT NULL,
  `help_category_id` smallint(5) unsigned NOT NULL,
  `description` text NOT NULL,
  `example` text NOT NULL,
  `url` char(128) NOT NULL,
  PRIMARY KEY  (`help_topic_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='help topics';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `host`
--

DROP TABLE IF EXISTS `host`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `host` (
  `Host` char(60) collate utf8_bin NOT NULL default '',
  `Db` char(64) collate utf8_bin NOT NULL default '',
  `Select_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Insert_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Update_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Delete_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Drop_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Grant_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `References_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Index_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Alter_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_tmp_table_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Lock_tables_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_view_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Show_view_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_routine_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Alter_routine_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Execute_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  PRIMARY KEY  (`Host`,`Db`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Host privileges;  Merged with database privileges';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `proc`
--

DROP TABLE IF EXISTS `proc`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `proc` (
  `db` char(64) character set utf8 collate utf8_bin NOT NULL default '',
  `name` char(64) NOT NULL default '',
  `type` enum('FUNCTION','PROCEDURE') NOT NULL,
  `specific_name` char(64) NOT NULL default '',
  `language` enum('SQL') NOT NULL default 'SQL',
  `sql_data_access` enum('CONTAINS_SQL','NO_SQL','READS_SQL_DATA','MODIFIES_SQL_DATA') NOT NULL default 'CONTAINS_SQL',
  `is_deterministic` enum('YES','NO') NOT NULL default 'NO',
  `security_type` enum('INVOKER','DEFINER') NOT NULL default 'DEFINER',
  `param_list` blob NOT NULL,
  `returns` char(64) NOT NULL default '',
  `body` longblob NOT NULL,
  `definer` char(77) character set utf8 collate utf8_bin NOT NULL default '',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00',
  `sql_mode` set('REAL_AS_FLOAT','PIPES_AS_CONCAT','ANSI_QUOTES','IGNORE_SPACE','NOT_USED','ONLY_FULL_GROUP_BY','NO_UNSIGNED_SUBTRACTION','NO_DIR_IN_CREATE','POSTGRESQL','ORACLE','MSSQL','DB2','MAXDB','NO_KEY_OPTIONS','NO_TABLE_OPTIONS','NO_FIELD_OPTIONS','MYSQL323','MYSQL40','ANSI','NO_AUTO_VALUE_ON_ZERO','NO_BACKSLASH_ESCAPES','STRICT_TRANS_TABLES','STRICT_ALL_TABLES','NO_ZERO_IN_DATE','NO_ZERO_DATE','INVALID_DATES','ERROR_FOR_DIVISION_BY_ZERO','TRADITIONAL','NO_AUTO_CREATE_USER','HIGH_NOT_PRECEDENCE') NOT NULL default '',
  `comment` char(64) character set utf8 collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (`db`,`name`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stored Procedures';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `procs_priv`
--

DROP TABLE IF EXISTS `procs_priv`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `procs_priv` (
  `Host` char(60) collate utf8_bin NOT NULL default '',
  `Db` char(64) collate utf8_bin NOT NULL default '',
  `User` char(16) collate utf8_bin NOT NULL default '',
  `Routine_name` char(64) collate utf8_bin NOT NULL default '',
  `Routine_type` enum('FUNCTION','PROCEDURE') collate utf8_bin NOT NULL,
  `Grantor` char(77) collate utf8_bin NOT NULL default '',
  `Proc_priv` set('Execute','Alter Routine','Grant') character set utf8 NOT NULL default '',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`Host`,`Db`,`User`,`Routine_name`,`Routine_type`),
  KEY `Grantor` (`Grantor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Procedure privileges';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tables_priv`
--

DROP TABLE IF EXISTS `tables_priv`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tables_priv` (
  `Host` char(60) collate utf8_bin NOT NULL default '',
  `Db` char(64) collate utf8_bin NOT NULL default '',
  `User` char(16) collate utf8_bin NOT NULL default '',
  `Table_name` char(64) collate utf8_bin NOT NULL default '',
  `Grantor` char(77) collate utf8_bin NOT NULL default '',
  `Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Table_priv` set('Select','Insert','Update','Delete','Create','Drop','Grant','References','Index','Alter','Create View','Show view') character set utf8 NOT NULL default '',
  `Column_priv` set('Select','Insert','Update','References') character set utf8 NOT NULL default '',
  PRIMARY KEY  (`Host`,`Db`,`User`,`Table_name`),
  KEY `Grantor` (`Grantor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table privileges';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `time_zone`
--

DROP TABLE IF EXISTS `time_zone`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `time_zone` (
  `Time_zone_id` int(10) unsigned NOT NULL auto_increment,
  `Use_leap_seconds` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`Time_zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zones';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `time_zone_leap_second`
--

DROP TABLE IF EXISTS `time_zone_leap_second`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `time_zone_leap_second` (
  `Transition_time` bigint(20) NOT NULL,
  `Correction` int(11) NOT NULL,
  PRIMARY KEY  (`Transition_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Leap seconds information for time zones';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `time_zone_name`
--

DROP TABLE IF EXISTS `time_zone_name`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `time_zone_name` (
  `Name` char(64) NOT NULL,
  `Time_zone_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone names';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `time_zone_transition`
--

DROP TABLE IF EXISTS `time_zone_transition`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `time_zone_transition` (
  `Time_zone_id` int(10) unsigned NOT NULL,
  `Transition_time` bigint(20) NOT NULL,
  `Transition_type_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`Time_zone_id`,`Transition_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone transitions';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `time_zone_transition_type`
--

DROP TABLE IF EXISTS `time_zone_transition_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `time_zone_transition_type` (
  `Time_zone_id` int(10) unsigned NOT NULL,
  `Transition_type_id` int(10) unsigned NOT NULL,
  `Offset` int(11) NOT NULL default '0',
  `Is_DST` tinyint(3) unsigned NOT NULL default '0',
  `Abbreviation` char(8) NOT NULL default '',
  PRIMARY KEY  (`Time_zone_id`,`Transition_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone transition types';
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user` (
  `Host` char(60) collate utf8_bin NOT NULL default '',
  `User` char(16) collate utf8_bin NOT NULL default '',
  `Password` char(41) character set latin1 collate latin1_bin NOT NULL default '',
  `Select_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Insert_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Update_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Delete_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Drop_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Reload_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Shutdown_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Process_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `File_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Grant_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `References_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Index_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Alter_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Show_db_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Super_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_tmp_table_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Lock_tables_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Execute_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Repl_slave_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Repl_client_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_view_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Show_view_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_routine_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Alter_routine_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `Create_user_priv` enum('N','Y') character set utf8 NOT NULL default 'N',
  `ssl_type` enum('','ANY','X509','SPECIFIED') character set utf8 NOT NULL default '',
  `ssl_cipher` blob NOT NULL,
  `x509_issuer` blob NOT NULL,
  `x509_subject` blob NOT NULL,
  `max_questions` int(11) unsigned NOT NULL default '0',
  `max_updates` int(11) unsigned NOT NULL default '0',
  `max_connections` int(11) unsigned NOT NULL default '0',
  `max_user_connections` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`Host`,`User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and global privileges';
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-06  8:30:21
