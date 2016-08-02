<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_quote_item ADD COLUMN company_id VARCHAR(50);
	ALTER TABLE sales_flat_order_item ADD COLUMN company_id VARCHAR(50);
	ALTER TABLE sales_flat_invoice ADD COLUMN delivery_pickup VARCHAR(25);
    ALTER TABLE sales_flat_invoice ADD COLUMN store_code VARCHAR(25);
    ALTER TABLE sales_flat_invoice ADD COLUMN company_id VARCHAR(50);
	ALTER TABLE sales_flat_invoice_item ADD COLUMN delivery_pickup VARCHAR(25);
    ALTER TABLE sales_flat_invoice_item ADD COLUMN store_code VARCHAR(25);
    ALTER TABLE sales_flat_invoice_item ADD COLUMN company_id VARCHAR(50);
    ");

$installer->endSetup();