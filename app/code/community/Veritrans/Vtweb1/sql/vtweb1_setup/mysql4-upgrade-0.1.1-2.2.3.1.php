<?php
$installer = $this;
$attribute  = array(
    'type'          => 'varchar',
    'backend_type'  => 'text',
    'frontend_input' => 'text',
    'is_user_defined' => true,
    'label'         => 'Installment Tenor',
    'visible'       => true,
    'required'      => false,
    'user_defined'  => true,
    'searchable'    => false,
    'filterable'    => false,
    'comparable'    => false,
);
$installer->addAttribute('order', 'installment_tenor', $attribute);
$installer->endSetup();