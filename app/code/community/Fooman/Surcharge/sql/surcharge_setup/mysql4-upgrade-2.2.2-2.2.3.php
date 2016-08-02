<?php

$installer = $this;
$installer->startSetup();

$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote_address')}
    ADD COLUMN `fooman_surcharge_description` varchar(255) AFTER `base_fooman_surcharge_amount`;"
);

$installer->endSetup();