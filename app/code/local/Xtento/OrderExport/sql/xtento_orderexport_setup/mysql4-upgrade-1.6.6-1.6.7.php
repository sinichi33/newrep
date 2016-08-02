<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE  `" . $this->getTable('xtento_orderexport_profile') . "` CHANGE `xsl_template` `xsl_template` MEDIUMTEXT NOT NULL;
");

$installer->endSetup();