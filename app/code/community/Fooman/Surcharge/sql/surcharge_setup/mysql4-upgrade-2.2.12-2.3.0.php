<?php

$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
$setup->startSetup();

if ($setup->getAttribute('catalog_product', 'fooman_product_surcharge') === false) {
    $setup->addAttribute(
        'catalog_product', 'fooman_product_surcharge',
        array(
            'group'                   => 'Prices',
            'type'                    => 'decimal',
            'label'                   => 'Product Surcharge',
            'note'                    => '',
            'input'                   => 'price',
            'class'                   => '',
            'global'                  => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible'                 => true,
            'required'                => false,
            'user_defined'            => false,
            'default'                 => '',
            'searchable'              => 0,
            'filterable'              => 0,
            'comparable'              => 0,
            'visible_on_front'        => 0,
            'unique'                  => 0,
            'used_in_product_listing' => 1,
            'configurable'            => 0,
        )
    );
}

$setup->endSetup();
