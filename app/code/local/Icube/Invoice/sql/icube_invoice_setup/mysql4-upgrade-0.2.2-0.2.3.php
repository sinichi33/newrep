<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `sales_flat_shipment` ADD COLUMN logistic_name VARCHAR(255) NULL;
    ALTER TABLE `sales_flat_shipment_grid` ADD COLUMN logistic_name VARCHAR(255) NULL;
");

$installer->endSetup();

?>