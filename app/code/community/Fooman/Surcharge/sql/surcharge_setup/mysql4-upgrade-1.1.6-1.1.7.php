<?php

$installer = $this;
$installer->startSetup();
if (version_compare(Mage::getVersion(), '1.4.1.0', '<')) {
    // Add surcharge to order
    // Get ID of the entity model 'sales/order'.
    $sql = 'SELECT entity_type_id FROM ' . $this->getTable('eav_entity_type') . ' WHERE entity_type_code="order"';
    $rowOrder = Mage::getSingleton('core/resource')
        ->getConnection('core_read')
        ->fetchRow($sql);

    // Create EAV-attribute for it.
    $att = array(
        'entity_type_id'  => $rowOrder['entity_type_id'],
        'attribute_code'  => 'fooman_surcharge_tax_amount',
        'is_global'       => '1',
        'is_visible'      => '1',
        'is_required'     => '0',
        'is_user_defined' => '0',
        'frontend_label'  => 'Surcharge Tax',
        'backend_type'    => 'decimal'

    );
    $attribute = new Mage_Eav_Model_Entity_Attribute();
    $attribute->loadByCode($att['entity_type_id'], $att['attribute_code'])
        ->setStoreId(0)
        ->addData($att);
    $attribute->save();

    // and for base
    $att = array(
        'entity_type_id'  => $rowOrder['entity_type_id'],
        'attribute_code'  => 'base_fooman_surcharge_tax_amount',
        'is_global'       => '1',
        'is_visible'      => '1',
        'is_required'     => '0',
        'is_user_defined' => '0',
        'frontend_label'  => 'Surcharge Tax',
        'backend_type'    => 'decimal'

    );
    $attribute = new Mage_Eav_Model_Entity_Attribute();
    $attribute->loadByCode($att['entity_type_id'], $att['attribute_code'])
        ->setStoreId(0)
        ->addData($att);
    $attribute->save();
}

$installer->endSetup();
