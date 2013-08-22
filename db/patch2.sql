ALTER TABLE `consumables`CHANGE `cost` `cost` float(6,2) unsigned NULL AFTER `qty`;

ALTER TABLE `events` CHANGE `cost` `cost` float(6,2) unsigned NULL AFTER `consumable_id`;

INSERT INTO patch_history SET num = 2;