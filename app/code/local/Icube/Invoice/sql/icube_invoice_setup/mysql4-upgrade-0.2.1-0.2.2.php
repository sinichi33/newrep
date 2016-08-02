<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE `sales_flat_invoice` DROP `awb_number`;
	ALTER TABLE `sales_flat_invoice_grid` DROP `awb_number`;
    ALTER TABLE `sales_flat_shipment` ADD COLUMN awb_number VARCHAR(255) NULL;
    ALTER TABLE `sales_flat_shipment_grid` ADD COLUMN awb_number VARCHAR(255) NULL;
");

$installer->endSetup();

?>