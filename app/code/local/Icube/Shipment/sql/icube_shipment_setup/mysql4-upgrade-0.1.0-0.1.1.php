<?php
$installer = $this;

$installer->startSetup();

	
	$installer->run("
	    ALTER TABLE `sales_flat_shipment` 
	    	DROP awb_number,
	    	DROP logistic_name;
	    ALTER TABLE `sales_flat_shipment_grid` 
	    	DROP awb_number,
	    	DROP logistic_name;
	");


$installer->endSetup();