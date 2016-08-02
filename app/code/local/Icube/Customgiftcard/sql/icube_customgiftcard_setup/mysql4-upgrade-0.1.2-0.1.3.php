<?php
$installer = $this;
$installer->startSetup();

$sql=<<<SQLTEXT
	ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}` 
	ADD `promo_items` SMALLINT(6) NOT NULL DEFAULT '0', 
	ADD `products_id` TEXT NULL COMMENT 'separated by coma' , 
	ADD `categories_id` TEXT NULL COMMENT 'separated by coma' , 
	ADD `min_purchase_value` DECIMAL(17,4) NOT NULL DEFAULT '0.0000' ,
	ADD `valid_from_date` DATE NULL , 
	ADD `valid_to_date` DATE NULL ;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 