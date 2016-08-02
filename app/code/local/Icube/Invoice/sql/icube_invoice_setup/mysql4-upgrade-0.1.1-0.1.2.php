<?php
$installer = $this;


$invoice = Mage::getBaseDir('var').DS.'SAP/invoice';
$ioFile = new Varien_Io_File();
$ioFile->checkAndCreateFolder($invoice);

$archive = Mage::getBaseDir('var').DS.'SAP/invoice/archive';
$ioFile = new Varien_Io_File();
$ioFile->checkAndCreateFolder($archive);

$installer->endSetup();