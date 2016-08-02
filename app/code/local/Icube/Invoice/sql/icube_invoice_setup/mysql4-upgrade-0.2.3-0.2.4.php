<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_invoice_grid ADD COLUMN invoice_status VARCHAR(20) NULL;
    ALTER TABLE pointofsale ADD COLUMN company_id VARCHAR(50) NULL;
	ALTER TABLE pointofsaleADD COLUMN pickup_location_code VARCHAR(50) NULL;
	ALTER TABLE pointofsale ADD COLUMN pickup_location_label VARCHAR(50) NULL;
    ");

$installer->run("
	UPDATE `sales_flat_invoice_grid` AS ig
	  LEFT JOIN `sales_flat_invoice` AS i ON ig.entity_id = i.entity_id
	SET ig.invoice_status = i.invoice_status
	");


$installer->endSetup();

