<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('icube_creditmemo/refundable')};  
    CREATE TABLE {$this->getTable('icube_creditmemo/refundable')}
    ( 	
    	id int(11) unsigned NOT NULL auto_increment, 
    	order_increment_id varchar(50) DEFAULT NULL, 
    	general_marketing_gc_ori decimal(17,4) DEFAULT NULL,
    	general_marketing_gc_refundable decimal(17,4) DEFAULT NULL,
    	refund_previous_gc_ori decimal(17,4) DEFAULT NULL,
    	refund_previous_gc_refundable decimal(17,4) DEFAULT NULL,
    	redeem_gc_ori decimal(17,4) DEFAULT NULL,
    	redeem_gc_refundable decimal(17,4) DEFAULT NULL,
    	other_payment_ori decimal(17,4) DEFAULT NULL,
    	other_payment_refundable decimal(17,4) DEFAULT NULL,
    	PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
    
$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('icube_creditmemo/history')};  
    CREATE TABLE {$this->getTable('icube_creditmemo/history')}
    ( 	
    	id int(11) unsigned NOT NULL auto_increment, 
    	order_increment_id varchar(50) DEFAULT NULL, 
    	creditmemo_increment_id varchar(50) DEFAULT NULL,
    	type varchar(50) NOT NULL,
    	value decimal(17,4) DEFAULT NULL,
    	PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
    
    /*
	// This code run in icube_customgiftcard_setup/mysql4-upgrade-0.1.1-0.1.2.php
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
    
	*/

$installer->endSetup();