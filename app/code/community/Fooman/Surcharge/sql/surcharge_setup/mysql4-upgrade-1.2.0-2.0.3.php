<?php

$installer = $this;
$installer->startSetup();
$installer->endSetup();

//Upgrade existing surcharges to new split between fixed and rate
$pathsToCheck = array();
$pathsToCheck[] = array(
    'type' => 'surcharge/fooman_surcharge_cc/ccsurchargehandlingtype',
    'old'  => 'surcharge/fooman_surcharge_cc/ccsurchargerate',
    'new'  => 'surcharge/fooman_surcharge_cc/ccsurchargefixed'
);
$pathsToCheck[] = array(
    'type' => 'surcharge/fooman_surcharge_method/methodsurchargehandlingtype',
    'old'  => 'surcharge/fooman_surcharge_method/methodsurchargerate',
    'new'  => 'surcharge/fooman_surcharge_method/methodsurchargefixed'
);

foreach ($pathsToCheck as $path) {
    $select = new Zend_Db_Select($installer->getConnection());
    $select->from($this->getTable('core/config_data'));
    $select->where("`path` = '{$path['type']}'");
    $storeConfigs = $installer->getConnection()->fetchAll($select);
    foreach ($storeConfigs as $storeConfig) {
        if ($storeConfig['value'] == Mage_Shipping_Model_Carrier_Abstract::HANDLING_TYPE_FIXED) {
            $select = new Zend_Db_Select($installer->getConnection());
            $select->from($this->getTable('core/config_data'));
            $select->where("`path` = '{$path['old']}'");
            $select->where("`scope` = '{$storeConfig['scope']}'");
            $select->where("`scope_id` = '{$storeConfig['scope_id']}'");
            $currentFixed = $installer->getConnection()->fetchAll($select);
            if (isset($currentFixed[0])) {
                $new = array(
                    'scope'    => $currentFixed[0]['scope'],
                    'scope_id' => $currentFixed[0]['scope_id'],
                    'path'     => $path['new'],
                    'value'    => $currentFixed[0]['value']
                );
                $installer->getConnection()->insert($this->getTable('core/config_data'), $new);
                $installer->run("DELETE FROM `{$this->getTable('core/config_data')}` WHERE `path` = '{$path['old']}'");
            }
        }
    }
}


