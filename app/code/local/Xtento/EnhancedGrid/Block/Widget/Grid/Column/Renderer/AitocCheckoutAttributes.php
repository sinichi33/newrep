<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-05-12T23:33:50+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Renderer/AitocCheckoutAttributes.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_AitocCheckoutAttributes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $orderId = $row->getEntityId();
        $order = Mage::getModel('sales/order')->load($orderId);
        if (!$order->getId()) {
            return "";
        }
        $fieldId = $this->getColumn()->getIndex();
        $aitocData = array();
        // Fetch fields
        $oAitcheckoutfields = Mage::getModel('aitcheckoutfields/aitcheckoutfields');
        $customAttrList = $oAitcheckoutfields->getOrderCustomData($order->getEntityId(), $order->getStoreId(), true);
        foreach ($customAttrList as $aCustomAttrList) {
            if (isset($aCustomAttrList['code']) && isset($aCustomAttrList['value'])) {
                $aitocData[$aCustomAttrList['code']] = $aCustomAttrList['value'];
            }
        }
        if ($order->getCustomerId()) {
            $customAttrList = $oAitcheckoutfields->getCustomerData($order->getCustomerId(), $order->getStoreId(), true);
            foreach ($customAttrList as $aCustomAttrList) {
                if (isset($aCustomAttrList['code']) && isset($aCustomAttrList['value'])) {
                    $aitocData[$aCustomAttrList['code']] = $aCustomAttrList['value'];
                }
            }
        }
        if (isset($aitocData[$fieldId])) {
            return $aitocData[$fieldId];
        } else {
            return "";
        }
    }

    /*
     * Return dummy filter.
     */
    public function getFilter()
    {
        return false;
    }
}

/* Sample column in Custom.php:
                'aitoc_affiliate_name' => array(
                    'header' => Mage::helper('xtento_enhancedgrid')->__('Affiliate Name'),
                    'id' => 'aitoc_affiliate_name',
                    'index' => 'affiliate_name', // Attribute code in Aitoc field manager
                    'change_filter' => false,
                    'change_renderer' => false,
                    'type' => 'text',
                    'sortable' => false,
                    'renderer' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_AitocCheckoutAttributes',
                    'filter' => 'Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_AitocCheckoutAttributes'
                ),
*/

?>