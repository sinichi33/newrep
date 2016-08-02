<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-06-05T18:29:52+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/Observer.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_Observer
{
    const MODULE_ENABLED = 'enhancedgrid/general/enabled';

    public function adminhtmlBlockHtmlBefore(Varien_Event_Observer $observer)
    {
        if (!$this->_initBlocks()) {
            return $this;
        }
        $block = $observer->getEvent()->getBlock();
        Mage::getSingleton('xtento_enhancedgrid/grid_processor')->processBlock($block);
    }

    public function coreBlockAbstractPrepareLayoutAfter(Varien_Event_Observer $observer)
    {
        if (!$this->_initBlocks()) {
            return $this;
        }
        $block = $observer->getEvent()->getBlock();
        if (Mage::helper('xtento_enhancedgrid')->isMageExport()) {
            Mage::getSingleton('xtento_enhancedgrid/grid_processor')->processBlock($block);
        }
    }

    public function coreBlockAbstractToHtmlAfter(Varien_Event_Observer $observer)
    {
        if (!$this->_initBlocks()) {
            return $this;
        }
        $block = $observer->getEvent()->getBlock();
        $transport = $observer->getEvent()->getTransport();
        if (empty($transport)) {
            // Magento 1.3 or older
            return $this;
        }
        // Add "Grid Customization" button
        if (Mage::helper('xtento_enhancedgrid')->getGridBlockType($block)) {
            //if (Mage::getSingleton('admin/session')->isAllowed('sales/enhancedgrid/customize')) {
            if (!Mage::app()->getRequest()->isPost()) {
                if (Mage::getDesign()->getTheme('template') == 'go') {
                    // Compatibility with the popular "Magento Go" admin theme
                    $buttonHtml = '<div style="text-align: right; margin: 0 0 6px;"><button title="' . Mage::helper('xtento_enhancedgrid')->__('Grid Customization') . '" type="button" class="scalable go" onclick="setLocation(\'' . Mage::helper('adminhtml')->getUrl('*/enhancedgrid_grid/index') . '\')"><span><span><span>' . Mage::helper('xtento_enhancedgrid')->__('Grid Customization') . '</span></span></span></button></div>';
                } else {
                    $buttonHtml = '<div style="text-align: right; margin: 0 0 6px;"><button title="' . Mage::helper('xtento_enhancedgrid')->__('Grid Customization') . '" type="button" class="scalable go" onclick="setLocation(\'' . Mage::helper('adminhtml')->getUrl('*/enhancedgrid_grid/index') . '\')"><span>' . Mage::helper('xtento_enhancedgrid')->__('Grid Customization') . '</span></button></div>';
                }
                $transport->setHtml($buttonHtml . $transport->getHtml());
            }
            //}
        }
        return $this;
    }

    public function controllerActionPredispatch(Varien_Event_Observer $observer)
    {
        if (!$this->_initBlocks()) {
            return $this;
        }
        Mage::getSingleton('xtento_enhancedgrid/grid_rewrite')->rewriteGrids();
    }

    public function coreCollectionAbstractLoadBefore(Varien_Event_Observer $observer)
    {
        if (Mage::registry('xtento_enhancedgrid_block_info') !== null) {
            $blockInfo = Mage::registry('xtento_enhancedgrid_block_info');
            #if (!Mage::helper('xtento_enhancedgrid')->isMageExport()) {
            #    Mage::unregister('xtento_enhancedgrid_block_info');
            #}
            if (!$this->_initBlocks()) {
                return $this;
            }
            $addCustomFields = false;
            if (@class_exists('Mage_Sales_Model_Mysql4_Order_Collection', false) && $observer->getCollection() instanceof Mage_Sales_Model_Mysql4_Order_Collection) {
                $addCustomFields = true;
            }
            if (@class_exists('Mage_Sales_Model_Mysql4_Order_Collection_Abstract', false) && $observer->getCollection() instanceof Mage_Sales_Model_Mysql4_Order_Collection_Abstract) {
                $addCustomFields = true;
            }
            if (@class_exists('Mage_Sales_Model_Resource_Order_Collection', false) && $observer->getCollection() instanceof Mage_Sales_Model_Resource_Order_Collection) {
                $addCustomFields = true;
            }
            if (@class_exists('Mage_Sales_Model_Resource_Order_Collection_Abstract', false) && $observer->getCollection() instanceof Mage_Sales_Model_Resource_Order_Collection_Abstract) {
                $addCustomFields = true;
            }
            if ($addCustomFields && !preg_match("/_item/i", get_class($observer->getCollection()))) {
                Mage::unregister('xtento_enhancedgrid_block_info');
                Mage::getSingleton('xtento_enhancedgrid/grid_collection')->addCustomFieldsToCollection($observer->getCollection(), $blockInfo);
            }
        }
    }

    private function _initBlocks()
    {
        if (!Mage::helper('xtento_enhancedgrid')->getModuleEnabled()) {
            // Module isn't enabled yet.
            return false;
        }
        return true;
    }

    public function controllerActionPredispatchAdminhtml($event)
    {
        // Check if this module was made for the edition (CE/PE/EE) it's being run in
        $controller = $event->getControllerAction();
        if (in_array($controller->getRequest()->getControllerName(), array('order', 'sales_order', 'adminhtml_sales_order', 'admin_sales_order', 'orderspro_order'))) {
            if (!Mage::registry('edition_warning_shown')) {
                if (Xtento_EnhancedGrid_Helper_Data::EDITION !== 'EE' && Xtento_EnhancedGrid_Helper_Data::EDITION !== '') {
                    if (Mage::helper('xtcore/utils')->getIsPEorEE() && Mage::helper('xtento_enhancedgrid')->getModuleEnabled()) {
                        if (Xtento_EnhancedGrid_Helper_Data::EDITION !== 'EE') {
                            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtcore')->__('Attention: The installed Enhanced Order Grid version is not compatible with the Enterprise Edition of Magento. The compatibility of the currently installed extension version has only been confirmed with the Community Edition of Magento. Please go to <a href="https://www.xtento.com" target="_blank">www.xtento.com</a> to purchase or download the Enterprise Edition of this extension in our store if you\'ve already purchased it.'));
                        }
                    }
                }
                Mage::register('edition_warning_shown', true);
            }
        }
    }
}