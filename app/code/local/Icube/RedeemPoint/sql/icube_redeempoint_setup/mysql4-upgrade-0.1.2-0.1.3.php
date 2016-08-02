<?php 

$forms = array(
    'adminhtml_customer',
    'customer_account_create',
    'customer_account_edit'
);

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'member_ace');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'member_toyskingdom');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'member_informa');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

