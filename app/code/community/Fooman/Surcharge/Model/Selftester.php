<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Selftester extends Fooman_Common_Model_Selftester
{

    public function _getVersions()
    {
        parent::_getVersions();
        $this->messages[]
            = "Surcharge DB version: " . Mage::getResourceModel('core/resource')->getDbVersion('surcharge_setup');
        $this->messages[]
            = "Surcharge Config version: " . (string)Mage::getConfig()->getModuleConfig('Fooman_Surcharge')->version;

        $totals = Mage::getConfig()->getNode('global/sales/quote/totals');
        foreach ($totals->children() as $code => $total) {
            $this->messages[] = $code . ' ' . (string)$total->getClassName();
        }
    }

    public function _getDbFields()
    {
        $mageOneThreeEav = array(
            array(
                "eav", "order", "fooman_surcharge_amount",
                array('type' => 'decimal', 'label' => 'Surcharge', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "order", "base_fooman_surcharge_amount",
                array('type' => 'decimal', 'label' => 'Surcharge', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "order", "fooman_surcharge_description", array(
                'type' => 'text', 'label' => 'Surcharge Description', 'required' => 0, 'global' => 1, 'visible' => 1
            )
            ),
            array(
                "eav", "order", "fooman_surcharge_tax_amount",
                array('type' => 'decimal', 'label' => 'Surcharge Tax', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "order", "base_fooman_surcharge_tax_amount",
                array('type' => 'decimal', 'label' => 'Surcharge Tax', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),

            array(
                "eav",
                "order",
                "fooman_surcharge_amount_invoiced",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Amount Invoiced',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "base_fooman_surcharge_amount_invoiced",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Base Amount Invoiced',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "fooman_surcharge_amount_refunded",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Amount Refunded',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "base_fooman_surcharge_amount_refunded",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Base Amount Refunded',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "fooman_surcharge_tax_amount_invoiced",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Tax Amount Invoiced',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "base_fooman_surcharge_tax_amount_invoiced",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Base Tax Amount Invoiced',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "fooman_surcharge_tax_amount_refunded",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Tax Amount Refunded',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),
            array(
                "eav",
                "order",
                "base_fooman_surcharge_tax_amount_refunded",
                array(
                    'type'     => 'decimal',
                    'label'    => 'Surcharge Base Tax Amount Refunded',
                    'required' => 0,
                    'global'   => 1,
                    'visible'  => 1
                )
            ),

            //INVOICE
            array(
                "eav", "invoice", "fooman_surcharge_amount",
                array('type' => 'decimal', 'label' => 'Surcharge', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "invoice", "base_fooman_surcharge_amount",
                array('type' => 'decimal', 'label' => 'Surcharge', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "invoice", "fooman_surcharge_tax_amount",
                array('type' => 'decimal', 'label' => 'Surcharge Tax', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "invoice", "base_fooman_surcharge_tax_amount",
                array('type' => 'decimal', 'label' => 'Surcharge Tax', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            //CREDITMEMO
            array(
                "eav", "creditmemo", "fooman_surcharge_amount",
                array('type' => 'decimal', 'label' => 'Surcharge', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "creditmemo", "base_fooman_surcharge_amount",
                array('type' => 'decimal', 'label' => 'Surcharge', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "creditmemo", "fooman_surcharge_tax_amount",
                array('type' => 'decimal', 'label' => 'Surcharge Tax', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),
            array(
                "eav", "creditmemo", "base_fooman_surcharge_tax_amount",
                array('type' => 'decimal', 'label' => 'Surcharge Tax', 'required' => 0, 'global' => 1, 'visible' => 1)
            ),

        );
        $mageOneThree = array(
            array(
                "sql-column", "sales_flat_quote", "base_fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `base_subtotal_with_discount`"
            ),
            array(
                "sql-column", "sales_flat_quote", "fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `base_subtotal_with_discount`"
            ),
            array(
                "sql-column", "sales_flat_quote", "fooman_surcharge_description",
                "varchar(255)  AFTER `base_fooman_surcharge_amount`"
            ),

            array(
                "sql-column", "sales_flat_quote_address", "base_fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `base_discount_amount`"
            ),
            array(
                "sql-column", "sales_flat_quote_address", "fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `base_discount_amount`"
            ),
            array(
                "sql-column", "sales_flat_quote_address", "base_fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `base_fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_quote_address", "fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `base_fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_quote_address", "fooman_surcharge_description",
                "varchar(255) AFTER `base_fooman_surcharge_amount`"
            ),
            array(
                "eav", "catalog_product", "fooman_product_surcharge", array(
                'type' => 'decimal', 'label' => 'Product Surcharge', 'required' => 0,
                'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL, 'visible' => 1, 'input' => 'price',
                'group' => 'Prices'
            )
            ),
        );
        $mageOneFourOne = array(
            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_amount", "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_order", "base_fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_description",
                "varchar(255) AFTER `base_fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_description`"
            ),
            array(
                "sql-column", "sales_flat_order", "base_fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_tax_amount`"
            ),

            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_amount_invoiced",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_order", "base_fooman_surcharge_amount_invoiced",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_amount_refunded",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_order", "base_fooman_surcharge_amount_refunded",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),

            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_tax_amount_invoiced",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_order", "base_fooman_surcharge_tax_amount_invoiced",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_order", "fooman_surcharge_tax_amount_refunded",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_order", "base_fooman_surcharge_tax_amount_refunded",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),

            array(
                "sql-column", "sales_flat_invoice", "fooman_surcharge_amount", "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_invoice", "base_fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_invoice", "fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_invoice", "base_fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),

            array(
                "sql-column", "sales_flat_creditmemo", "fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_creditmemo", "base_fooman_surcharge_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),
            array(
                "sql-column", "sales_flat_creditmemo", "fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000'"
            ),
            array(
                "sql-column", "sales_flat_creditmemo", "base_fooman_surcharge_tax_amount",
                "decimal(12,4) NOT NULL default '0.0000' AFTER `fooman_surcharge_amount`"
            ),

        );
        if (version_compare(Mage::getVersion(), '1.4.1.0', '<')) {
            return array_merge($mageOneThree, $mageOneThreeEav);
        } else {
            return array_merge($mageOneThree, $mageOneFourOne);
        }
    }

    public function _getSettings()
    {
        $conn = Mage::getSingleton('core/resource');
        $read = $conn->getConnection('core_read');
        return array(
            'core_config_data' => $read->fetchAll(
                "SELECT * FROM `{$conn->getTableName('core_config_data')}` WHERE path like '%surcharge%'"
            )
        );
    }

    public function _getFiles()
    {
        //REPLACE
        return array(
            'app/etc/modules/Fooman_TotalsSortingFix.xml',
            'app/etc/modules/Fooman_Surcharge.xml',
            'app/code/community/Fooman/Surcharge/Helper/Tax.php',
            'app/code/community/Fooman/Surcharge/Helper/Fixes.php',
            'app/code/community/Fooman/Surcharge/Helper/Compatibility.php',
            'app/code/community/Fooman/Surcharge/Helper/Config.php',
            'app/code/community/Fooman/Surcharge/Helper/Data.php',
            'app/code/community/Fooman/Surcharge/etc/system.xml',
            'app/code/community/Fooman/Surcharge/etc/config.xml',
            'app/code/community/Fooman/Surcharge/etc/api2.xml',
            'app/code/community/Fooman/Surcharge/etc/wsdl.xml',
            'app/code/community/Fooman/Surcharge/Block/Tax/Checkout/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Block/Adminhtml/Extensioninfo.php',
            'app/code/community/Fooman/Surcharge/Block/Adminhtml/Sales/Order/Create/Totals/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Block/Adminhtml/Sales/Order/Creditmemo/Create/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Block/Adminhtml/Sales/Order/Totals.php',
            'app/code/community/Fooman/Surcharge/Block/Sales/Order/Totals.php',
            'app/code/community/Fooman/Surcharge/Model/Paypal/Standard.php',
            'app/code/community/Fooman/Surcharge/Model/Paypal/Hostedpro/Request.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/ShippingMethod.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Group.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Country.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Abstract.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/MinEnforced.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Method.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Product.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/MinFee.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Cc.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge/Region.php',
            'app/code/community/Fooman/Surcharge/Model/Order/Creditmemo/Total/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Model/Order/Invoice/Total/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Model/Order/Total/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Model/Selftester.php',
            'app/code/community/Fooman/Surcharge/Model/Quote/Address/Total/Tax.php',
            'app/code/community/Fooman/Surcharge/Model/Quote/Address/Total/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Model/Observer.php',
            'app/code/community/Fooman/Surcharge/Model/Surcharge.php',
            'app/code/community/Fooman/Surcharge/Model/System/AddressTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/SurchargeAddressTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/ProductSurchargeTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/Totals.php',
            'app/code/community/Fooman/Surcharge/Model/System/HandlingTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/ShippingMethods.php',
            'app/code/community/Fooman/Surcharge/Model/System/CountryAddressTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/MinFeeTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/SurchargeTaxClass.php',
            'app/code/community/Fooman/Surcharge/Model/System/Cctype.php',
            'app/code/community/Fooman/Surcharge/Model/System/ShippingSurchargeTypes.php',
            'app/code/community/Fooman/Surcharge/Model/System/Methods.php',
            'app/code/community/Fooman/Surcharge/Model/System/Groups.php',
            'app/code/community/Fooman/Surcharge/Model/Sales/Pdf/Surcharge.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-1.1.6-1.1.7.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-1.1.5-1.1.6.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-3.0.9-3.0.10.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-install-1.1.4.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-2.2.12-2.3.0.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-2.2.2-2.2.3.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-1.2.0-2.0.3.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-3.1.1-3.1.2.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-2.0.6-2.0.9.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/uninstalll.sql',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-1.1.4-1.1.5.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-2.1.0-2.1.1.php',
            'app/code/community/Fooman/Surcharge/sql/surcharge_setup/mysql4-upgrade-2.9.9-3.0.0.php',
            'app/code/community/Fooman/TotalsSortingFix/Helper/Data.php',
            'app/code/community/Fooman/TotalsSortingFix/etc/config.xml',
            'app/design/frontend/base/default/layout/surcharge.xml',
            'app/design/frontend/base/default/template/surcharge/tax/checkout/surcharge.phtml',
            'app/design/adminhtml/default/default/layout/surcharge.xml',
            'app/design/adminhtml/default/default/template/surcharge/sales/order/creditmemo/create/totals/surcharge.phtml',
            'app/design/adminhtml/default/default/template/surcharge/sales/order/create/totals/surcharge.phtml',
        );
        //REPLACE_END
    }
}


