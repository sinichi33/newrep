<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-01-12T13:58:19+01:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Model/System/Config/Backend/Import/Enabled.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Model_System_Config_Backend_Import_Enabled extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        Mage::register('customordernumber_modify_event', true, true);
        parent::_beforeSave();
    }

    public function has_value_for_configuration_changed($observer)
    {
        if (Mage::registry('customordernumber_modify_event') == true) {
            Mage::unregister('customordernumber_modify_event');
            Xtento_CustomOrderNumber_Model_System_Config_Source_Order_Status::isEnabled();
        }
    }
}
