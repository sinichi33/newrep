<?php 
$installer = $this;
$installer->startSetup();
$entity = $installer->getEntityTypeId('customer');

$installer->removeAttribute($entity, 'card_number');
$installer->addAttribute($entity, 'member_ace', array(
        'type' => 'text',
        'label' => 'Member Ace',
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
$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'member_ace');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

$installer->addAttribute($entity, 'member_toyskingdom', array(
        'type' => 'text',
        'label' => 'Member Toys Kingdom',
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
$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'member_toyskingdom');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

$installer->addAttribute($entity, 'member_informa', array(
        'type' => 'text',
        'label' => 'Member Informa',
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
$attribute = Mage::getSingleton('eav/config')->getAttribute($installer->getEntityTypeId('customer'), 'member_informa');
$attribute->setData('used_in_forms', $forms);
$attribute->save();