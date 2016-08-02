<?php

$installer = $this;
$installer->startSetup();

$table = $this->getTable('veritrans');
$installer->run("ALTER TABLE ".$table." CHANGE status vtstatus varchar(50) NOT NULL default ''");

$installer->endSetup();