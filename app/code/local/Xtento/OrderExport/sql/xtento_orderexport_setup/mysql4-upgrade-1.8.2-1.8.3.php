<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE  `" . $this->getTable('xtento_orderexport_profile') . "` CHANGE `export_filter_status` `export_filter_status` MEDIUMTEXT NOT NULL;
");

$installer->endSetup();