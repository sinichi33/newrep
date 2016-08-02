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

if (!in_array('show_on_grid', $cols)) {
    $installer->run("ALTER TABLE `{$this->getTable('eav/attribute')}` ADD `show_on_grid` TINYINT( 1 )  UNSIGNED NOT NULL");
}

$installer->endSetup();