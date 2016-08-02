<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE  `" . $this->getTable('xtento_enhancedgrid_grid') . "` ADD  `hidden_status` varchar(255) NOT NULL AFTER `configuration`;
");

$installer->endSetup();