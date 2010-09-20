-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 18, 2010 at 12:38 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `printmaster`
--

-- --------------------------------------------------------

--
-- Table structure for table `consumables`
--

CREATE TABLE IF NOT EXISTS `consumables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `qty` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Quantity of consumable in stock',
  `col_c` tinyint(4) DEFAULT NULL COMMENT '1 if consumable is of colour ''Cyan''',
  `col_y` tinyint(4) DEFAULT NULL COMMENT '1 if consumable is of colour ''Yellow''',
  `col_m` tinyint(4) DEFAULT NULL COMMENT '1 if consumable is of colour ''Magenta''',
  `col_k` tinyint(4) DEFAULT NULL COMMENT '1 if consumable is of colour ''Black''',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details about consumables and their stock';

-- --------------------------------------------------------

--
-- Table structure for table `consumables_models`
--

CREATE TABLE IF NOT EXISTS `consumables_models` (
  `consumable_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`consumable_id`,`model_id`),
  KEY `printermodel` (`model_id`),
  KEY `consumable` (`consumable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Link table to map consumables to printer models';

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `printer_id` int(10) unsigned NOT NULL COMMENT 'Printer ID installed in to',
  `consumable_id` int(10) unsigned NOT NULL COMMENT 'Consumable ID installed',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `printerhistory` (`printer_id`),
  KEY `consumablehistory` (`consumable_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Audit history of consumable installation into printers';

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE IF NOT EXISTS `manufacturers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of printer manufacturer',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details of printer manufacturers';

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manufacturer_id` int(10) unsigned NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of printer model',
  `colour` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Colour (1) or mono (0)',
  PRIMARY KEY (`id`),
  KEY `modelmanufacturers` (`manufacturer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Details of printer models';

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

CREATE TABLE IF NOT EXISTS `printers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` int(10) unsigned NOT NULL COMMENT 'Type of printer',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Printer name',
  `location` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Location/room where printer is',
  `department` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Department responsible for printer',
  `ipaddress` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Network address of printer',
  `server` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Print server installed on',
  `serial` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Printer serial number',
  `notes` longtext COLLATE utf8_unicode_ci COMMENT 'Additional notes',
  PRIMARY KEY (`id`),
  KEY `printmodels` (`model_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Information about printers';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consumables_models`
--
ALTER TABLE `consumables_models`
  ADD CONSTRAINT `consumables_models_ibfk_3` FOREIGN KEY (`consumable_id`) REFERENCES `consumables` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `consumables_models_ibfk_4` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`printer_id`) REFERENCES `printers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `events_ibfk_4` FOREIGN KEY (`consumable_id`) REFERENCES `consumables` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturers` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `printers`
--
ALTER TABLE `printers`
  ADD CONSTRAINT `printers_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON UPDATE NO ACTION;
