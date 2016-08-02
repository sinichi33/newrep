<?php 
$installer = $this;
$installer->startSetup();
$entity = $installer->getEntityTypeId('customer');

$installer->removeAttribute($entity, 'card_number');
$installer->addAttribute($entity, 'card_number', array(
        'type' => 'text',
        'label' => 'Card Number',
        'input' => 'text',
        'visible' => FALSE,
        'required' => FALSE,
        'default_value' => '',
        'adminhtml_only' => '0'
));
$forms = array(
    'adminhtml_customer',
    'customer_account_edit'
);
$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'card_number');
$attribute->setData('used_in_forms', $forms);
$attribute->save();