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
  CONSTRAINT `compiledResults_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`stuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compiledresults`
--

LOCK TABLES `compiledresults` WRITE;
/*!40000 ALTER TABLE `compiledresults` DISABLE KEYS */;
INSERT INTO `compiledresults` VALUES (5,'EXOL100001',49,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"91.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"82.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"88.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"79.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"67.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Mathematics\",\"score\":\"70.00\",\"grade\":\"B\",\"points\":2}],\"points\":9,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-26 21:46:38',15,NULL,'compiled'),(6,'EXOL100001',50,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"87.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"80.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"77.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"51.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Computer Science\",\"score\":\"62.00\",\"grade\":\"C\",\"points\":3},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"45.00\",\"grade\":\"C\",\"points\":3}],\"points\":13,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-26 21:46:38',15,NULL,'compiled'),(7,'EXOL100001',51,'{\"subjects\":[{\"subject\":\"Business Studies\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"75.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Computer Science\",\"score\":\"81.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"79.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"English Language\",\"score\":\"95.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Mathematics\",\"score\":\"86.00\",\"grade\":\"A\",\"points\":1}],\"points\":7,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-26 21:46:38',15,NULL,'compiled'),(8,'EXOL100001',52,'{\"subjects\":[{\"subject\":\"Biology\",\"score\":\"100.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Business Studies\",\"score\":\"83.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Chemistry\",\"score\":\"93.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Historia ya Tanzania na Maadili\",\"score\":\"90.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Kiswahili\",\"score\":\"92.00\",\"grade\":\"A\",\"points\":1},{\"subject\":\"Geography\",\"score\":\"70.00\",\"grade\":\"B\",\"points\":2},{\"subject\":\"Elimu ya Dini ya Kiislamu\",\"score\":\"49.00\",\"grade\":\"C\",\"points\":3}],\"points\":10,\"division\":\"I\",\"remark\":\"Excelent\"}','2025-11-26 21:46:38',15,NULL,'compiled');
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
INSERT INTO `districts` VALUES (1,'Ilala CBD',2),(2,'Ilala',2),(3,'Kinondoni',2),(4,'Temeke',2),(5,'Ubungo',2),(6,'Kigamboni',2),(7,'Arusha CBD',1),(8,'Arusha',1),(9,'Arumeru',1),(10,'Monduli',1),(11,'Longido',1),(12,'Karatu',1),(13,'Ngorongoro',1),(14,'Moshi CBD',8),(15,'Moshi',8),(16,'Hai',8),(17,'Siha',8),(18,'Mwanga',8),(19,'Same',8),(20,'Rombo',8),(21,'Bukoba CBD',3),(22,'Bukoba',3),(23,'Missenyi',3),(24,'Karagwe',3),(25,'Muleba',3),(26,'Biharamulo',3),(27,'Ngara',3),(28,'Kyerwa',3),(29,'Kibaha CBD',10),(30,'Kibaha',10),(31,'Bagamoyo',10),(32,'Kisarawe',10),(33,'Mkuranga',10),(34,'Rufiji',10),(35,'Mafia',10),(36,'Kibiti',10),(37,'Tanga CBD',9),(38,'Tanga',9),(39,'Pangani',9),(40,'Muheza',9),(41,'Mkinga',9),(42,'Korogwe',9),(43,'Lushoto',9),(44,'Handeni',9),(45,'Kilindi',9),(46,'Nyamagana',4),(47,'Ilemela',4),(48,'Sengerema',4),(49,'Magu',4),(50,'Misungwi',4),(51,'Ukelewe',4),(52,'Kwimba',4);
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (15,'Ashiru','Esmail','Hussein','Male','1996-09-20','Technician','Admin','ashirukattoo@gmail.com','$2y$12$2rJqwtHFNggojushwh9vy..bMLHuZ1vSojP3OxeOEwWHhBgtdfpoa','2025-12-13','2025-11-26 17:11:05','Active'),(16,'Jovin','Joseph','Kagaruki','Male','1999-10-22','Teacher','Academic','jovinkagaruki@gmail.com','$2y$12$v2In1p4/P4SSBWTVDHVY1uGGUeP9ef1B1ntJfkFHwmtRzdhyJM9j2','2025-04-11','2025-11-26 17:11:05','Active');
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
INSERT INTO `exams` VALUES ('EXOL100001','Annual Examination',1,'ACAL001','[{\"id\":\"27\",\"subject\":\"Biology\",\"code\":\"033\",\"class\":\"1\"},{\"id\":\"28\",\"subject\":\"Business Studies\",\"code\":\"065\",\"class\":\"1\"},{\"id\":\"24\",\"subject\":\"Chemistry\",\"code\":\"032\",\"class\":\"1\"},{\"id\":\"33\",\"subject\":\"Computer Science\",\"code\":\"034\",\"class\":\"1\"},{\"id\":\"30\",\"subject\":\"Elimu ya Dini ya Kiislamu\",\"code\":\"015\",\"class\":\"1\"},{\"id\":\"29\",\"subject\":\"English Language\",\"code\":\"022\",\"class\":\"1\"},{\"id\":\"23\",\"subject\":\"Geography\",\"code\":\"013\",\"class\":\"1\"},{\"id\":\"34\",\"subject\":\"Historia ya Tanzania na Maadili\",\"code\":\"060\",\"class\":\"1\"},{\"id\":\"26\",\"subject\":\"Kiswahili\",\"code\":\"021\",\"class\":\"1\"},{\"id\":\"25\",\"subject\":\"Mathematics\",\"code\":\"043\",\"class\":\"1\"},{\"id\":\"32\",\"subject\":\"Physics\",\"code\":\"031\",\"class\":\"1\"}]','2025-11-26 21:43:16','2025-11-27 00:44:39','current');
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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guardians`
--

LOCK TABLES `guardians` WRITE;
/*!40000 ALTER TABLE `guardians` DISABLE KEYS */;
INSERT INTO `guardians` VALUES (1,'Hussein Ismail','Male','1969-04-12','peasant','husseinismail@gmail.com','kilomba1234','2025-10-26 07:13:23','Active'),(8,'Shaidu Ismail','male',NULL,'Peasant','shaidunuru@gmail.com','123410302025','2025-10-30 17:24:12','Active'),(17,'Nassoro Yahya','male',NULL,'Teacher','nassoroyahya@gmail.com','123410312025','2025-10-31 12:46:11','Active');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025-10-14-145630','App\\Database\\Migrations\\CreateAllTables','default','App',1761416556,1),(2,'2025-10-31-132601','App\\Database\\Migrations\\SecondBatch','default','App',1761920893,2),(4,'2025-11-01-132851','App\\Database\\Migrations\\Teaching','default','App',1762005055,3),(5,'2025-11-14-061258','App\\Database\\Migrations\\Setup','default','App',1763102209,4);
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
  KEY `rawResults_subject_id_foreign` (`subject_id`),
  KEY `rawResults_raCreatedBy_foreign` (`raCreatedBy`),
  KEY `rawResults_student_id_foreign_idx` (`student_id`),
  CONSTRAINT `rawResults_raCreatedBy_foreign` FOREIGN KEY (`raCreatedBy`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rawResults_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`stuID`),
  CONSTRAINT `rawResults_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rawresults`
--

LOCK TABLES `rawresults` WRITE;
/*!40000 ALTER TABLE `rawresults` DISABLE KEYS */;
INSERT INTO `rawresults` VALUES (1,'EXOL100001',49,27,87.00,'A','2025-11-26 21:43:45',15,'2025-11-26 21:46:39','compiled'),(2,'EXOL100001',49,28,91.00,'A','2025-11-26 21:43:46',15,'2025-11-26 21:46:39','compiled'),(3,'EXOL100001',49,24,82.00,'A','2025-11-26 21:43:46',15,'2025-11-26 21:46:39','compiled'),(4,'EXOL100001',49,33,45.00,'C','2025-11-26 21:43:46',15,'2025-11-26 21:46:39','compiled'),(5,'EXOL100001',49,30,88.00,'A','2025-11-26 21:43:46',15,'2025-11-26 21:46:39','compiled'),(6,'EXOL100001',49,29,79.00,'A','2025-11-26 21:43:47',15,'2025-11-26 21:46:39','compiled'),(7,'EXOL100001',49,23,67.00,'B','2025-11-26 21:43:47',15,'2025-11-26 21:46:39','compiled'),(8,'EXOL100001',49,34,32.00,'D','2025-11-26 21:43:48',15,'2025-11-26 21:46:39','compiled'),(9,'EXOL100001',49,26,58.00,'C','2025-11-26 21:43:48',15,'2025-11-26 21:46:39','compiled'),(10,'EXOL100001',49,25,70.00,'B','2025-11-26 21:43:48',15,'2025-11-26 21:46:39','compiled'),(11,'EXOL100001',49,32,49.00,'C','2025-11-26 21:43:48',15,'2025-11-26 21:46:39','compiled'),(12,'EXOL100001',50,27,87.00,'A','2025-11-26 21:43:48',15,'2025-11-26 21:46:39','compiled'),(13,'EXOL100001',50,28,51.00,'C','2025-11-26 21:43:49',15,'2025-11-26 21:46:39','compiled'),(14,'EXOL100001',50,24,22.00,'F','2025-11-26 21:43:49',15,'2025-11-26 21:46:39','compiled'),(15,'EXOL100001',50,33,62.00,'C','2025-11-26 21:43:49',15,'2025-11-26 21:46:39','compiled'),(16,'EXOL100001',50,30,45.00,'C','2025-11-26 21:43:49',15,'2025-11-26 21:46:39','compiled'),(17,'EXOL100001',50,29,31.00,'D','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(18,'EXOL100001',50,23,80.00,'A','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(19,'EXOL100001',50,34,83.00,'A','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(20,'EXOL100001',50,26,77.00,'A','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(21,'EXOL100001',50,25,29.00,'F','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(22,'EXOL100001',50,32,24.00,'F','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(23,'EXOL100001',51,27,51.00,'C','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(24,'EXOL100001',51,28,83.00,'A','2025-11-26 21:43:50',15,'2025-11-26 21:46:39','compiled'),(25,'EXOL100001',51,24,75.00,'A','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(26,'EXOL100001',51,33,81.00,'A','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(27,'EXOL100001',51,30,79.00,'A','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(28,'EXOL100001',51,29,95.00,'A','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(29,'EXOL100001',51,23,32.00,'D','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(30,'EXOL100001',51,34,40.00,'D','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(31,'EXOL100001',51,26,92.00,'A','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(32,'EXOL100001',51,25,86.00,'A','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(33,'EXOL100001',51,32,42.00,'D','2025-11-26 21:43:51',15,'2025-11-26 21:46:39','compiled'),(34,'EXOL100001',52,27,100.00,'A','2025-11-26 21:43:52',15,'2025-11-26 21:46:39','compiled'),(35,'EXOL100001',52,28,83.00,'A','2025-11-26 21:43:52',15,'2025-11-26 21:46:39','compiled'),(36,'EXOL100001',52,24,93.00,'A','2025-11-26 21:43:52',15,'2025-11-26 21:46:39','compiled'),(37,'EXOL100001',52,33,37.00,'D','2025-11-26 21:43:52',15,'2025-11-26 21:46:39','compiled'),(38,'EXOL100001',52,30,49.00,'C','2025-11-26 21:43:52',15,'2025-11-26 21:46:39','compiled'),(39,'EXOL100001',52,29,37.00,'D','2025-11-26 21:43:52',15,'2025-11-26 21:46:39','compiled'),(40,'EXOL100001',52,23,70.00,'B','2025-11-26 21:43:53',15,'2025-11-26 21:46:39','compiled'),(41,'EXOL100001',52,34,90.00,'A','2025-11-26 21:43:53',15,'2025-11-26 21:46:39','compiled'),(42,'EXOL100001',52,26,92.00,'A','2025-11-26 21:43:53',15,'2025-11-26 21:46:39','compiled'),(43,'EXOL100001',52,25,30.00,'D','2025-11-26 21:43:53',15,'2025-11-26 21:46:39','compiled'),(44,'EXOL100001',52,32,22.00,'F','2025-11-26 21:43:53',15,'2025-11-26 21:46:39','compiled');
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Arusha','North Zone','Mainland'),(2,'Dar es Salaam','Eastern Zone','Mainland'),(3,'Kagera','Lake zone','mainland'),(4,'Mwanza','lake zone','mainland'),(5,'Shinyanga','lake zone','mainland'),(6,'Kigoma','Western','mainland'),(7,'Dodoma','Central Zone','Mainland'),(8,'Klimanjaro','Northern Zone','mainland'),(9,'Tanga','Eastern Zone','mainland'),(10,'Pwani','Eastern Zone','Mainland'),(11,'Manyara','Northern Zone','Mainland'),(12,'Singida','Central Zone','Mainland'),(13,'Morogoro','Eastern Zone','Mainland'),(14,'Iringa','Soutthern Highland Zone','Mainland'),(15,'Mbeya','Southern Highland Zone','Mainland'),(16,'Njombe','Southern Highland','MAinland'),(17,'Songwe','Southern Highland','Mainland'),(18,'Ruvuma','Southern Zone','Mainland'),(19,'Katavi','Western Zone','Mainland'),(20,'Tabora','Western Zone','Mainland'),(21,'Mtwara','Southen ZOne','Mainland'),(22,'Lindi','Southern Zone','Mainland'),(23,'Simuyu','Lake Zone','Mainland'),(24,'Mara','Lake Zone','Mainland');
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
  `rsStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`rsID`),
  KEY `route_stations_route_id_foreign` (`route_id`),
  KEY `route_stations_station_id_foreign` (`station_id`),
  CONSTRAINT `route_stations_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`rouID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `route_stations_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`staID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `route_stations`
--

LOCK TABLES `route_stations` WRITE;
/*!40000 ALTER TABLE `route_stations` DISABLE KEYS */;
INSERT INTO `route_stations` VALUES (1,2,3,'2025-11-21 19:14:08','Active'),(2,3,4,'2025-11-21 19:38:14','Active'),(3,3,4,'2025-11-21 19:38:26','Active'),(4,2,6,'2025-11-23 16:21:46','Active'),(5,2,7,'2025-11-23 16:22:15','Active');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (2,'Same to Dar_es_Salaam','Klimanjaro','Dar es Salaam','2025-11-19 18:33:21',NULL,'active'),(3,'Same to Mwanza','Klimanjaro','Mwanza','2025-11-21 22:37:55',NULL,'active');
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `school_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Lyamahoro',
  `school_logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ministry` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shool_adress` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `school_street` int DEFAULT NULL,
  `school_email` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `school_phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `school_status` enum('Active','Inactive','Pending') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Lyamahoro Secondary School','images/lss_logo.png','President\'s Office, Regional Authorities and Local Governments','P.O.Box 747',NULL,'lyamahorosec@gmail.com','0758192801','Active');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
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
  `street_id` int DEFAULT NULL,
  `staCreated` datetime DEFAULT NULL,
  `staUpdated` datetime DEFAULT NULL,
  `staStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`staID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations`
--

LOCK TABLES `stations` WRITE;
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
INSERT INTO `stations` VALUES (1,'Katoro',NULL,NULL,NULL,9,'2025-11-19 17:38:06',NULL,'active'),(2,'Kyetema',NULL,NULL,NULL,8,'2025-11-20 06:46:41',NULL,'active'),(3,'Mbezi',NULL,NULL,NULL,13,'2025-11-21 16:25:31',NULL,'active'),(4,'Nyegezi',NULL,NULL,NULL,17,'2025-11-21 16:57:43',NULL,'active'),(5,'Nyegezi Kona',NULL,NULL,NULL,16,'2025-11-22 18:34:19',NULL,'active'),(6,'Dunda Bagamoyo',NULL,NULL,NULL,19,'2025-11-22 19:18:35',NULL,'active'),(7,'Magomeni Bagamoyo',NULL,NULL,NULL,39,'2025-11-22 19:18:36',NULL,'active'),(8,'l;kl\'',NULL,NULL,NULL,0,'2025-11-26 15:15:03',NULL,'active');
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `streets`
--

LOCK TABLES `streets` WRITE;
/*!40000 ALTER TABLE `streets` DISABLE KEYS */;
INSERT INTO `streets` VALUES (5,'Kazinga',1),(6,'Kaibanja',1),(7,'Nyakigando',1),(8,'Kiijongo',1),(9,'Ngarama',2),(10,'Jogoo',25),(11,'Ndumbwi',25),(12,'Mbezi Mtoni',25),(13,'Mbezi Kati',25),(14,'Mbezi Juu',25),(15,'Swila',29),(16,'Nkamba',29),(17,'Califonia',29),(18,'Kuzenza',29),(19,'Dunda',34),(20,'Mwambao',34),(21,'Tandika',34),(22,'Ramiya',34),(23,'Mwanakalenge',34),(24,'Majani Mapana',34),(25,'Benki',34),(26,'Soko Jipya',34),(27,'Mangesani',34),(28,'Shaurimoyo',34),(29,'Kaole Ufundi',34),(30,'Miti mingi',34),(31,'Bondeni',34),(32,'Madukani',34),(33,'Ukuni',34),(34,'Mto wa Nyanza',34),(35,'Maji Coast',35),(36,'Bong\'wa',35),(37,'Magomeni \"C\"',35),(38,'Sanzale',35),(39,'Mji Mpya',35),(40,'Kiromo \"A\"',36),(41,'Kiromo \"B\"',36),(42,'Mandawe',36),(43,'Chaga \"A\"',36),(44,'Chaga \"B\"',36),(45,'Mbuyuni',36),(46,'Migudeni',36),(47,'Kitonga A',36),(48,'Kitonga B',36);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (49,'Mastura','Muzamiru','Bashungwa','Female','2010-10-25',1,1,'active'),(50,'Mastura','Hamis','Hashim','Female','2009-10-25',1,17,'active'),(51,'Asha','Ashiru','Azihry','Female','2010-10-11',1,1,'active'),(52,'Twasin','Jofly','Kassim','Male','2010-02-25',1,17,'active');
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
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_streams`
--

LOCK TABLES `subject_streams` WRITE;
/*!40000 ALTER TABLE `subject_streams` DISABLE KEYS */;
INSERT INTO `subject_streams` VALUES (100,1,25,1,NULL,NULL,'2025-11-26 21:08:14',NULL),(101,1,23,1,NULL,NULL,'2025-11-26 21:08:21',NULL),(102,1,24,0,NULL,NULL,'2025-11-26 21:08:28',NULL),(103,1,26,1,NULL,NULL,'2025-11-26 21:08:40',NULL),(104,1,27,0,NULL,NULL,'2025-11-26 21:08:48',NULL),(105,1,28,1,NULL,NULL,'2025-11-26 21:08:55',NULL),(106,1,29,1,NULL,NULL,'2025-11-26 21:09:24',NULL),(107,1,30,0,NULL,NULL,'2025-11-26 21:09:32',NULL),(108,1,32,0,NULL,NULL,'2025-11-26 21:09:46',NULL),(109,1,33,0,NULL,NULL,'2025-11-26 21:09:53',NULL),(110,1,34,1,NULL,NULL,'2025-11-26 21:10:01',NULL);
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
  `subShort` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subCategory` enum('Core','Option') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Core',
  `subLevel` enum('O-level','A-Level','Both') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Both',
  `subCurriculum` enum('Old','New','Both') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Both',
  `subCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `subUpdated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`subID`),
  UNIQUE KEY `subCode_UNIQUE` (`subCode`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (22,'Basic Mathematics','041','B/Math','Core','O-level','Old','2025-11-26 17:32:30',NULL),(23,'Geography','013','Geog','Core','O-level','Both','2025-11-26 17:34:37',NULL),(24,'Chemistry','032','Chem','Core','O-level','Both','2025-11-26 18:06:26','2025-11-26 18:06:26'),(25,'Mathematics','043','Math','Core','O-level','New','2025-11-26 18:06:26','2025-11-26 18:06:26'),(26,'Kiswahili','021','Kisw','Core','O-level','Both','2025-11-26 18:06:26','2025-11-26 18:06:26'),(27,'Biology','033','BS','Option','O-level','Both','2025-11-26 21:06:10','2025-11-26 21:06:10'),(28,'Business Studies','065','Buss','Core','O-level','New','2025-11-26 21:06:10','2025-11-26 21:06:10'),(29,'English Language','022','Lang','Core','O-level','Both','2025-11-26 21:06:10','2025-11-26 21:06:10'),(30,'Elimu ya Dini ya Kiislamu','015','EDK','Option','O-level','Both','2025-11-26 21:06:10','2025-11-26 21:06:10'),(31,'History','012','Hist','Option','O-level','Both','2025-11-26 21:06:10','2025-11-26 21:06:10'),(32,'Physics','031','Phys','Option','O-level','Both','2025-11-26 21:06:10','2025-11-26 21:06:10'),(33,'Computer Science','034','CS','Option','O-level','New','2025-11-26 21:06:10','2025-11-26 21:06:10'),(34,'Historia ya Tanzania na Maadili','060','HTM','Core','O-level','New','2025-11-26 21:06:10','2025-11-26 21:06:10');
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
  `trStatus` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Coming',
  PRIMARY KEY (`trID`),
  KEY `tour_routes_tour_id_foreign` (`tour_id`),
  KEY `tour_routes_route_id_foreign` (`route_id`),
  KEY `tour_routes_employee_id_foreign` (`employee_id`),
  KEY `tour_routes_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `tour_routes_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_routes_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`rouID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_routes_tour_id_foreign` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`touID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tour_routes_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`veID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (1,'December Holiday','Departure','2025-12-10 00:00:00','2025-12-17 00:00:00','Active'),(2,'Jun 2025 Holiday','Departure','2025-06-07 00:00:00','2025-06-11 00:00:00','Active'),(3,'Jun 2025 Holiday','Arrival','2025-07-04 00:00:00','2025-07-07 00:00:00','Active');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
INSERT INTO `vehicles` VALUES (1,'EDR1258','SCANIA 2345','Rent','Bus 1'),(2,'EDA4258','SCANIA 2345','Self','Bus 2'),(3,'EDR1458','SCANIA y45','Rent','Bus 3');
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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wards`
--

LOCK TABLES `wards` WRITE;
/*!40000 ALTER TABLE `wards` DISABLE KEYS */;
INSERT INTO `wards` VALUES (1,'Kaibanja',22),(2,'Katoro',22),(3,'Kasharu',22),(4,'Nyakibimbili',22),(5,'Butelankuzi',22),(6,'Ibwera',22),(7,'Maruku',22),(8,'Kemondo',22),(9,'Magomeni',3),(10,'Mzimuni',3),(11,'Ndugumbi',3),(12,'Tandale',3),(13,'Makumbusho',3),(14,'Mwananyamala',3),(15,'Hananasif',3),(16,'Kinondoni',3),(17,'Msasani',3),(18,'Mikocheni',3),(19,'Kijitonyama',3),(20,'Kigogo',3),(21,'Kawe',3),(22,'Kunduchi',3),(23,'Bunju',3),(24,'Mbweni',3),(25,'Mbezi Juu',3),(26,'Makongo',3),(27,'Wazo',3),(28,'Mabwepande',3),(29,'Nyegezi',46),(30,'Butimba',46),(31,'Mkuyuni',46),(32,'Mirongo',46),(33,'Buhongwa',46),(34,'Dunda',31),(35,'Magomeni',31),(36,'Kiromo',31),(37,'Zinga',31),(38,'Kerege',31),(39,'Yombo',31),(40,'Fukayosi',31),(41,'Vigwaza',31),(42,'Kiwangwa',31),(43,'Msata',31),(44,'Lugoba',31),(45,'Msoga',31),(46,'Talawanda',31),(47,'Pera',31),(48,'Bwilingu',31),(49,'Ubenazomozi',31),(50,'Mandera',31),(51,'Kimange',31),(52,'Mwewe',31),(53,'Kibindu',31),(54,'Miono',31),(55,'Mkange',31),(56,'Mapinga',31),(57,'Nia Njema',31),(58,'Kisutu',31),(59,'Makurunge',31);
/*!40000 ALTER TABLE `wards` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-27  6:03:37
