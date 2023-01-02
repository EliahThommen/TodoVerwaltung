-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: m151_database
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB

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
-- Current Database: `m151_database`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `m151_database` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `m151_database`;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Test Kategorie'),(2,'Kochen'),(4,'Neue Kategorie');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo`
--

DROP TABLE IF EXISTS `todo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `todo` (
  `todo_ID` int(11) NOT NULL AUTO_INCREMENT,
  `priority` int(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `expire_date` datetime NOT NULL,
  `Status` int(11) NOT NULL,
  `In_Archiv` tinyint(1) NOT NULL,
  `category_category_ID` int(11) NOT NULL,
  `user_user_ID` int(11) NOT NULL,
  PRIMARY KEY (`todo_ID`),
  KEY `fk_To-Do-Obj_Category1` (`category_category_ID`),
  KEY `fk_To-Do-Obj_user1` (`user_user_ID`),
  CONSTRAINT `fk_To-Do-Obj_Category1` FOREIGN KEY (`category_category_ID`) REFERENCES `category` (`category_ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_To-Do-Obj_user1` FOREIGN KEY (`user_user_ID`) REFERENCES `user` (`user_ID`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo`
--

LOCK TABLES `todo` WRITE;
/*!40000 ALTER TABLE `todo` DISABLE KEYS */;
INSERT INTO `todo` VALUES (8,5,'Titel','beschreibung','2022-03-04 00:00:00','2022-03-04 00:00:00',6,1,1,3),(18,5,'Titel','beschreibung','2022-03-04 00:00:00','2022-03-04 00:00:00',6,0,1,1),(49,2,'abc','abc','2022-03-04 00:00:00','2022-03-04 00:00:00',22,1,2,3);
/*!40000 ALTER TABLE `todo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_ID` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Max','Muster','MaxMuster','$2y$10$G/npWj62/42C0FVDxeBEMOESfho1Vx6B9HGRx/L8FyOjjpNEQXJT2','eliahthommen@gmail.com'),(3,'Noah','Hnevsa','Noahhnevsa','$2y$10$uBwWZeyIJFHhyOzj2kLd1uMOn470FpsgwtXHw1Qvr3L1vZdkdcEwK','noah@gmail.com');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_has_category`
--

DROP TABLE IF EXISTS `user_has_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_has_category` (
  `category_category_ID` int(11) NOT NULL,
  `user_user_ID` int(11) NOT NULL,
  PRIMARY KEY (`user_user_ID`,`category_category_ID`),
  KEY `fk_user_has_Category_Category1` (`category_category_ID`),
  CONSTRAINT `fk_user_has_Category_Category1` FOREIGN KEY (`category_category_ID`) REFERENCES `category` (`category_ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_user_has_Category_user1` FOREIGN KEY (`user_user_ID`) REFERENCES `user` (`user_ID`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_category`
--

LOCK TABLES `user_has_category` WRITE;
/*!40000 ALTER TABLE `user_has_category` DISABLE KEYS */;
INSERT INTO `user_has_category` VALUES (1,3),(2,3);
/*!40000 ALTER TABLE `user_has_category` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-25 23:18:53
