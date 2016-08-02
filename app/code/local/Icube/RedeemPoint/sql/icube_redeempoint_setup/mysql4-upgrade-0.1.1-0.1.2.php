<?php 
$installer = $this;
$installer->startSetup();
$entityId = $installer->getEntityTypeId('customer');

$eav = array(
        'backend_type' => 'varchar',
        'is_user_defined' => 1,
        'is_visible' => 1 
        );
$forms = array(
    'customer_account_create'
);

$attributeId = $this->getAttribute($entityId, 'member_ace', 'attribute_id');
$installer->updateAttribute($entityId, $attributeId, $eav);

// Add the attribute to the form-filter
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'member_ace');
$attribute->setData('used_in_forms', $forms);
$attribute->save();


$attributeId = $this->getAttribute($entityId, 'member_toyskingdom', 'attribute_id');
$installer->updateAttribute($entityId, $attributeId, $eav);

// Add the attribute to the form-filter
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'member_toyskingdom');
$attribute->setData('used_in_forms', $forms);
$attribute->save();


$attributeId = $this->getAttribute($entityId, 'member_informa', 'attribute_id');
$installer->updateAttribute($entityId, $attributeId, $eav);

// Add the attribute to the form-filter
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'member_informa');
$attribute->setData('used_in_forms', $forms);
$attribute->save();


$installer->endSetup();