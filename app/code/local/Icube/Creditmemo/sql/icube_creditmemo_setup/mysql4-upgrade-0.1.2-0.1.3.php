<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_creditmemo ADD COLUMN refund_as_gc INT(11) NULL;
    ");

$installer->endSetup();