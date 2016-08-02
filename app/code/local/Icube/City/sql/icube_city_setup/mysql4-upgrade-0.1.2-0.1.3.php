<?php
$installer = $this;
$installer->startSetup();

$attribute  = array(
    'type'              => 'varchar',
    'input'             => 'text',
    'label'             => 'Kode Jalur',
    'global'            => 1,
    'visible'           => 1,
    'required'          => 0,
    'user_defined'      => 0,
    'visible_on_front' => 0
);
$installer->addAttribute('customer_address', 'kodejalur', $attribute);
$used_in_forms[]="customer_register_address";
$used_in_forms[]="customer_address_edit";
$used_in_forms[]="adminhtml_customer_address";

Mage::getSingleton('eav/config')->getAttribute('customer_address', 'kodejalur')
    ->setData('used_in_forms', $used_in_forms)
    ->save();

$tablequote = $this->getTable('sales/quote_address');
$installer->run("
ALTER TABLE  $tablequote ADD  `kodejalur` varchar(255) NOT NULL;
");
 
$tableorder = $this->getTable('sales/order_address');
$installer->run("
ALTER TABLE  $tableorder ADD  `kodejalur` varchar(255) NOT NULL;
");


$installer->endSetup();
