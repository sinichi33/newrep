<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2013-10-05T17:15:37+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Adminhtml/Grid/Grid/Renderer/Status.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Adminhtml_Grid_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if ($row->getEnabled() == 0) {
            return '<span class="grid-severity-critical"><span>' . Mage::helper('xtento_enhancedgrid')->__('Disabled') . '</span></span>';
        } else if ($row->getEnabled() == 1) {
            return '<span class="grid-severity-notice"><span>' . Mage::helper('xtento_enhancedgrid')->__('Enabled') . '</span></span>';
        }
    }
}