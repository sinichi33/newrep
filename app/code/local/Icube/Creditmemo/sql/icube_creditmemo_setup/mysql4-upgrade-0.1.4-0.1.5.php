<?php
$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE sales_flat_creditmemo_item ADD COLUMN handling_fee_item decimal(12,4) DEFAULT NULL;");

$installer->endSetup();