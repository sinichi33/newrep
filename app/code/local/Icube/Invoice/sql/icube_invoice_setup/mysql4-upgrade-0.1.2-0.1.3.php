<?php
$installer = $this;

$invoice = Mage::getBaseDir('var').DS.'SAP/flatorder';
$ioFile = new Varien_Io_File();
$ioFile->checkAndCreateFolder($invoice);

$archive = Mage::getBaseDir('var').DS.'SAP/flatorder/archive';
$ioFile = new Varien_Io_File();
$ioFile->checkAndCreateFolder($archive);

$installer->endSetup();