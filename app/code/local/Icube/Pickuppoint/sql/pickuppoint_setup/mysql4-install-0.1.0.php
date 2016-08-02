<?php
$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('pickuppoint')};
");

$installer->run("
CREATE TABLE {$this->getTable('pickuppoint')}(
  `pickup_id` int(11) NOT NULL AUTO_INCREMENT,
  `pickup_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `geocode` varchar(255) DEFAULT NULL,
 
  PRIMARY KEY (`pickup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$table = $this->getTable('pointofsale');

if (Mage::getSingleton('core/resource')->getConnection('core_write')->isTableExists($table)) {

	$installer->run("
		INSERT INTO pickuppoint (pickup_code)
		SELECT DISTINCT pickup_location_code FROM pointofsale WHERE pickup_location_code IS NOT NULL;
	");
}


$installer->endSetup();
	 