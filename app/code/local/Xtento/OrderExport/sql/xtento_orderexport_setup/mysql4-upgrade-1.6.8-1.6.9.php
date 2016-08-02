<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `" . $this->getTable('xtento_orderexport_log') . "` ADD INDEX `log_index` (`profile_id`, `created_at`);
");

$installer->endSetup();