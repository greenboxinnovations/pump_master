-- MySQL dump 10.13  Distrib 5.6.39, for Linux (x86_64)
--
-- Host: localhost    Database: pump_master_test
-- ------------------------------------------------------
-- Server version	5.6.39-cll-lve

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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `pass` text NOT NULL,
  `user_pump_id` int(11) NOT NULL,
  `imei` text,
  `role` varchar(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$12$BU0Y5a331F2kK4Ji1.4aGudgD67ItgOfWitjhmZNM2oOj/Du0tsuS',1,NULL,'admin'),(2,'vijay','123456',1,NULL,'operator'),(3,'dharmendra','123456',1,NULL,'operator'),(4,'omprakash','123456',1,NULL,'operator'),(5,'mhaske','123456',1,NULL,'operator'),(6,'maherban','123456',1,NULL,'operator'),(7,'ravi','123456',1,NULL,'operator'),(8,'vasant','123456',1,NULL,'operator'),(9,'sanjay','123456',1,NULL,'operator'),(10,'santosh','123456',1,NULL,'operator'),(11,'gopi','123456',1,NULL,'operator'),(12,'superadmin','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'admin'),(25,'manager','$2y$12$qjbTZ5Jis3b5shP5WU.zK.I3d8edks5Uq6CJke5GsTgiaLtLVFoIa',1,NULL,'manager'),(28,'akshay','12345',1,NULL,'operator'),(29,'sou','12345',1,NULL,'operator'),(30,'fff','12345',1,NULL,'operator'),(31,'tum','12345',1,NULL,'operator'),(32,'yyy','12345',1,NULL,'operator'),(33,'f','12345',1,NULL,'operator'),(34,'wwwww','12345',1,NULL,'operator'),(35,'sss','12345',1,NULL,'operator'),(36,'aaaaaa','12345',1,NULL,'operator'),(37,'fvfdvfd','12345',1,NULL,'operator'),(38,'dsaads','12345',1,NULL,'operator'),(39,'dcdd','12345',1,NULL,'operator'),(40,'cxx','12345',1,NULL,'operator'),(41,'dccdcd','12345',1,NULL,'operator'),(42,'vfdvdfvfdvfdvfd','12345',1,NULL,'operator'),(43,'jmjhhjh','12345',1,NULL,'operator'),(44,'vvvvvvvvvvvvvvvv','12345',1,NULL,'operator');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-27  6:46:15
