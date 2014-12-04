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
-- Dumping data for table `meshlium`
--

LOCK TABLES `meshlium` WRITE;
/*!40000 ALTER TABLE `meshlium` DISABLE KEYS */;
INSERT INTO `meshlium` VALUES (1,'meshlium-a','Meshlium at Section A',103.771750156521,1.2988867609735,4326,'2014-06-16 07:23:40');
/*!40000 ALTER TABLE `meshlium` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY  (`id`),
   KEY `id_wasp` (`id_wasp`,`sensor`,`timestamp`),
  KEY `id_wasp_2` (`id_wasp`,`timestamp`)
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
-- Dumping data for table `sensors`
--

LOCK TABLES `sensors` WRITE;
/*!40000 ALTER TABLE `sensors` DISABLE KEYS */;
INSERT INTO `sensors` VALUES (0,'Carbon Monoxide','Carbon Monoxide','CO','voltage',2),(1,'Carbon Dioxide','Carbon Dioxide','CO2','voltage',2),(2,'Oxygen','Oxygen','O2','voltage',2),(3,'Methane','Methane','CH4','voltage',2),(4,'Liquefied Petroleum Gases','Liquefied Petroleum Gases','LPG','voltage',2),(5,'Ammonia','Ammonia','NH3','voltage',2),(6,'Air Pollutants 1','Air Pollutants 1','AP1','voltage',2),(7,'Air Pollutants 2','Air Pollutants 2','AP2','voltage',2),(8,'Solvent Vapors','Solvent Vapors','SV','voltage',2),(9,'Nitrogren Dioxide','Nitrogren Dioxide','NO2','voltage',2),(10,'Ozone','Ozone','O3','voltage',2),(11,'Hydrocarbons','Hydrocarbons','VOC','voltage',2),(12,'Temperature Celsius','Temperature Celsius','TCA','ºC',2),(13,'Temperature Fahrenheit','Temperature Fahrenheit','TFA','ºF',2),(14,'Humidity','Humidity','HUMA','%RH',2),(15,'Pressure atmospheric','Pressure atmospheric','PA','Kilo Pascales',2),(16,'Pressure/Weight','Pressure/Weight','PW','Ohms',2),(17,'Bend','Bend','BEND','Ohms',2),(18,'Vibration','Vibration','VBR','Open/Closed',0),(19,'Hall Effect','Hall Effect','HALL','Open/Closed',0),(20,'Liquid Presence','Liquid Presence','LP','Open/Closed',0),(21,'Liquid Level','Liquid Level','LL','Open/Closed',0),(22,'Luminosity','Luminosity','LUM','Ohms',2),(23,'Presence','Presence','PIR','presence/not presence',0),(24,'Stretch','Stretch','ST','Ohms',0),(25,'Microphone','Microphone','MCP','dBA',0),(26,'Crack detection gauge','Crack detection gauge','CDG','true/false',0),(27,'Crack propagation gauge','Crack propagation gauge','CPG','Ohms',2),(28,'Linear Displacement','Linear Displacement','LD','mm',2),(29,'Dust','Dust','DUST','mg/m3',2),(30,'Ultrasound','Ultrasound','US','m',2),(31,'Magnetic Field','Magnetic Field','MF','LSBs',3),(32,'Parking Spot Status','Parking Spot Status','PS','Occupied/Empty',0),(33,'Temperature ºC (Sensirion)','Temperature ºC (Sensirion)','TCB','ºC',2),(34,'Temperature ºF (Sensirion)','Temperature ºF (Sensirion)','TFB','ºF',2),(35,'Humidity (Sensirion)','Humidity (Sensirion)','HUMB','%RH',2),(36,'Soil Temperature','Soil Temperature','SOILT','ºC',2),(37,'Soil Moisture','Soil Moisture','SOIL','Frequency',2),(38,'Leaf Wetness','Leaf Wetness','LW','%',0),(39,'Solar Radiation','Solar Radiation','PAR','umol*m-2*s-1',2),(40,'Ultraviolet Radiation','Ultraviolet Radiation','UV','umol*m-2*s-1',2),(41,'Trunk Diameter','Trunk Diameter','TD','mm',2),(42,'Stem Diameter','Stem Diameter','SD','mm',2),(43,'Fruit Diameter','Fruit Diameter','FD','mm',2),(44,'Anemometer','Anemometer','ANE','km/h',2),(45,'Wind Vane','Wind Vane','WV','Direction',0),(46,'Pluviometer','Pluviometer','PLV','mm/min',2),(47,'Geiger Tube','Geiger Tube','RAD','uSv or cpm',2),(48,'Current','Current','CU','A',2),(49,'Water Flow','Water Flow','WF','l/min',2),(50,'Load Cell','Load Cell','LC','voltaje',2),(51,'Distante Foil','Distante Foil','DF','Ohms',2),(52,'Battery','Battery','BAT','%',0),(53,'Global Positioning System','Global Positioning System','GPS','degrees',3),(54,'RSSI','RSSI','RSSI','N/A',1),(55,'MAC Address','MAC Address','MAC','N/A',3),(56,'Network Address (XBEE)','Network Address (XBEE)','NA','N/A',3),(57,'Network ID origin (XBEE)','Network ID origin (XBEE)','NID','N/A',3),(58,'Date','Date','DATE','N/A',3),(59,'Time','Time','TIME','N/A',3),(60,'GMT','GMT','GMT','N/A',1),(61,'Free_RAM','Free_RAM','RAM','bytes',1),(62,'Internal Temperature','Internal Temperature','IN_TEMP','ºC',2),(63,'Accelerometer','Accelerometer','ACC','mg',3),(64,'Millis','Millis','MILLIS','ms',4),(65,'String','String','STR','N/A',3),(66,'Meshlium Bluetooth Scanner','Meshlium Bluetooth Scanner','MBT','N/A',3),(67,'Meshlium WI-FI Scanner','Meshlium WI-FI Scanner','MWIFI','N/A',3),(68,'Unique Identifier','Unique Identifier','UID','N/A',3),(69,'RFID Block','RFID Block','RB','N/A',3);
/*!40000 ALTER TABLE `sensors` ENABLE KEYS */;
UNLOCK TABLES;


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


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-06  8:30:21
