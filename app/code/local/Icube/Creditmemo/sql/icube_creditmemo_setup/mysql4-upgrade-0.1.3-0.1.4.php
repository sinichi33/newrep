<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_creditmemo ADD COLUMN refund_cash INT(11) NULL;
    ");

$installer->endSetup();