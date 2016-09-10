/* This script is generated from current Develop Database. */
/* Existing data is for testing purpose.*/

CREATE DATABASE  IF NOT EXISTS `StellaDB` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `StellaDB`;
-- MySQL dump 10.13  Distrib 5.7.12, for osx10.9 (x86_64)
--
-- Host: localhost    Database: StellaDB
-- ------------------------------------------------------
-- Server version	5.7.12

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
-- Table structure for table `Administrator`
--

DROP TABLE IF EXISTS `Administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Administrator` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `CreationTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Administrator`
--

LOCK TABLES `Administrator` WRITE;
/*!40000 ALTER TABLE `Administrator` DISABLE KEYS */;
INSERT INTO `Administrator` VALUES (1,'StellaSu','stella',0,'2016-08-17 19:51:08');
/*!40000 ALTER TABLE `Administrator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Area`
--

DROP TABLE IF EXISTS `Area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Area` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UpdateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Area`
--

LOCK TABLES `Area` WRITE;
/*!40000 ALTER TABLE `Area` DISABLE KEYS */;
INSERT INTO `Area` VALUES (1,'San Diego','Finest city in America',0,'2016-09-05 21:02:08'),(2,'New York','Metropolis city',0,'2016-09-05 21:03:48'),(3,'LA','Los Angeles',1,'2016-09-05 21:54:46'),(4,'LA','Los Angeles',1,'2016-09-05 21:54:47'),(5,'LA','Los Angeles',1,'2016-09-05 14:52:10'),(6,'LA','Los Angeles',1,'2016-09-05 21:54:39'),(7,'LA','Los Angeles',1,'2016-09-05 21:54:41');
/*!40000 ALTER TABLE `Area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CustomerItinerary`
--

DROP TABLE IF EXISTS `CustomerItinerary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CustomerItinerary` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ItineraryId` int(10) unsigned NOT NULL,
  `CustomerSubmissionId` int(10) unsigned NOT NULL,
  `Note` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `Closed` tinyint(1) NOT NULL DEFAULT '0',
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `CreationTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `ItineraryId` (`ItineraryId`),
  KEY `CustomerSubmissionId` (`CustomerSubmissionId`),
  CONSTRAINT `customeritinerary_ibfk_1` FOREIGN KEY (`ItineraryId`) REFERENCES `Itinerary` (`Id`),
  CONSTRAINT `customeritinerary_ibfk_2` FOREIGN KEY (`CustomerSubmissionId`) REFERENCES `CustomerSubmission` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CustomerItinerary`
--

LOCK TABLES `CustomerItinerary` WRITE;
/*!40000 ALTER TABLE `CustomerItinerary` DISABLE KEYS */;
INSERT INTO `CustomerItinerary` VALUES (1,1,19,NULL,0,0,0,'2016-08-14 14:36:04','2016-09-07 05:02:30'),(2,2,23,NULL,0,0,0,'2016-08-14 16:04:34','2016-08-14 16:04:34'),(3,2,24,NULL,0,0,0,'2016-08-14 16:05:24','2016-08-14 16:05:24');
/*!40000 ALTER TABLE `CustomerItinerary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CustomerSubmission`
--

DROP TABLE IF EXISTS `CustomerSubmission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CustomerSubmission` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `LastName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PhoneNumber` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Wechat` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Message` varchar(20000) COLLATE utf8_unicode_ci NOT NULL,
  `Type` int(10) unsigned NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `CreationTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `fk_Type` (`Type`),
  CONSTRAINT `fk_Type` FOREIGN KEY (`Type`) REFERENCES `SubmissionType` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CustomerSubmission`
--

LOCK TABLES `CustomerSubmission` WRITE;
/*!40000 ALTER TABLE `CustomerSubmission` DISABLE KEYS */;
INSERT INTO `CustomerSubmission` VALUES (1,'Biyao','Su',NULL,'6462484597','stella_su','留言',1,1,'2016-07-28 20:24:36','2016-09-08 03:51:16'),(2,'碧瑶','苏',NULL,NULL,'stella_su','53843209%￥*“：',1,1,'2016-07-28 20:26:13','2016-09-08 03:50:14'),(3,'Biyao','Su','biyaosu@gmail.com','4445554433',NULL,'\'\'haishui好蓝&lt;div&gt;',1,1,'2016-07-28 20:35:19','2016-09-05 05:26:54'),(4,'Biyao','Su','biyaosu@gmail.com','6462484597',NULL,'e',1,1,'2016-07-28 20:39:14','2016-09-05 05:23:45'),(5,'Biyao','Su','biyaosu@gmail.com','6462484597',NULL,'e',1,0,'2016-07-28 20:47:32','2016-07-28 20:47:32'),(6,'宝宝','乖',NULL,'6666666666',NULL,'乖',1,0,'2016-08-02 21:14:32','2016-08-02 21:14:32'),(7,'宝宝','乖',NULL,'7777777777',NULL,'第二个乖宝宝',1,0,'2016-08-02 21:17:17','2016-08-02 21:17:17'),(8,'宝宝','乖',NULL,NULL,'guaibaobao','第三个',1,0,'2016-08-02 21:18:04','2016-08-02 21:18:04'),(9,'娃','葫芦',NULL,'6789098765','h8cq5iach','留个言',2,0,'2016-08-14 13:33:06','2016-08-14 13:33:06'),(10,'宝宝','乖',NULL,'2222222222',NULL,'留言',2,1,'2016-08-14 13:42:09','2016-09-07 05:08:51'),(11,'宝宝','乖',NULL,'2222222222',NULL,'留言',2,0,'2016-08-14 13:49:18','2016-08-14 13:49:18'),(12,'宝宝','乖',NULL,'2222222222',NULL,'(No message)',2,1,'2016-08-14 14:25:13','2016-09-07 05:11:20'),(13,'宝宝','乖',NULL,'2222222222',NULL,'(No message)',2,1,'2016-08-14 14:27:10','2016-08-14 14:27:10'),(14,'宝宝','乖',NULL,'2222222222',NULL,'(No message)',2,0,'2016-08-14 14:28:13','2016-08-14 14:28:13'),(15,'宝宝','乖',NULL,'2222222222',NULL,'(No message)',2,0,'2016-08-14 14:28:41','2016-09-07 05:10:34'),(16,'宝宝','乖',NULL,'2222222222',NULL,'(No message)',2,0,'2016-08-14 14:31:11','2016-08-14 14:31:11'),(17,'乖','乖','kkkk@kkkk.com',NULL,NULL,'(No message)',2,1,'2016-08-14 14:32:08','2016-09-08 03:55:02'),(18,'凯奇','尼古拉斯',NULL,'5555555555',NULL,'(No message)',2,1,'2016-08-14 14:34:54','2016-08-14 14:34:54'),(19,'凯奇','尼古拉斯',NULL,'5555555555',NULL,'(No message)',2,0,'2016-08-14 14:36:04','2016-09-07 05:02:30'),(20,'肖恩','康奈里',NULL,'6667774444',NULL,'勇闯夺命岛',1,0,'2016-08-14 15:41:13','2016-08-14 15:41:13'),(21,'宝宝','海绵','haiian@baobao.com',NULL,NULL,'baobao',1,0,'2016-08-14 15:43:23','2016-08-14 15:43:23'),(22,'大星','派',NULL,NULL,'bigstar','啦啦啦',2,0,'2016-08-14 16:01:21','2016-08-14 16:01:21'),(23,'Wang','Alice',NULL,NULL,'fahinae','(No message)',2,0,'2016-08-14 16:04:34','2016-08-14 16:04:34'),(24,'Wang','Alice',NULL,'4445555555','fahinae','(No message)',2,0,'2016-08-14 16:05:24','2016-08-14 16:05:24');
/*!40000 ALTER TABLE `CustomerSubmission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Destination`
--

DROP TABLE IF EXISTS `Destination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Destination` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UpdatedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Destination`
--

LOCK TABLES `Destination` WRITE;
/*!40000 ALTER TABLE `Destination` DISABLE KEYS */;
INSERT INTO `Destination` VALUES (1,'洛杉矶LAX机场','LAX Airport',0,'2016-08-03 20:30:10'),(2,'圣地亚哥机场','San Diego Airport',0,'2016-08-03 20:30:54'),(3,'JFK','No description',0,'2016-09-08 21:40:50'),(4,'JFK','No description',1,'2016-09-08 21:41:52'),(5,'JFK','No description',1,'2016-09-08 21:43:19'),(6,'JFK','No description',1,'2016-09-08 21:44:39'),(7,'JFK','No description',1,'2016-09-08 21:45:38');
/*!40000 ALTER TABLE `Destination` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Itinerary`
--

DROP TABLE IF EXISTS `Itinerary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Itinerary` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DestinationId` int(10) unsigned NOT NULL,
  `Date` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `Hour` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `Minute` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `TimeZone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Vehicle` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Public` tinyint(1) NOT NULL DEFAULT '1',
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `CreationTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `DestinationId` (`DestinationId`),
  CONSTRAINT `itinerary_ibfk_1` FOREIGN KEY (`DestinationId`) REFERENCES `Destination` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Itinerary`
--

LOCK TABLES `Itinerary` WRITE;
/*!40000 ALTER TABLE `Itinerary` DISABLE KEYS */;
INSERT INTO `Itinerary` VALUES (1,1,'20160808','8','15','America/Los_Angeles','大巴',1,0,'2016-08-08 21:18:59','2016-08-08 21:18:59'),(2,1,'20160808','8','45','America/Los_Angeles','mini van',1,0,'2016-08-14 16:00:37','2016-09-08 03:53:18'),(3,2,'20160809','16','30','America/Los_Angeles','劳斯莱斯',1,0,'2016-08-14 16:07:15','2016-08-14 16:07:15'),(4,2,'20160908','4','10','America/Los_Angeles','car',1,0,'2016-09-08 21:36:57','2016-09-08 21:36:57'),(5,6,'20160930','5','3','America/Los_Angeles','bus',1,1,'2016-09-08 21:44:39','2016-09-08 21:44:39'),(6,7,'20160930','5','3','America/Los_Angeles','bus',1,1,'2016-09-08 21:45:38','2016-09-08 21:45:38'),(7,3,'20161001','2','3','America/Los_Angeles','car',1,0,'2016-09-08 21:48:56','2016-09-08 21:48:56'),(8,2,'20160913','3','7','America/Los_Angeles','bus',1,0,'2016-09-08 21:51:55','2016-09-08 21:51:55'),(9,1,'20161018','9','15','America/Los_Angeles','car',1,0,'2016-09-08 21:54:45','2016-09-08 21:54:45'),(10,3,'20160914','5','0','America/Los_Angeles','train',1,0,'2016-09-08 21:55:55','2016-09-08 21:55:55'),(11,1,'20161109','18','0','America/Los_Angeles','汽车',1,0,'2016-09-08 21:57:22','2016-09-08 21:57:22');
/*!40000 ALTER TABLE `Itinerary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SubmissionType`
--

DROP TABLE IF EXISTS `SubmissionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SubmissionType` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `UpdatedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SubmissionType`
--

LOCK TABLES `SubmissionType` WRITE;
/*!40000 ALTER TABLE `SubmissionType` DISABLE KEYS */;
INSERT INTO `SubmissionType` VALUES (1,'Customized Travel Customer Message Submission',0,'2016-07-18 21:49:27'),(2,'Shuttle Service Customer Message Submission',0,'2016-08-14 13:11:50');
/*!40000 ALTER TABLE `SubmissionType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Text`
--

DROP TABLE IF EXISTS `Text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Text` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Url` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Text` varchar(20000) COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT '0',
  `Version` int(10) unsigned NOT NULL,
  `UpdatedTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Text`
--

LOCK TABLES `Text` WRITE;
/*!40000 ALTER TABLE `Text` DISABLE KEYS */;
INSERT INTO `Text` VALUES (1,'/customizedtravel','1','您可以根据需求订制旅游路线。','description for customized travel',0,1,'2016-07-19 18:58:31'),(2,'/customizedtravel','1','您可以订制旅游路线。',NULL,1,2,'2016-07-21 18:48:19'),(3,'/shuttleservice','2','请选择您的车次。','ss_main_description',0,1,'2016-08-02 21:55:46'),(4,'/customizedtravel','1','修改',NULL,0,1,'2016-09-03 19:28:30'),(5,'/customizedtravel','1','修改',NULL,0,1,'2016-09-03 19:30:55'),(6,'/customizedtravel','1','修改',NULL,0,2,'2016-09-03 19:31:16'),(7,'/customizedtravel','1','定制',NULL,0,2,'2016-09-03 19:33:04'),(8,'/customizedtravel','1','定制',NULL,0,2,'2016-09-03 19:36:18'),(9,'/customizedtravel','1','订制',NULL,0,2,'2016-09-03 19:41:14'),(10,'/customizedtravel','1','旅游',NULL,0,3,'2016-09-03 20:44:45'),(11,'/customizedtravel','1','您可以根据需求订制旅游路线。',NULL,0,4,'2016-09-03 20:45:39'),(12,'/shuttleservice','2','您可以根据需求订制行程！',NULL,0,2,'2016-09-03 20:46:00');
/*!40000 ALTER TABLE `Text` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-09 21:34:11
