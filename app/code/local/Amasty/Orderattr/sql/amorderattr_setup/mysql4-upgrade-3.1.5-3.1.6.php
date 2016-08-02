<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `{$this->getTable('eav/attribute')}` ADD `required_on_front_only` TINYINT( 1 ) UNSIGNED NOT NULL ;
");

$installer->run("
	ALTER TABLE `{$this->getTable('amorderattr/order_attribute')}`
		ADD KEY `IDX_AMASTY_ORDER_ATTRIBUTES_ORDERS` (`order_id`);
	ALTER TABLE `{$this->getTable('amorderattr/order_attribute')}`
		ADD CONSTRAINT `FK_AMASTY_ORDER_ATTRIBUTES_ORDERS` FOREIGN KEY (`order_id`) REFERENCES `{$this->getTable('sales/order')}` (`entity_id`) ON DELETE CASCADE;
");

$installer->endSetup();