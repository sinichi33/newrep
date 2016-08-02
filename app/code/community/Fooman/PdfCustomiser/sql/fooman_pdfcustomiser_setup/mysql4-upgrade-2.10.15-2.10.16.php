<?php
$installer = $this;

$installer->startSetup();

	/* 
	* ICUBE custom 
	* update default invoice title & invoice columns
	*/
	Mage::getConfig()->saveConfig('sales_pdf/invoice/invoicetitle', 'INVOICE', 'default', 0);
	Mage::getConfig()->saveConfig('sales_pdf/invoice/invoicecolumns', 'name,sku,price,discount,price_after_disc,qty,tax,rowtotal2', 'default', 0);


$installer->endSetup();