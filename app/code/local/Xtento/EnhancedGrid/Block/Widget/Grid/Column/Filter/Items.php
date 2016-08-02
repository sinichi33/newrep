<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-05-12T23:33:33+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Filter/Items.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Filter_Items extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public static function itemFilter($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return true;
        }
        $collection->getSelect()->having("item_filter like ?", "%$value%");
        return true;
    }
}