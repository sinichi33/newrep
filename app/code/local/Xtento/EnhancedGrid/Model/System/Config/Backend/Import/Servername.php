<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-18T11:20:17+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/System/Config/Backend/Import/Servername.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_System_Config_Backend_Import_Servername extends Mage_Core_Model_Config_Data
{
    const MODULE_MESSAGE = 'The Enhanced Order grid extension couldn\'t be enabled. Please make sure you are using a valid license key.';

    public function afterLoad()
    {
        $sName1 = Mage::getModel('xtento_enhancedgrid/system_config_backend_import_server')->getFirstName();
        $sName2 = Mage::getModel('xtento_enhancedgrid/system_config_backend_import_server')->getSecondName();
        if ($sName1 !== $sName2) {
            $this->setValue(sprintf('%s (Main: %s)', $sName1, $sName2));
        } else {
            $this->setValue(sprintf('%s', $sName1));
        }
    }
}
