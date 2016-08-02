<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `" . $this->getTable('xtento_orderexport_profile') . "`
ADD `export_action_cancel_order` INT(1) NOT NULL DEFAULT '0' AFTER `export_action_change_status`;
");

$installer->endSetup();