<?php
$installer = $this;

$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS {$this->getTable('shippingbeta/handlingrate')};  
    CREATE TABLE {$this->getTable('shippingbeta/handlingrate')}
    ( 	
    	id int(11) unsigned NOT NULL auto_increment, 
    	store_id varchar(50) NOT NULL default '', 
    	price_zone int(10) NOT NULL default 0, 
    	PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");

$installer->run("
	INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (1, 'A332', 5);
	INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (2, 'A422', 5);
	INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (3, 'A319', 0);
	INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (4, 'A433', 7);
	INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (5, 'A327', 9);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (6, 'A322', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (7, 'A314', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (8, 'A336', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (9, 'A346', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (10, 'H328', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (11, 'H374', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (12, 'H305', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (13, 'H384', 10);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (14, 'H312', 15);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (15, 'H327', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (16, 'H302', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (17, 'H308', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (18, 'H326', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (19, 'T304', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (20, 'T322', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (21, 'T314', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (22, 'T324', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (23, 'T318', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (24, 'T305', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (25, 'T302', 0);
    INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (26, 'T317', 0);
	INSERT INTO {$this->getTable('shippingbeta/handlingrate')} VALUES (27, 'T303', 0);
    ");


$installer->endSetup();