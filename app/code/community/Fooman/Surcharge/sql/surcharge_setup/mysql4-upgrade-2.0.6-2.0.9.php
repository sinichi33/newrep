<?php

$installer = $this;
$installer->startSetup();

if (version_compare(Mage::getVersion(), '1.4.1.0', '>=')) {
    try {
        $installer->run(
            "ALTER TABLE {$this->getTable('sales_flat_order')}
              ADD COLUMN `fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000';

               ALTER TABLE {$this->getTable('sales_flat_order')}
               ADD COLUMN `base_fooman_surcharge_amount` decimal(12,4) NOT NULL default '0.0000'
               AFTER `fooman_surcharge_amount`;

                ALTER TABLE {$this->getTable('sales_flat_order')}
                ADD COLUMN `fooman_surcharge_description` varchar(255)
                AFTER `base_fooman_surcharge_amount`;

                ALTER TABLE {$this->getTable('sales_flat_order')}
                ADD COLUMN `fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000'
                AFTER `fooman_surcharge_description`;

                ALTER TABLE {$this->getTable('sales_flat_order')}
                ADD COLUMN `base_fooman_surcharge_tax_amount` decimal(12,4) NOT NULL default '0.0000'
                AFTER `fooman_surcharge_tax_amount`;"
        );
    } catch (Exception $e) {
        //need to catch it here since Magento could have already added the columns
        //as part of the upgrade 1.4.0.1 to 1.4.1.0
        Mage::logException($e);
        Mage::log('Fooman Surcharge error' . $e->getMessage());
    }
}
$installer->endSetup();

