<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS `" . $this->getTable('xtento_enhancedgrid_grid') . "` (
  `grid_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `role_ids` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `enabled` int(1) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `configuration` text NOT NULL,
  `last_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`grid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `" . $this->getTable('xtento_enhancedgrid_grid_columns') . "` (
  `type` varchar(255) NOT NULL,
  `columns` text NOT NULL,
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
");

$installer->endSetup();