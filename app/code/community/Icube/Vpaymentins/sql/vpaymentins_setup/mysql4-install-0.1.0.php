<?php

$installer=$this;
$installer->startSetup();
$entitiesToAlter = array('quote_payment','order_payment');
$attributes = array(
    'insbank' => array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT
    ),
    'instenor' => array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT
    )
);

foreach ($entitiesToAlter as $entityName) {
    foreach ($attributes as $attributeCode => $attributeParams) {
        $installer->addAttribute($entityName, $attributeCode, $attributeParams);
    }
}
$installer->endSetup();