<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-01-13T12:52:41+01:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Helper/Data.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Helper_Data extends Mage_Core_Helper_Abstract
{
    const EDITION = 'EE';

    public function getModuleEnabled()
    {
        if (!Mage::getStoreConfigFlag(Xtento_CustomOrderNumber_Model_Observer::MODULE_ENABLED)) {
            return 0;
        }
        $moduleEnabled = Mage::getModel('core/config_data')->load('customordernumber/general/' . str_rot13('frevny'), 'path')->getValue();
        if (empty($moduleEnabled) || !$moduleEnabled || (0x28 !== strlen(trim($moduleEnabled)))) {
            return 0;
        }
        if (!Mage::registry('moduleString')) {
            Mage::register('moduleString', 'false');
        }
        return true;
    }
}