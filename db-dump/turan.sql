-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2014 at 01:22 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `turan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_admin_admin1_idx` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `login`, `password`, `salt`, `created_at`, `created_by`) VALUES
(2, 'EmptY', 'u7SkF33bANKxDZoDVpkY544pXaqsrzimXcx3qyZV+s1Bi9qhTB5tcJeDIWA+InvP3PV4emU8/5rqHk4tlZ3tpw==', 'fYZYTast49ASEnZkb4STKE3KFhBK2Eih7Htb6aHRf5h4N2Z624', '1979-01-01 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`) VALUES
(1, 'ROLE_SUPER_ADMIN'),
(2, 'ROLE_CONTENT_MANAGER'),
(3, 'ROLE_WAITER'),
(4, 'ROLE_HELPDESK');

-- --------------------------------------------------------

--
-- Table structure for table `admin_to_admin_roles`
--

CREATE TABLE IF NOT EXISTS `admin_to_admin_roles` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `admin_roles_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_turan_admin_has_turan_admin_roles_turan_admin_roles1_idx` (`admin_roles_id`),
  KEY `fk_turan_admin_has_turan_admin_roles_turan_admin1_idx` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin_to_admin_roles`
--

INSERT INTO `admin_to_admin_roles` (`id`, `admin_id`, `admin_roles_id`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `locale`
--

CREATE TABLE IF NOT EXISTS `locale` (
  `locale` varchar(2) NOT NULL,
  `name` varchar(15) NOT NULL,
  `alias` varchar(45) NOT NULL,
  PRIMARY KEY (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locale`
--

INSERT INTO `locale` (`locale`, `name`, `alias`) VALUES
('en', 'English', 'TR_ENGLISH_LOCALE'),
('ru', 'Russian', 'TR_RUSSIAN_LOCALE');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `creator` int(10) unsigned NOT NULL,
  `modifier` int(10) unsigned DEFAULT NULL,
  `news_categories_id` int(10) unsigned NOT NULL,
  `locale` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_admin1_idx` (`creator`),
  KEY `fk_news_admin2_idx` (`modifier`),
  KEY `fk_news_news_categories1_idx` (`news_categories_id`),
  KEY `fk_news_locale1_idx` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `body`, `created_at`, `updated_at`, `is_published`, `creator`, `modifier`, `news_categories_id`, `locale`) VALUES
(2, 'title', '<div>\r\n	iajgipsjgisgiohsoihgsih</div>', '2014-01-12 19:35:58', NULL, 1, 2, NULL, 3, 'ru'),
(3, 'aeta', '<div>\r\n	afdszxvcv</div>', '2014-01-12 21:44:24', NULL, 1, 2, NULL, 3, 'ru'),
(9, 'aushflahlja', '<div>\r\n	siohgoshgoishgdiohsddgioh</div>', '2014-01-13 01:07:20', NULL, 1, 2, NULL, 4, 'en'),
(11, 'adgsdg', '<div>\r\n	psdjzgisjh pjspigjs pgjs ghsohdgoushguoshdgouh</div>', '2014-01-13 01:08:54', NULL, 0, 2, NULL, 4, 'en'),
(14, 'asdfjafpjadgsdg', '<div>\r\n	psdjzgisjh pjspigjs pgjs ghsohdgoushguoshdgouh</div>', '2014-01-13 01:10:28', NULL, 1, 2, NULL, 4, 'en'),
(15, 'agagagag', '<div>\r\n	ad ipejg pjg piag ahg</div>', '2014-01-13 01:10:55', NULL, 1, 2, NULL, 4, 'en'),
(16, 'aksf aipjeg pjsepigjspgipsghsigh', '<div>\r\n	jhfoijsaifjafj aijf apj fpajfp japjfipajf paj fpaj pfjap jf</div>', '2014-01-13 01:11:20', NULL, 1, 2, NULL, 4, 'en'),
(17, 'sngs;gs;g sjgljjsjgsj', '<div>\r\n	psjgpsj gpsej gpsjegp jspiegjseijgisejgi sjeijspegj spjgsjgp&#39;</div>', '2014-01-13 01:12:11', NULL, 1, 2, NULL, 3, 'ru'),
(18, 'asjf', '<div>\r\n	aigjeapjg poej gpaeo japejfpajfpajfpj</div>', '2014-01-13 01:12:30', NULL, 1, 2, NULL, 3, 'ru'),
(19, 'afjapjfpajfepjawpjfapwjfajsifjapjpj', '<div>\r\n	jafjafpjapsjfapsjfajfpaj fpjaspf japfj apjsf pasjfpajsfpasfpj</div>', '2014-01-13 01:12:50', NULL, 0, 2, NULL, 3, 'ru'),
(20, 'aiosfaoihj', '<div>\r\n	aoighoa ehgoeahouhafhauhaoh foahe fuhae ufhaefhaoe hfeauhfoaehfohaof ha</div>', '2014-01-13 01:13:11', NULL, 1, 2, NULL, 3, 'ru');

-- --------------------------------------------------------

--
-- Table structure for table `news_categories`
--

CREATE TABLE IF NOT EXISTS `news_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `locale` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_categories_locale1_idx` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `locale`) VALUES
(3, 'TMP_NEW', 'ru'),
(4, 'TMP_NEW_EN', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `static_page`
--

CREATE TABLE IF NOT EXISTS `static_page` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(127) NOT NULL,
  `page_body` longtext NOT NULL,
  `page_seo` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `admin_creator_id` int(10) unsigned NOT NULL,
  `admin_modifier_id` int(10) unsigned DEFAULT NULL,
  `locale` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_turan_page_turan_admin_idx` (`admin_creator_id`),
  KEY `fk_turan_page_turan_admin1_idx` (`admin_modifier_id`),
  KEY `fk_static_page_locale1_idx` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `static_page`
--

INSERT INTO `static_page` (`id`, `page_name`, `page_body`, `page_seo`, `date_created`, `date_modified`, `is_published`, `admin_creator_id`, `admin_modifier_id`, `locale`) VALUES
(1, 'static', '<div>\r\n	aoifgpaiiaphgpahgpih</div>', 'url_seo', '2014-01-12 19:28:42', NULL, 1, 2, NULL, 'en');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `admin_to_admin_roles`
--
ALTER TABLE `admin_to_admin_roles`
  ADD CONSTRAINT `fk_turan_admin_has_turan_admin_roles_turan_admin1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_turan_admin_has_turan_admin_roles_turan_admin_roles1` FOREIGN KEY (`admin_roles_id`) REFERENCES `admin_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_admin1` FOREIGN KEY (`creator`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_admin2` FOREIGN KEY (`modifier`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_news_categories1` FOREIGN KEY (`news_categories_id`) REFERENCES `news_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_locale1` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON UPDATE CASCADE;

--
-- Constraints for table `news_categories`
--
ALTER TABLE `news_categories`
  ADD CONSTRAINT `fk_news_categories_locale1` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON UPDATE CASCADE;

--
-- Constraints for table `static_page`
--
ALTER TABLE `static_page`
  ADD CONSTRAINT `fk_turan_page_turan_admin` FOREIGN KEY (`admin_creator_id`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turan_page_turan_admin1` FOREIGN KEY (`admin_modifier_id`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_static_page_locale1` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
