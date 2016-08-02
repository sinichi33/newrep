<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-05-12T23:33:50+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Block/Widget/Grid/Column/Renderer/Trackingtable.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Block_Widget_Grid_Column_Renderer_Trackingtable extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '';
        $trackingNumbers = array();
        $trackingNumbersPlaintext = array();

        $orderId = $row->getEntityId();
        if ($row->getData('order_id')) {
            $orderId = $row->getOrderId();
        }

        $trackingUrl = "";
        if (!Mage::helper('xtento_enhancedgrid')->isMageExport()) {
            if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.4.0.0', '>=')) {
                if (!$row->getData('protect_code')) {
                    $row = Mage::getModel('sales/order')->load($orderId);
                }
                $trackingUrl = Mage::helper('shipping')->getTrackingPopupUrlBySalesModel($row);
            } else {
                $trackingUrl = Mage::helper('shipping')->getTrackingPopUpUrlByOrderId($orderId);
            }
        }
        // Starting from Magento 1.6, the trackingnumber field has been renamed from number to track_number
        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.6.0.0', '>=')) {
            $tracks = Mage::getModel('sales/order_shipment_track')
                ->getCollection()
                ->addAttributeToSelect('track_number')
                ->addAttributeToSelect('title')
                ->setOrderFilter($orderId);
            foreach ($tracks as $track) {
                $trackingNumbers[] = '<tr><td>' . $this->escapeHtml($track->getTitle()) . '</td><td><a href="#" alt="' . $this->escapeHtml($track->getTitle()) . '" title="' . $this->escapeHtml($track->getTitle()) . '" onclick="popWin(\'' . $trackingUrl . '\',\'trackorder\',\'width=800,height=600,left=0,top=0,resizable=yes,scrollbars=yes\')" >' . $this->escapeHtml($track->getTrackNumber()) . '</a></td></tr>';
                $trackingNumbersPlaintext[] = sprintf("%s (%s)", $track->getTrackNumber(), $track->getTitle());
            }
        } else {
            $tracks = Mage::getModel('sales/order_shipment_track')
                ->getCollection()
                ->addAttributeToSelect('number')
                ->addAttributeToSelect('title')
                ->setOrderFilter($orderId);
            foreach ($tracks as $track) {
                $trackingNumbers[] = '<tr><td>' . $this->escapeHtml($track->getTitle()) . '</td><td><a href="#" alt="' . $this->escapeHtml($track->getTitle()) . '" title="' . $this->escapeHtml($track->getTitle()) . '" onclick="popWin(\'' . $trackingUrl . '\',\'trackorder\',\'width=800,height=600,left=0,top=0,resizable=yes,scrollbars=yes\')" >' . $this->escapeHtml($track->getNumber()) . '</a></td></tr>';
                $trackingNumbersPlaintext[] = sprintf("%s (%s)", $track->getNumber(), $track->getTitle());
            }
        }

        if (!empty($trackingNumbers)) {
            if (Mage::helper('xtento_enhancedgrid')->isMageExport()) {
                $html = implode(', ', $trackingNumbersPlaintext);
            } else {
                $html = '<div><table class="xtento-item-table"><thead><tr class="xtento-item-tr">';
                $html .= '<th>'.Mage::helper('xtento_enhancedgrid')->__('Carrier').'</th>';
                $html .= '<th>'.Mage::helper('xtento_enhancedgrid')->__('Tracking Number').'</th>';
                $html .= '</tr></thead><tbody>';
                $html .= implode("", $trackingNumbers);
                $html .= '</tbody></table></div>';
            }
        }
        return $html;
    }

    /*
     * Return dummy filter.
     */
    public function getFilter()
    {
        return false;
    }
}

?>