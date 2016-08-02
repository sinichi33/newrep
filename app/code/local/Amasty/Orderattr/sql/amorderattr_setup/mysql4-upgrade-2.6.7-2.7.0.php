<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

$installer = $this;

$installer->startSetup();

$fieldsSql = 'SHOW COLUMNS FROM ' . $this->getTable('eav/attribute');
$cols = $installer->getConnection()->fetchCol($fieldsSql);

if (!in_array('customer_groups', $cols)) {
    $installer->run("ALTER TABLE `{$this->getTable('eav/attribute')}` ADD `customer_groups` VARCHAR( 128 ) NOT NULL");
}

$installer->endSetup();