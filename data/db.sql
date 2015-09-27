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
  `file_id` int(11) NOT NULL,
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
INSERT INTO `opds_books` VALUES (1,'don quixote','12345','2015-09-08','2015-09-08','en','abc pub',1,'0000-00-00','adventure story of don quixote',300,3000000,1,'/opencar/upload/author','/opencar/upload/author','/opencar/upload/author','/opencar/upload/author'),(2,'crugo','45678','2015-09-08','2015-09-08','bn','onnoprokash',2,'0000-00-00','fgfhfgdgdfe',100,1000000,2,'/opencar/upload/author','/opencar/upload/author','/opencar/upload/author','/opencar/upload/author'),(3,'project nebula','4567833','2015-09-08','2015-09-08','bn','onnoprokash',1,'0000-00-00','llllokjko',100,1000000,3,'/opencar/upload/author','/opencar/upload/author','/opencar/upload/author','/opencar/upload/author');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_book_to_author`
--

LOCK TABLES `opds_book_to_author` WRITE;
/*!40000 ALTER TABLE `opds_book_to_author` DISABLE KEYS */;
INSERT INTO `opds_book_to_author` VALUES (1,1,1),(2,1,2),(3,2,3),(4,3,2),(5,50,3),(6,50,1),(7,53,2),(8,53,2),(9,52,3),(10,57,1),(11,54,3),(12,55,2),(13,56,3);
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
INSERT INTO `opds_catalogs` VALUES (1,'all','All Books','/test/api/catalogs/all','All'),(2,'writer','By Writer','/test/api/catalogs/bywriter','Writer'),(3,'publisher','By Publisher','/test/api/catalogs/bycategory','By Category'),(4,'bestseller','Best Seller','/test/api/catalogs/catagory/bestseller','Best Seller'),(5,'featured','Featured','/test/api/catalogs/catagory/featured','Featured'),(6,'latest','Latest','/test/api/catalogs/catagory/latest','Latest');
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
  `salt` varchar(100) DEFAULT 'adfkhgfjuytiuy8762872674kbj768',
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
INSERT INTO `opds_users` VALUES (0,'bbbbbbbbb@bbb.com','d1c86ff1c','a81fb8a000f1e808bddd1d0804bc48289fc49578'),(1,'user1','adfkhgfjuytiuy8762872674kbj768',''),(2,'user2','adfkhgfjuytiuy8762872674kbj768',''),(9,'asdf@gmail.com','adfkhgfjuytiuy8762872674kbj768','1234'),(1441902293,'user3','2sc53#6g@7hcd','2s.gFhk3xGxoA');
/*!40000 ALTER TABLE `opds_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_storages`
--

DROP TABLE IF EXISTS `opds_storages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_storages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mountpoint` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_storages`
--

LOCK TABLES `opds_storages` WRITE;
/*!40000 ALTER TABLE `opds_storages` DISABLE KEYS */;
INSERT INTO `opds_storages` VALUES (1,'storages/epub0/','e'),(2,'storages/image0/','i');
/*!40000 ALTER TABLE `opds_storages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_files`
--

DROP TABLE IF EXISTS `opds_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `storage_id` int(11) NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_files`
--

LOCK TABLES `opds_files` WRITE;
/*!40000 ALTER TABLE `opds_files` DISABLE KEYS */;
INSERT INTO `opds_files` VALUES (1,'e','ffff.epub',1,''),(2,'e','dddd.epub',1,'');
/*!40000 ALTER TABLE `opds_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_user_to_file`
--

DROP TABLE IF EXISTS `opds_user_to_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_user_to_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_user_to_file`
--

LOCK TABLES `opds_user_to_file` WRITE;
/*!40000 ALTER TABLE `opds_user_to_file` DISABLE KEYS */;
INSERT INTO `opds_user_to_file` VALUES (1,1,1),(2,1,2);
/*!40000 ALTER TABLE `opds_user_to_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_product_to_book_files`
--

DROP TABLE IF EXISTS `opds_product_to_book_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_product_to_book_files` (
  `product_id` int(11) NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_product_to_book_files`
--

LOCK TABLES `opds_product_to_book_files` WRITE;
/*!40000 ALTER TABLE `opds_product_to_book_files` DISABLE KEYS */;
INSERT INTO `opds_product_to_book_files` VALUES (50,1,NULL),(58,2,NULL);
/*!40000 ALTER TABLE `opds_product_to_book_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_logs`
--

DROP TABLE IF EXISTS `opds_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_logs`
--

LOCK TABLES `opds_logs` WRITE;
/*!40000 ALTER TABLE `opds_logs` DISABLE KEYS */;
INSERT INTO `opds_logs` VALUES (87,'api/books/file/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159481,0.046814,'1',0),(88,'api/books/file/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159573,10.3285,'1',0),(89,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443159660,0.088917,'1',200),(90,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443159666,0.102417,'1',200),(91,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159670,0.09694,'1',200),(92,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159675,0.110642,'1',200),(93,'api/books/file/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159713,0.239365,'1',0),(94,'api/books/file/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159763,0.272583,'1',0),(95,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443159775,0.0493321,'1',0),(96,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443159971,0.0906999,'1',200),(97,'api/catalogs/bycategory','get',NULL,'','192.168.43.1',1443159976,0.0894399,'1',200),(98,'api/books/category/59','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443159995,0.0799661,'1',200),(99,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443167662,0.0744071,'1',200),(100,'api/catalogs/bycategory','get',NULL,'','192.168.43.1',1443167665,0.070174,'1',200),(101,'api/books/category/17','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443167666,0.070472,'1',200),(102,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443167667,0.112191,'1',200),(103,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443167673,0.257428,'1',0),(104,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443167732,0.0559299,'1',0),(105,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443168396,0.0477271,'1',0),(106,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443174976,0.047332,'1',0),(107,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443175581,0.0956981,'1',200),(108,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443175582,0.098875,'1',200),(109,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175583,0.097688,'1',200),(110,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175585,0.0966151,'1',200),(111,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175593,0.221971,'1',0),(112,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175645,0.198126,'1',0),(113,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175649,0.216461,'1',0),(114,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175657,0.164215,'1',0),(115,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175712,0.214303,'1',0),(116,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175723,0.198748,'1',0),(117,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443175848,0.324479,'1',0),(118,'api/books/buy/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443176195,0.0461781,'1',0),(119,'api/books/buy/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176320,0.046134,'1',0),(120,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176525,0.070596,'1',200),(121,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176536,0.772448,'1',0),(122,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176586,0.633751,'1',0),(123,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443176632,0.0625582,'1',0),(124,'api/catalogs/bycategory','get',NULL,'','192.168.43.1',1443176884,0.0861659,'1',200),(125,'api/books/category/17','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176885,0.104066,'1',200),(126,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176887,0.0854621,'1',200),(127,'api/books/file/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443176899,0.139023,'1',0),(128,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443177143,0.068867,'1',200),(129,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443177145,0.0806711,'1',200),(130,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443177146,0.0998518,'1',200),(131,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443177148,0.096734,'1',200),(132,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443177380,0.0740631,'1',200),(133,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443177380,0.0742731,'1',200),(134,'api/books/playground','get',NULL,'','127.0.0.1',1443179290,0.0527799,'1',0),(135,'api/books/playground','get',NULL,'','127.0.0.1',1443179316,0.0370612,'1',0),(136,'api/books/playground','get',NULL,'','127.0.0.1',1443179348,0.040092,'1',0),(137,'api/books/playground','get',NULL,'','127.0.0.1',1443179368,0.051429,'1',0),(138,'api/books/playground','get',NULL,'','127.0.0.1',1443179393,0.072866,'1',0),(139,'api/books/playground','get',NULL,'','127.0.0.1',1443179413,0.0433722,'1',0),(140,'api/books/playground','get',NULL,'','127.0.0.1',1443179448,0.0506041,'1',0),(141,'api/books/playground','get',NULL,'','127.0.0.1',1443179530,0.0383959,'1',0),(142,'api/books/playground','get',NULL,'','127.0.0.1',1443179561,0.034507,'1',0),(143,'api/books/playground','get',NULL,'','127.0.0.1',1443179628,0.0599301,'1',0),(144,'api/books/playground','get',NULL,'','127.0.0.1',1443179707,0.0577948,'1',0),(145,'api/books/playground','get',NULL,'','127.0.0.1',1443179772,0.045794,'1',0),(146,'api/books/playground','get',NULL,'','127.0.0.1',1443179787,0.039125,'1',0),(147,'api/books/playground','get',NULL,'','127.0.0.1',1443179952,0.0563302,'1',0),(148,'api/books/playground','get',NULL,'','127.0.0.1',1443180129,0.0398581,'1',0),(149,'api/books/playground','get',NULL,'','127.0.0.1',1443194632,0.05038,'1',0),(150,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443195780,0.0984991,'1',200),(151,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443195862,0.080864,'1',200),(152,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443196115,0.084543,'1',200),(153,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443196126,0.1014,'1',200),(154,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443199710,0.100393,'1',200),(155,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443200801,0.096292,'1',200),(156,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443202103,0.088737,'1',200),(157,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443202802,0.108939,'1',200),(158,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443202894,0.10276,'1',200),(159,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443202928,0.0960698,'1',200),(160,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443203037,0.0665801,'1',200),(161,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443203041,0.098006,'1',200),(162,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203042,0.104366,'1',200),(163,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203044,0.0781949,'1',200),(164,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203046,0.0942161,'1',200),(165,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203048,0.0989342,'1',200),(166,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203049,0.0831649,'1',200),(167,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203050,0.181676,'1',200),(168,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203050,0.105577,'1',200),(169,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203057,0.0928218,'1',200),(170,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443203098,0.281809,'1',200),(171,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443203100,0.176204,'1',200),(172,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203101,0.145485,'1',200),(173,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203102,0.0801589,'1',200),(174,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203105,0.0806839,'1',200),(175,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203110,0.082,'1',200),(176,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443203436,0.0969119,'1',200),(177,'api/catalogs/categories','get',NULL,'','192.168.43.1',1443203444,0.100507,'1',200),(178,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443203446,0.127979,'1',200),(179,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203447,0.120541,'1',200),(180,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203448,0.158017,'1',200),(181,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203449,0.075846,'1',200),(182,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203451,0.107662,'1',200),(183,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443203479,0.088176,'1',200),(184,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443203536,0.080303,'1',200),(185,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203538,0.0922191,'1',200),(186,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203540,0.0838959,'1',200),(187,'api/catalogs/bywriter','get',NULL,'','192.168.43.1',1443203674,0.090539,'1',200),(188,'api/books/writer/1','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203676,0.097337,'1',200),(189,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203677,0.156261,'1',200),(190,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203678,0.108511,'1',200),(191,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203679,0.100127,'1',200),(192,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203680,0.0846541,'1',200),(193,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203724,0.094048,'1',200),(194,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203732,0.081604,'1',200),(195,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203765,0.112425,'1',200),(196,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203956,0.0951259,'1',200),(197,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','192.168.43.1',1443203962,0.0970452,'1',200),(198,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443251844,0.110994,'1',0),(199,'api/books/item/50','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443251944,0.125359,'1',200),(200,'api/books/item/58','get','a:2:{i:0;N;i:1;N;}','','127.0.0.1',1443251949,0.083112,'1',200);
/*!40000 ALTER TABLE `opds_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opds_customer_book_subscription`
--

DROP TABLE IF EXISTS `opds_customer_book_subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opds_customer_book_subscription` (
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_ts` datetime NOT NULL,
  UNIQUE KEY `customer_id` (`customer_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opds_customer_book_subscription`
--

LOCK TABLES `opds_customer_book_subscription` WRITE;
/*!40000 ALTER TABLE `opds_customer_book_subscription` DISABLE KEYS */;
INSERT INTO `opds_customer_book_subscription` VALUES (2,50,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `opds_customer_book_subscription` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-26 13:26:59
