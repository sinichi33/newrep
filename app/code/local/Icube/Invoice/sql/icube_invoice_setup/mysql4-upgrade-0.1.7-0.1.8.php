<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN pickup_voucher VARCHAR(50) NULL;
    ");

$installer->endSetup();