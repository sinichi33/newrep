<?php

$installer = $this;
$installer->startSetup();

//add columns to order payment
$tableOrder = $this->getTable('sales/order_payment');
$installer->run("ALTER TABLE ".$tableOrder." ADD COLUMN payment_due_date datetime DEFAULT NULL");
$installer->run("ALTER TABLE ".$tableOrder." ADD COLUMN token_merchant varchar(100) DEFAULT NULL");

//insert EAV
$installer->run("
INSERT INTO `{$this->getTable('eav_attribute')}`(entity_type_id,attribute_code,attribute_model,backend_model,backend_type,backend_table,
frontend_model,frontend_input,frontend_label,frontend_class,source_model,is_required,is_user_defined,default_value,is_unique,note)
values(5, 'payment_due_date', null, 'eav/entity_attribute_backend_datetime', 'datetime', '', '',
'date', '',null, '',1,0,'',0,'');
");
$installer->run("
INSERT INTO `{$this->getTable('eav_attribute')}`(entity_type_id,attribute_code,attribute_model,backend_model,backend_type,backend_table,
frontend_model,frontend_input,frontend_label,frontend_class,source_model,is_required,is_user_defined,default_value,is_unique,note)
VALUES ( 5, 'token_merchant', NULL , NULL , 'varchar', '', '', 'text', '', NULL , '', 1, 0, '', 0, '' );
");


$installer->endSetup();