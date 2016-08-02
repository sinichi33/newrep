<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-08-11T14:11:55+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Helper/Data.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Helper_Data extends Mage_Core_Helper_Abstract
{
    const EDITION = 'EE';

    public function getModuleEnabled()
    {
        if (!Mage::getStoreConfigFlag('enhancedgrid/general/enabled')) {
            return 0;
        }
        $moduleEnabled = Mage::getModel('core/config_data')->load('enhancedgrid/general/' . str_rot13('frevny'), 'path')->getValue();
        if (empty($moduleEnabled) || !$moduleEnabled || (0x28 !== strlen(trim($moduleEnabled)))) {
            return 0;
        }
        return true;
    }

    public function getGridBlockType($block)
    {
        $request = Mage::app()->getRequest();
        $gridType = false;
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER
            && $block->getId() == 'sales_order_grid' && $block instanceof Mage_Adminhtml_Block_Widget_Grid
        ) {
            $gridType = Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER;
        }
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER
            && $block->getId() == 'sales_order_grid_archive' && $block instanceof Mage_Adminhtml_Block_Widget_Grid
        ) {
            // Enterprise Sales Order Archive
            $gridType = Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER;
        }
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_INVOICE
            && $block->getId() == 'sales_invoice_grid' && $block instanceof Mage_Adminhtml_Block_Widget_Grid
        ) {
            $gridType = Xtento_EnhancedGrid_Model_Grid::GRID_SALES_INVOICE;
        }
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_SHIPMENT
            && $block->getId() == 'sales_shipment_grid' && $block instanceof Mage_Adminhtml_Block_Widget_Grid
        ) {
            $gridType = Xtento_EnhancedGrid_Model_Grid::GRID_SALES_SHIPMENT;
        }
        if (Mage::helper('xtento_enhancedgrid')->getController($request) == Xtento_EnhancedGrid_Model_Grid::GRID_SALES_CREDITMEMO
            && $block->getId() == 'sales_creditmemo_grid' && $block instanceof Mage_Adminhtml_Block_Widget_Grid
        ) {
            $gridType = Xtento_EnhancedGrid_Model_Grid::GRID_SALES_CREDITMEMO;
        }
        return $gridType;
    }

    public function getController($request)
    {
        if (in_array($request->getControllerName(), array('order', 'sales_order', 'admin_sales_order', 'adminhtml_sales_order', 'orderspro_order', 'sales_archive'))) {
            return Xtento_EnhancedGrid_Model_Grid::GRID_SALES_ORDER;
        }
        if (in_array($request->getControllerName(), array('invoice', 'sales_invoice', 'adminhtml_sales_invoice'))) {
            return Xtento_EnhancedGrid_Model_Grid::GRID_SALES_INVOICE;
        }
        if (in_array($request->getControllerName(), array('shipment', 'sales_shipment', 'adminhtml_sales_shipment'))) {
            return Xtento_EnhancedGrid_Model_Grid::GRID_SALES_SHIPMENT;
        }
        if (in_array($request->getControllerName(), array('creditmemo', 'sales_creditmemo', 'adminhtml_sales_creditmemo'))) {
            return Xtento_EnhancedGrid_Model_Grid::GRID_SALES_CREDITMEMO;
        }
        return false;
    }

    /*
     * Is the current request a CSV/Excel XML export using the built-in functionality of Magento?
     */
    public function isMageExport()
    {
        return (stristr(Mage::app()->getRequest()->getControllerName(), 'sales_') && (Mage::app()->getRequest()->getActionName() == 'exportCsv' || Mage::app()->getRequest()->getActionName() == 'exportExcel'));
    }
}