<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount_pool')}`
ADD COLUMN `campaign_name` varchar(100) AFTER `status`,
ADD UNIQUE `idx_gc_pool_code` USING HASH (`code`) comment '';
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
