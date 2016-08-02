<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}` ADD `company_id` VARCHAR(50) NULL ;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
	 