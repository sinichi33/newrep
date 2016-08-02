<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-04-07T14:13:49+02:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Model/System/Config/Backend/Import/Servername.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Model_System_Config_Backend_Import_Servername extends Mage_Core_Model_Config_Data
{
    const MODULE_MESSAGE = 'The Order Number Customizer extension couldn\'t be enabled. Please make sure you are using a valid license key.';

    public function afterLoad()
    {
        $sName1 = Mage::getModel('xtento_customordernumber/system_config_backend_import_server')->getFirstName();
        $sName2 = Mage::getModel('xtento_customordernumber/system_config_backend_import_server')->getSecondName();
        if ($sName1 !== $sName2) {
            $this->setValue(sprintf('%s (Main: %s)', $sName1, $sName2));
        } else {
            $this->setValue(sprintf('%s', $sName1));
        }
    }
}
