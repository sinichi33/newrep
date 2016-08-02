<?php
$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE sales_flat_invoice MODIFY COLUMN invoice_status VARCHAR(50) DEFAULT NULL");

$installer->endSetup();