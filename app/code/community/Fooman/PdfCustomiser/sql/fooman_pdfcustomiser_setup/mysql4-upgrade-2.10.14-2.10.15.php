<?php
$installer = $this;

$installer->startSetup();

	/* 
	* ICUBE custom 
	* update barcode type config data
	*/
	Mage::getConfig()->saveConfig('sales_pdf/all/allbarcode', 'C39E', 'default', 0);


$installer->endSetup();