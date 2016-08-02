<?php
$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$this->getTable('shipping_premiumrate')} MODIFY COLUMN dest_city varchar(255) NOT NULL default ''");

$installer->run("ALTER TABLE {$this->getTable('shipping_premiumrate')} 
MODIFY COLUMN price_from_value decimal(17,4) NOT NULL default 9999999999999.0000,
MODIFY COLUMN price_to_value decimal(17,4) NOT NULL default 9999999999999.0000,
MODIFY COLUMN price decimal(17,4) NOT NULL default 0.0000,
MODIFY COLUMN cost decimal(17,4) NOT NULL default 0.0000;");

$installer->endSetup();