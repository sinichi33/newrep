<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `" . $this->getTable('xtento_orderexport_profile') . "`
ADD `export_action_add_comment` TEXT AFTER `export_action_change_status`;
");

$installer->endSetup();