ALTER TABLE `consumables` ADD `cost` float(4,2) unsigned NULL AFTER `qty`;

ALTER TABLE `events` ADD `cost` float(4,2) unsigned NULL AFTER `consumable_id`;

INSERT INTO patch_history SET num = 1;