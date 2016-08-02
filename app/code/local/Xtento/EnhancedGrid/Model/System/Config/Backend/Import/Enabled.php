<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-05T16:17:55+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/System/Config/Backend/Import/Enabled.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_System_Config_Backend_Import_Enabled extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        Mage::register('enhancedgrid_modify_event', true, true);
        parent::_beforeSave();
    }

    public function has_value_for_configuration_changed($observer)
    {
        if (Mage::registry('enhancedgrid_modify_event') == true) {
            Mage::unregister('enhancedgrid_modify_event');
            Xtento_EnhancedGrid_Model_System_Config_Source_Order_Status::isEnabled();
        }
    }
}
