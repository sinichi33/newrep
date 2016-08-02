<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}` ADD `customer_id` INT(10) NULL , ADD `type` VARCHAR(255) NOT NULL ;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 