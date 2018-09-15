-- MySQL dump 10.13  Distrib 5.6.39, for Linux (x86_64)
--
-- Host: localhost    Database: pump_master
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$12$SxVbULKczn.SHeY7r.LfO.hzbX/OADc0PCu/uOyJxpDRx0HMi10l2',1,NULL,'admin'),(2,'vijay','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(3,'dharmendra','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(4,'omprakash','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(5,'mhaske','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(6,'maherban','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(7,'ravi','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(8,'vasant','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(9,'sanjay','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(10,'santosh','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(11,'gopi','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(12,'superadmin','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'admin'),(14,'Imran','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(15,'DHANRAJ','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(16,'manager','$2y$12$rFB9FuiEMwmuBvcjd5o.aubS/4FwJ/A5hXZ/WptPTAMQU0xgPZ11S',1,NULL,'manager'),(17,'NIKITA','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(18,'ANIKET','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(19,'CHIMAN','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(20,'NIKHIL','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(21,'accounts','$2y$12$i6x2JYoT6CebVBBpgoIp0OBMaIKhliq4q/QoKwfbjTq4bFogNKdyS',1,NULL,'office'),(22,'abhishek','$2y$12$bU.T1unXE7UqBled5qCSOu0QALdLxwFf5Qc1GcZKpQ.uahtd9pB16',1,NULL,'operator'),(23,'Pramod','12345',1,NULL,'operator'),(24,'GANESH','12345',1,NULL,'operator'),(25,'ASHWINI','12345',1,NULL,'operator');
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

-- Dump completed on 2018-09-13 21:55:49
