<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_invoice ADD COLUMN invoice_status VARCHAR(20) NULL;
    ALTER TABLE sales_flat_invoice ADD COLUMN pickup_voucher VARCHAR(50) NULL;
    ");

$installer->endSetup();