ALTER TABLE `tags` CHANGE `type` `type` varchar(20) COLLATE 'utf8_unicode_ci' NOT NULL AFTER `title`, COMMENT='';

CREATE TABLE `printers_tags` (
  `printer_id` int(10) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`printer_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `printers_tags_ibfk_1` FOREIGN KEY (`printer_id`) REFERENCES `printers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `printers_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `tags` ADD `colour` char(6) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'C7D2E1', COMMENT='';