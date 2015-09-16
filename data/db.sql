-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: opencart
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

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
-- Table structure for table `opds_authors`
--

DROP TABLE IF EXISTS `opds_authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_authors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `uri` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_authors`
--

LOCK TABLES `opds_authors` WRITE;
/*!40000 ALTER TABLE `opds_authors` DISABLE KEYS */;
INSERT INTO `opds_authors` VALUES (0,'john','/opencart/upload/author','gjhgjhgjh'),(1,'smith','/opencar/upload/author','dfdfdfdfd'),(2,'charles','/opencar/upload/author','ddgdgdgdgdgd');
/*!40000 ALTER TABLE `opds_authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_books`
--

DROP TABLE IF EXISTS `opds_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `isbn` varchar(200) NOT NULL,
  `publish_ts` date NOT NULL,
  `update_ts` date NOT NULL,
  `language` varchar(200) NOT NULL,
  `publisher` varchar(200) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `issue_ts` date NOT NULL,
  `summary` varchar(200) NOT NULL,
  `no_of_pages` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `alternate_link` varchar(200) NOT NULL,
  `buy_link` varchar(200) NOT NULL,
  `main_image` varchar(200) NOT NULL,
  `thumb_image` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_books`
--

LOCK TABLES `opds_books` WRITE;
/*!40000 ALTER TABLE `opds_books` DISABLE KEYS */;
INSERT INTO `opds_books` VALUES (1,'don quixote','12345','2015-09-08','2015-09-08','en','abc pub',1,'0000-00-00','adventure story of don quixote',300,3000000,'/opencar/upload/author','/opencar/upload/author','/opencar/upload/author','/opencar/upload/author'),(2,'crugo','45678','2015-09-08','2015-09-08','bn','onnoprokash',2,'0000-00-00','fgfhfgdgdfe',100,1000000,'/opencar/upload/author','/opencar/upload/author','/opencar/upload/author','/opencar/upload/author'),(3,'project nebula','4567833','2015-09-08','2015-09-08','bn','onnoprokash',1,'0000-00-00','llllokjko',100,1000000,'/opencar/upload/author','/opencar/upload/author','/opencar/upload/author','/opencar/upload/author');
/*!40000 ALTER TABLE `opds_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_book_to_author`
--

DROP TABLE IF EXISTS `opds_book_to_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_book_to_author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_book_to_author`
--

LOCK TABLES `opds_book_to_author` WRITE;
/*!40000 ALTER TABLE `opds_book_to_author` DISABLE KEYS */;
INSERT INTO `opds_book_to_author` VALUES (1,1,1),(2,1,2),(3,2,3),(4,3,2);
/*!40000 ALTER TABLE `opds_book_to_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_catagory`
--

DROP TABLE IF EXISTS `opds_catagory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_catagory` (
  `id` int(11) NOT NULL,
  `catalog_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_catagory`
--

LOCK TABLES `opds_catagory` WRITE;
/*!40000 ALTER TABLE `opds_catagory` DISABLE KEYS */;
INSERT INTO `opds_catagory` VALUES (1,2,'Aaaa','/books/writer/2'),(2,2,'Bbbb','/books/writer/1');
/*!40000 ALTER TABLE `opds_catagory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_catalogs`
--

DROP TABLE IF EXISTS `opds_catalogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_catalogs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `link` varchar(1000) NOT NULL,
  `description` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_catalogs`
--

LOCK TABLES `opds_catalogs` WRITE;
/*!40000 ALTER TABLE `opds_catalogs` DISABLE KEYS */;
INSERT INTO `opds_catalogs` VALUES (1,'all','All Books','/test/api/catalogs/catagory/all','All'),(2,'writer','By Writer','/test/api/catalogs/catagory/writer','Writer'),(3,'publisher','By Publisher','/test/api/catalogs/catagory/publisher','Publisher'),(4,'bestseller','Best Seller','/test/api/catalogs/catagory/bestseller','Best Seller'),(5,'featured','Featured','/test/api/catalogs/catagory/featured','Featured'),(6,'latest','Latest','/test/api/catalogs/catagory/latest','Latest');
/*!40000 ALTER TABLE `opds_catalogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_publishers`
--

DROP TABLE IF EXISTS `opds_publishers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_publishers`
--

LOCK TABLES `opds_publishers` WRITE;
/*!40000 ALTER TABLE `opds_publishers` DISABLE KEYS */;
INSERT INTO `opds_publishers` VALUES (1,'Oreilly Publisher','Oreilly Publisher'),(2,'Wrox Publisher','Wrox Publisher');
/*!40000 ALTER TABLE `opds_publishers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_users`
--

DROP TABLE IF EXISTS `opds_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL DEFAULT 'adfkhgfjuytiuy8762872674kbj768',
  `password` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_users`
--

LOCK TABLES `opds_users` WRITE;
/*!40000 ALTER TABLE `opds_users` DISABLE KEYS */;
INSERT INTO `opds_users` VALUES (1,'user1','adfkhgfjuytiuy8762872674kbj768',''),(2,'user2','adfkhgfjuytiuy8762872674kbj768',''),(1441902293,'user3','2sc53#6g@7hcd','2s.gFhk3xGxoA');
/*!40000 ALTER TABLE `opds_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-16 20:12:46
