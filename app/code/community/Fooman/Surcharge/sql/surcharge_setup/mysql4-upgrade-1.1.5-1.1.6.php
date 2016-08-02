<?php

$installer = $this;
$installer->startSetup();

//Add surcharge taxes to quote
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote_address')}
    ADD COLUMN `base_fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `base_fooman_surcharge_amount`;"
);
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote_address')}
    ADD COLUMN `fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `base_fooman_surcharge_amount`;"
);


$installer->endSetup();