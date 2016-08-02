<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `{$this->getTable('enterprise_giftcardaccount')}` MODIFY COLUMN type VARCHAR(255) NOT NULL DEFAULT 'general';
SQLTEXT;

$installer->run($sql);

$installer->endSetup();
