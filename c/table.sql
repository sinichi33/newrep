CREATE TABLE `customer_token` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenId` VARCHAR(100) NOT NULL,
  `customer_group_id` SMALLINT(5) NOT NULL,
  `entity_id` INT(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

ALTER TABLE `customer_token` CHANGE `customer_group_id` `customer_group_id` SMALLINT(5) NULL , CHANGE `entity_id` `entity_id` INT(10) NULL ;
ALTER TABLE `customer_token` ADD UNIQUE `FKtokenId` (`tokenId`);
ALTER TABLE `customer_token` ADD COLUMN `tokenType` SMALLINT(1) DEFAULT '0' NOT NULL COMMENT '0=generic,1=unique' AFTER `customer_group_id`;
ALTER TABLE `customer_token` ADD COLUMN `email_sent` SMALLINT(1) DEFAULT '0' NOT NULL AFTER `entity_id`;

CREATE TABLE `customer_token_history` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenId` VARCHAR(100) NOT NULL,
  `ip` VARCHAR(50) DEFAULT NULL,
  `visitDate` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

