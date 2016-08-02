<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_invoice ADD COLUMN handling_voucher VARCHAR(50) NULL;
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN handling_voucher VARCHAR(50) NULL;
    ");

$installer->endSetup();