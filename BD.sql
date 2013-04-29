-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-04-2013 a las 18:18:06
-- Versión del servidor: 5.1.30
-- Versión de PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `yuppics2`
--
CREATE DATABASE `yuppics2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `yuppics2`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accomodation_imgs`
--

DROP TABLE IF EXISTS `accomodation_imgs`;
CREATE TABLE IF NOT EXISTS `accomodation_imgs` (
  `id_img` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_img`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `accomodation_imgs`
--

INSERT INTO `accomodation_imgs` (`id_img`, `width`, `height`) VALUES
(1, 45, 35),
(2, 35, 70);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accomodation_page`
--

DROP TABLE IF EXISTS `accomodation_page`;
CREATE TABLE IF NOT EXISTS `accomodation_page` (
  `id_page` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_imgs` int(10) unsigned NOT NULL,
  `url_preview` varchar(150) NOT NULL,
  PRIMARY KEY (`id_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `accomodation_page`
--

INSERT INTO `accomodation_page` (`id_page`, `num_imgs`, `url_preview`) VALUES
(1, 2, 'application/yuppics/pages/preview/pag1.png'),
(2, 3, 'application/yuppics/pages/preview/pag2.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accomodation_page_imgs`
--

DROP TABLE IF EXISTS `accomodation_page_imgs`;
CREATE TABLE IF NOT EXISTS `accomodation_page_imgs` (
  `id_page_img` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_page` int(10) unsigned NOT NULL,
  `id_img` int(10) unsigned NOT NULL,
  `coord_x` int(10) unsigned NOT NULL,
  `coord_y` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_page_img`),
  KEY `id_page` (`id_page`),
  KEY `id_img` (`id_img`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `accomodation_page_imgs`
--

INSERT INTO `accomodation_page_imgs` (`id_page_img`, `id_page`, `id_img`, `coord_x`, `coord_y`) VALUES
(1, 1, 1, 5, 5),
(2, 1, 1, 50, 50),
(3, 2, 1, 5, 5),
(4, 2, 1, 5, 50),
(5, 2, 2, 50, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `address_book`
--

DROP TABLE IF EXISTS `address_book`;
CREATE TABLE IF NOT EXISTS `address_book` (
  `id_address` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_customer` bigint(20) unsigned NOT NULL,
  `id_country` int(10) unsigned NOT NULL,
  `id_state` int(10) unsigned NOT NULL,
  `contact_first_name` varchar(30) NOT NULL,
  `contact_last_name` varchar(40) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `company` varchar(110) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `street` varchar(100) NOT NULL,
  `between_streets` varchar(160) NOT NULL,
  `colony` varchar(70) NOT NULL,
  `city` varchar(70) NOT NULL,
  `default_billing` tinyint(1) NOT NULL DEFAULT '0',
  `default_shipping` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_address`),
  KEY `id_customer` (`id_customer`),
  KEY `id_country` (`id_country`),
  KEY `id_state` (`id_state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `address_book`
--

INSERT INTO `address_book` (`id_address`, `id_customer`, `id_country`, `id_state`, `contact_first_name`, `contact_last_name`, `phone`, `company`, `rfc`, `street`, `between_streets`, `colony`, `city`, `default_billing`, `default_shipping`) VALUES
(1, 2, 1, 2, 'Gamaliel', 'Mendoza', '0', '', '', 'Av Niños Heroes', '', 'Juan Jose 3', 'Villa de Alvarez', 0, 0),
(2, 2, 1, 1, 'Gamaliel1', 'Mendoza', '0', 'Yuppics', 'FSA312343DD2', 'Av Niños Heroes', 'DDAsd', 'Pelisasd', 'Villa de Alvarez', 1, 1),
(4, 1, 1, 4, 'Gamaliel', 'Mendoza Solis', '0', 'asd', 'asdasdasdasd', 'asd', 'as', 'asdasd', 'asd', 0, 0),
(5, 1, 1, 2, 'Gamaliel', 'Mendoza Solis', '0', 'compañia', 'FSD223322DXX', 'AngularJSui.org', '', 'DAss', 'web', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('712dd63086cab520afcce343e2bb91d8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31 AlexaToolba', 1366680553, 'a:7:{s:9:"user_data";s:0:"";s:10:"id_usuario";s:1:"2";s:6:"nombre";s:8:"Gamaliel";s:5:"email";s:21:"gamalielm@indieds.com";s:4:"type";s:8:"customer";s:7:"idunico";s:24:"l5175c60d7be4d0.11396918";s:10:"id_yuppics";s:1:"6";}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `max_fotos` int(10) unsigned NOT NULL DEFAULT '48',
  `percentage` double unsigned NOT NULL COMMENT 'Porcentaje para los cupones'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcar la base de datos para la tabla `config`
--

INSERT INTO `config` (`max_fotos`, `percentage`) VALUES
(48, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id_message` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `subject` varchar(80) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `contact`
--

INSERT INTO `contact` (`id_message`, `name`, `email`, `subject`, `message`, `status`) VALUES
(1, 'gama', 'gamameso@gmail.com', '', 'asdasdasd as dasd', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id_country` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `countries`
--

INSERT INTO `countries` (`id_country`, `name`) VALUES
(1, 'México');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id_coupon` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(35) NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `percentage` double unsigned NOT NULL DEFAULT '0' COMMENT 'Porcentaje Aplicado',
  `uses_total` int(11) NOT NULL,
  `date_start` timestamp NULL DEFAULT NULL,
  `date_end` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:utilizable, 1:canjeado',
  PRIMARY KEY (`id_coupon`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `coupons`
--

INSERT INTO `coupons` (`id_coupon`, `name`, `code`, `amount`, `percentage`, `uses_total`, `date_start`, `date_end`, `created`, `status`) VALUES
(5, 'Cupon de regalo', 'MJW=wu#g', 100, 0, 1, NULL, NULL, '2012-12-24 18:44:10', 1),
(6, 'calis', 'mes2', 0, 40, 5, NULL, NULL, '2013-04-19 10:22:08', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupons_history`
--

DROP TABLE IF EXISTS `coupons_history`;
CREATE TABLE IF NOT EXISTS `coupons_history` (
  `id_history` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_coupon` bigint(20) unsigned NOT NULL,
  `id_customer` bigint(20) unsigned NOT NULL,
  `id_order` bigint(20) unsigned NOT NULL,
  `amount` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_history`),
  KEY `id_coupon` (`id_coupon`),
  KEY `id_customer` (`id_customer`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `coupons_history`
--

INSERT INTO `coupons_history` (`id_history`, `id_coupon`, `id_customer`, `id_order`, `amount`, `created`) VALUES
(1, 5, 2, 3, 100, '2013-03-18 18:59:09'),
(2, 5, 2, 4, 100, '2013-03-18 19:47:28'),
(3, 5, 2, 5, 100, '2013-03-18 19:51:55'),
(4, 5, 2, 6, 100, '2013-03-18 19:58:13'),
(5, 6, 2, 24, 120, '2013-04-19 10:48:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_promo`
--

DROP TABLE IF EXISTS `customer_promo`;
CREATE TABLE IF NOT EXISTS `customer_promo` (
  `id_promo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_customer` bigint(20) unsigned NOT NULL,
  `id_coupon` bigint(20) unsigned DEFAULT NULL,
  `link_facebook` tinyint(1) NOT NULL DEFAULT '0',
  `invit_facebook` tinyint(1) NOT NULL DEFAULT '0',
  `tweet` tinyint(1) NOT NULL DEFAULT '0',
  `feedback` tinyint(1) NOT NULL DEFAULT '0',
  `feedback_text` text,
  PRIMARY KEY (`id_promo`),
  KEY `id_customer` (`id_customer`),
  KEY `id_coupon` (`id_coupon`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `customer_promo`
--

INSERT INTO `customer_promo` (`id_promo`, `id_customer`, `id_coupon`, `link_facebook`, `invit_facebook`, `tweet`, `feedback`, `feedback_text`) VALUES
(4, 4, NULL, 0, 0, 1, 0, NULL),
(5, 2, NULL, 1, 1, 0, 1, 'ads as das das da sda sdasd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id_customer` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(35) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `url_avatar` varchar(150) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('on','off','delete') NOT NULL,
  `facebook_id` varchar(50) NOT NULL,
  `twitter_id` varchar(50) NOT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcar la base de datos para la tabla `customers`
--

INSERT INTO `customers` (`id_customer`, `username`, `password`, `first_name`, `last_name`, `email`, `phone`, `url_avatar`, `created`, `updated`, `status`, `facebook_id`, `twitter_id`, `newsletter`) VALUES
(1, 'elgame', 'e10adc3949ba59abbe56e057f20f883e', 'Gamaliel', 'Mendoza Solis', 'gamameso@gmail.com', '', '', '2012-12-17 16:03:20', '2013-02-11 13:48:18', 'on', '', '', 0),
(2, '', '', 'Gamaliel', 'Mendoza', 'gamalielm@indieds.com', '', 'application/images/avatars/100000678188027.jpg', '0000-00-00 00:00:00', '2013-02-19 15:43:33', 'on', '100000678188027', '', 0),
(3, 'furby', 'e10adc3949ba59abbe56e057f20f883e', 'oscar', 'furbya', 'furby@indieds.com', '', '', '2012-12-18 17:24:32', '2012-12-18 17:24:32', 'on', '', '', 0),
(4, 'jorge', 'e10adc3949ba59abbe56e057f20f883e', 'jorge', 'palomera', 'jorge@gmail.com', '', '', '2012-12-18 17:40:33', '2012-12-18 17:40:33', 'on', '', '', 0),
(5, 'jjdd', 'e10adc3949ba59abbe56e057f20f883e', 'jaja', 'jsjs', 'jsjs@dd.com', '', '', '2012-12-18 17:43:28', '2012-12-18 17:43:28', 'on', '', '', 0),
(6, 'dasd', 'e10adc3949ba59abbe56e057f20f883e', 'dd', 'daa', 'aasd@dds.ss', '', '', '2012-12-18 17:45:45', '2012-12-18 17:45:45', 'on', '', '', 0),
(7, 'ddd', 'e10adc3949ba59abbe56e057f20f883e', 'asd', 'dd', 'ssas@dd.dd', '', '', '2012-12-18 18:51:10', '2012-12-18 18:51:10', 'on', '', '', 0),
(8, 'pepe', '', 'pepe 12', 'perez', 'pepe@gmail.com', '', '', '2012-12-18 18:53:37', '2012-12-18 18:53:57', 'on', '', '', 0),
(9, 'jorge1', '', 'jorge asdasd', 'nava', 'jorge1@gmail.com', '', '', '2012-12-18 19:18:16', '2012-12-18 19:18:34', 'on', '', '', 0),
(10, '', '', 'Juancho', 'Perez', 'pruebadd@hotmail.com', '', '', '2012-12-24 12:54:48', '2012-12-24 12:54:48', 'on', '100002277694498', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id_faq` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(200) NOT NULL,
  `response` text NOT NULL,
  `tags` varchar(200) NOT NULL,
  `popular` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:popular, 0:no popular',
  PRIMARY KEY (`id_faq`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `faqs`
--

INSERT INTO `faqs` (`id_faq`, `question`, `response`, `tags`, `popular`) VALUES
(1, 'Métodos de envío', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', '', 0),
(2, 'Tamaños y medidas del Photobook', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', '', 1),
(3, 'Fotografías', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', '', 1),
(4, 'Envíos a toda la república', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', '', 0),
(5, 'Métodos de Pago', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frames`
--

DROP TABLE IF EXISTS `frames`;
CREATE TABLE IF NOT EXISTS `frames` (
  `id_frame` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url_preview` varchar(150) NOT NULL,
  PRIMARY KEY (`id_frame`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `frames`
--

INSERT INTO `frames` (`id_frame`, `name`, `url_preview`) VALUES
(1, 'Borde 1', 'application/yuppics/frames/preview/frame1.png'),
(2, 'Border 2', 'application/yuppics/frames/preview/frame2.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `frames_imgs`
--

DROP TABLE IF EXISTS `frames_imgs`;
CREATE TABLE IF NOT EXISTS `frames_imgs` (
  `id_frame` int(10) unsigned NOT NULL,
  `id_img` int(10) unsigned NOT NULL,
  `url_frame` varchar(150) NOT NULL,
  PRIMARY KEY (`id_frame`,`id_img`),
  KEY `id_img` (`id_img`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `frames_imgs`
--

INSERT INTO `frames_imgs` (`id_frame`, `id_img`, `url_frame`) VALUES
(1, 1, 'application/yuppics/frames/frame11.png'),
(1, 2, 'application/yuppics/frames/frame12.png'),
(2, 1, 'application/yuppics/frames/frame21.png'),
(2, 2, 'application/yuppics/frames/frame22.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id_order` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_customer` bigint(20) unsigned NOT NULL,
  `id_address_billing` bigint(20) unsigned NOT NULL,
  `id_address_shipping` bigint(20) unsigned NOT NULL,
  `total_shipping` double NOT NULL,
  `total_discount` double NOT NULL,
  `total` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('p','a','e','c') NOT NULL COMMENT 'p:pendiente, a:autorizado, e:enviado, c:cancelado',
  `comment` varchar(200) NOT NULL,
  `guide_num` varchar(50) NOT NULL DEFAULT '' COMMENT 'guia de envio',
  PRIMARY KEY (`id_order`),
  KEY `id_customer` (`id_customer`),
  KEY `id_address_billing` (`id_address_billing`),
  KEY `id_address_shipping` (`id_address_shipping`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Volcar la base de datos para la tabla `orders`
--

INSERT INTO `orders` (`id_order`, `id_customer`, `id_address_billing`, `id_address_shipping`, `total_shipping`, `total_discount`, `total`, `created`, `updated`, `status`, `comment`, `guide_num`) VALUES
(2, 2, 1, 1, 0, 0, 100, '2013-01-08 15:01:12', '0000-00-00 00:00:00', 'p', '', ''),
(3, 2, 2, 2, 0, 100, 100, '2013-03-18 19:03:59', '2013-03-18 18:59:09', 'p', '', ''),
(4, 2, 2, 2, 0, 100, 100, '2013-03-18 20:03:47', '2013-03-18 19:47:28', 'p', '', ''),
(5, 2, 2, 2, 0, 100, 100, '2013-03-18 20:03:51', '2013-03-18 19:51:55', 'p', '', ''),
(6, 2, 2, 2, 0, 100, 100, '2013-03-18 20:03:58', '2013-03-18 19:58:13', 'p', '', ''),
(22, 2, 2, 2, 0, 0, 300, '2013-04-19 10:04:19', '2013-04-19 10:19:12', 'p', '', ''),
(23, 2, 2, 2, 0, 0, 300, '2013-04-19 10:04:47', '2013-04-19 10:47:52', 'p', '', ''),
(24, 2, 2, 2, 0, 120, 180, '2013-04-19 10:04:48', '2013-04-19 10:48:30', 'p', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders_yuppics`
--

DROP TABLE IF EXISTS `orders_yuppics`;
CREATE TABLE IF NOT EXISTS `orders_yuppics` (
  `id_order` bigint(20) unsigned NOT NULL,
  `id_yuppics` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unitary_price` double unsigned NOT NULL,
  PRIMARY KEY (`id_order`,`id_yuppics`),
  KEY `id_yuppics` (`id_yuppics`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `orders_yuppics`
--

INSERT INTO `orders_yuppics` (`id_order`, `id_yuppics`, `quantity`, `unitary_price`) VALUES
(2, 3, 1, 100),
(3, 2, 0, 0),
(4, 2, 0, 0),
(5, 2, 0, 0),
(6, 2, 0, 0),
(22, 5, 3, 100),
(23, 5, 3, 100),
(24, 5, 3, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `products`
--

INSERT INTO `products` (`id_product`, `name`, `price`) VALUES
(1, 'yuppics', 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE IF NOT EXISTS `states` (
  `id_state` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_country` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_state`),
  KEY `id_country` (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `states`
--

INSERT INTO `states` (`id_state`, `id_country`, `name`) VALUES
(1, 1, 'Jalisco'),
(2, 1, 'Colima'),
(4, 1, 'Monterrey');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id_tag` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id_tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcar la base de datos para la tabla `tags`
--

INSERT INTO `tags` (`id_tag`, `name`) VALUES
(1, 'Hortensias'),
(2, 'verde'),
(3, 'blanco'),
(4, 'azul'),
(5, 'mar'),
(6, 'Medusa'),
(7, 'Desierto'),
(8, 'arena'),
(9, 'montañas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `themes`
--

DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `id_theme` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `background_img` varchar(150) NOT NULL,
  `background_color` varchar(10) NOT NULL,
  `text_color` varchar(10) NOT NULL,
  `autor` varchar(80) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_theme`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `themes`
--

INSERT INTO `themes` (`id_theme`, `name`, `background_img`, `background_color`, `text_color`, `autor`, `status`) VALUES
(1, 'Tema 1', 'application/yuppics/themes/026e7ec31302068a4e76bfffee7d544b.jpg', '#EBEBEB', '#FF2020', 'Johnson&Johnson', 1),
(2, 'Tema 2', 'application/yuppics/themes/b0cba5bce1a7578a8ab5be0dab7f6a0a.jpg', '#FF9326', '#FEFEDC', 'El camino', 1),
(3, 'Tema 3', 'application/yuppics/themes/b3b6678e5fa11dfe1c8341c8bed1fe77.jpg', '#04FFFF', '#0404FE', 'El pepe', 1),
(4, 'Tema 4', 'application/yuppics/themes/de0bf9af33c8d1c0d0070bc579b16c5b.jpg', '#FE0404', '#FFB6B6', 'Johnson&Johnson', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `themes_tags`
--

DROP TABLE IF EXISTS `themes_tags`;
CREATE TABLE IF NOT EXISTS `themes_tags` (
  `id_tag` bigint(20) unsigned NOT NULL,
  `id_theme` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_tag`,`id_theme`),
  KEY `id_theme` (`id_theme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `themes_tags`
--

INSERT INTO `themes_tags` (`id_tag`, `id_theme`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 2),
(6, 2),
(4, 3),
(7, 3),
(8, 3),
(9, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id_voucher` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `code` varchar(35) NOT NULL,
  `amount` double NOT NULL,
  `uses_total` int(11) NOT NULL,
  `date_start` timestamp NULL DEFAULT NULL,
  `date_end` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_voucher`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vouchers`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vouchers_history`
--

DROP TABLE IF EXISTS `vouchers_history`;
CREATE TABLE IF NOT EXISTS `vouchers_history` (
  `id_history` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_voucher` bigint(20) unsigned NOT NULL,
  `id_customer` bigint(20) unsigned NOT NULL,
  `id_order` bigint(20) unsigned NOT NULL,
  `amount` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_history`),
  KEY `id_voucher` (`id_voucher`),
  KEY `id_customer` (`id_customer`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vouchers_history`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yuppics`
--

DROP TABLE IF EXISTS `yuppics`;
CREATE TABLE IF NOT EXISTS `yuppics` (
  `id_yuppic` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_customer` bigint(20) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `author` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comprado` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:no comprado, 1:comprado',
  PRIMARY KEY (`id_yuppic`),
  KEY `id_customer` (`id_customer`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `yuppics`
--

INSERT INTO `yuppics` (`id_yuppic`, `id_customer`, `id_product`, `title`, `author`, `quantity`, `created`, `comprado`) VALUES
(1, 2, 1, 'Calando tema yuppic', ':) :)', 1, '2012-01-01 00:00:00', 1),
(2, 2, 1, 'Dias grandes', 'dda', 1, '2013-01-04 00:00:00', 1),
(3, 2, 1, 'asd', 'asdasd', 1, '2013-01-10 00:00:00', 1),
(4, 2, 1, 'dddas', 'Autor de Yuppic', 1, '2013-04-16 14:26:09', 0),
(5, 2, 1, 'Calando titulo', 'Autor mio', 3, '2013-04-18 23:25:20', 1),
(6, 2, 1, '—— TÍTULO ——', 'Autor de Yuppic', 1, '2013-04-19 10:53:36', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yuppics_pages`
--

DROP TABLE IF EXISTS `yuppics_pages`;
CREATE TABLE IF NOT EXISTS `yuppics_pages` (
  `id_ypage` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_yuppic` bigint(20) unsigned NOT NULL,
  `id_page` int(10) unsigned NOT NULL,
  `num_pag` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ypage`),
  KEY `id_yuppic` (`id_yuppic`),
  KEY `id_page` (`id_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Volcar la base de datos para la tabla `yuppics_pages`
--

INSERT INTO `yuppics_pages` (`id_ypage`, `id_yuppic`, `id_page`, `num_pag`) VALUES
(5, 2, 1, 1),
(6, 2, 1, 2),
(7, 2, 1, 3),
(8, 2, 1, 4),
(9, 2, 1, 5),
(21, 3, 1, 1),
(24, 1, 1, 1),
(25, 1, 1, 2),
(26, 1, 1, 3),
(27, 1, 1, 4),
(28, 1, 1, 5),
(29, 1, 1, 6),
(30, 1, 1, 7),
(31, 1, 1, 8),
(32, 1, 1, 9),
(33, 1, 1, 10),
(34, 1, 1, 11),
(35, 1, 1, 12),
(36, 2, 1, 6),
(38, 5, 1, 1),
(39, 5, 1, 2),
(40, 5, 1, 3),
(42, 6, 1, 1),
(43, 6, 1, 2),
(44, 6, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yuppics_pages_photos`
--

DROP TABLE IF EXISTS `yuppics_pages_photos`;
CREATE TABLE IF NOT EXISTS `yuppics_pages_photos` (
  `id_ypage` bigint(20) unsigned NOT NULL,
  `id_photo` bigint(20) unsigned DEFAULT NULL,
  `id_page_img` int(10) unsigned NOT NULL,
  `id_frame` int(10) unsigned DEFAULT NULL,
  `coord_x` float NOT NULL DEFAULT '0',
  `coord_y` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ypage`,`id_page_img`),
  KEY `id_photo` (`id_photo`),
  KEY `id_page_img` (`id_page_img`),
  KEY `id_frame` (`id_frame`),
  KEY `id_ypage` (`id_ypage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `yuppics_pages_photos`
--

INSERT INTO `yuppics_pages_photos` (`id_ypage`, `id_photo`, `id_page_img`, `id_frame`, `coord_x`, `coord_y`) VALUES
(5, 3, 1, 2, 0, 0),
(5, 2, 2, 2, 0, 0),
(6, 3, 1, 1, 0, 0),
(6, 4, 2, 2, 0, 0),
(7, 5, 1, 1, 0, 0),
(7, 6, 2, 2, 0, 0),
(8, 7, 1, 1, 0, 0),
(8, 8, 2, 2, 0, 0),
(9, 9, 1, 1, -25.67, 0),
(9, 10, 2, 2, 0, 0),
(21, 12, 1, 1, 0, 0),
(21, 13, 2, 2, 0, 0),
(24, 18, 1, 2, -12.63, 0.53),
(24, 19, 2, 2, -13.11, -0.54),
(25, 20, 1, NULL, -7.77, 1.59),
(25, 21, 2, NULL, -7.77, 0),
(26, 22, 1, NULL, 0, 0),
(26, 23, 2, NULL, 0, 0),
(27, 24, 1, NULL, 0, 0),
(27, 25, 2, NULL, 0, 0),
(28, 26, 1, NULL, 0, 0),
(28, 27, 2, NULL, 0, 0),
(29, 28, 1, NULL, 0, 0),
(29, 29, 2, NULL, 0, 0),
(30, 30, 1, NULL, 0, 0),
(30, 31, 2, NULL, 0, 0),
(31, 32, 1, NULL, 0, 0),
(31, 33, 2, NULL, 0, 0),
(32, 34, 1, NULL, 0, 0),
(32, 35, 2, NULL, 0, 0),
(33, 36, 1, NULL, 0, 0),
(33, 37, 2, NULL, 0, 0),
(34, 38, 1, NULL, 0, 0),
(34, 39, 2, NULL, 0, 0),
(35, 40, 1, NULL, 0, 0),
(36, 11, 1, NULL, 0, 0),
(36, 59, 2, 1, 0, 0),
(38, 60, 1, NULL, 0, 0),
(38, 61, 2, NULL, 0, 0),
(39, 62, 1, NULL, 0, 0),
(39, 63, 2, NULL, 0, 0),
(40, 64, 1, NULL, 0, 0),
(40, 65, 2, NULL, 0, 0),
(42, 66, 1, NULL, 0, 0),
(42, 67, 2, NULL, 0, 0),
(43, 68, 1, NULL, 0, 0),
(43, 69, 2, NULL, 0, 0),
(44, 70, 1, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yuppics_photos`
--

DROP TABLE IF EXISTS `yuppics_photos`;
CREATE TABLE IF NOT EXISTS `yuppics_photos` (
  `id_photo` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_yuppic` bigint(20) unsigned NOT NULL,
  `url_img` varchar(150) NOT NULL,
  `url_thumb` varchar(150) NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `id_yuppic` (`id_yuppic`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

--
-- Volcar la base de datos para la tabla `yuppics_photos`
--

INSERT INTO `yuppics_photos` (`id_photo`, `id_yuppic`, `url_img`, `url_thumb`) VALUES
(2, 2, 'application/yuppics/2/2/PHOTOS/24411_10150142513095387_4105557_n.jpg', 'application/yuppics/2/2/PHOTOS/24411_10150142513095387_4105557_n_thumb.jpg'),
(3, 2, 'application/yuppics/2/2/PHOTOS/26181_10150173111215297_1330523_n.jpg', 'application/yuppics/2/2/PHOTOS/26181_10150173111215297_1330523_n_thumb.jpg'),
(4, 2, 'application/yuppics/2/2/PHOTOS/26181_10150173143850297_6332664_n.jpg', 'application/yuppics/2/2/PHOTOS/26181_10150173143850297_6332664_n_thumb.jpg'),
(5, 2, 'application/yuppics/2/2/PHOTOS/8834_151778146825_1997369_n.jpg', 'application/yuppics/2/2/PHOTOS/8834_151778146825_1997369_n_thumb.jpg'),
(6, 2, 'application/yuppics/2/2/PHOTOS/23682_1400944146295_7005143_n.jpg', 'application/yuppics/2/2/PHOTOS/23682_1400944146295_7005143_n_thumb.jpg'),
(7, 2, 'application/yuppics/2/2/PHOTOS/26181_10150173111330297_2723785_n.jpg', 'application/yuppics/2/2/PHOTOS/26181_10150173111330297_2723785_n_thumb.jpg'),
(8, 2, 'application/yuppics/2/2/PHOTOS/8834_151778106825_7096319_n.jpg', 'application/yuppics/2/2/PHOTOS/8834_151778106825_7096319_n_thumb.jpg'),
(9, 2, 'application/yuppics/2/2/PHOTOS/36758_128223220541284_5567661_n.jpg', 'application/yuppics/2/2/PHOTOS/36758_128223220541284_5567661_n_thumb.jpg'),
(10, 2, 'application/yuppics/2/2/PHOTOS/180755_10150126899645676_7903779_n.jpg', 'application/yuppics/2/2/PHOTOS/180755_10150126899645676_7903779_n_thumb.jpg'),
(11, 2, 'application/yuppics/2/2/PHOTOS/66329_434839067260_1186517_n.jpg', 'application/yuppics/2/2/PHOTOS/66329_434839067260_1186517_n_thumb.jpg'),
(12, 3, 'application/yuppics/2/3/PHOTOS/582420_4073427797661_1898705540_n.jpg', 'application/yuppics/2/3/PHOTOS/582420_4073427797661_1898705540_n_thumb.jpg'),
(13, 3, 'application/yuppics/2/3/PHOTOS/155800_172368772796460_2914455_n.jpg', 'application/yuppics/2/3/PHOTOS/155800_172368772796460_2914455_n_thumb.jpg'),
(14, 3, 'application/yuppics/2/3/PHOTOS/66329_434839067260_1186517_n.jpg', 'application/yuppics/2/3/PHOTOS/66329_434839067260_1186517_n_thumb.jpg'),
(15, 3, 'application/yuppics/2/3/PHOTOS/257997_10150270828420676_832804_o.jpg', 'application/yuppics/2/3/PHOTOS/257997_10150270828420676_832804_o_thumb.jpg'),
(16, 3, 'application/yuppics/2/3/PHOTOS/195177_10150156574170676_6649300_o.jpg', 'application/yuppics/2/3/PHOTOS/195177_10150156574170676_6649300_o_thumb.jpg'),
(17, 3, 'application/yuppics/2/3/PHOTOS/36758_128223220541284_5567661_n.jpg', 'application/yuppics/2/3/PHOTOS/36758_128223220541284_5567661_n_thumb.jpg'),
(18, 1, 'application/yuppics/2/1/PHOTOS/154238_172368889463115_2941475_n.jpg', 'application/yuppics/2/1/PHOTOS/154238_172368889463115_2941475_n_thumb.jpg'),
(19, 1, 'application/yuppics/2/1/PHOTOS/164045_172368789463125_2665847_n.jpg', 'application/yuppics/2/1/PHOTOS/164045_172368789463125_2665847_n_thumb.jpg'),
(20, 1, 'application/yuppics/2/1/PHOTOS/36758_128223220541284_5567661_n.jpg', 'application/yuppics/2/1/PHOTOS/36758_128223220541284_5567661_n_thumb.jpg'),
(21, 1, 'application/yuppics/2/1/PHOTOS/155800_172368772796460_2914455_n.jpg', 'application/yuppics/2/1/PHOTOS/155800_172368772796460_2914455_n_thumb.jpg'),
(22, 1, 'application/yuppics/2/1/PHOTOS/582420_4073427797661_1898705540_n.jpg', 'application/yuppics/2/1/PHOTOS/582420_4073427797661_1898705540_n_thumb.jpg'),
(23, 1, 'application/yuppics/2/1/PHOTOS/257997_10150270828420676_832804_o.jpg', 'application/yuppics/2/1/PHOTOS/257997_10150270828420676_832804_o_thumb.jpg'),
(24, 1, 'application/yuppics/2/1/PHOTOS/195177_10150156574170676_6649300_o.jpg', 'application/yuppics/2/1/PHOTOS/195177_10150156574170676_6649300_o_thumb.jpg'),
(25, 1, 'application/yuppics/2/1/PHOTOS/180755_10150126899645676_7903779_n.jpg', 'application/yuppics/2/1/PHOTOS/180755_10150126899645676_7903779_n_thumb.jpg'),
(26, 1, 'application/yuppics/2/1/PHOTOS/154238_172368889463115_2941475_n.jpg', 'application/yuppics/2/1/PHOTOS/154238_172368889463115_2941475_n_thumb.jpg'),
(27, 1, 'application/yuppics/2/1/PHOTOS/164045_172368789463125_2665847_n.jpg', 'application/yuppics/2/1/PHOTOS/164045_172368789463125_2665847_n_thumb.jpg'),
(28, 1, 'application/yuppics/2/1/PHOTOS/155800_172368772796460_2914455_n.jpg', 'application/yuppics/2/1/PHOTOS/155800_172368772796460_2914455_n_thumb.jpg'),
(29, 1, 'application/yuppics/2/1/PHOTOS/66329_434839067260_1186517_n.jpg', 'application/yuppics/2/1/PHOTOS/66329_434839067260_1186517_n_thumb.jpg'),
(30, 1, 'application/yuppics/2/1/PHOTOS/36758_128223220541284_5567661_n.jpg', 'application/yuppics/2/1/PHOTOS/36758_128223220541284_5567661_n_thumb.jpg'),
(31, 1, 'application/yuppics/2/1/PHOTOS/28662_419080565675_2318723_n.jpg', 'application/yuppics/2/1/PHOTOS/28662_419080565675_2318723_n_thumb.jpg'),
(32, 1, 'application/yuppics/2/1/PHOTOS/29243_1429123523990_736814_n.jpg', 'application/yuppics/2/1/PHOTOS/29243_1429123523990_736814_n_thumb.jpg'),
(33, 1, 'application/yuppics/2/1/PHOTOS/24411_10150142513030387_5404514_n.jpg', 'application/yuppics/2/1/PHOTOS/24411_10150142513030387_5404514_n_thumb.jpg'),
(34, 1, 'application/yuppics/2/1/PHOTOS/24411_10150142513095387_4105557_n.jpg', 'application/yuppics/2/1/PHOTOS/24411_10150142513095387_4105557_n_thumb.jpg'),
(35, 1, 'application/yuppics/2/1/PHOTOS/23682_1400944146295_7005143_n.jpg', 'application/yuppics/2/1/PHOTOS/23682_1400944146295_7005143_n_thumb.jpg'),
(36, 1, 'application/yuppics/2/1/PHOTOS/26181_10150173143850297_6332664_n.jpg', 'application/yuppics/2/1/PHOTOS/26181_10150173143850297_6332664_n_thumb.jpg'),
(37, 1, 'application/yuppics/2/1/PHOTOS/26181_10150173111330297_2723785_n.jpg', 'application/yuppics/2/1/PHOTOS/26181_10150173111330297_2723785_n_thumb.jpg'),
(38, 1, 'application/yuppics/2/1/PHOTOS/26181_10150173111215297_1330523_n.jpg', 'application/yuppics/2/1/PHOTOS/26181_10150173111215297_1330523_n_thumb.jpg'),
(39, 1, 'application/yuppics/2/1/PHOTOS/8834_151778066825_6353724_n.jpg', 'application/yuppics/2/1/PHOTOS/8834_151778066825_6353724_n_thumb.jpg'),
(40, 1, 'application/yuppics/2/1/PHOTOS/8834_151778106825_7096319_n.jpg', 'application/yuppics/2/1/PHOTOS/8834_151778106825_7096319_n_thumb.jpg'),
(55, 1, 'application/yuppics/2/1/PHOTOS/17058_101084536590819_5471876_n.jpg', 'application/yuppics/2/1/PHOTOS/17058_101084536590819_5471876_n_thumb.jpg'),
(56, 1, 'application/yuppics/2/1/PHOTOS/18058_100355766663696_5682913_n.jpg', 'application/yuppics/2/1/PHOTOS/18058_100355766663696_5682913_n_thumb.jpg'),
(57, 1, 'application/yuppics/2/1/PHOTOS/17058_101084446590828_6342504_n.jpg', 'application/yuppics/2/1/PHOTOS/17058_101084446590828_6342504_n_thumb.jpg'),
(58, 2, 'application/yuppics/2/2/PHOTOS/582420_4073427797661_1898705540_n.jpg', 'application/yuppics/2/2/PHOTOS/582420_4073427797661_1898705540_n_thumb.jpg'),
(59, 2, 'application/yuppics/2/2/PHOTOS/195177_10150156574170676_6649300_o.jpg', 'application/yuppics/2/2/PHOTOS/195177_10150156574170676_6649300_o_thumb.jpg'),
(60, 5, 'application/yuppics/2/5/PHOTOS/18058_100355766663696_5682913_n.jpg', 'application/yuppics/2/5/PHOTOS/18058_100355766663696_5682913_n_thumb.jpg'),
(61, 5, 'application/yuppics/2/5/PHOTOS/17058_101084536590819_5471876_n.jpg', 'application/yuppics/2/5/PHOTOS/17058_101084536590819_5471876_n_thumb.jpg'),
(62, 5, 'application/yuppics/2/5/PHOTOS/195177_10150156574170676_6649300_o.jpg', 'application/yuppics/2/5/PHOTOS/195177_10150156574170676_6649300_o_thumb.jpg'),
(63, 5, 'application/yuppics/2/5/PHOTOS/36758_128223220541284_5567661_n.jpg', 'application/yuppics/2/5/PHOTOS/36758_128223220541284_5567661_n_thumb.jpg'),
(64, 5, 'application/yuppics/2/5/PHOTOS/155800_172368772796460_2914455_n.jpg', 'application/yuppics/2/5/PHOTOS/155800_172368772796460_2914455_n_thumb.jpg'),
(65, 5, 'application/yuppics/2/5/PHOTOS/257997_10150270828420676_832804_o.jpg', 'application/yuppics/2/5/PHOTOS/257997_10150270828420676_832804_o_thumb.jpg'),
(66, 6, 'application/yuppics/2/6/PHOTOS/195177_10150156574170676_6649300_o.jpg', 'application/yuppics/2/6/PHOTOS/195177_10150156574170676_6649300_o_thumb.jpg'),
(67, 6, 'application/yuppics/2/6/PHOTOS/24411_10150142513030387_5404514_n.jpg', 'application/yuppics/2/6/PHOTOS/24411_10150142513030387_5404514_n_thumb.jpg'),
(68, 6, 'application/yuppics/2/6/PHOTOS/36758_128223220541284_5567661_n.jpg', 'application/yuppics/2/6/PHOTOS/36758_128223220541284_5567661_n_thumb.jpg'),
(69, 6, 'application/yuppics/2/6/PHOTOS/257997_10150270828420676_832804_o.jpg', 'application/yuppics/2/6/PHOTOS/257997_10150270828420676_832804_o_thumb.jpg'),
(70, 6, 'application/yuppics/2/6/PHOTOS/26181_10150173111215297_1330523_n.jpg', 'application/yuppics/2/6/PHOTOS/26181_10150173111215297_1330523_n_thumb.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `yuppics_theme`
--

DROP TABLE IF EXISTS `yuppics_theme`;
CREATE TABLE IF NOT EXISTS `yuppics_theme` (
  `id_ytheme` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_yuppic` bigint(20) unsigned NOT NULL,
  `background_img` varchar(150) NOT NULL,
  `background_color` varchar(10) NOT NULL,
  `text_color` varchar(10) NOT NULL,
  PRIMARY KEY (`id_ytheme`),
  KEY `id_yuppic` (`id_yuppic`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `yuppics_theme`
--

INSERT INTO `yuppics_theme` (`id_ytheme`, `id_yuppic`, `background_img`, `background_color`, `text_color`) VALUES
(1, 1, 'application/yuppics/2/1/b0cba5bce1a7578a8ab5be0dab7f6a0a.jpg', '#ff9326', '#fefedc'),
(2, 2, 'application/yuppics/2/2/b0cba5bce1a7578a8ab5be0dab7f6a0a.jpg', '#ff9326', '#fefedc'),
(3, 3, 'application/yuppics/2/3/b3b6678e5fa11dfe1c8341c8bed1fe77.jpg', '#04FFFF', '#0404FE'),
(4, 4, 'application/yuppics/2/4/026e7ec31302068a4e76bfffee7d544b.jpg', '#ebebeb', '#ff2020'),
(5, 5, 'application/yuppics/2/5/026e7ec31302068a4e76bfffee7d544b.jpg', '#ebebeb', '#ff2020'),
(6, 6, 'application/yuppics/2/6/b0cba5bce1a7578a8ab5be0dab7f6a0a.jpg', '#ff9326', '#fefedc');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `accomodation_page_imgs`
--
ALTER TABLE `accomodation_page_imgs`
  ADD CONSTRAINT `accomodation_page_imgs_ibfk_1` FOREIGN KEY (`id_page`) REFERENCES `accomodation_page` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accomodation_page_imgs_ibfk_2` FOREIGN KEY (`id_img`) REFERENCES `accomodation_imgs` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `address_book`
--
ALTER TABLE `address_book`
  ADD CONSTRAINT `address_book_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `address_book_ibfk_2` FOREIGN KEY (`id_country`) REFERENCES `countries` (`id_country`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `address_book_ibfk_3` FOREIGN KEY (`id_state`) REFERENCES `states` (`id_state`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `coupons_history`
--
ALTER TABLE `coupons_history`
  ADD CONSTRAINT `coupons_history_ibfk_1` FOREIGN KEY (`id_coupon`) REFERENCES `coupons` (`id_coupon`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupons_history_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coupons_history_ibfk_3` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `customer_promo`
--
ALTER TABLE `customer_promo`
  ADD CONSTRAINT `customer_promo_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_promo_ibfk_2` FOREIGN KEY (`id_coupon`) REFERENCES `coupons` (`id_coupon`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `frames_imgs`
--
ALTER TABLE `frames_imgs`
  ADD CONSTRAINT `frames_imgs_ibfk_1` FOREIGN KEY (`id_img`) REFERENCES `accomodation_imgs` (`id_img`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `frames_imgs_ibfk_2` FOREIGN KEY (`id_frame`) REFERENCES `frames` (`id_frame`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_address_billing`) REFERENCES `address_book` (`id_address`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`id_address_shipping`) REFERENCES `address_book` (`id_address`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `orders_yuppics`
--
ALTER TABLE `orders_yuppics`
  ADD CONSTRAINT `orders_yuppics_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_yuppics_ibfk_2` FOREIGN KEY (`id_yuppics`) REFERENCES `yuppics` (`id_yuppic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `states_ibfk_1` FOREIGN KEY (`id_country`) REFERENCES `countries` (`id_country`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `themes_tags`
--
ALTER TABLE `themes_tags`
  ADD CONSTRAINT `themes_tags_ibfk_1` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id_tag`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `themes_tags_ibfk_2` FOREIGN KEY (`id_theme`) REFERENCES `themes` (`id_theme`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vouchers_history`
--
ALTER TABLE `vouchers_history`
  ADD CONSTRAINT `vouchers_history_ibfk_1` FOREIGN KEY (`id_voucher`) REFERENCES `vouchers` (`id_voucher`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vouchers_history_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vouchers_history_ibfk_3` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `yuppics`
--
ALTER TABLE `yuppics`
  ADD CONSTRAINT `yuppics_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yuppics_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `yuppics_pages`
--
ALTER TABLE `yuppics_pages`
  ADD CONSTRAINT `yuppics_pages_ibfk_1` FOREIGN KEY (`id_yuppic`) REFERENCES `yuppics` (`id_yuppic`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yuppics_pages_ibfk_2` FOREIGN KEY (`id_page`) REFERENCES `accomodation_page` (`id_page`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `yuppics_pages_photos`
--
ALTER TABLE `yuppics_pages_photos`
  ADD CONSTRAINT `yuppics_pages_photos_ibfk_2` FOREIGN KEY (`id_page_img`) REFERENCES `accomodation_page_imgs` (`id_page_img`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yuppics_pages_photos_ibfk_4` FOREIGN KEY (`id_ypage`) REFERENCES `yuppics_pages` (`id_ypage`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `yuppics_pages_photos_ibfk_5` FOREIGN KEY (`id_photo`) REFERENCES `yuppics_photos` (`id_photo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `yuppics_pages_photos_ibfk_6` FOREIGN KEY (`id_frame`) REFERENCES `frames` (`id_frame`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `yuppics_photos`
--
ALTER TABLE `yuppics_photos`
  ADD CONSTRAINT `yuppics_photos_ibfk_1` FOREIGN KEY (`id_yuppic`) REFERENCES `yuppics` (`id_yuppic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `yuppics_theme`
--
ALTER TABLE `yuppics_theme`
  ADD CONSTRAINT `yuppics_theme_ibfk_1` FOREIGN KEY (`id_yuppic`) REFERENCES `yuppics` (`id_yuppic`) ON DELETE CASCADE ON UPDATE CASCADE;
