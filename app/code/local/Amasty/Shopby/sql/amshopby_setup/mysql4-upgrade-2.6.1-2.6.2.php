<?php
/**
 * @copyright Amasty.
 */
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('amshopby/filter')}` ADD `use_and_logic` TINYINT(1) NOT NULL DEFAULT 0;
");

$this->endSetup();
