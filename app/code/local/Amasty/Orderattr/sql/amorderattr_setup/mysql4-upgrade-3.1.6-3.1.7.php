<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `{$this->getTable('eav/attribute')}` ADD `include_html_print_order` TINYINT( 1 ) UNSIGNED NOT NULL ;
");

$installer->endSetup();