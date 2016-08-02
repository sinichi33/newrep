<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-05T17:25:29+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/System/Config/Source/Grid/Type.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_System_Config_Source_Grid_Type
{
    public function toOptionArray()
    {
        return Mage::getSingleton('xtento_enhancedgrid/grid')->getTypes();
    }
}