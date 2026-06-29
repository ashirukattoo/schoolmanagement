CREATE DATABASE  IF NOT EXISTS `lyamahoro` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `lyamahoro`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: lyamahoro
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academicyears`
--

DROP TABLE IF EXISTS `academicyears`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academicyears` (
  `ayID` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `ayName` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
  `ayLevel` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `ayStart` date NOT NULL,
  `ayEnd` date NOT NULL,
  `ayCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ayUpdated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ayStatus` enum('Current','Previous','Next','Old') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Next',
  PRIMARY KEY (`ayID`),
  UNIQUE KEY `ayID` (`ayID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academicyears`
--

LOCK TABLES `academicyears` WRITE;
/*!40000 ALTER TABLE `academicyears` DISABLE KEYS */;
INSERT INTO `academicyears` VALUES ('ACAL001','2024/2025','A-Level','2024-07-01','2025-06-30','2025-11-07 14:42:01',NULL,'Old'),('ACOL001','2024','O-level','2024-01-01','2024-12-31','2025-11-04 19:55:49',NULL,'Old');
/*!40000 ALTER TABLE `academicyears` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `named` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `short` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `numeral` tinyint NOT NULL,
  `level` int NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'Form One','FI',1,1,'2025-10-25 18:22:58',NULL),(2,'Form Two','FII',2,1,'2025-10-25 18:22:58',NULL),(3,'Form Three','FIII',3,1,'2025-10-25 18:22:58',NULL),(4,'Form Four','FIV',4,1,'2025-10-25 18:22:58',NULL),(5,'Form Five','FV',5,2,NULL,NULL),(6,'Form Six','FVI',6,2,NULL,NULL);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compiledresults`
--

DROP TABLE IF EXISTS `compiledresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compiledresults` (
  `crID` int NOT NULL AUTO_INCREMENT,
  `exam_id` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `student_id` int NOT NULL,
  `crResults` text COLLATE utf8mb4_general_ci NOT NULL,
  `crCreated` datetime NOT NULL,
  `crCompiledBy` int NOT NULL,
  `raUpdated` datetime DEFAULT NULL,
  `raStatus` enum('sent','forwarded','compiled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'compiled',
  PRIMARY KEY (`crID`),
  KEY `compiledResults_exam_id_foreign` (`exam_id`),
  KEY `compiledResults_student_id_foreign` (`student_id`),
  KEY `compiledResults_crCompiledBy_foreign` (`crCompiledBy`),
  CONSTRAINT `compiledResults_crCompiledBy_foreign` FOREIGN KEY (`crCompiledBy`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compiledResults_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compiledResults_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`stuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compiledresults`
--

LOCK TABLES `compiledresults` WRITE;
/*!40000 ALTER TABLE `compiledresults` DISABLE KEYS */;
INSERT INTO `compiledresults` VALUES (1,'EXOL100001',4,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"85.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"84.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Chemistry\",\"score\":\"54.00\",\"grade\":\"C\",\"points\":3}],\"points\":10,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(2,'EXOL100001',5,'{\"subjects\":[{\"subject\":\"Business Studies\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"88.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"84.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"52.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"46.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Chemistry\",\"score\":\"39.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Geography\",\"score\":\"41.00\",\"grade\":\"D\",\"points\":4}],\"points\":17,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(3,'EXOL100001',6,'{\"subjects\":[{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"68.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Biology\",\"score\":\"53.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Basic Mathematics\",\"score\":\"35.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Chemistry\",\"score\":\"31.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Geography\",\"score\":\"31.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"History\",\"score\":\"32.00\",\"grade\":\"D\",\"points\":4}],\"points\":22,\"division\":\"III\",\"remark\":\"Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(4,'EXOL100001',7,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"65.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"English Language\",\"score\":\"70.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Geography\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3}],\"points\":11,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(5,'EXOL100001',8,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"85.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"88.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"74.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Kiswahili\",\"score\":\"68.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"72.00\",\"grade\":\"B\",\"points\":2}],\"points\":11,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(6,'EXOL100001',9,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"94.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"64.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"61.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"59.00\",\"grade\":\"C\",\"points\":3}],\"points\":13,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(7,'EXOL100001',10,'{\"subjects\":[{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"84.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"89.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"95.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"55.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Chemistry\",\"score\":\"30.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Physics\",\"score\":\"37.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Basic Mathematics\",\"score\":\"18.00\",\"grade\":\"F\",\"points\":5}],\"points\":19,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(8,'EXOL100001',21,'{\"subjects\":[{\"subject\":\"Kiswahili\",\"score\":\"89.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Geography\",\"score\":\"72.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"69.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Chemistry\",\"score\":\"47.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Physics\",\"score\":\"62.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"31.00\",\"grade\":\"D\",\"points\":4}],\"points\":17,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(9,'EXOL100001',22,'{\"subjects\":[{\"subject\":\"Chemistry\",\"score\":\"95.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"46.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"48.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"38.00\",\"grade\":\"D\",\"points\":4}],\"points\":16,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(10,'EXOL100001',24,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"86.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"93.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"74.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"History\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3}],\"points\":12,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(11,'EXOL100001',11,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Computer Science\",\"score\":\"61.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Business Studies\",\"score\":\"31.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Chemistry\",\"score\":\"32.00\",\"grade\":\"D\",\"points\":4}],\"points\":16,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(12,'EXOL100001',12,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"76.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"90.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"69.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Geography\",\"score\":\"68.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Kiswahili\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Chemistry\",\"score\":\"31.00\",\"grade\":\"D\",\"points\":4}],\"points\":14,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(13,'EXOL100001',13,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"96.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"74.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"69.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Geography\",\"score\":\"51.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Basic Mathematics\",\"score\":\"44.00\",\"grade\":\"D\",\"points\":4}],\"points\":14,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(14,'EXOL100001',14,'{\"subjects\":[{\"subject\":\"Geography\",\"score\":\"82.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"69.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"History\",\"score\":\"68.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Biology\",\"score\":\"59.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Chemistry\",\"score\":\"60.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Computer Science\",\"score\":\"47.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Basic Mathematics\",\"score\":\"34.00\",\"grade\":\"D\",\"points\":4}],\"points\":18,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(15,'EXOL100001',15,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"78.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"48.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"58.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Biology\",\"score\":\"41.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"English Language\",\"score\":\"43.00\",\"grade\":\"D\",\"points\":4}],\"points\":17,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(16,'EXOL100001',16,'{\"subjects\":[{\"subject\":\"English Language\",\"score\":\"90.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"72.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Chemistry\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"57.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"52.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Physics\",\"score\":\"51.00\",\"grade\":\"C\",\"points\":3}],\"points\":16,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(17,'EXOL100001',17,'{\"subjects\":[{\"subject\":\"Business Studies\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"65.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Basic Mathematics\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Biology\",\"score\":\"63.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"60.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Physics\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3}],\"points\":16,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(18,'EXOL100001',18,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"86.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"86.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"94.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"English Language\",\"score\":\"48.00\",\"grade\":\"C\",\"points\":3}],\"points\":10,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(19,'EXOL100001',19,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"96.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"48.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"45.00\",\"grade\":\"C\",\"points\":3}],\"points\":11,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(20,'EXOL100001',20,'{\"subjects\":[{\"subject\":\"Chemistry\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"66.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Business Studies\",\"score\":\"54.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"54.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Basic Mathematics\",\"score\":\"38.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"English Language\",\"score\":\"42.00\",\"grade\":\"D\",\"points\":4}],\"points\":18,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(21,'EXOL100001',44,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"95.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"88.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"English Language\",\"score\":\"45.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Computer Science\",\"score\":\"62.00\",\"grade\":\"C\",\"points\":3}],\"points\":12,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(22,'EXOL100001',3,'{\"subjects\":[{\"subject\":\"Geography\",\"score\":\"78.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Kiswahili\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Business Studies\",\"score\":\"53.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Basic Mathematics\",\"score\":\"37.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"Chemistry\",\"score\":\"34.00\",\"grade\":\"D\",\"points\":4}],\"points\":17,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(23,'EXOL100001',23,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"76.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"85.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"82.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"English Language\",\"score\":\"64.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Geography\",\"score\":\"54.00\",\"grade\":\"C\",\"points\":3}],\"points\":12,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(24,'EXOL100001',25,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"76.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Chemistry\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Kiswahili\",\"score\":\"66.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Business Studies\",\"score\":\"58.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"45.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"57.00\",\"grade\":\"C\",\"points\":3}],\"points\":16,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(25,'EXOL100001',26,'{\"subjects\":[{\"subject\":\"History\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"96.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"78.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"74.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"English Language\",\"score\":\"48.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Basic Mathematics\",\"score\":\"42.00\",\"grade\":\"D\",\"points\":4}],\"points\":13,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(26,'EXOL100001',27,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"95.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"88.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"85.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"72.00\",\"grade\":\"B\",\"points\":2}],\"points\":8,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(27,'EXOL100001',28,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"76.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"96.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"57.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Chemistry\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"47.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"61.00\",\"grade\":\"C\",\"points\":3}],\"points\":15,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(28,'EXOL100001',29,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"93.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"58.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Business Studies\",\"score\":\"64.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"58.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Geography\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"53.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"57.00\",\"grade\":\"C\",\"points\":3}],\"points\":19,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(29,'EXOL100001',30,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"94.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"65.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Business Studies\",\"score\":\"63.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"History\",\"score\":\"51.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"33.00\",\"grade\":\"D\",\"points\":4}],\"points\":15,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(30,'EXOL100001',31,'{\"subjects\":[{\"subject\":\"Chemistry\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"97.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"82.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"68.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Biology\",\"score\":\"68.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Business Studies\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"English Language\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2}],\"points\":11,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(31,'EXOL100001',32,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"76.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"84.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"89.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"94.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1}],\"points\":7,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(32,'EXOL100001',33,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"88.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"74.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Biology\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"64.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Computer Science\",\"score\":\"37.00\",\"grade\":\"D\",\"points\":4}],\"points\":15,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(33,'EXOL100001',34,'{\"subjects\":[{\"subject\":\"Business Studies\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"79.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Biology\",\"score\":\"59.00\",\"grade\":\"C\",\"points\":3}],\"points\":11,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(34,'EXOL100001',35,'{\"subjects\":[{\"subject\":\"Business Studies\",\"score\":\"79.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Basic Mathematics\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Biology\",\"score\":\"62.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Chemistry\",\"score\":\"45.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"56.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"33.00\",\"grade\":\"D\",\"points\":4}],\"points\":18,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(35,'EXOL100001',36,'{\"subjects\":[{\"subject\":\"Business Studies\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"93.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"85.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"62.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"60.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Biology\",\"score\":\"32.00\",\"grade\":\"D\",\"points\":4}],\"points\":14,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(36,'EXOL100001',37,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"53.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"50.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Geography\",\"score\":\"59.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"55.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"40.00\",\"grade\":\"D\",\"points\":4}],\"points\":18,\"division\":\"II\",\"remark\":\"Very Good\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(37,'EXOL100001',38,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"84.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"93.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"96.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"65.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Chemistry\",\"score\":\"56.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Kiswahili\",\"score\":\"54.00\",\"grade\":\"C\",\"points\":3}],\"points\":13,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(38,'EXOL100001',39,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"98.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"90.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Physics\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2}],\"points\":10,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(39,'EXOL100001',40,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"94.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"78.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"74.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Physics\",\"score\":\"52.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Biology\",\"score\":\"41.00\",\"grade\":\"D\",\"points\":4},{\"subject\":\"History\",\"score\":\"43.00\",\"grade\":\"D\",\"points\":4}],\"points\":16,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:08',1,NULL,'compiled'),(40,'EXOL100001',41,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"86.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"78.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"96.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"72.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"73.00\",\"grade\":\"B\",\"points\":2}],\"points\":9,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:09',1,NULL,'compiled'),(41,'EXOL100001',42,'{\"subjects\":[{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"93.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"History\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"85.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"65.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Biology\",\"score\":\"63.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"English Language\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3}],\"points\":12,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:09',1,NULL,'compiled'),(42,'EXOL100001',43,'{\"subjects\":[{\"subject\":\"Basic Mathematics\",\"score\":\"79.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Biology\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"89.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Physics\",\"score\":\"79.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"71.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Geography\",\"score\":\"55.00\",\"grade\":\"C\",\"points\":3}],\"points\":11,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-09 09:56:09',1,NULL,'compiled');
/*!40000 ALTER TABLE `compiledresults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `districts` (
  `disID` int NOT NULL AUTO_INCREMENT,
  `disName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `region_id` int NOT NULL,
  PRIMARY KEY (`disID`),
  KEY `districts_region_id_foreign` (`region_id`),
  CONSTRAINT `districts_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`regID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_teaches`
--

DROP TABLE IF EXISTS `employee_teaches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_teaches` (
  `code` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `employee_id` int NOT NULL,
  `stream_id` int NOT NULL,
  `assigneDate` date NOT NULL,
  `todate` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` enum('Active','Canceled','Old','Next') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`code`),
  UNIQUE KEY `code` (`code`),
  KEY `employee_teaches_employee_id_foreign` (`employee_id`),
  KEY `employee_teaches_stream_id_foreign` (`stream_id`),
  CONSTRAINT `employee_teaches_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `employee_teaches_stream_id_foreign` FOREIGN KEY (`stream_id`) REFERENCES `subject_streams` (`scID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_teaches`
--

LOCK TABLES `employee_teaches` WRITE;
/*!40000 ALTER TABLE `employee_teaches` DISABLE KEYS */;
INSERT INTO `employee_teaches` VALUES ('AC001',7,49,'2025-11-07','0000-00-00','2025-11-07 14:30:17','Active'),('AC002',6,42,'2025-11-07','0000-00-00','2025-11-07 14:31:14','Active'),('AC003',4,45,'2025-11-07','0000-00-00','2025-11-07 14:42:40','Active');
/*!40000 ALTER TABLE `employee_teaches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `empID` int NOT NULL AUTO_INCREMENT,
  `empFname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `empMname` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `empSurname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `empSex` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `empDob` date DEFAULT NULL,
  `empRole` enum('Teacher','Staff','Technician') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Teacher',
  `empPosition` enum('Head','Deputy','Academic','Discipline','Class Master','Subject Master','Secretary','Labtechnician','Specific Task','Admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Subject Master',
  `empEmail` varchar(75) COLLATE utf8mb4_general_ci NOT NULL,
  `empPassword` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `empHired` date DEFAULT NULL,
  `empRegisterd` datetime DEFAULT NULL,
  `empStatus` enum('Active','Leaved','Blocked','Added') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Added',
  PRIMARY KEY (`empID`),
  UNIQUE KEY `empEmail` (`empEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Ashiru','Esmail','Hussein','Male','1996-09-20','Technician','Admin','ashirukattoo@gmail.com','$2y$12$osxYYnvZEr2Az677ZSUoBe6mxwy4VaV8FEPtRuISSzZdf/6LDiZmS','2025-12-13','2025-10-25 18:10:43','Active'),(2,'Jovin','Joseph','Kagaruki','Male','1999-10-22','Teacher','Academic','jovinkagaruki@gmail.com','$2y$12$JQfgHa4CNwG6pNkk3/Z79.YkoJI8LcoBDD1l8YsTkTz8XBKNygVm.','2025-04-11','2025-10-25 18:10:43','Active'),(3,'Damian','Christopher','Chriss','Male','1994-07-14','Teacher','Subject Master','dammychristopher@gmail.com','$2y$12$LL/rpGEjUCk7Xzvfrgrd2OwOTmMB57POhWWVIXjDHp2klEibLr.Iq','2025-11-01','2025-11-01 09:22:23','Active'),(4,'Mwashamba','','Mohammed','Female','1997-02-12','Teacher','Subject Master','mwashamba@gmail.com','$2y$12$wi55mkvChvOAFfD6VsF0n.LHkYYORiGrXltdHWnKtNP33iOJxu8pa','2025-11-01','2025-11-01 09:29:01','Active'),(5,'Anitha','K','Joseph','Female','2006-11-11','Staff','Secretary','anithajoseph@gmail.com','$2y$12$FH/bBlr6JkBLjW1BNZxE2ONSxELhburE5rjDfTicilxbQ32LDFsxu','2025-09-01','2025-11-01 09:30:50','Active'),(6,'Pesa','Ikelenge','Magesa','Male','1997-12-21','Teacher','Subject Master','pesaikelenge@gmail.com','$2y$12$OYbz933AdrOBrYFOnRsE7.kGvUB75DjOh4V50DgIa94237oLbmLZO','2025-04-01','2025-11-01 09:38:33','Active'),(7,'Edina','Tumsime','Bajwa','Female','1998-11-25','Teacher','Subject Master','ednatumsime@gmail.com','$2y$12$PtWeqdIEzaLxfb7XJQhfauHZ69SmVLdemm7KD214LKQrQlaXxdUrK','2025-07-08','2025-11-01 18:45:10','Active'),(8,'Brighton','S','Kivambe','Male','1991-12-31','Teacher','Subject Master','brightonkivambe@gmail.com','$2y$12$GVElAA7DwizLxVFLK5H1gu1vn6kKUABc/CH9R0qEgrXTWeNhfHKa2','2020-07-16','2025-11-07 16:18:59','Active'),(9,'Makukuri','','Mwidini','Male','1985-02-11','Teacher','Subject Master','makukurimwidin@gmail.com','$2y$12$KroVFonKTIzUunCFREssK./HhCoHCrfEpwhXBqtmLPenysECmRsqq','2020-11-07','2025-11-07 16:20:29','Active'),(11,'Nadia','','Mahamood','Female','1985-12-20','Teacher','Subject Master','nadiamahamood@gmail.com','$2y$12$oRRHSQjkDl4bFwuoEfp05.BLAVQp.g/iVHi4gEEQFLhtBwSsS1Dfq','2012-11-07','2025-11-07 16:22:41','Active'),(12,'Loveness','','Mbilinyi','Female','1994-12-10','Teacher','Subject Master','mbilinyiloveness@gmail.com','$2y$12$yiYGnU3mq7adczXOvYfd2u5WU/la9MjT19bhLCRSCfvCTSdVkbmwS','2022-11-07','2025-11-07 16:24:14','Active'),(13,'Benjamin','','Benjamin','Male','1997-02-02','Teacher','Subject Master','benjamin123@gmail.com','$2y$12$FZZqvq.d9VEoq3spU8W2QOmfYcFi7YplpKmfBl.LlIfqGC.lsNHpq','2025-07-07','2025-11-07 16:26:03','Active'),(14,'Housen','','Mohammed','Male','1997-02-02','Teacher','Subject Master','housenmoh123@gmail.com','$2y$12$KYdAkC9W1vsjVxLCqAlnc.BF/RK23DcnnOYT0unrCvQ.0OFVl22Bq','2025-07-07','2025-11-07 16:26:58','Active');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exams` (
  `exID` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `exName` varchar(75) COLLATE utf8mb4_general_ci NOT NULL,
  `class_id` int NOT NULL,
  `ay_id` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `exSubjects` text COLLATE utf8mb4_general_ci NOT NULL,
  `exCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exUpdated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `exStatus` enum('progress','old','next','canceled','current') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'next',
  PRIMARY KEY (`exID`),
  UNIQUE KEY `exID` (`exID`),
  KEY `exams_class_id_foreign` (`class_id`),
  KEY `exams_ay_id_foreign` (`ay_id`),
  CONSTRAINT `exams_ay_id_foreign` FOREIGN KEY (`ay_id`) REFERENCES `academicyears` (`ayID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `exams_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` VALUES ('EXOL100001','Mid-Term Examination',1,'ACOL001','[{\"id\":\"1\",\"subject\":\"Basic Mathematics\",\"code\":\"041\",\"class\":\"1\"},{\"id\":\"2\",\"subject\":\"Biology\",\"code\":\"033\",\"class\":\"1\"},{\"id\":\"3\",\"subject\":\"Business Studies\",\"code\":\"BSS\",\"class\":\"1\"},{\"id\":\"5\",\"subject\":\"Chemistry\",\"code\":\"032\",\"class\":\"1\"},{\"id\":\"15\",\"subject\":\"Computer Science\",\"code\":\"034\",\"class\":\"1\"},{\"id\":\"8\",\"subject\":\"Elimu ya Dini ya Kiislamu\",\"code\":\"015\",\"class\":\"1\"},{\"id\":\"7\",\"subject\":\"English Language\",\"code\":\"022\",\"class\":\"1\"},{\"id\":\"9\",\"subject\":\"Geography\",\"code\":\"013\",\"class\":\"1\"},{\"id\":\"10\",\"subject\":\"Historia ya Tanzania na Maadili\",\"code\":\"060\",\"class\":\"1\"},{\"id\":\"11\",\"subject\":\"History\",\"code\":\"012\",\"class\":\"1\"},{\"id\":\"12\",\"subject\":\"Kiswahili\",\"code\":\"021\",\"class\":\"1\"},{\"id\":\"14\",\"subject\":\"Physics\",\"code\":\"031\",\"class\":\"1\"}]','2025-11-09 07:40:48','2025-11-09 12:56:09','current'),('EXOL100002','Terminal Examination',1,'ACOL001','[{\"id\":\"1\",\"subject\":\"Basic Mathematics\",\"code\":\"041\",\"class\":\"1\"},{\"id\":\"2\",\"subject\":\"Biology\",\"code\":\"033\",\"class\":\"1\"},{\"id\":\"3\",\"subject\":\"Business Studies\",\"code\":\"BSS\",\"class\":\"1\"},{\"id\":\"5\",\"subject\":\"Chemistry\",\"code\":\"032\",\"class\":\"1\"},{\"id\":\"15\",\"subject\":\"Computer Science\",\"code\":\"034\",\"class\":\"1\"},{\"id\":\"8\",\"subject\":\"Elimu ya Dini ya Kiislamu\",\"code\":\"015\",\"class\":\"1\"},{\"id\":\"7\",\"subject\":\"English Language\",\"code\":\"022\",\"class\":\"1\"},{\"id\":\"9\",\"subject\":\"Geography\",\"code\":\"013\",\"class\":\"1\"},{\"id\":\"10\",\"subject\":\"Historia ya Tanzania na Maadili\",\"code\":\"060\",\"class\":\"1\"},{\"id\":\"11\",\"subject\":\"History\",\"code\":\"012\",\"class\":\"1\"},{\"id\":\"12\",\"subject\":\"Kiswahili\",\"code\":\"021\",\"class\":\"1\"},{\"id\":\"14\",\"subject\":\"Physics\",\"code\":\"031\",\"class\":\"1\"}]','2025-11-09 10:21:17',NULL,'next');
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guardians`
--

DROP TABLE IF EXISTS `guardians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guardians` (
  `guID` int NOT NULL AUTO_INCREMENT,
  `empname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `guSex` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `empDob` date DEFAULT NULL,
  `guOccupasion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `empEmail` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `empPassword` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `guRegisterd` datetime DEFAULT NULL,
  `guStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`guID`),
  UNIQUE KEY `empEmail` (`empEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guardians`
--

LOCK TABLES `guardians` WRITE;
/*!40000 ALTER TABLE `guardians` DISABLE KEYS */;
INSERT INTO `guardians` VALUES (1,'Hussein Ismail','Male','1969-04-12','peasant','husseinismail@gmail.com','kilomba1234','2025-10-26 07:13:23','Active'),(2,'','female',NULL,'','','123410302025','2025-10-30 16:53:27','Active'),(8,'Shaidu Ismail','male',NULL,'Peasant','shaidunuru@gmail.com','123410302025','2025-10-30 17:24:12','Active'),(17,'Nassoro Yahya','male',NULL,'Teacher','nassoroyahya@gmail.com','123410312025','2025-10-31 12:46:11','Active');
/*!40000 ALTER TABLE `guardians` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025-10-14-145630','App\\Database\\Migrations\\CreateAllTables','default','App',1761416556,1),(2,'2025-10-31-132601','App\\Database\\Migrations\\SecondBatch','default','App',1761920893,2),(4,'2025-11-01-132851','App\\Database\\Migrations\\Teaching','default','App',1762005055,3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rawresults`
--

DROP TABLE IF EXISTS `rawresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rawresults` (
  `raID` int NOT NULL AUTO_INCREMENT,
  `exam_id` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `raScore` decimal(5,2) NOT NULL,
  `raGrade` char(3) COLLATE utf8mb4_general_ci NOT NULL,
  `raCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `raCreatedBy` int NOT NULL,
  `raUpdated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `raStatus` enum('raw','deleted','compiled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'raw',
  PRIMARY KEY (`raID`),
  KEY `rawResults_exam_id_foreign` (`exam_id`),
  KEY `rawResults_subject_id_foreign` (`subject_id`),
  KEY `rawResults_raCreatedBy_foreign` (`raCreatedBy`),
  KEY `rawResults_student_id_foreign_idx` (`student_id`),
  CONSTRAINT `rawResults_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rawResults_raCreatedBy_foreign` FOREIGN KEY (`raCreatedBy`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rawResults_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`stuID`),
  CONSTRAINT `rawResults_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=477 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rawresults`
--

LOCK TABLES `rawresults` WRITE;
/*!40000 ALTER TABLE `rawresults` DISABLE KEYS */;
INSERT INTO `rawresults` VALUES (1,'EXOL100001',4,1,98.00,'A','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(2,'EXOL100001',4,2,85.00,'A','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(3,'EXOL100001',4,3,97.00,'A','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(4,'EXOL100001',4,5,54.00,'C','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(5,'EXOL100001',4,7,61.00,'C','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(6,'EXOL100001',4,8,17.00,'F','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(7,'EXOL100001',4,9,97.00,'A','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(8,'EXOL100001',4,11,84.00,'A','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(9,'EXOL100001',4,12,49.00,'C','2025-11-09 09:55:22',1,'2025-11-09 09:56:09','compiled'),(10,'EXOL100001',4,14,73.00,'B','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(11,'EXOL100001',4,10,14.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(12,'EXOL100001',5,1,52.00,'C','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(13,'EXOL100001',5,2,22.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(14,'EXOL100001',5,3,92.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(15,'EXOL100001',5,5,39.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(16,'EXOL100001',5,7,46.00,'C','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(17,'EXOL100001',5,8,22.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(18,'EXOL100001',5,9,41.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(19,'EXOL100001',5,11,88.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(20,'EXOL100001',5,12,19.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(21,'EXOL100001',5,14,84.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(22,'EXOL100001',5,10,16.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(23,'EXOL100001',6,1,35.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(24,'EXOL100001',6,2,53.00,'C','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(25,'EXOL100001',6,3,20.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(26,'EXOL100001',6,5,31.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(27,'EXOL100001',6,7,28.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(28,'EXOL100001',6,8,98.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(29,'EXOL100001',6,9,31.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(30,'EXOL100001',6,11,32.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(31,'EXOL100001',6,12,68.00,'B','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(32,'EXOL100001',6,14,37.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(33,'EXOL100001',6,10,43.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(34,'EXOL100001',7,1,65.00,'B','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(35,'EXOL100001',7,2,87.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(36,'EXOL100001',7,3,80.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(37,'EXOL100001',7,5,75.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(38,'EXOL100001',7,7,70.00,'B','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(39,'EXOL100001',7,9,50.00,'C','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(40,'EXOL100001',7,11,83.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(41,'EXOL100001',7,12,34.00,'D','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(42,'EXOL100001',7,14,26.00,'F','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(43,'EXOL100001',7,10,45.00,'C','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(44,'EXOL100001',8,1,85.00,'A','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(45,'EXOL100001',8,2,73.00,'B','2025-11-09 09:55:23',1,'2025-11-09 09:56:09','compiled'),(46,'EXOL100001',8,3,55.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(47,'EXOL100001',8,5,55.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(48,'EXOL100001',8,7,98.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(49,'EXOL100001',8,8,74.00,'B','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(50,'EXOL100001',8,9,88.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(51,'EXOL100001',8,11,58.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(52,'EXOL100001',8,12,68.00,'B','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(53,'EXOL100001',8,14,44.00,'D','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(54,'EXOL100001',8,10,72.00,'B','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(55,'EXOL100001',9,1,64.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(56,'EXOL100001',9,2,92.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(57,'EXOL100001',9,3,37.00,'D','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(58,'EXOL100001',9,5,98.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(59,'EXOL100001',9,7,81.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(60,'EXOL100001',9,8,61.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(61,'EXOL100001',9,9,94.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(62,'EXOL100001',9,11,59.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(63,'EXOL100001',9,12,21.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(64,'EXOL100001',9,14,45.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(65,'EXOL100001',9,10,23.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(66,'EXOL100001',10,1,18.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(67,'EXOL100001',10,2,10.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(68,'EXOL100001',10,3,17.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(69,'EXOL100001',10,5,30.00,'D','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(70,'EXOL100001',10,7,20.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(71,'EXOL100001',10,8,84.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(72,'EXOL100001',10,9,55.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(73,'EXOL100001',10,11,29.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(74,'EXOL100001',10,12,89.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(75,'EXOL100001',10,14,37.00,'D','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(76,'EXOL100001',10,10,95.00,'A','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(77,'EXOL100001',21,1,14.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(78,'EXOL100001',21,2,71.00,'B','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(79,'EXOL100001',21,3,17.00,'F','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(80,'EXOL100001',21,5,47.00,'C','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(81,'EXOL100001',21,7,31.00,'D','2025-11-09 09:55:24',1,'2025-11-09 09:56:09','compiled'),(82,'EXOL100001',21,8,43.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(83,'EXOL100001',21,9,72.00,'B','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(84,'EXOL100001',21,11,21.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(85,'EXOL100001',21,12,89.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(86,'EXOL100001',21,14,62.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(87,'EXOL100001',21,10,69.00,'B','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(88,'EXOL100001',22,1,24.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(89,'EXOL100001',22,2,49.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(90,'EXOL100001',22,3,12.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(91,'EXOL100001',22,5,95.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(92,'EXOL100001',22,7,38.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(93,'EXOL100001',22,8,22.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(94,'EXOL100001',22,9,44.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(95,'EXOL100001',22,11,46.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(96,'EXOL100001',22,12,13.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(97,'EXOL100001',22,14,81.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(98,'EXOL100001',22,15,80.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(99,'EXOL100001',22,10,48.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(100,'EXOL100001',24,1,36.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(101,'EXOL100001',24,2,86.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(102,'EXOL100001',24,3,93.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(103,'EXOL100001',24,5,74.00,'B','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(104,'EXOL100001',24,7,23.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(105,'EXOL100001',24,8,91.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(106,'EXOL100001',24,9,16.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(107,'EXOL100001',24,11,50.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(108,'EXOL100001',24,12,50.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(109,'EXOL100001',24,14,97.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(110,'EXOL100001',24,15,59.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(111,'EXOL100001',24,10,49.00,'C','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(112,'EXOL100001',11,1,29.00,'F','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(113,'EXOL100001',11,2,97.00,'A','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(114,'EXOL100001',11,3,31.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(115,'EXOL100001',11,5,32.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(116,'EXOL100001',11,7,43.00,'D','2025-11-09 09:55:25',1,'2025-11-09 09:56:09','compiled'),(117,'EXOL100001',11,8,31.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(118,'EXOL100001',11,9,67.00,'B','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(119,'EXOL100001',11,11,24.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(120,'EXOL100001',11,12,75.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(121,'EXOL100001',11,14,98.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(122,'EXOL100001',11,15,61.00,'C','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(123,'EXOL100001',11,10,37.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(124,'EXOL100001',12,1,76.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(125,'EXOL100001',12,2,90.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(126,'EXOL100001',12,3,69.00,'B','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(127,'EXOL100001',12,5,31.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(128,'EXOL100001',12,7,27.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(129,'EXOL100001',12,8,87.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(130,'EXOL100001',12,9,68.00,'B','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(131,'EXOL100001',12,11,13.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(132,'EXOL100001',12,12,49.00,'C','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(133,'EXOL100001',12,14,11.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(134,'EXOL100001',12,15,41.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(135,'EXOL100001',12,10,13.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(136,'EXOL100001',13,1,44.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(137,'EXOL100001',13,2,87.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(138,'EXOL100001',13,3,96.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(139,'EXOL100001',13,5,74.00,'B','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(140,'EXOL100001',13,7,11.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(141,'EXOL100001',13,8,81.00,'A','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(142,'EXOL100001',13,9,51.00,'C','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(143,'EXOL100001',13,11,31.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(144,'EXOL100001',13,12,39.00,'D','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(145,'EXOL100001',13,14,17.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(146,'EXOL100001',13,15,26.00,'F','2025-11-09 09:55:26',1,'2025-11-09 09:56:09','compiled'),(147,'EXOL100001',13,10,69.00,'B','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(148,'EXOL100001',14,1,34.00,'D','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(149,'EXOL100001',14,2,59.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(150,'EXOL100001',14,3,43.00,'D','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(151,'EXOL100001',14,5,60.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(152,'EXOL100001',14,7,69.00,'B','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(153,'EXOL100001',14,8,28.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(154,'EXOL100001',14,9,82.00,'A','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(155,'EXOL100001',14,11,68.00,'B','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(156,'EXOL100001',14,12,35.00,'D','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(157,'EXOL100001',14,14,14.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(158,'EXOL100001',14,15,47.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(159,'EXOL100001',14,10,19.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(160,'EXOL100001',15,1,80.00,'A','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(161,'EXOL100001',15,2,41.00,'D','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(162,'EXOL100001',15,3,48.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(163,'EXOL100001',15,5,28.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(164,'EXOL100001',15,7,43.00,'D','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(165,'EXOL100001',15,9,78.00,'A','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(166,'EXOL100001',15,11,58.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(167,'EXOL100001',15,12,14.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(168,'EXOL100001',15,14,18.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(169,'EXOL100001',15,15,81.00,'A','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(170,'EXOL100001',15,10,21.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(171,'EXOL100001',16,1,72.00,'B','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(172,'EXOL100001',16,2,11.00,'F','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(173,'EXOL100001',16,3,32.00,'D','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(174,'EXOL100001',16,5,49.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(175,'EXOL100001',16,7,90.00,'A','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(176,'EXOL100001',16,9,75.00,'A','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(177,'EXOL100001',16,11,57.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(178,'EXOL100001',16,12,52.00,'C','2025-11-09 09:55:27',1,'2025-11-09 09:56:09','compiled'),(179,'EXOL100001',16,14,51.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(180,'EXOL100001',16,15,29.00,'F','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(181,'EXOL100001',16,10,52.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(182,'EXOL100001',17,1,50.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(183,'EXOL100001',17,2,63.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(184,'EXOL100001',17,3,75.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(185,'EXOL100001',17,5,91.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(186,'EXOL100001',17,7,36.00,'D','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(187,'EXOL100001',17,8,33.00,'D','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(188,'EXOL100001',17,9,65.00,'B','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(189,'EXOL100001',17,11,60.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(190,'EXOL100001',17,12,35.00,'D','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(191,'EXOL100001',17,14,49.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(192,'EXOL100001',17,10,50.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(193,'EXOL100001',18,1,83.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(194,'EXOL100001',18,2,86.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(195,'EXOL100001',18,3,86.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(196,'EXOL100001',18,5,94.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(197,'EXOL100001',18,7,48.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(198,'EXOL100001',18,8,19.00,'F','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(199,'EXOL100001',18,9,26.00,'F','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(200,'EXOL100001',18,11,71.00,'B','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(201,'EXOL100001',18,12,47.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(202,'EXOL100001',18,14,13.00,'F','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(203,'EXOL100001',18,10,98.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(204,'EXOL100001',19,1,77.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(205,'EXOL100001',19,2,48.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(206,'EXOL100001',19,3,21.00,'F','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(207,'EXOL100001',19,5,96.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(208,'EXOL100001',19,7,25.00,'F','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(209,'EXOL100001',19,8,98.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(210,'EXOL100001',19,9,97.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(211,'EXOL100001',19,11,45.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(212,'EXOL100001',19,12,75.00,'A','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(213,'EXOL100001',19,14,61.00,'C','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(214,'EXOL100001',19,10,44.00,'D','2025-11-09 09:55:28',1,'2025-11-09 09:56:09','compiled'),(215,'EXOL100001',20,1,38.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(216,'EXOL100001',20,2,14.00,'F','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(217,'EXOL100001',20,3,54.00,'C','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(218,'EXOL100001',20,5,87.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(219,'EXOL100001',20,7,42.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(220,'EXOL100001',20,8,66.00,'B','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(221,'EXOL100001',20,9,14.00,'F','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(222,'EXOL100001',20,11,77.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(223,'EXOL100001',20,12,54.00,'C','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(224,'EXOL100001',20,14,12.00,'F','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(225,'EXOL100001',20,15,44.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(226,'EXOL100001',20,10,29.00,'F','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(227,'EXOL100001',44,1,31.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(228,'EXOL100001',44,2,95.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(229,'EXOL100001',44,3,88.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(230,'EXOL100001',44,5,26.00,'F','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(231,'EXOL100001',44,7,45.00,'C','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(232,'EXOL100001',44,9,77.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(233,'EXOL100001',44,11,71.00,'B','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(234,'EXOL100001',44,12,13.00,'F','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(235,'EXOL100001',44,14,97.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(236,'EXOL100001',44,15,62.00,'C','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(237,'EXOL100001',44,10,35.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(238,'EXOL100001',3,1,37.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(239,'EXOL100001',3,2,73.00,'B','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(240,'EXOL100001',3,3,53.00,'C','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(241,'EXOL100001',3,5,34.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(242,'EXOL100001',3,7,40.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(243,'EXOL100001',3,8,38.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(244,'EXOL100001',3,9,78.00,'A','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(245,'EXOL100001',3,11,35.00,'D','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(246,'EXOL100001',3,12,67.00,'B','2025-11-09 09:55:29',1,'2025-11-09 09:56:09','compiled'),(247,'EXOL100001',3,14,34.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(248,'EXOL100001',3,15,97.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(249,'EXOL100001',3,10,22.00,'F','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(250,'EXOL100001',23,1,76.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(251,'EXOL100001',23,2,85.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(252,'EXOL100001',23,3,92.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(253,'EXOL100001',23,5,43.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(254,'EXOL100001',23,7,64.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(255,'EXOL100001',23,9,54.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(256,'EXOL100001',23,11,67.00,'B','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(257,'EXOL100001',23,12,63.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(258,'EXOL100001',23,14,82.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(259,'EXOL100001',23,15,34.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(260,'EXOL100001',23,10,63.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(261,'EXOL100001',25,1,76.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(262,'EXOL100001',25,2,73.00,'B','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(263,'EXOL100001',25,3,58.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(264,'EXOL100001',25,5,73.00,'B','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(265,'EXOL100001',25,7,45.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(266,'EXOL100001',25,8,57.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(267,'EXOL100001',25,9,35.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(268,'EXOL100001',25,11,17.00,'F','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(269,'EXOL100001',25,12,66.00,'B','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(270,'EXOL100001',25,14,56.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(271,'EXOL100001',25,10,19.00,'F','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(272,'EXOL100001',26,1,42.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(273,'EXOL100001',26,2,12.00,'F','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(274,'EXOL100001',26,3,36.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(275,'EXOL100001',26,5,74.00,'B','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(276,'EXOL100001',26,7,48.00,'C','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(277,'EXOL100001',26,8,40.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(278,'EXOL100001',26,9,37.00,'D','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(279,'EXOL100001',26,11,91.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(280,'EXOL100001',26,12,96.00,'A','2025-11-09 09:55:30',1,'2025-11-09 09:56:09','compiled'),(281,'EXOL100001',26,14,83.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(282,'EXOL100001',26,10,78.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(283,'EXOL100001',27,1,27.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(284,'EXOL100001',27,2,95.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(285,'EXOL100001',27,3,72.00,'B','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(286,'EXOL100001',27,5,88.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(287,'EXOL100001',27,7,83.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(288,'EXOL100001',27,8,28.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(289,'EXOL100001',27,9,85.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(290,'EXOL100001',27,11,23.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(291,'EXOL100001',27,12,87.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(292,'EXOL100001',27,14,83.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(293,'EXOL100001',27,10,57.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(294,'EXOL100001',28,1,81.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(295,'EXOL100001',28,2,20.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(296,'EXOL100001',28,3,57.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(297,'EXOL100001',28,5,50.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(298,'EXOL100001',28,7,76.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(299,'EXOL100001',28,8,96.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(300,'EXOL100001',28,9,10.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(301,'EXOL100001',28,11,47.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(302,'EXOL100001',28,12,61.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(303,'EXOL100001',28,14,40.00,'D','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(304,'EXOL100001',28,10,23.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(305,'EXOL100001',29,1,93.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(306,'EXOL100001',29,2,58.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(307,'EXOL100001',29,3,64.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(308,'EXOL100001',29,5,34.00,'D','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(309,'EXOL100001',29,7,10.00,'F','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(310,'EXOL100001',29,8,58.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(311,'EXOL100001',29,9,49.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(312,'EXOL100001',29,11,53.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(313,'EXOL100001',29,12,39.00,'D','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(314,'EXOL100001',29,14,39.00,'D','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(315,'EXOL100001',29,10,57.00,'C','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(316,'EXOL100001',30,1,91.00,'A','2025-11-09 09:55:31',1,'2025-11-09 09:56:09','compiled'),(317,'EXOL100001',30,2,21.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(318,'EXOL100001',30,3,63.00,'C','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(319,'EXOL100001',30,5,29.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(320,'EXOL100001',30,7,23.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(321,'EXOL100001',30,8,33.00,'D','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(322,'EXOL100001',30,9,23.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(323,'EXOL100001',30,11,51.00,'C','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(324,'EXOL100001',30,12,65.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(325,'EXOL100001',30,14,25.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(326,'EXOL100001',30,15,94.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(327,'EXOL100001',30,10,77.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(328,'EXOL100001',31,1,68.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(329,'EXOL100001',31,2,68.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(330,'EXOL100001',31,3,71.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(331,'EXOL100001',31,5,98.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(332,'EXOL100001',31,7,71.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(333,'EXOL100001',31,8,74.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(334,'EXOL100001',31,9,11.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(335,'EXOL100001',31,11,38.00,'D','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(336,'EXOL100001',31,12,97.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(337,'EXOL100001',31,14,82.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(338,'EXOL100001',31,15,38.00,'D','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(339,'EXOL100001',31,10,10.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(340,'EXOL100001',32,1,76.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(341,'EXOL100001',32,2,77.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(342,'EXOL100001',32,3,34.00,'D','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(343,'EXOL100001',32,5,24.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(344,'EXOL100001',32,7,65.00,'B','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(345,'EXOL100001',32,8,23.00,'F','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(346,'EXOL100001',32,9,84.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(347,'EXOL100001',32,11,89.00,'A','2025-11-09 09:55:32',1,'2025-11-09 09:56:09','compiled'),(348,'EXOL100001',32,12,81.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(349,'EXOL100001',32,14,94.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(350,'EXOL100001',32,15,77.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(351,'EXOL100001',32,10,89.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(352,'EXOL100001',33,1,88.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(353,'EXOL100001',33,2,50.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(354,'EXOL100001',33,3,80.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(355,'EXOL100001',33,5,24.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(356,'EXOL100001',33,7,17.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(357,'EXOL100001',33,8,77.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(358,'EXOL100001',33,9,24.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(359,'EXOL100001',33,11,23.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(360,'EXOL100001',33,12,64.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(361,'EXOL100001',33,14,74.00,'B','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(362,'EXOL100001',33,15,37.00,'D','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(363,'EXOL100001',33,10,16.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(364,'EXOL100001',34,1,49.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(365,'EXOL100001',34,2,59.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(366,'EXOL100001',34,3,81.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(367,'EXOL100001',34,5,32.00,'D','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(368,'EXOL100001',34,7,32.00,'D','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(369,'EXOL100001',34,8,91.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(370,'EXOL100001',34,9,45.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(371,'EXOL100001',34,11,18.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(372,'EXOL100001',34,12,98.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(373,'EXOL100001',34,14,80.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(374,'EXOL100001',34,15,79.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(375,'EXOL100001',34,10,58.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(376,'EXOL100001',35,1,49.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(377,'EXOL100001',35,2,62.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(378,'EXOL100001',35,3,79.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(379,'EXOL100001',35,5,45.00,'C','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(380,'EXOL100001',35,7,33.00,'D','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(381,'EXOL100001',35,8,17.00,'F','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(382,'EXOL100001',35,9,75.00,'A','2025-11-09 09:55:33',1,'2025-11-09 09:56:09','compiled'),(383,'EXOL100001',35,11,30.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(384,'EXOL100001',35,12,36.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(385,'EXOL100001',35,14,26.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(386,'EXOL100001',35,10,56.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(387,'EXOL100001',36,1,11.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(388,'EXOL100001',36,2,32.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(389,'EXOL100001',36,3,80.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(390,'EXOL100001',36,5,93.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(391,'EXOL100001',36,7,24.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(392,'EXOL100001',36,9,83.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(393,'EXOL100001',36,11,24.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(394,'EXOL100001',36,12,62.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(395,'EXOL100001',36,14,85.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(396,'EXOL100001',36,10,60.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(397,'EXOL100001',37,1,81.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(398,'EXOL100001',37,2,53.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(399,'EXOL100001',37,3,10.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(400,'EXOL100001',37,5,98.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(401,'EXOL100001',37,7,40.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(402,'EXOL100001',37,8,50.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(403,'EXOL100001',37,9,59.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(404,'EXOL100001',37,11,10.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(405,'EXOL100001',37,12,55.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(406,'EXOL100001',37,14,19.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(407,'EXOL100001',37,10,35.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(408,'EXOL100001',38,1,84.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(409,'EXOL100001',38,2,41.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(410,'EXOL100001',38,3,65.00,'B','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(411,'EXOL100001',38,5,56.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(412,'EXOL100001',38,7,20.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(413,'EXOL100001',38,8,67.00,'B','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(414,'EXOL100001',38,9,28.00,'F','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(415,'EXOL100001',38,11,32.00,'D','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(416,'EXOL100001',38,12,54.00,'C','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(417,'EXOL100001',38,14,93.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(418,'EXOL100001',38,10,96.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(419,'EXOL100001',39,1,98.00,'A','2025-11-09 09:55:34',1,'2025-11-09 09:56:09','compiled'),(420,'EXOL100001',39,2,59.00,'C','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(421,'EXOL100001',39,3,67.00,'B','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(422,'EXOL100001',39,5,87.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(423,'EXOL100001',39,7,91.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(424,'EXOL100001',39,8,71.00,'B','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(425,'EXOL100001',39,9,40.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(426,'EXOL100001',39,11,90.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(427,'EXOL100001',39,12,45.00,'C','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(428,'EXOL100001',39,14,73.00,'B','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(429,'EXOL100001',39,10,35.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(430,'EXOL100001',40,1,80.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(431,'EXOL100001',40,2,41.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(432,'EXOL100001',40,3,15.00,'F','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(433,'EXOL100001',40,5,12.00,'F','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(434,'EXOL100001',40,7,94.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(435,'EXOL100001',40,8,74.00,'B','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(436,'EXOL100001',40,9,78.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(437,'EXOL100001',40,11,43.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(438,'EXOL100001',40,12,17.00,'F','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(439,'EXOL100001',40,14,52.00,'C','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(440,'EXOL100001',40,15,23.00,'F','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(441,'EXOL100001',40,10,36.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(442,'EXOL100001',41,1,86.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(443,'EXOL100001',41,2,37.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(444,'EXOL100001',41,3,72.00,'B','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(445,'EXOL100001',41,5,78.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(446,'EXOL100001',41,7,17.00,'F','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(447,'EXOL100001',41,8,73.00,'B','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(448,'EXOL100001',41,9,87.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(449,'EXOL100001',41,11,92.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(450,'EXOL100001',41,12,60.00,'C','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(451,'EXOL100001',41,14,43.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(452,'EXOL100001',41,15,44.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(453,'EXOL100001',41,10,96.00,'A','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(454,'EXOL100001',42,1,34.00,'D','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(455,'EXOL100001',42,2,63.00,'C','2025-11-09 09:55:35',1,'2025-11-09 09:56:09','compiled'),(456,'EXOL100001',42,3,11.00,'F','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(457,'EXOL100001',42,5,38.00,'D','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(458,'EXOL100001',42,7,49.00,'C','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(459,'EXOL100001',42,8,93.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(460,'EXOL100001',42,9,53.00,'C','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(461,'EXOL100001',42,11,92.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(462,'EXOL100001',42,12,62.00,'C','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(463,'EXOL100001',42,14,85.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(464,'EXOL100001',42,15,65.00,'B','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(465,'EXOL100001',42,10,91.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(466,'EXOL100001',43,1,79.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(467,'EXOL100001',43,2,77.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(468,'EXOL100001',43,3,44.00,'D','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(469,'EXOL100001',43,5,34.00,'D','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(470,'EXOL100001',43,7,34.00,'D','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(471,'EXOL100001',43,9,55.00,'C','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(472,'EXOL100001',43,11,29.00,'F','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(473,'EXOL100001',43,12,89.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(474,'EXOL100001',43,14,79.00,'A','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(475,'EXOL100001',43,15,67.00,'B','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled'),(476,'EXOL100001',43,10,71.00,'B','2025-11-09 09:55:36',1,'2025-11-09 09:56:09','compiled');
/*!40000 ALTER TABLE `rawresults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regions` (
  `regID` int NOT NULL AUTO_INCREMENT,
  `regName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `regZone` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `regLand` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`regID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `route_stations`
--

DROP TABLE IF EXISTS `route_stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `route_stations` (
  `rsID` int NOT NULL AUTO_INCREMENT,
  `route_id` int NOT NULL,
  `station_id` int NOT NULL,
  `rsCreated` datetime DEFAULT NULL,
  `rsStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`rsID`),
  KEY `route_stations_route_id_foreign` (`route_id`),
  KEY `route_stations_station_id_foreign` (`station_id`),
  CONSTRAINT `route_stations_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`rouID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `route_stations_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`staID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `route_stations`
--

LOCK TABLES `route_stations` WRITE;
/*!40000 ALTER TABLE `route_stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `route_stations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `routes` (
  `rouID` int NOT NULL AUTO_INCREMENT,
  `rouName` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `rouStart` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `rouEnd` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `rouCreated` datetime DEFAULT NULL,
  `rouUpdated` datetime DEFAULT NULL,
  `rouStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`rouID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stations` (
  `staID` int NOT NULL AUTO_INCREMENT,
  `staName` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `staGps` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staBefore` int DEFAULT NULL,
  `staAfter` int DEFAULT NULL,
  `staCreated` datetime DEFAULT NULL,
  `staUpdated` datetime DEFAULT NULL,
  `staStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`staID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations`
--

LOCK TABLES `stations` WRITE;
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `streams`
--

DROP TABLE IF EXISTS `streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `streams` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `sName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `sCreated` datetime DEFAULT NULL,
  `sUpdated` datetime DEFAULT NULL,
  `sStatus` enum('Active','Canceled','Next') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`sid`),
  KEY `streams_class_id_foreign` (`class_id`),
  CONSTRAINT `streams_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `streams`
--

LOCK TABLES `streams` WRITE;
/*!40000 ALTER TABLE `streams` DISABLE KEYS */;
INSERT INTO `streams` VALUES (1,1,'A','2025-10-25 19:36:47',NULL,'Active'),(2,1,'B','2025-10-25 19:36:47',NULL,'Active'),(3,1,'C','2025-10-25 19:36:47',NULL,'Active'),(4,2,'A','2025-10-26 12:41:01',NULL,'Active'),(7,3,'A','2025-10-26 13:16:49',NULL,'Active'),(8,3,'B','2025-10-26 13:19:43',NULL,'Active'),(9,4,'A','2025-10-26 13:19:53',NULL,'Active'),(10,4,'B','2025-10-26 13:19:58',NULL,'Active'),(11,5,'HGK','2025-10-26 13:20:10',NULL,'Active'),(12,5,'PCB','2025-10-26 13:20:18',NULL,'Active'),(13,6,'HGK','2025-10-26 13:21:33',NULL,'Active'),(14,6,'PCB','2025-10-26 13:21:41',NULL,'Active'),(15,2,'B','2025-10-26 13:26:14',NULL,'Active'),(16,2,'C','2025-10-26 13:26:25',NULL,'Active'),(17,5,'EGM','2025-10-28 20:22:26',NULL,'Active'),(18,5,'HKL','2025-11-01 21:50:01',NULL,'Active');
/*!40000 ALTER TABLE `streams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `streets`
--

DROP TABLE IF EXISTS `streets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `streets` (
  `strID` int NOT NULL AUTO_INCREMENT,
  `strName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ward_id` int NOT NULL,
  PRIMARY KEY (`strID`),
  KEY `streets_ward_id_foreign` (`ward_id`),
  CONSTRAINT `streets_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`waID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `streets`
--

LOCK TABLES `streets` WRITE;
/*!40000 ALTER TABLE `streets` DISABLE KEYS */;
/*!40000 ALTER TABLE `streets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_routestation`
--

DROP TABLE IF EXISTS `student_routestation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_routestation` (
  `srsID` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `route_station_id` int NOT NULL,
  `assigned_by` int DEFAULT NULL,
  `assigned_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`srsID`),
  KEY `student_routestation_student_id_foreign` (`student_id`),
  KEY `student_routestation_route_station_id_foreign` (`route_station_id`),
  KEY `student_routestation_assigned_by_foreign` (`assigned_by`),
  CONSTRAINT `student_routestation_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `student_routestation_route_station_id_foreign` FOREIGN KEY (`route_station_id`) REFERENCES `route_stations` (`rsID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_routestation_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`stuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_routestation`
--

LOCK TABLES `student_routestation` WRITE;
/*!40000 ALTER TABLE `student_routestation` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_routestation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `stuID` int NOT NULL AUTO_INCREMENT,
  `stuFname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `stuMname` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `stuSurname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `stuSex` enum('Female','Male') COLLATE utf8mb4_general_ci NOT NULL,
  `stuDob` date DEFAULT NULL,
  `stream_id` int DEFAULT NULL,
  `guardian_id` int DEFAULT NULL,
  `stuStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`stuID`),
  KEY `students_guardian_id_foreign` (`guardian_id`),
  KEY `students_stream_id_foreign` (`stream_id`),
  CONSTRAINT `students_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`guID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `students_stream_id_foreign` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`sid`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (3,'Mastura','Yasin','Mzamiru','Female','2014-06-11',3,NULL,'Active'),(4,'Asanath','Birungi','Hussein','Female','2011-01-22',1,NULL,'Active'),(5,'Hamisa','Hussein','Ismail','Female','2012-10-17',1,1,'Active'),(6,'Amisa','Kokusima','Ismail','Female','0000-00-00',1,NULL,'Active'),(7,'Anisia','Kokusima','Ismail','Female','0000-00-00',1,NULL,'Active'),(8,'Ashura','Kokusima','Ismail','Female','0000-00-00',1,NULL,'Active'),(9,'Asfath','Kokusima','Edson','Female','0000-00-00',1,NULL,'Active'),(10,'Asha','Kokusima','Charles','Female','2010-06-23',1,1,'Active'),(11,'Azaina','Kokusima','Suleiman','Female','0000-00-00',2,NULL,'active'),(12,'Batur','Kokusima','Ismail','Female','0000-00-00',2,NULL,'active'),(13,'Bakhris','Kokusima','Ismail','Female','0000-00-00',2,NULL,'Active'),(14,'Amina','Atugonza','Ismail','Female','0000-00-00',2,NULL,'Active'),(15,'Angel','Emmanuel','Edson','Female','0000-00-00',2,NULL,'Active'),(16,'Claudia','Aganyila','Charles','Female','0000-00-00',2,NULL,'active'),(17,'Salma','Swaibu','Suleiman','Female','0000-00-00',2,NULL,'Active'),(18,'Farihya','Hussein','Ismail','Female','0000-00-00',2,1,'active'),(19,'Farihath','Hussein','Ismail','Female','0000-00-00',2,1,'active'),(20,'Abdulaziz','Shaidu','Ismail','Male','0000-00-00',2,NULL,'Active'),(21,'Twasin',NULL,'Yasin','Male','0000-00-00',1,NULL,'Active'),(22,'Shafiyu','Antony','Idrissa','Male','0000-00-00',1,NULL,'Active'),(23,'Epimachius',NULL,'Elasmus','Male','0000-00-00',3,NULL,'active'),(24,'Samia','Sajad','Hassan','Female','2011-01-20',1,NULL,'Active'),(25,'Aisha','Kokusima','Suleiman','Female','2008-12-24',3,NULL,'active'),(26,'Amisa','Kokusima','Ismail','Female','2008-12-25',3,NULL,'Active'),(27,'Anisia','Kokusima','Ismail','Female','2008-12-26',3,NULL,'active'),(28,'Ashura','Kokusima','Ismail','Female','2008-12-27',3,NULL,'active'),(29,'Asfath','Kokusima','Edson','Female','2008-12-28',3,NULL,'active'),(30,'Asha','Kokusima','Charles','Female','2008-12-29',3,NULL,'active'),(31,'Azaina','Kokusima','Suleiman','Female','2008-12-30',3,NULL,'active'),(32,'Batur','Kokusima','Ismail','Female','2008-12-31',3,NULL,'active'),(33,'Bakhris','Kokusima','Ismail','Female','2009-01-01',3,NULL,'active'),(34,'Amina','Atugonza','Ismail','Female','2009-01-02',3,NULL,'Active'),(35,'Angel','Emmanuel','Edson','Female','2009-01-03',3,NULL,'active'),(36,'Claudia','Aganyila','Charles','Female','2009-01-04',3,NULL,'active'),(37,'Salma','Swaibu','Suleiman','Female','2009-01-05',3,NULL,'active'),(38,'Farihya','Hussein','Ismail','Female','2009-01-06',3,NULL,'active'),(39,'Farihath','Hussein','Ismail','Female','2009-01-07',3,NULL,'active'),(40,'Abdulaziz','Shaidu','Ismail','Male','2009-01-08',3,NULL,'Active'),(41,'Twasin',NULL,'Yasin','Male','2009-01-09',3,NULL,'active'),(42,'Shafiyu','Antony','Idrissa','Male','2009-01-10',3,NULL,'active'),(43,'Epimachius',NULL,'Elasmus','Male','2009-01-11',3,NULL,'active'),(44,'Justa','Emmanuel','Kiiza','Female','2013-10-10',2,NULL,'Active');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_streams`
--

DROP TABLE IF EXISTS `subject_streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subject_streams` (
  `scID` int NOT NULL AUTO_INCREMENT,
  `stream_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `isMandatory` tinyint(1) NOT NULL DEFAULT '1',
  `hasPractical` tinyint(1) DEFAULT NULL,
  `isSubsidiary` tinyint(1) DEFAULT NULL,
  `subCreated` datetime DEFAULT NULL,
  `subUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`scID`),
  KEY `subject_streams_stream_id_foreign` (`stream_id`),
  KEY `subject_streams_subject_id_foreign` (`subject_id`),
  CONSTRAINT `subject_streams_stream_id_foreign` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subject_streams_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_streams`
--

LOCK TABLES `subject_streams` WRITE;
/*!40000 ALTER TABLE `subject_streams` DISABLE KEYS */;
INSERT INTO `subject_streams` VALUES (40,1,1,1,NULL,NULL,'2025-11-04 16:23:56',NULL),(41,1,2,1,NULL,NULL,'2025-11-04 16:24:05',NULL),(42,1,3,1,NULL,NULL,'2025-11-04 16:24:15',NULL),(43,1,5,1,NULL,NULL,'2025-11-04 16:24:23',NULL),(44,1,7,1,NULL,NULL,'2025-11-04 16:24:44',NULL),(45,1,8,1,NULL,NULL,'2025-11-04 16:24:54',NULL),(46,1,9,1,NULL,NULL,'2025-11-04 16:25:02',NULL),(47,1,11,1,NULL,NULL,'2025-11-04 16:25:10',NULL),(48,1,12,1,NULL,NULL,'2025-11-04 16:25:15',NULL),(49,1,14,1,NULL,NULL,'2025-11-04 16:25:21',NULL),(50,1,15,1,NULL,NULL,'2025-11-04 16:25:27',NULL),(67,11,4,1,NULL,1,'2025-11-07 16:07:47',NULL),(68,11,10,1,NULL,1,'2025-11-07 16:08:03',NULL),(69,11,11,1,NULL,NULL,'2025-11-07 16:09:02',NULL),(70,11,9,1,NULL,NULL,'2025-11-07 16:09:26',NULL),(71,11,12,1,NULL,NULL,'2025-11-07 16:10:04',NULL),(72,12,4,1,NULL,1,'2025-11-07 16:11:03',NULL),(73,12,18,1,NULL,1,'2025-11-07 16:11:32',NULL),(74,12,10,1,NULL,1,'2025-11-07 16:12:45',NULL),(75,12,14,1,1,NULL,'2025-11-07 16:13:36',NULL),(76,12,5,1,1,NULL,'2025-11-07 16:13:52',NULL),(78,12,2,1,1,NULL,'2025-11-07 16:15:10',NULL),(79,3,1,1,NULL,NULL,'2025-11-07 16:48:35',NULL),(80,3,2,0,NULL,NULL,'2025-11-07 16:48:50',NULL),(81,3,3,1,NULL,NULL,'2025-11-07 16:49:06',NULL),(82,3,7,1,NULL,NULL,'2025-11-07 16:49:22',NULL),(83,3,8,0,NULL,NULL,'2025-11-07 16:49:38',NULL),(84,3,9,1,NULL,NULL,'2025-11-07 16:49:52',NULL),(85,3,10,1,NULL,NULL,'2025-11-07 16:50:59',NULL),(86,3,11,1,NULL,NULL,'2025-11-07 16:51:09',NULL),(87,3,12,1,NULL,NULL,'2025-11-07 16:51:23',NULL),(88,3,12,1,NULL,NULL,'2025-11-07 16:51:23',NULL),(89,4,1,1,NULL,NULL,'2025-11-07 17:55:58',NULL),(90,4,2,1,NULL,NULL,'2025-11-07 17:56:17',NULL),(91,4,5,1,NULL,NULL,'2025-11-07 17:56:27',NULL),(92,15,5,1,NULL,NULL,'2025-11-07 17:56:27',NULL),(93,4,6,1,NULL,NULL,'2025-11-07 17:56:39',NULL),(94,4,7,1,NULL,NULL,'2025-11-07 17:57:04',NULL),(95,4,8,0,NULL,NULL,'2025-11-07 17:57:16',NULL),(96,4,9,1,NULL,NULL,'2025-11-07 17:57:25',NULL),(97,4,11,1,NULL,NULL,'2025-11-07 17:57:38',NULL),(98,4,12,1,NULL,NULL,'2025-11-07 18:01:33',NULL),(99,4,14,1,NULL,NULL,'2025-11-07 18:01:45',NULL);
/*!40000 ALTER TABLE `subject_streams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `subID` int NOT NULL AUTO_INCREMENT,
  `subName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `subCode` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `subCategory` enum('Core','Option') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Core',
  `subLevel` enum('O-level','A-Level','Both') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Both',
  `subCurriculum` enum('Old','New','Both') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Both',
  `subCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `subUpdated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`subID`),
  UNIQUE KEY `subCode_UNIQUE` (`subCode`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Basic Mathematics','041','Core','O-level','Both','2025-10-26 14:26:55',NULL),(2,'Biology','033','Core','Both','Both','2025-10-26 14:31:39','2025-10-28 21:33:08'),(3,'Business Studies','BSS','Core','O-level','New','2025-10-26 14:32:33',NULL),(4,'Academic Communication','AC01','Core','A-Level','New','2025-10-26 14:34:13',NULL),(5,'Chemistry','032','Option','Both','Both','2025-10-26 14:36:06','2025-10-28 21:33:08'),(6,'Civics','011','Core','O-level','Old','2025-10-26 14:36:58',NULL),(7,'English Language','022','Core','Both','Both','2025-10-26 14:39:10','2025-10-28 21:33:08'),(8,'Elimu ya Dini ya Kiislamu','015','Option','O-level','Both','2025-10-26 14:40:15',NULL),(9,'Geography','013','Core','Both','Both','2025-10-26 14:41:40','2025-10-28 21:33:08'),(10,'Historia ya Tanzania na Maadili','060','Core','Both','New','2025-10-26 14:44:21','2025-11-07 20:00:42'),(11,'History','012','Option','Both','Both','2025-10-26 14:45:15','2025-10-28 21:33:08'),(12,'Kiswahili','021','Core','Both','Both','2025-10-26 14:49:11','2025-10-28 21:33:08'),(13,'Literature in English','024','Option','O-level','Both','2025-10-26 14:51:04',NULL),(14,'Physics','031','Option','Both','Both','2025-10-26 14:52:32','2025-10-28 21:33:08'),(15,'Computer Science','034','Option','O-level','Both','2025-10-27 17:37:01',NULL),(16,'Economics','E22','Core','A-Level','Both','2025-10-28 17:24:00',NULL),(17,'General Studies','GS01','Core','A-Level','Old','2025-10-28 17:35:48',NULL),(18,'Basic Applied Mathematics','141','Option','A-Level','Both','2025-10-28 18:11:20',NULL),(19,'French','025','Option','O-level','Both','2025-11-01 18:51:25',NULL),(20,'Mathematics','043','Core','O-level','New','2025-11-07 20:00:42',NULL);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_feedbacks`
--

DROP TABLE IF EXISTS `tour_feedbacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_feedbacks` (
  `tfID` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `gurdian_id` int NOT NULL,
  `tfResponse` text COLLATE utf8mb4_general_ci,
  `tfState` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employee_id` int NOT NULL,
  `tfComment` text COLLATE utf8mb4_general_ci,
  `tfStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tfCreated` datetime DEFAULT NULL,
  `tfUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`tfID`),
  KEY `tour_feedbacks_student_id_foreign` (`student_id`),
  KEY `tour_feedbacks_gurdian_id_foreign` (`gurdian_id`),
  KEY `tour_feedbacks_employee_id_foreign` (`employee_id`),
  CONSTRAINT `tour_feedbacks_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_feedbacks_gurdian_id_foreign` FOREIGN KEY (`gurdian_id`) REFERENCES `guardians` (`guID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_feedbacks_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`stuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_feedbacks`
--

LOCK TABLES `tour_feedbacks` WRITE;
/*!40000 ALTER TABLE `tour_feedbacks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tour_feedbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_routes`
--

DROP TABLE IF EXISTS `tour_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_routes` (
  `trID` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `route_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `trDeparture` datetime DEFAULT NULL,
  `trArrival` datetime DEFAULT NULL,
  `trPosition` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`trID`),
  KEY `tour_routes_tour_id_foreign` (`tour_id`),
  KEY `tour_routes_route_id_foreign` (`route_id`),
  KEY `tour_routes_employee_id_foreign` (`employee_id`),
  KEY `tour_routes_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `tour_routes_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_routes_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`rouID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_routes_tour_id_foreign` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`touID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_routes_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`veID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_routes`
--

LOCK TABLES `tour_routes` WRITE;
/*!40000 ALTER TABLE `tour_routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tour_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tours` (
  `touID` int NOT NULL AUTO_INCREMENT,
  `touName` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `touCategory` enum('Departure','Arrival','Tour') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `touStart` datetime DEFAULT NULL,
  `touEnd` datetime DEFAULT NULL,
  `touStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`touID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehicles` (
  `veID` int NOT NULL AUTO_INCREMENT,
  `vePlateNumber` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `veModel` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `veOwnership` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `veNamed` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`veID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wards`
--

DROP TABLE IF EXISTS `wards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wards` (
  `waID` int NOT NULL AUTO_INCREMENT,
  `waName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `district_id` int NOT NULL,
  PRIMARY KEY (`waID`),
  KEY `wards_district_id_foreign` (`district_id`),
  CONSTRAINT `wards_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`disID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wards`
--

LOCK TABLES `wards` WRITE;
/*!40000 ALTER TABLE `wards` DISABLE KEYS */;
/*!40000 ALTER TABLE `wards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'lyamahoro'
--

--
-- Dumping routines for database 'lyamahoro'
--
/*!50003 DROP PROCEDURE IF EXISTS `insertGuardian` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertGuardian`(
	IN p_fname VARCHAR(75),
	IN p_sex ENUM('Male','Female'),
	IN p_dob DATE,
	IN p_email VARCHAR(75),
	IN p_password VARCHAR(255),
	IN p_occupation varchar(75)
    )
BEGIN 
DECLARE v_count INT DEFAULT 0;
START TRANSACTION;
SELECT COUNT(*) INTO v_count FROM guardians WHERE LOWER(empname) = LOWER(p_fname)
              AND LOWER(empEmail) = LOWER(p_email);
IF v_count > 0 THEN ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Guardian with same info already exists";
ELSE
	INSERT INTO guardians (empname, guSex, empDob, guOccupasion, empEmail, empPassword, guRegisterd, guStatus) 
			VALUES (lower(p_fname), p_sex, p_dob,  p_occupation, p_email, p_password, now(), 'Active');
	COMMIT;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertStream` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertStream`(
	IN p_name VARCHAR(75),
	IN p_class INT,
    IN p_status VARCHAR(25)
    )
BEGIN 
DECLARE v_count INT DEFAULT 0;
START TRANSACTION;
SELECT COUNT(*) INTO v_count FROM streams WHERE LOWER(sName) = LOWER(p_name)
              AND class_id = p_class;
IF v_count > 0 THEN ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Stream with same info already exists";
ELSE
	INSERT INTO `streams` (`class_id`, `sName`, `sCreated`, `sStatus`) VALUES (p_class, p_name, now(), p_status);
	COMMIT;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insertStudent` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertStudent`(
	IN p_fname VARCHAR(45),
	IN p_mname VARCHAR(45),
	IN p_surname VARCHAR(45),
	IN p_sex ENUM('Male','Female'),
	IN p_dob DATE,
	IN p_stream INT,
	IN p_guardian INT )
BEGIN 
DECLARE v_count INT DEFAULT 0;
START TRANSACTION;
SELECT COUNT(*) INTO v_count FROM students WHERE LOWER(stuFname) = LOWER(p_fname)
              AND LOWER(stuSurname) = LOWER(p_surname)
              AND stream_id = p_stream
              AND stuDob = p_dob;
IF v_count > 0 THEN ROLLBACK;
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Student with same info already exists";
ELSE
	INSERT INTO students (stuFname, stuMname, stuSurname, stuSex, stuDob, stream_id, guardian_id, stuStatus)
                VALUES (p_fname, p_mname, p_surname, p_sex, p_dob, p_stream, p_guardian, 'Active');
	COMMIT;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-11  6:51:37


create table if not exists employee_roles (
erID int not null primary key auto_increment,
employee_id int not null,
erRole enum('Admin', 'Head', 'Deputy', 'Academic', 'Discipline', 'sbm', 'cm') not null default 'sbm',
erAssigned date not null,
erUnAssigned date not null,
erCreated timestamp default current_timestamp,
erUpdated timestamp default null on update current_timestamp,
erStatus enum('Active', 'Inactive', 'Stoped', 'Dedicated', 'Undedicated') not null default 'Active');