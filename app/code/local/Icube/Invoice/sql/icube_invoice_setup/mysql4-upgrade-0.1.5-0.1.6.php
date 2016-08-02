<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('icube_invoice/status')};  
    CREATE TABLE {$this->getTable('icube_invoice/status')}
    ( 	
    	id int(11) unsigned NOT NULL auto_increment, 
    	status_code varchar(255) NOT NULL default '', 
    	status_label varchar(255) NOT NULL default '', 
    	group_of varchar(255) NOT NULL default '', 
    	PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->run("
	INSERT INTO {$this->getTable('icube_invoice/status')} VALUES (1, 'collect_item', 'Collect Item', '');
	INSERT INTO {$this->getTable('icube_invoice/status')} VALUES (2, 'ready_to_pick', 'Ready to Pick', 'bopis');
	INSERT INTO {$this->getTable('icube_invoice/status')} VALUES (3, 'picked', 'Picked', 'bopis');
	INSERT INTO {$this->getTable('icube_invoice/status')} VALUES (4, 'cancel', 'Not Available / Cancel', '');
	INSERT INTO {$this->getTable('icube_invoice/status')} VALUES (5, 'ready_to_ship', 'Ready to Ship', 'dfs');
	INSERT INTO {$this->getTable('icube_invoice/status')} VALUES (6, 'shipped', 'Shipped', 'dfs');
    ");


$installer->endSetup();