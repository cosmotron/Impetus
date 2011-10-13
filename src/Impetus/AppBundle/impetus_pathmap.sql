-- MySQL dump 10.13  Distrib 5.5.15, for osx10.6 (i386)
--
-- Host: localhost    Database: impetus
-- ------------------------------------------------------
-- Server version	5.5.15

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
-- Table structure for table `path_node`
--

DROP TABLE IF EXISTS `path_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `path_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `h_pos` double NOT NULL,
  `v_pos` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `path_node`
--

LOCK TABLES `path_node` WRITE;
/*!40000 ALTER TABLE `path_node` DISABLE KEYS */;
INSERT INTO `path_node` VALUES (1,'Exponents 1',0,0),(2,'Exponents 2',0,-0.5),(3,'Estimation 1',1,0),(4,'Exponents 3',0,-1),(5,'Exponents 4',0,-1.5),(6,'Estimation 2',1,-0.5),(7,'Estimation 3',1,-1),(8,'Monomials 1',1,-1.5),(9,'Monomials 2',1,-2),(10,'Polynomials 1',1,-2.5),(11,'Polynomials 2',1,-3),(12,'Polynomials 3',1,-3.5),(13,'Polynomials 4',1,-4),(14,'Polynomials 5',0,-4.5),(15,'Quadratics 1',0,-5),(16,'Polynomials 6',-1,-5),(17,'Quadratics 2',0,-5.5),(18,'Quadratics 3',0,-6),(19,'Quadratics 4',0,-6.5),(20,'Quadratics 5',0,-7),(21,'Trinomials 1',2,-4.5),(22,'Trinomials 2',2,-5),(23,'Trinomials 3',2,-5.5),(24,'Angles 1',2,0),(25,'Angles 2',2,-0.5),(26,'Angles 3',2,-1),(27,'Angles 4',2,-1.5),(28,'Parallel Lines',2,-2),(29,'Inequalities 1',3,-0.5),(30,'Inequalities 2',3,-1),(31,'Inequalities 3',3,-1.5),(32,'Inequalities 4',3,-2),(33,'Inequalities 5',4,-2.5),(34,'Inequalities 6',4,-3),(35,'Inequalities 7',4,-3.5),(36,'Graphing 1',5,0),(37,'Graphing 2',5,-0.5),(38,'Graphing 3',5,-1),(39,'Graphing 4',5,-1.5),(40,'Graphing 5',5,-2),(41,'Ordered Pairs',6,0),(42,'Domain & Range 1',6,-0.5),(43,'Domain & Range 2',6,-1),(44,'Domain & Range 3',6,-1.5),(45,'Functions',6,-2),(46,'Relationships 1',6,-2.5),(47,'Relationships 2',6,-3),(48,'Fractions 1',-1,0),(49,'Fractions 2',-1,-0.5),(50,'Unit Conversion 1',-1,-1),(51,'Unit Conversion 2',-1,-1.5),(52,'Unit Conversion 3',-1,-2),(53,'Induction',-2,0),(54,'Decimals',-2,-0.5),(55,'Representations',-2,-1),(56,'Percents 1',-2,-1.5),(57,'Percents 2',-2,-2),(58,'Percents 3',-2,-2.5),(59,'Proportions 1',-3,0),(60,'Proportions 2',-3,-0.5),(61,'Proportions 3',-3,-1),(62,'Patterns 1',-4,0),(63,'Patterns 2',-4,-0.5),(64,'Patterns 3',-4,-1),(65,'Symbols',-5,0);
/*!40000 ALTER TABLE `path_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `path_node_prereqs`
--

DROP TABLE IF EXISTS `path_node_prereqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `path_node_prereqs` (
  `node_id` int(11) NOT NULL,
  `prereq_node_id` int(11) NOT NULL,
  PRIMARY KEY (`node_id`,`prereq_node_id`),
  KEY `IDX_ECCF3E1E460D9FD7` (`node_id`),
  KEY `IDX_ECCF3E1E70B24A06` (`prereq_node_id`),
  CONSTRAINT `FK_ECCF3E1E70B24A06` FOREIGN KEY (`prereq_node_id`) REFERENCES `path_node` (`id`),
  CONSTRAINT `FK_ECCF3E1E460D9FD7` FOREIGN KEY (`node_id`) REFERENCES `path_node` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `path_node_prereqs`
--

LOCK TABLES `path_node_prereqs` WRITE;
/*!40000 ALTER TABLE `path_node_prereqs` DISABLE KEYS */;
INSERT INTO `path_node_prereqs` VALUES (2,1),(4,2),(5,4),(6,3),(7,6),(8,4),(9,8),(10,9),(11,10),(12,11),(13,12),(14,13),(15,14),(16,14),(17,15),(18,17),(19,18),(20,19),(21,13),(22,21),(23,22),(25,24),(26,25),(27,26),(28,27),(30,29),(31,30),(32,31),(33,32),(33,40),(34,33),(35,34),(37,36),(38,37),(39,38),(40,39),(42,41),(43,42),(44,43),(45,44),(46,45),(47,46),(49,48),(50,49),(50,54),(51,50),(52,51),(55,49),(55,54),(56,55),(57,56),(58,57),(60,59),(61,60),(63,62),(64,63);
/*!40000 ALTER TABLE `path_node_prereqs` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-09-28  3:43:31
