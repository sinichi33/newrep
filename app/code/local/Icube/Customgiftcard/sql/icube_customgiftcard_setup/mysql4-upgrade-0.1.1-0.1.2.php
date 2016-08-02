<?php
$installer = new Mage_Sales_Model_Resource_Setup('core_setup');

$attribute  = array(
    'type'          => 'varchar',
    'backend'  		=> '',
    'label'         => 'Billing Number',
    'input'    		=> 'text',
    'visible'       => true,
    'required'      => false,
    'filterable'    => true,
    'comparable'    => true,
);
$installer->addAttribute('creditmemo', 'billing_number', $attribute);

$installer->run("
    ALTER TABLE sales_flat_creditmemo_grid ADD COLUMN billing_number VARCHAR(255) NULL;
    ");

$installer->endSetup();
?>

