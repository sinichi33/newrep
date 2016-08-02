<?php

$installer = $this;
$installer->startSetup();

if (version_compare(Mage::getVersion(), '1.4.1.0', '>=')) {
    $installer->run(
        "ALTER TABLE {$this->getTable('sales_flat_order')}
          ADD COLUMN `fooman_surcharge_amount_invoiced` decimal(12,4) NOT NULL default '0.0000';

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `base_fooman_surcharge_amount_invoiced` decimal(12,4) NOT NULL default '0.0000'
        AFTER `fooman_surcharge_amount`;

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `fooman_surcharge_amount_refunded` decimal(12,4) NOT NULL default '0.0000';

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `base_fooman_surcharge_amount_refunded` decimal(12,4) NOT NULL default '0.0000'
        AFTER `fooman_surcharge_amount`;

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `fooman_surcharge_tax_amount_invoiced` decimal(12,4) NOT NULL default '0.0000';

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `base_fooman_surcharge_tax_amount_invoiced` decimal(12,4) NOT NULL default '0.0000'
        AFTER `fooman_surcharge_amount`;

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `fooman_surcharge_tax_amount_refunded` decimal(12,4) NOT NULL default '0.0000';

        ALTER TABLE {$this->getTable('sales_flat_order')}
        ADD COLUMN `base_fooman_surcharge_tax_amount_refunded` decimal(12,4) NOT NULL default '0.0000'
        AFTER `fooman_surcharge_amount`;
     "
    );
    $installer->run(
        "ALTER TABLE {$this->getTable('sales_flat_invoice')}
        ADD COLUMN `fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000';

    ALTER TABLE {$this->getTable('sales_flat_invoice')}
    ADD COLUMN `base_fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `fooman_surcharge_amount`;

    ALTER TABLE {$this->getTable('sales_flat_invoice')}
    ADD COLUMN `fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000';

    ALTER TABLE {$this->getTable('sales_flat_invoice')}
    ADD COLUMN `base_fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `fooman_surcharge_amount`;

    ALTER TABLE {$this->getTable('sales_flat_creditmemo')}
    ADD COLUMN `fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000';

    ALTER TABLE {$this->getTable('sales_flat_creditmemo')}
    ADD COLUMN `base_fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `fooman_surcharge_amount`;

    ALTER TABLE {$this->getTable('sales_flat_creditmemo')}
    ADD COLUMN `fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000';

    ALTER TABLE {$this->getTable('sales_flat_creditmemo')}
    ADD COLUMN `base_fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000'
    AFTER `fooman_surcharge_amount`;
    "
    );
} else {
    $fieldsToAdd = array(
        'fooman_surcharge_amount_invoiced'          => 'Surcharge Amount Invoiced',
        'base_fooman_surcharge_amount_invoiced'     => 'Surcharge Base Amount Invoiced',
        'fooman_surcharge_amount_refunded'          => 'Surcharge Amount Refunded',
        'base_fooman_surcharge_amount_refunded'     => 'Surcharge Base Amount Refunded',
        'fooman_surcharge_tax_amount_invoiced'      => 'Surcharge Tax Amount Invoiced',
        'base_fooman_surcharge_tax_amount_invoiced' => 'Surcharge Base Tax Amount Invoiced',
        'fooman_surcharge_tax_amount_refunded'      => 'Surcharge Tax Amount Refunded',
        'base_fooman_surcharge_tax_amount_refunded' => 'Surcharge Base Tax Amount Refunded'
    );
    $sql = 'SELECT entity_type_id FROM ' . $this->getTable('eav_entity_type') . ' WHERE entity_type_code="order"';
    $rowOrder = Mage::getSingleton('core/resource')
        ->getConnection('core_read')
        ->fetchRow($sql);
    foreach ($fieldsToAdd as $field => $label) {
        $att = array(
            'entity_type_id'  => $rowOrder['entity_type_id'],
            'attribute_code'  => $field,
            'is_global'       => '1',
            'is_visible'      => '1',
            'is_required'     => '0',
            'is_user_defined' => '0',
            'frontend_label'  => $label,
            'backend_type'    => 'decimal'
        );
        $attribute = new Mage_Eav_Model_Entity_Attribute();
        $attribute->loadByCode($att['entity_type_id'], $att['attribute_code'])
            ->setStoreId(0)
            ->addData($att);
        $attribute->save();
    }
    $tableFields = array(
        'fooman_surcharge_amount'          => 'Surcharge Amount',
        'base_fooman_surcharge_amount'     => 'Surcharge Base Amount',
        'fooman_surcharge_tax_amount'      => 'Surcharge Tax Amount',
        'base_fooman_surcharge_tax_amount' => 'Surcharge Base Tax Amount',
    );
    $entityTypes = array('invoice', 'creditmemo');
    foreach ($entityTypes as $entityType) {
        $sql = 'SELECT entity_type_id FROM ' . $this->getTable('eav_entity_type')
            . ' WHERE entity_type_code="'.$entityType.'"';
        $rowOrder = Mage::getSingleton('core/resource')
            ->getConnection('core_read')
            ->fetchRow($sql);
        foreach ($tableFields as $field => $label) {
            $att = array(
                'entity_type_id'  => $rowOrder['entity_type_id'],
                'attribute_code'  => $field,
                'is_global'       => '1',
                'is_visible'      => '1',
                'is_required'     => '0',
                'is_user_defined' => '0',
                'frontend_label'  => $label,
                'backend_type'    => 'decimal'
            );
            $attribute = new Mage_Eav_Model_Entity_Attribute();
            $attribute->loadByCode($att['entity_type_id'], $att['attribute_code'])
                ->setStoreId(0)
                ->addData($att);
            $attribute->save();
        }
    }
}
$installer->endSetup();