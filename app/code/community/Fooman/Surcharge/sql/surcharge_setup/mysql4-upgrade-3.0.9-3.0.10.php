<?php

$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
$setup->startSetup();

if ($setup->getAttribute('catalog_product', 'fooman_surcharge_exclude_prod') === false) {
    $setup->addAttribute(
        'catalog_product', 'fooman_surcharge_exclude_prod',
        array(
            'group'                   => 'Prices',
            'type'                    => 'int',
            'label'                   => 'Exclude from Surcharge',
            'note'                    => '',
            'input'                   => 'select',
            'class'                   => '',
            'source'                  => 'eav/entity_attribute_source_boolean',
            'visible'                 => true,
            'required'                => false,
            'user_defined'            => false,
            'default'                 => 0,
            'searchable'              => 0,
            'filterable'              => 0,
            'comparable'              => 0,
            'visible_on_front'        => 0,
            'unique'                  => 0,
            'used_in_product_listing' => 0,
            'configurable'            => 0,
        )
    );
}

if ($setup->getAttribute('catalog_product', 'fooman_order_no_surcharge') === false) {
    $setup->addAttribute(
        'catalog_product', 'fooman_order_no_surcharge',
        array(
             'group'                   => 'Prices',
             'type'                    => 'int',
             'label'                   => 'No Surcharge On Order',
             'note'                    => '',
             'input'                   => 'select',
             'class'                   => '',
             'source'                  => 'eav/entity_attribute_source_boolean',
             'visible'                 => true,
             'required'                => false,
             'user_defined'            => false,
             'default'                 => 0,
             'searchable'              => 0,
             'filterable'              => 0,
             'comparable'              => 0,
             'visible_on_front'        => 0,
             'unique'                  => 0,
             'used_in_product_listing' => 0,
             'configurable'            => 0,
        )
    );
}

$setup->endSetup();
