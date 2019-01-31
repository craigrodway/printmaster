CREATE TABLE `orders` (
  `id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_date` date NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `item_qty` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  FOREIGN KEY (`item_id`) REFERENCES `consumables` (`id`) ON DELETE CASCADE
) COMMENT='' ENGINE='InnoDB' COLLATE 'utf8_unicode_ci';