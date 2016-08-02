<?php

$installer = $this;
$installer->startSetup();

//Delete old payment info from core_config_data
$installer->run(
    "DELETE FROM `{$this->getTable('core/config_data')}` WHERE `path` LIKE '%fooman_surcharge%'"
);
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
        'attribute_code'  => 'fooman_surcharge_amount',
        'is_global'       => '1',
        'is_visible'      => '1',
        'is_required'     => '0',
        'is_user_defined' => '0',
        'frontend_label'  => 'Surcharge',
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
        'attribute_code'  => 'base_fooman_surcharge_amount',
        'is_global'       => '1',
        'is_visible'      => '1',
        'is_required'     => '0',
        'is_user_defined' => '0',
        'frontend_label'  => 'Surcharge',
        'backend_type'    => 'decimal'

    );
    $attribute = new Mage_Eav_Model_Entity_Attribute();
    $attribute->loadByCode($att['entity_type_id'], $att['attribute_code'])
        ->setStoreId(0)
        ->addData($att);
    $attribute->save();

    // store description
    $att = array(
        'entity_type_id'  => $rowOrder['entity_type_id'],
        'attribute_code'  => 'fooman_surcharge_description',
        'is_global'       => '1',
        'is_visible'      => '1',
        'is_required'     => '0',
        'is_user_defined' => '0',
        'frontend_label'  => 'Surcharge Description',
        'backend_type'    => 'varchar'

    );
    $attribute = new Mage_Eav_Model_Entity_Attribute();
    $attribute->loadByCode($att['entity_type_id'], $att['attribute_code'])
        ->setStoreId(0)
        ->addData($att);
    $attribute->save();
}
//Add surcharge to quote
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote')}
    ADD COLUMN `base_fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `base_subtotal_with_discount`;"
);
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote')}
    ADD COLUMN `fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `base_subtotal_with_discount`;"
);
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote')}
    ADD COLUMN `fooman_surcharge_description` varchar(255)
    AFTER `base_fooman_surcharge_amount`;"
);


//And quote address
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote_address')}
    ADD COLUMN `base_fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `base_discount_amount`;"
);
$installer->run(
    "ALTER TABLE {$this->getTable('sales_flat_quote_address')}
    ADD COLUMN `fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `base_discount_amount`;"
);

$installer->endSetup();