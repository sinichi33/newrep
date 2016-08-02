<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_creditmemo ADD COLUMN is_create_gc smallint(5) NOT NULL DEFAULT '0';
    ");

$installer->endSetup();