<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('icube_rma/items')};  
    CREATE TABLE {$this->getTable('icube_rma/items')}
    ( 	
    	id int(11) unsigned NOT NULL auto_increment, 
    	rma_id int(11) NOT NULL default 0, 
    	item_id int(11) NOT NULL default 0,
        description text, 
    	PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('icube_rma/images')};  
    CREATE TABLE {$this->getTable('icube_rma/images')}
    (   
        id int(11) unsigned NOT NULL auto_increment, 
        rma_id int(11) NOT NULL default 0, 
        images text, 
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");


$installer->endSetup();