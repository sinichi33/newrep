<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}`
ADD COLUMN `restrict_combine` tinyint AFTER `valid_to_date`,
ADD COLUMN `campaign_name` varchar(255) AFTER `restrict_combine`;
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
