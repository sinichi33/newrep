<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}`
ADD COLUMN `bin_type` varchar(10) AFTER `campaign_name`;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
