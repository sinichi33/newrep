<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-08-19T10:43:49+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Renderer/Backordered.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Backordered extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $orderItems = $row->getAllItems();
        $backOrderItemQty = 0;
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getQtyBackordered() > 0) {
                $backOrderItemQty += $orderItem->getQtyBackordered();
            }
        }
        $columnHtml = 'No';
        if ($backOrderItemQty > 0) {
            $columnHtml = '<span style="font-size:14px; color: red; font-weight: bold;">'.Mage::helper('xtento_enhancedgrid')->__('Yes').' ('.$backOrderItemQty.')</span>';
        }
        return $columnHtml;
    }
}

?>