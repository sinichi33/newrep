<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_order_item ADD COLUMN zcom DECIMAL(16,4) NULL;
    ALTER TABLE sales_flat_order_item ADD COLUMN zdmm DECIMAL(16,4) NULL;
    ALTER TABLE sales_flat_order_item ADD COLUMN zpur DECIMAL(16,4) NULL;
    ALTER TABLE sales_flat_order_item ADD COLUMN zdot DECIMAL(16,4) NULL;
    ALTER TABLE sales_flat_order_item ADD COLUMN zpu1 DECIMAL(16,4) NULL;
    ALTER TABLE sales_flat_order_item ADD COLUMN zpu2 DECIMAL(16,4) NULL;
    ALTER TABLE sales_flat_order_item ADD COLUMN zban DECIMAL(16,4) NULL;
");

$installer->endSetup();

?>