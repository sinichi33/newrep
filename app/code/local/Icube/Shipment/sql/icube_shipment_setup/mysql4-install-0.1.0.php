<?php
$installer = $this;

$installer->startSetup();

$installer->run("
	ALTER TABLE sales_flat_shipment_grid ADD COLUMN sap_so_number VARCHAR(25);
	");
	
	/*
	// This code run in icube_invoice_setup/mysql4-upgrade-0.2.0-0.2.1.php
	$installer->run("
		ALTER TABLE sales_flat_shipment ADD COLUMN sap_so_number VARCHAR(25);
	    ALTER TABLE sales_flat_shipment ADD COLUMN delivered_date DATETIME;
	    ");
    */
	
	/*
	// This code run in icube_invoice_setup/mysql4-upgrade-0.2.1-0.2.2.php
	$installer->run("
		ALTER TABLE `sales_flat_invoice` DROP `awb_number`;
		ALTER TABLE `sales_flat_invoice_grid` DROP `awb_number`;
	    ALTER TABLE `sales_flat_shipment` ADD COLUMN awb_number VARCHAR(255) NULL;
	    ALTER TABLE `sales_flat_shipment_grid` ADD COLUMN awb_number VARCHAR(255) NULL;
	");

	/*
	// This code run in icube_invoice_setup/mysql4-upgrade-0.2.2-0.2.3.php
	$installer->run("
	    ALTER TABLE `sales_flat_shipment` ADD COLUMN logistic_name VARCHAR(255) NULL;
	    ALTER TABLE `sales_flat_shipment_grid` ADD COLUMN logistic_name VARCHAR(255) NULL;
	");
	*/

$installer->endSetup();