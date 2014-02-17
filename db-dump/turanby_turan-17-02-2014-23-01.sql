-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 18 2014 г., 00:01
-- Версия сервера: 5.1.68-cll-lve
-- Версия PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `turanby_turan`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`id`, `login`, `password`, `salt`, `created_at`, `created_by`) VALUES
(2, 'EmptY', 'u7SkF33bANKxDZoDVpkY544pXaqsrzimXcx3qyZV+s1Bi9qhTB5tcJeDIWA+InvP3PV4emU8/5rqHk4tlZ3tpw==', 'fYZYTast49ASEnZkb4STKE3KFhBK2Eih7Htb6aHRf5h4N2Z624', '1979-01-01 00:00:00', NULL),
(3, 'darkos', '/0Qc3S3IsIBnqy0CPU+UXkoAaSAOe8gwxoy5KgOs5m6xoA8ijRA3133Q+g9cjuyGBgzJCmaQjIWYfZC0dLQcYw==', 'EEizK6YK5bbkiQY639F8nha6fe2b565zzbT78DG3FKaRkZHbZd', '2014-01-13 12:09:36', NULL),
(6, 'sterehov', 'F7BjXY4Vy2J68Fb5k96601xDlhjfijjTl2owgkjTp+/c7HXPazzFdG4Tb9nCs1dBTisUkeqj5TNqOodXJM+8Ag==', 'yQz8743BnzAZ8zB6dEH6ni8nY4H9SBKad88922Abi22hindnTN', '2014-02-11 10:34:51', 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `admin_to_admin_roles`
--

INSERT INTO `admin_to_admin_roles` (`id`, `admin_id`, `admin_roles_id`) VALUES
(1, 2, 1),
(2, 3, 1),
(8, 6, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `creator` int(10) unsigned NOT NULL,
  `locale` varchar(2) NOT NULL,
  `main_pic` bigint(19) unsigned DEFAULT NULL,
  `gallery_type_id` int(10) unsigned NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `main_video_pic` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gallery_locale1_idx` (`locale`),
  KEY `fk_gallery_admin1_idx` (`creator`),
  KEY `fk_gallery_gallery_pics1_idx` (`main_pic`),
  KEY `fk_gallery_gallery_type1_idx` (`gallery_type_id`),
  KEY `fk_gallery_gallery_vids1_idx` (`main_video_pic`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `gallery`
--

INSERT INTO `gallery` (`id`, `name`, `creator`, `locale`, `main_pic`, `gallery_type_id`, `is_published`, `main_video_pic`) VALUES
(16, 'TEST_VIDEO_GALLERY', 2, 'ru', NULL, 2, 0, NULL),
(17, 'TEST_PHOTO_GALLERY', 2, 'ru', NULL, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_pics`
--

CREATE TABLE IF NOT EXISTS `gallery_pics` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `frontend_path` varchar(255) NOT NULL,
  `title` text,
  `gallery_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gallery_pics_gallery1_idx` (`gallery_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_type`
--

CREATE TABLE IF NOT EXISTS `gallery_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `alias` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `gallery_type`
--

INSERT INTO `gallery_type` (`id`, `name`, `alias`) VALUES
(1, 'Photo', 'GT_PHOTO_GALLERY'),
(2, 'Video', 'GT_VIDEO_GALLERY');

-- --------------------------------------------------------

--
-- Структура таблицы `gallery_vids`
--

CREATE TABLE IF NOT EXISTS `gallery_vids` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `frontend_path` varchar(255) NOT NULL,
  `title` text,
  `gallery_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gallery_vids_gallery1_idx` (`gallery_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `locale`
--

CREATE TABLE IF NOT EXISTS `locale` (
  `locale` varchar(2) NOT NULL,
  `name` varchar(15) NOT NULL,
  `alias` varchar(45) NOT NULL,
  PRIMARY KEY (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `locale`
--

INSERT INTO `locale` (`locale`, `name`, `alias`) VALUES
('en', 'English', 'TR_ENGLISH_LOCALE'),
('ru', 'Russian', 'TR_RUSSIAN_LOCALE');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `creator` int(10) unsigned NOT NULL,
  `modifier` int(10) unsigned DEFAULT NULL,
  `news_categories_id` int(10) unsigned NOT NULL,
  `locale` varchar(2) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_admin1_idx` (`creator`),
  KEY `fk_news_admin2_idx` (`modifier`),
  KEY `fk_news_news_categories1_idx` (`news_categories_id`),
  KEY `fk_news_locale1_idx` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `body`, `created_at`, `updated_at`, `is_published`, `creator`, `modifier`, `news_categories_id`, `locale`) VALUES
(1, 'Сайт взлетел', '<div>\nМы рады приветствовать вас на нашем сайте! Тут можно совершить виртуальную экскурсию по залам, просмотреть фотографии, узнать о последних нововведениях (которых грядет очень много!), а в будущем - даже ознакомиться с меню в режиме онлайн. Обо всех новостях можно также узнать на нашей странице в Вконтакте: <a href="\\">http://vk.com/turanpizza</a><br />\nОставайтесь с нами!</div>', '2013-11-15 23:25:00', '2014-01-13 17:22:11', 1, 2, 2, 3, 'ru'),
(2, 'Новогодняя ночь', 'Туран – это неповторимый стиль, тонкий вкус и непревзойденная кухня. Новогодняя ночь 2014 станет первой в нашей истории, и будьте уверены, – такого вы еще не видели! Волшебная праздничная атмосфера будет создана не только прекрасным интерьером, но и шоу-программой: вас ждет обаятельный ведущий, световое шоу, зажигательная музыка, костюмы и перевоплощения (даже На''ви из нашумевшего \\"Аватара\\"!), подарки, шикарный фейерверк и еще много приятных сюрпризов.<br>Проведите эту ночь у нас, и вы будете вспоминать ее весь год!<br>Стоимость: 1 500 000 на персону, все включено.<br>Справки и бронирование мест по телефону: +375(44) 566-22-29<br>', '2013-11-16 23:25:00', '2013-11-19 23:25:00', 1, 2, 2, 3, 'ru'),
(3, 'Туран ищет таланты', '<div>\r\n	Мы постоянно совершенствуем и обновляем свою культурную программу. Нас интересуют вокалисты, музыканты, танцоры и все-все-все, кто любит и умеет делать шоу. Вы всегда можете предложить нам сотрудничество, написав на <a href="\\">zegam@yandex.ru</a> или позвонив по номеру 54-10-35</div>', '2013-11-27 23:25:00', '2014-02-11 10:35:43', 1, 3, 6, 3, 'ru'),
(4, 'Игра Мафия', '<div>\r\n	16 января Клуб игры в Мафию &quot;Преступление и Наказание &quot; приглашает окунуться в современную гангстерскую разборку. Место встречи &nbsp;- TURAN. Время - с 19:00 до 23:00. В четверг!<br />\r\n	Обязательная запись по телефону - 80299468817</div>', '2014-01-05 22:49:16', NULL, 1, 6, NULL, 3, 'ru'),
(5, 'Яркие будни с Turan', '<div>\r\n	Друзья! С сегодняшнего дня у нас по будним дням и воскресениям действуют разнообразные и очень приятные скидки.</div>\r\n<div>\r\n	<br />\r\n	<div>\r\n		Понедельник : Happy Monday<br />\r\n		Салаты : 45 000<br />\r\n		Паста : 45 000<br />\r\n		Ризотто : 40 000</div>\r\n	<br />\r\n	<div>\r\n		Горячие блюда : 70 000<br />\r\n		Блюдо на 4-ых : 260 000</div>\r\n	<br />\r\n	<div>\r\n		Вторник : Пивной день<br />\r\n		При заказе 3+ бокалов пива кортошка фри в подарок<br />\r\n		<br />\r\n		Среда : Pizza Time<br />\r\n		Любая пицца 65 000</div>\r\n	<br />\r\n	<div>\r\n		Четверг : Пьяный Четверг<br />\r\n		Любой коктель за пол цены<br />\r\n		<br />\r\n		Воскресенье : Праздник сладкоежки<br />\r\n		Скидка на десерты 25%</div>\r\n	<br />\r\n	<div>\r\n		Также напомним, что у нас есть волшебные блюда &quot;русская рулетка&quot;, которые помогут решить множество проблем. У нас всегда интересно.<br />\r\n		<a href="http://vk.com/turanpizza?w=wall-55069186_45" target="_blank">http://vk.com/turanpizza?w=wall-55069186_45</a></div>\r\n</div>\r\n<div>\r\n</div>', '2014-01-27 22:53:02', '2014-02-13 23:17:35', 1, 6, 6, 3, 'ru'),
(6, '7 февраля Crims Party Turan', '<div>\r\n	7 февраля в Turan открывается новый сезон самых жарких и невероятных вечеринок Crims Party, по которым мы уже успели соскучиться. Спасибо всем кто был с нами, кто помогал создавать эту неповторимую и чарующую атмосферу на каждой пати. Мы будем рады видеть вас в одном из лучших заведений города - ресторанный комплекс &quot;Turan&quot;.<br />\r\n	Страница вечеринки: <a href="https://vk.com/crimsparty6">https://vk.com/crimsparty6</a>\r\n	<div>\r\n		line-up:<br />\r\n		★ MKMD<br />\r\n		★ Gene Karz<br />\r\n		★ Maller</div>\r\n	Вход 50 000<br />\r\n	Face Control | Dress Code | 18+</div>\r\n<div>\r\n</div>', '2014-02-01 22:40:25', '2014-02-13 23:18:10', 1, 6, 6, 3, 'ru'),
(7, 'Флиртаника | 13 февраля | Ресторан Turan', '<div>\r\n	13 февраля, в канун Дня всех влюбленных в ресторане Turan пройдет вечеринка быстрых знакомств (speeddating), безумно популярный в Европе формат.<br />\r\n	Вечер флирта и знакомств &laquo;Флиртаника&raquo; - 25 свиданий за 3 часа. Модный, динамичный формат знакомств гарантирует незабываемые впечатления и дает шанс приятно пообщаться, обрести новых знакомых и, возможно даже встретить свою вторую половинку!<br />\r\n	Вас ждет романтическая шоу-программа, фуршет от лучшего ресторана города Гродно &quot;Туран&quot;, профессиональное ведение и розыгрыш великолепных подарков от организаторов и партнеров мероприятия, в числе которых свадебная церемония на островах за счет туристической компании &laquo;Аврора Тур&raquo;, сертификаты на свадебные путешествия от компании, скидки на свадебные пакеты (фото, видео, проведение) от &laquo;Флай-студио&raquo; и, конечно, подарочный сертификат от ресторана &laquo;Туран&raquo; и столик на две персоны в День Святого Валентина для Вас!<br />\r\n	Для участия приглашаем позитивно настроенных и открытых для общения людей:&nbsp; мужчин 25-40 лет и девушек 22-35 лет.<br /><br />\r\n	Количество мест строго ограничено!<br />\r\n	Регистрация и заказ пригласетельных:<br />\r\n	+375(33) 300-00-85 (МТС)<br />\r\n	+375(29) 58-77-88-0 (МТС)</div>', '2014-02-04 22:38:03', '2014-02-13 23:18:45', 1, 6, 6, 3, 'ru'),
(8, 'Интеллектуально-развлекательная игра "Мозголомы"', '<div>\r\n	Впервые в Гродно<br />\r\n	интеллектуально-развлекательная игра &quot;Мозголомы&quot; в ресторане &quot;Туран&quot;!\r\n	<div>\r\n		Что вас ожидает:</div>\r\n	<ol>\r\n		<li>\r\n			Интересные вопросы.</li>\r\n		<li>\r\n			Музыкальные и видео задания.</li>\r\n		<li>\r\n			Непринужденная атмосфера.</li>\r\n		<li>\r\n			Бар.</li>\r\n		<li>\r\n			Призы.</li>\r\n	</ol>\r\n	2 марта, начало в 17:30<br />\r\n	пр-т Клецкова, 15а<br />\r\n	Вход: 20 тыс. руб.<br />\r\n	Игра командная: 3-4 человека.<br />\r\n	Место: банкетный зал комплекса &quot;Туран&quot; (2-ой этаж, напротив ресторана).</div>\r\n<div>\r\n</div>', '2014-02-11 20:09:24', '2014-02-11 22:11:55', 1, 6, 6, 3, 'ru'),
(9, 'Финал конкурса "Мисс Туран"', '<div>\r\n	Итак, дорогие друзья! Конкурс&quot; Мисс Туран&quot; подошел к финальной стадии и нам нужна ваша помощь в определении самой красивой, обаятельной и привлекательной участницы.<br />\r\n	Проходите по <a href="http://www.015.by/photo/260" target="_blank">ссылке</a> &nbsp;и отдайте свой голос одной или нескольким девушкам, которые понравятся вам больше всего. Напоминаем, что победительнице мы торжественно вручим сертификат на услуги Aurora Tour на сумму &nbsp;2 500 000 рублей ну и конечно за ней останется это символическое почетное звание.<br />\r\n	Ваш голос очень важен!&nbsp;</div>', '2014-02-11 22:44:42', '2014-02-13 22:46:10', 1, 6, 6, 3, 'ru');

-- --------------------------------------------------------

--
-- Структура таблицы `news_categories`
--

CREATE TABLE IF NOT EXISTS `news_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `locale` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_news_categories_locale1_idx` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `locale`) VALUES
(3, 'Новости', 'ru'),
(4, 'News', 'en');

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
  `locale` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_turan_page_turan_admin_idx` (`admin_creator_id`),
  KEY `fk_turan_page_turan_admin1_idx` (`admin_modifier_id`),
  KEY `fk_static_page_locale1_idx` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `static_page`
--

INSERT INTO `static_page` (`id`, `page_name`, `page_body`, `page_seo`, `date_created`, `date_modified`, `is_published`, `admin_creator_id`, `admin_modifier_id`, `locale`) VALUES
(1, 'Банкетный зал в Гродно', '<div class="contacts-description">\r\n	<div class="position-block">\r\n		<b>Время работы:</b><br />\r\n		индивидуально для заказчика</div>\r\n	<div class="position-block">\r\n		<b>Тел:</b> 8 (0152) 50-92-86<br />\r\n		+375(33) 300-00-85 (МТС)<br />\r\n		+375(44) 566-22-28 (Velcom)</div>\r\n	<div class="position-block">\r\n		<b>Электронная почта:</b><br />\r\n		<a href="mailto:banket.zal@turan.by">info@turan.by</a></div>\r\n</div>\r\n<div class="panoram-view ballroom">\r\n	<section class="rounded">\r\n		<iframe height="250px" src="asset/banket.html" style="border: 0px;" width="100%">Ваш браузер не поддерживает плавающие фреймы!</iframe><img class="main-panoram-view" src="asset/images/4.jpg" /><!-- <a href="#" class="btn"> Панорама </a><a href="#" class="btn"> Фото </a><a href="#" class="btn"> План зала </a> --></section>\r\n</div>\r\n<div class="sub-menu">\r\n	<ul>\r\n		<!--<li><a href="#">Меню</a></li><li><a href="#">Коктельная карта</a></li><li><a href="#">Бронирование</a></li><li><a href="#">Спец. предложения</a></li> -->\r\n		<li>\r\n			<ul>\r\n			</ul>\r\n		</li>\r\n	</ul>\r\n</div>\r\n<div class="info">\r\n	Роскошный и восхитительный, просторный и элегантный &ndash; банкетный зал &laquo;Туран&raquo; покоряет своей простой и изящностью. Выполненный в пастельных тонах он, как чистая страница, приглашает вас написать свою историю праздника: будь то появление новой семьи, полувековое путешествие по дороге жизни или день рождения успешного бизнеса.<br />\r\n	-150 посадочных мест;<br />\r\n	-проведение конференций;<br />\r\n	-услуга &laquo;аренда зала&raquo;;</div>', 'ballroom', '2014-01-13 00:00:00', '2014-02-13 22:34:53', 1, 2, 6, 'ru'),
(2, 'Контактная информация ООО "Зегам"', '<div class="address">\r\n	<h3>\r\n		Адрес:</h3>\r\n	<p>\r\n		230020, Республика Беларусь<br />\r\n		г. Гродно, проспект Клецкова 15а</p>\r\n	<div class="phones">\r\n		<p>\r\n			Пиццерия:<br />\r\n			Тел.: 8 (0152) 51-00-51<br />\r\n			+375(33) 300-00-84 (МТС)<br />\r\n			+375(44) 566-22-27 (Velcom)</p>\r\n		<p>\r\n			Ресторан:<br />\r\n			Тел.: 8 (0152) 50-92-86<br />\r\n			+375(33) 300-00-85 (МТС)<br />\r\n			+375(44) 566-22-28 (Velcom)</p>\r\n	</div>\r\n	<!--<div class="call-center">--><!--<img src="./img/call.png">--><!--Call-Center 777--><!--</div>--></div>\r\n<div class="map rounded">\r\n	<!-- <script type="text/javascript" charset="utf-8" src="http://api-maps.yandex.ru/services/constructor/1.0/js/?sid=fnZ-ZeLrt2EyQUFVbBdfj6vkO4bUwXBZ&width=500&height=300"></script>--><img alt="" src="http://static-maps.yandex.ru/1.x/?ll=23.84555,53.64842&amp;z=17&amp;size=560,300&amp;lang=ru-RU&amp;l=map&amp;origin=jsapi-constructor&amp;pt=23.84475,53.64832,pm2lbl" style="width: 530px; height: 300px;" /></div>\r\n<div class="company-data">\r\n	<h3>\r\n		Электронная почта:</h3>\r\n	<p>\r\n		<a href="mailto:zegam@yandex.by">info@turan.by</a></p>\r\n</div>\r\n<div>\r\n</div>', 'contacts', '2014-01-13 00:00:00', '2014-02-13 22:35:02', 1, 2, 6, 'ru'),
(3, 'История', '<div class="history"><p>Туран создан для того, чтобы Вы могли воплотить все свои желания и фантазии в жизнь.</p><p>Uникальность ресторанного комплекса состоит в том, что здесь каждый может найти зал по своему вкусу. Празднуете день рождения? Добро пожаловать в пиццерию! Организовывайте корпоратив? К вашим услугам ресторан. Планируйте роскошную свадьбу? Приглашаем в банкетный зал.</p><p>Rазвлекательные программы, юмористические шоу, театрализованные представления, карнавальные вечеринки – мы готовы удивлять наших гостей снова и снова, сопровождая каждый праздник уникальными развлекательными номерами и живой музыкой. «Туран» – это идеальное сочетание отдыха для души и тела.</p><p>Атмосфера безграничного гостеприимства в соединении с европейскими кулинарными традициями сделает ваш вечер в «Туране» приятным и запоминающимся. Старательные официанты и опытные шеф-повара приложат максимум усилий для того, чтобы угодить даже самым требовательным посетителям.</p><p>Nе стоит отказывать себе в удовольствии… Посетите «Туран» – откройте для себя мир изысканных блюд, безукоризненного обслуживания и притягательной атмосферы праздника, устроенного в вашу честь. Помните, желания обязательно должны сбываться.</p><div class="right" style="float: right;">С уважением, ваш «Туран»</div></div>', 'history', '2014-01-13 00:00:00', '2014-01-13 00:00:00', 1, 2, 2, 'ru'),
(4, 'Пиццерия в Гродно', '<div class="contacts-description">\r\n	<div class="position-block">\r\n		<b>Время работы:</b><br />\r\n		будни с 10:00 до 22:00<br />\r\n		выходные с 10:00 до 01:00</div>\r\n	<div class="position-block">\r\n		<b>Тел:</b> 8 (0152) 51-00-51<br />\r\n		+375(33) 300-00-84 (МТС)<br />\r\n		+375(44) 566-22-27 (Velcom)</div>\r\n	<div class="position-block">\r\n		<b>Электронная почта:</b><br />\r\n		<a href="mailto:banket.zal@turan.by">info@turan.by</a></div>\r\n</div>\r\n<div class="panoram-view ballroom">\r\n	<section class="rounded">\r\n		<iframe height="250px" src="asset/pizza.html" style="border: 0px;" width="100%">Ваш браузер не поддерживает плавающие фреймы!</iframe><img class="main-panoram-view" src="asset/images/1.jpg" /><!--<a href="#" class="btn"> Панорама </a> <a href="#" class="btn"> Фото </a><a href="#" class="btn"> План зала </a> --></section>\r\n</div>\r\n<div class="sub-menu">\r\n	<ul>\r\n		<!--<li><a href="#">Меню</a></li><li><a href="#">Коктельная карта</a></li><li><a href="#">Бронирование</a></li><li><a href="#">Спец. предложения</a></li> -->\r\n		<li>\r\n			<ul>\r\n			</ul>\r\n		</li>\r\n	</ul>\r\n</div>\r\n<div class="info">\r\n	Ищите уютное место для встречи с друзьями? Хотите весело и вкусно провести время всей семьей? Приходите в пиццерию &laquo;Туран&raquo;! Вас ждет итальянское меню, дружелюбная атмосфера, телетрансляции спортивных событий, детские праздники, а также колесо времени, заставляющее забыть о часах и по-настоящему насладиться отдыхом.<br />\r\n	- 100 посадочных мест;<br />\r\n	- предоставление детских стульчиков и раскрасок;<br />\r\n	- проведение детских и тематических праздников;</div>', 'pizzeria', '2014-01-13 00:00:00', '2014-02-13 22:35:26', 1, 2, 6, 'ru'),
(5, 'Ресторан в Гродно', '<div class="contacts-description">\r\n	<div class="position-block">\r\n		<b>Время работы:</b><br />\r\n		будни с 18:00 до 01:00<br />\r\n		выходные с 18:00 до 02:00</div>\r\n	<div class="position-block">\r\n		<b>Тел:</b> 8 (0152) 50-92-86<br />\r\n		+375(33) 300-00-85 (МТС)<br />\r\n		+375(44) 566-22-28 (Velcom)</div>\r\n	<div class="position-block">\r\n		<b>Электронная почта:</b><br />\r\n		<a href="mailto:banket.zal@turan.by">info@turan.by</a></div>\r\n</div>\r\n<div class="panoram-view ballroom">\r\n	<section class="rounded">\r\n		<iframe height="250px" src="asset/restorant.html" style="border: 0px;" width="100%">Ваш браузер не поддерживает плавающие фреймы!</iframe><img class="main-panoram-view" src="asset/images/3.jpg" /><!-- <a href="#" class="btn"> Панорама </a><a href="#" class="btn"> Фото </a><a href="#" class="btn"> План зала </a> --></section>\r\n</div>\r\n<div class="sub-menu">\r\n	<ul>\r\n		<!-- <li><a href="#">Меню</a></li><li><a href="#">Коктельная карта</a></li><li><a href="#">Бронирование</a></li><li><a href="#">Спец. предложения</a></li> -->\r\n		<li>\r\n			<ul>\r\n			</ul>\r\n		</li>\r\n	</ul>\r\n</div>\r\n<div class="info">\r\n	Ресторан &laquo;Туран&raquo; создан, чтобы удивлять. Здесь слышны шаги тишины, здесь чувствуется дыхание изысканности, здесь каждый день на ваших глазах распускаются розы. Необычный зал, потрясающее меню &ndash; &laquo;Туран&raquo; навсегда перевернет ваше представление о том, что такое высокая кухня и каким должен быть настоящий Ресторан.<br />\r\n	- 100 посадочных мест;<br />\r\n	- живая музыка;<br />\r\n	- проведение корпоративных мероприятий;</div>', 'restaurant', '2014-01-13 00:00:00', '2014-02-13 23:30:43', 1, 2, 6, 'ru');

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
  ADD CONSTRAINT `fk_news_locale1` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news_news_categories1` FOREIGN KEY (`news_categories_id`) REFERENCES `news_categories` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `news_categories`
--
ALTER TABLE `news_categories`
  ADD CONSTRAINT `fk_news_categories_locale1` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `static_page`
--
ALTER TABLE `static_page`
  ADD CONSTRAINT `fk_turan_page_turan_admin` FOREIGN KEY (`admin_creator_id`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_turan_page_turan_admin1` FOREIGN KEY (`admin_modifier_id`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_static_page_locale1` FOREIGN KEY (`locale`) REFERENCES `locale` (`locale`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
