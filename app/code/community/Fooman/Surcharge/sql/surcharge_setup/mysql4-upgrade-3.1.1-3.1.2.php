<?php

$setup = new Mage_Catalog_Model_Resource_Eav_Mysql4_Setup('core_setup');
$setup->startSetup();

$setup->updateAttribute('catalog_product', 'fooman_product_surcharge', 'frontend_input', 'text');

$setup->endSetup();
