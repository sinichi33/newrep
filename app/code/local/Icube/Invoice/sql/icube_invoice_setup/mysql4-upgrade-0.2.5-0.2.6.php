<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_quote_item ADD COLUMN handling_fee_item decimal(12,4) DEFAULT NULL;
	ALTER TABLE sales_flat_order_item ADD COLUMN handling_fee_item decimal(12,4) DEFAULT NULL;
    ALTER TABLE sales_flat_invoice_item ADD COLUMN handling_fee_item decimal(12,4) DEFAULT NULL;
    ");

$installer->endSetup();