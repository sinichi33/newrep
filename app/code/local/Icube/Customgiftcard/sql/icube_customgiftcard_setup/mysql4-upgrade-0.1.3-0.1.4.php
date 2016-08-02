<?php
$installer = $this;
$installer->startSetup();

$sql=<<<SQLTEXT
	ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}` 
	CHANGE `promo_items` `promo_items` SMALLINT(6) NOT NULL DEFAULT '1',
	CHANGE `products_id` `product_skus` TEXT NULL COMMENT 'separated by coma' , 
	CHANGE `categories_id` `category_ids` TEXT NULL COMMENT 'separated by coma' ;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 