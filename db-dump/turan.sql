-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 30 2013 г., 00:44
-- Версия сервера: 5.5.34-0ubuntu0.13.04.1
-- Версия PHP: 5.5.5-1+debphp.org~raring+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `turan`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`id`, `login`, `password`, `salt`, `created_at`, `created_by`) VALUES
(2, 'EmptY', 'u7SkF33bANKxDZoDVpkY544pXaqsrzimXcx3qyZV+s1Bi9qhTB5tcJeDIWA+InvP3PV4emU8/5rqHk4tlZ3tpw==', 'fYZYTast49ASEnZkb4STKE3KFhBK2Eih7Htb6aHRf5h4N2Z624', '1979-01-01 00:00:00', NULL),
(4, 'admin', 'SOgLZ8rNe03MJqCsRhlquqCIg4ajVSkNaovVxV0CQtV1zeXY1dppO2qgn+IfqBLdkkIuk1RbqNp67GaY07yWfQ==', 'ztNDD2Y28aADezdEZnkaFBnENYsaNnH8HiB98hZ6iiysGBYsQ8', '2013-12-03 21:13:58', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `admin_roles`
--

CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`) VALUES
(1, 'ROLE_SUPER_ADMIN'),
(2, 'ROLE_CONTENT_MANAGER'),
(3, 'ROLE_WAITER'),
(4, 'ROLE_HELPDESK');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_to_admin_roles`
--

CREATE TABLE IF NOT EXISTS `admin_to_admin_roles` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL,
  `admin_roles_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_turan_admin_has_turan_admin_roles_turan_admin_roles1_idx` (`admin_roles_id`),
  KEY `fk_turan_admin_has_turan_admin_roles_turan_admin1_idx` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `admin_to_admin_roles`
--

INSERT INTO `admin_to_admin_roles` (`id`, `admin_id`, `admin_roles_id`) VALUES
(1, 2, 1),
(4, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `creator` int(10) unsigned NOT NULL,
  `modifier` int(10) unsigned DEFAULT NULL,
  `news_categories_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_admin1_idx` (`creator`),
  KEY `fk_news_admin2_idx` (`modifier`),
  KEY `fk_news_news_categories1_idx` (`news_categories_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `body`, `created_at`, `updated_at`, `is_published`, `creator`, `modifier`, `news_categories_id`) VALUES
(1, 'Туран ищет таланты', '<div>\r\n	Мы постоянно совершенствуем и обновляем свою культурную программу. Нас интересуют вокалисты, музыканты, танцоры и все-все-все, кто любит и умеет делать шоу. Вы всегда можете предложить нам сотрудничество, написав на <a href="\\">zegam@yandex.ru</a> или позвонив по номеру 54-10-35</div>', '2013-12-29 19:41:52', NULL, 1, 4, NULL, 1),
(2, 'Новогодняя ночь', '<div>\r\n	Туран &ndash; это неповторимый стиль, тонкий вкус и непревзойденная кухня. Новогодняя ночь 2014 станет первой в нашей истории, и будьте уверены, &ndash; такого вы еще не видели! Волшебная праздничная атмосфера будет создана не только прекрасным интерьером, но и шоу-программой: вас ждет обаятельный ведущий, световое шоу, зажигательная музыка, костюмы и перевоплощения (даже На&#39;ви из нашумевшего \\&quot;Аватара\\&quot;!), подарки, шикарный фейерверк и еще много приятных сюрпризов.<br />\r\n	Проведите эту ночь у нас, и вы будете вспоминать ее весь год!<br />\r\n	Стоимость: 1 500 000 на персону, все включено.<br />\r\n	Справки и бронирование мест по телефону: +375(44) 566-22-29</div>', '2013-12-29 19:45:20', NULL, 1, 4, NULL, 1),
(3, 'Сайт взлетел', 'Мы рады приветствовать вас на нашем сайте! Тут можно совершить виртуальную экскурсию по залам, просмотреть фотографии, узнать о последних нововведениях (которых грядет очень много!), а в будущем - даже ознакомиться с меню в режиме онлайн. Обо всех новостях можно также узнать на нашей странице в Вконтакте: <a href=\\"http://vk.com/turanpizza\\">http://vk.com/turanpizza</a> <br>Оставайтесь с нами!', '2013-12-30 00:27:01', NULL, 1, 4, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `news_categories`
--

CREATE TABLE IF NOT EXISTS `news_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`) VALUES
(1, 'Новости');

-- --------------------------------------------------------

--
-- Структура таблицы `static_page`
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_turan_page_turan_admin_idx` (`admin_creator_id`),
  KEY `fk_turan_page_turan_admin1_idx` (`admin_modifier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `static_page`
--

INSERT INTO `static_page` (`id`, `page_name`, `page_body`, `page_seo`, `date_created`, `date_modified`, `is_published`, `admin_creator_id`, `admin_modifier_id`) VALUES
(1, 'newPage', 'ajigjagijiajg', 'new', '2013-11-29 05:13:15', '2013-11-29 06:36:39', 1, 2, 2);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `admin_to_admin_roles`
--
ALTER TABLE `admin_to_admin_roles`
  ADD CONSTRAINT `fk_turan_admin_has_turan_admin_roles_turan_admin1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_turan_admin_has_turan_admin_roles_turan_admin_roles1` FOREIGN KEY (`admin_roles_id`) REFERENCES `admin_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_admin1` FOREIGN KEY (`creator`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_admin2` FOREIGN KEY (`modifier`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_news_categories1` FOREIGN KEY (`news_categories_id`) REFERENCES `news_categories` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `static_page`
--
ALTER TABLE `static_page`
  ADD CONSTRAINT `fk_turan_page_turan_admin` FOREIGN KEY (`admin_creator_id`) REFERENCES `admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_turan_page_turan_admin1` FOREIGN KEY (`admin_modifier_id`) REFERENCES `admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
