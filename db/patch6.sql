CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(128) NOT NULL,
  `type` char(20) NOT NULL
) COMMENT='' ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `tags` ADD INDEX `id_type` (`id`, `type`);

CREATE TABLE `consumables_tags` (
  `consumable_id` int(10) unsigned NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  FOREIGN KEY (`consumable_id`) REFERENCES `consumables` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) COMMENT='' ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';

ALTER TABLE `consumables_tags` ADD PRIMARY KEY `consumable_id_tag_id` (`consumable_id`, `tag_id`);

INSERT INTO `tags` (`id`, `title`, `type`) VALUES
(1,	'OEM',	'consumable_type'),
(2,	'Remanufactured',	'consumable_type'),
(3,	'Compatible',	'consumable_type');
(4,	'Speciality',	'consumable_type');