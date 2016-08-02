<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_quote_item ADD COLUMN delivery_pickup VARCHAR(25) DEFAULT 'delivery';
    ALTER TABLE sales_flat_quote_item ADD COLUMN store_code VARCHAR(25) DEFAULT 'DC';
    ALTER TABLE sales_flat_order_item ADD COLUMN delivery_pickup VARCHAR(25) DEFAULT 'delivery';
    ALTER TABLE sales_flat_order_item ADD COLUMN store_code VARCHAR(25) DEFAULT 'DC';
    ");

$installer->endSetup();