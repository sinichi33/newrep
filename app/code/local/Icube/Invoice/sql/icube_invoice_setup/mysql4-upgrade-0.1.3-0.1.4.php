<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_invoice ADD COLUMN deliverymtd VARCHAR(255) NULL;");

$installer->endSetup();