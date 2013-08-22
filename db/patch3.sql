ALTER TABLE `printers` ADD `cost` float(6,2) unsigned NULL COMMENT 'Purchase cost';

ALTER TABLE `printers` ADD `purchase_date` date NULL COMMENT 'Purchase date';

INSERT INTO patch_history SET num = 3;