<?php
$installer = new Mage_Catalog_Model_Resource_Setup('core_setup');
$installer->startSetup();

$attribute  = array(
    'group'             => 'General Information',
    'type'              => 'varchar',
    'input'             => 'image',
    'backend'           => 'catalog/category_attribute_backend_image',
    'frontend_input'    => '',
    'frontend'          => '',
    'label'             => 'Icon',
    'class'             => '',
    'global'             => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,//scope can be SCOPE_STORE or SCOPE_GLOBAL or SCOPE_WEBSITE
    'visible'           => true,
    'frontend_class'     => '',
    'required'          => false,//or true
    'user_defined'      => true,
    //'position'            => 100,//any number will do
);
$installer->addAttribute('catalog_category', 'icon', $attribute);

$installer->endSetup();
?>
