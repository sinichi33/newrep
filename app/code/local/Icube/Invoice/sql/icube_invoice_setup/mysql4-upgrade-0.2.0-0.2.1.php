<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_shipment ADD COLUMN sap_so_number VARCHAR(25);
    ALTER TABLE sales_flat_shipment ADD COLUMN delivered_date DATETIME;
    ");

$installer->endSetup();