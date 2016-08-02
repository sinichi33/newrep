<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}`
ADD COLUMN `product_skus_exclusion` text AFTER `product_skus`,
ADD COLUMN `category_ids_exclusion` text AFTER `category_ids`;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
