# MySQL-Front 5.0  (Build 1.0)

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;


# Host: localhost    Database: cofe
# ------------------------------------------------------
# Server version 5.0.51a

#
# Table structure for table article
#

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` text,
  `content` mediumtext,
  `user_id` int(11) unsigned NOT NULL default '0',
  `category_id` int(11) unsigned NOT NULL default '0',
  `created_at` int(11) unsigned NOT NULL default '0',
  `photo` varchar(100) default NULL,
  `hidden` tinyint(3) unsigned NOT NULL default '0',
  `rate` int(11) NOT NULL default '0',
  `comments` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table category
#

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `type` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table comment
#

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL auto_increment,
  `content` text,
  `user_id` int(11) unsigned NOT NULL default '0',
  `item_id` int(11) unsigned NOT NULL default '0',
  `item_type` tinyint(3) unsigned NOT NULL default '0',
  `created_at` int(11) unsigned NOT NULL default '0',
  `updated_at` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table config
#

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `var` varchar(100) NOT NULL default '',
  `val` varchar(255) default '',
  PRIMARY KEY  (`var`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `config` VALUES ('contact_mail','admin@mail.mm');
INSERT INTO `config` VALUES ('site_description','description');
INSERT INTO `config` VALUES ('site_keywords','');
INSERT INTO `config` VALUES ('site_title','Site');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table country
#

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
INSERT INTO `country` VALUES (1,'Беларусь');
INSERT INTO `country` VALUES (2,'Украина');
INSERT INTO `country` VALUES (3,'Россия');
INSERT INTO `country` VALUES (4,'Литва');
INSERT INTO `country` VALUES (5,'Латвия');
INSERT INTO `country` VALUES (6,'Эстония');
INSERT INTO `country` VALUES (7,'Грузия');
INSERT INTO `country` VALUES (8,'Молдова');
INSERT INTO `country` VALUES (9,'Армения');
INSERT INTO `country` VALUES (10,'США');
INSERT INTO `country` VALUES (11,'Канада');
/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table modules
#

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) default '',
  `alias` varchar(255) default NULL,
  `processor` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
INSERT INTO `modules` VALUES (1,'Page','page','page');
INSERT INTO `modules` VALUES (2,'Admin','admin','admin');
INSERT INTO `modules` VALUES (14,'User','user','user');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table pages
#

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(11) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(100) NOT NULL default '',
  `text` mediumtext NOT NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `image` varchar(255) default NULL,
  `hidden` tinyint(3) unsigned NOT NULL default '0',
  `so` int(11) unsigned default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
INSERT INTO `pages` VALUES (1,0,'What is it','site','index','<div id=\"text-ar\">\r\n<p>In 1998, CCUSA expanded its offerings to include Practical Training USA. The J-1 trainee visa allows qualified individuals in the fields of Commerce, Finance, Business and Management to come to the USA and improve their skills through a 12 - 18 month training program. Successful completion of this program is a valuable enhancement to anyoneпїЅs CV.</p>\r\n<p>In 2003 Camp California debuted. After years of requests for an America style camp in mainland Europe, CCUSA opened the 1st ever US style summer camp on the coast of the Adriatic Sea in Croatia. With over 160 campers per 2 week session and a staff of 50 counselors from around the world, this American style camp has it all; international friendships, songs, games, ropes courses, climbing walls and, of course, a wonderful waterfront program. CCUSA recruits both campers and staff for Camp California.</p>\r\n</div>',1167859829,'indexindex','index',NULL,1,1);
INSERT INTO `pages` VALUES (5,0,'contact','contact','contact','<h1>Welcome to California Yoga Center</h1>\r\n<p><font class=\"content\"> \t\t\t\t\t\t\t\tWe offer yoga classes and workshops for all ages and abilities, with studios in <a href=\"http://californiayoga.com/locations.php\">Mountain View and Palo Alto.</a>   \t\t\t\t\t\t\t\tOur <a href=\"http://californiayoga.com/instructors.php\">dedicated instructors</a> have extensive training and many years of teaching experience. \t\t\t\t\t\t\t\t<br />\r\n<br />\r\n<a href=\"http://californiayoga.com/new_to_yoga.php\">Yoga in the Iyengar tradition</a> emphasizes a balance between strength and flexibility, builds endurance, and develops self-awareness through precision in movement and attention to subtle aspects of posture and breath, mind and spirit. </font></p>',1176155186,'','',NULL,1,1);
INSERT INTO `pages` VALUES (13,0,'404','404','404','',0,'','',NULL,1,4);
INSERT INTO `pages` VALUES (17,0,'About Us','about','about','<p><span class=\"content\">СЏ РёРЅС„РѕСЂРјР°С†РёСЏ РєР°СЃР°С‚РµР»СЊРЅРѕ СЂР°СЃРїРёСЃР°РЅРёСЏ, Р°РґСЂРµСЃР° Рё С†РµРЅ СЂР°Р·РјРµС‰РµРЅР° РЅР° СЃС‚СЂР°РЅРёС†Рµ <strong>Р Р°СЃРїРёСЃР°РЅРёРµ</strong>. пїЅ?РЅС„РѕСЂРјР°С†РёСЋ РїРѕ РјРµС‚РѕРґРёРєРµ С‚СЂРµРЅРёСЂРѕРІРєРё РјРѕР¶РЅРѕ РЅР°Р№С‚Рё РІ СЃС‚Р°С‚СЊСЏС…&nbsp; &quot;<em><strong>РњРµС‚РѕРґРёРєР° С‚СЂРµРЅРёСЂРѕРІРєРё...&quot;</strong></em>, <em><strong>&quot;Рћ РїСЂРѕС†РµСЃСЃРµ РѕР±СѓС‡РµРЅРёСЏ&quot; </strong></em>Рё РґСЂ. Р”Р»СЏ Р·Р°РЅРёРјР°СЋС‰РёС…СЃСЏ РµСЃС‚СЊ РєРѕРЅСЃРїРµРєС‚&nbsp; <em><strong>&quot;Р›РёС‡РЅР°СЏ РїСЂР°РєС‚РёРєР°&quot;</strong></em>. РЎС‚Р°С‚СЊРё РЅРµСЂРµРіСѓР»СЏСЂРЅРѕ РѕР±РЅРѕРІР»СЏСЋС‚СЃСЏ, Рѕ С‡РµРј РёР·РІРµС‰Р°РµС‚СЃСЏ РІ РґР°РЅРЅРѕР№ РєРѕР»РѕРЅРєРµ РќРѕРІРѕСЃ</span></p>',0,'','',NULL,0,1);
INSERT INTO `pages` VALUES (19,0,'How it works','How it works','index2','<div id=\"text-ar\">\r\n<p>In 1998, CCUSA expanded its offerings to include Practical Training USA. The J-1 trainee visa allows qualified individuals in the fields of Commerce, Finance, Business and Management to come to the USA and improve their skills through a 12 - 18 month training program. Successful completion of this program is a valuable enhancement to anyoneпїЅs CV.</p>\r\n<p>In 2003 Camp California debuted. After years of requests for an America style camp in mainland Europe, CCUSA opened the 1st ever US style summer camp on the coast of the Adriatic Sea in Croatia. With over 160 campers per 2 week session and a staff of 50 counselors from around the world, this American style camp has it all; international friendships, songs, games, ropes courses, climbing walls and, of course, a wonderful waterfront program. CCUSA recruits both campers and staff for Camp California.</p>\r\n</div>',1196978542,'','',NULL,1,6);
INSERT INTO `pages` VALUES (20,0,'Testimonials','Testimonials','testimonials','<p>TestimonialsTestimonialsTestimonials TestimonialsTestimonials Testimonials</p>',1196978670,'','',NULL,1,9);
INSERT INTO `pages` VALUES (21,20,'J.Jr.Clarkson','t1','t1','<p>In 1998, CCUSA expanded its offerings to include Practical Training USA. The J-1 trainee visa allows qualified individuals in the fields of Commerce Finance</p>',1196978714,'','',NULL,0,7);
INSERT INTO `pages` VALUES (22,20,'J.Jr.Clarkson','t2','t2','<p>In 1998, CCUSA expanded its offerings to include Practical Training USA. The J-1 trainee visa allows qualified individuals in the fields of Commerce Finance</p>',1196978765,'','',NULL,0,8);
INSERT INTO `pages` VALUES (23,0,'Solution for','Solution for','solution','',1196978824,'','',NULL,1,10);
INSERT INTO `pages` VALUES (24,0,'FAQ','faq','faq','<p>FAQ FAQ FAQ</p>',1196978900,'','',NULL,0,5);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table search_match
#

DROP TABLE IF EXISTS `search_match`;
CREATE TABLE `search_match` (
  `word_id` int(11) unsigned NOT NULL default '0',
  `item_id` int(11) unsigned NOT NULL default '0',
  `item_type` tinyint(3) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `search_match` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table search_word
#

DROP TABLE IF EXISTS `search_word`;
CREATE TABLE `search_word` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `word` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `search_word` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table tags
#

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `word_id` int(11) unsigned NOT NULL default '0',
  `item_id` int(11) unsigned NOT NULL default '0',
  `item_type` tinyint(3) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

#
# Table structure for table users
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(45) NOT NULL default '',
  `password` varchar(80) NOT NULL default '',
  `mail` varchar(100) default NULL,
  `name` varchar(20) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `birthday` date default NULL,
  `fullname` varchar(255) NOT NULL default '',
  `nomail` tinyint(3) NOT NULL default '0',
  `photo` varchar(64) NOT NULL default '',
  `sex` tinyint(3) unsigned NOT NULL default '0',
  `country_id` int(11) unsigned NOT NULL default '0',
  `about` text,
  `website` varchar(255) NOT NULL default '',
  `code` varchar(64) NOT NULL default '',
  `created_at` int(11) unsigned NOT NULL default '0',
  `last_login` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
INSERT INTO `users` VALUES (1,'root','e10adc3949ba59abbe56e057f20f883e','y','admin','','','2025-11-20','',0,'',0,0,NULL,'','',0,1196457903);
INSERT INTO `users` VALUES (6,'dfhdf','827ccb0eea8a706c4c34a16891f84e7b','dfg@dgf.fg','nobody','','','1962-02-02','fdh',0,'',1,0,'dfgh','','5357fe30a925052aecce9e14f04da4d8',1196457045,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
