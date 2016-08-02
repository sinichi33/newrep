<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

$installer = $this;

$installer->startSetup();

$installer->run("
	CREATE TABLE `{$this->getTable('amorderattr/shipping_methods')}` (
	  `entity_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	  `attribute_id` SMALLINT(5) UNSIGNED NOT NULL,
	  `shipping_method` VARCHAR(255) DEFAULT NULL COMMENT 'Shipping Method',
	  PRIMARY KEY (`entity_id`),
	  KEY `IDX_AMASTY_OA_ORDER_ATTRIBUTES` (`attribute_id`)
	) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
");
	
$installer->run("
	ALTER TABLE `{$this->getTable('amorderattr/shipping_methods')}`
		ADD CONSTRAINT `FK_AMASTY_OA_ORDER_ATTRIBUTES` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav/attribute')}` (`attribute_id`) ON DELETE CASCADE;
");

$installer->endSetup();