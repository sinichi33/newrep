<?php

$installer = $this;
$installer->startSetup();

try {
	//set config skin
	$config = Mage::getModel('core/config');          
    $config->saveConfig('design/package/name', 'beta', 'default');
    $config->saveConfig('design/theme/default', 'default', 'default');
    
    unset ($config);
    
} catch (Exception $e) {
    throw new Exception('CMS PAGE UPDATE FAILS. ' . $e->getMessage());
}



$installer->endSetup();