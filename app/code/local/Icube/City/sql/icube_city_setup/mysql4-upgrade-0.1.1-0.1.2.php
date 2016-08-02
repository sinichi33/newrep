<?php
$installer = $this;
$installer->startSetup();

$attribute  = array(
    'type'              => 'varchar',
    'input'             => 'text',
    'label'             => 'Kecamatan',
    'global'            => 1,
    'visible'           => 1,
    'required'          => 1,
    'user_defined'      => 0,
    'visible_on_front' => 1
);
$installer->addAttribute('customer_address', 'kecamatan', $attribute);


$used_in_forms[]="customer_register_address";
$used_in_forms[]="customer_address_edit";
$used_in_forms[]="adminhtml_customer_address";

Mage::getSingleton('eav/config')->getAttribute('customer_address', 'kecamatan')
    ->setData('used_in_forms', $used_in_forms)
    ->save();


$attribute1  = array(
    'type'              => 'varchar',
    'input'             => 'text',
    'label'             => 'Kelurahan',
    'global'             => 1,
    'visible'           => 1,
    'required'          => 0,
    'user_defined'      => 0,
    'visible_on_front' => 1
);
$installer->addAttribute('customer_address', 'kelurahan', $attribute1);


Mage::getSingleton('eav/config')->getAttribute('customer_address', 'kelurahan')
    ->setData('used_in_forms', $used_in_forms)
    ->save();


$tablequote = $this->getTable('sales/quote_address');
$installer->run("
ALTER TABLE  $tablequote ADD  `kecamatan` varchar(255) NOT NULL;
ALTER TABLE  $tablequote ADD  `kelurahan` varchar(255) NOT NULL;
");
 
$tablequote = $this->getTable('sales/order_address');
$installer->run("
ALTER TABLE  $tablequote ADD  `kecamatan` varchar(255) NOT NULL;
ALTER TABLE  $tablequote ADD  `kelurahan` varchar(255) NOT NULL;
");


$installer->endSetup();
