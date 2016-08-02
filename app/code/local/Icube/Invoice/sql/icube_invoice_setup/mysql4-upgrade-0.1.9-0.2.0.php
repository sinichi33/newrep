<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_invoice_grid ADD COLUMN delivery_pickup VARCHAR(25);
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN store_code VARCHAR(25);
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN company_id VARCHAR(50);
    ");

$installer->endSetup();