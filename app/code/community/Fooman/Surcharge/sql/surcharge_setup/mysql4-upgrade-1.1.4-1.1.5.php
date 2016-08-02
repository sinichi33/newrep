<?php

$installer = $this;
$installer->startSetup();

//Delete old payment info from core_config_data
$installer->run(
    "DELETE FROM `{$this->getTable('core/config_data')}` WHERE `path` LIKE '%fooman_surcharge%'"
);

$installer->endSetup();