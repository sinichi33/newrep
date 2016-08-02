<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-04-07T14:13:48+02:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Model/Observer.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Model_Observer
{
    const MODULE_ENABLED = 'customordernumber/general/enabled';
    const TYPE_INVOICE = 'invoice';
    const TYPE_SHIPMENT = 'shipment';
    const TYPE_CREDITMEMO = 'creditmemo';

    public function salesOrderInvoiceSaveBefore($observer)
    {
        $this->_updateIncrementId($observer->getInvoice(), self::TYPE_INVOICE);
        return $this;
    }

    public function salesOrderShipmentSaveBefore($observer)
    {
        $this->_updateIncrementId($observer->getShipment(), self::TYPE_SHIPMENT);
        return $this;
    }

    public function salesOrderCreditmemoSaveBefore($observer)
    {
        $this->_updateIncrementId($observer->getCreditmemo(), self::TYPE_CREDITMEMO);
        return $this;
    }

    /**
     * If "use same number as order number" is used, set the order increment_id for object
     *
     * @param $object
     * @param $entityType
     * @return $this
     */
    private function _updateIncrementId($object, $entityType)
    {
        if (!$object->getId()) {
            $order = $object->getOrder();
            $storeId = $order->getStore()->getStoreId();

            // Is (order/invoice/...) number customizer enabled for this store ID?
            if (!Mage::helper('xtento_customordernumber/config')->getConfigFlag($entityType, 'enabled', $storeId)) {
                return $this;
            }

            // Shall the order number be used? Just for invoice/shipment/credit memo numbers
            if (!Mage::helper('xtento_customordernumber/config')->getConfigFlag($entityType, 'same_as_order', $storeId)) {
                return $this;
            }

            $orderIncrementId = $order->getIncrementId();
            $numberPrefix = Mage::helper('xtento_customordernumber/config')->getConfigValue($entityType, 'id_prefix', $storeId);
            $replaceInId = Mage::helper('xtento_customordernumber/config')->getConfigValue($entityType, 'replace_in_id', $storeId);
            if (!empty($replaceInId)) {
                $orderIncrementId = str_replace($replaceInId, "", $orderIncrementId);
            }
            if (empty($orderIncrementId)) {
                return $this;
            }

            $maxIterations = 99;
            $newIncrementId = false;
            $subIncrementIdCounter = 0;
            while ($newIncrementId === false) {
                if ($subIncrementIdCounter > $maxIterations) {
                    break;
                }
                if ($subIncrementIdCounter > 0) {
                    $newIncrementId = $numberPrefix . $orderIncrementId . '-' . $subIncrementIdCounter;
                } else {
                    $newIncrementId = $numberPrefix . $orderIncrementId;
                }
                $collection = Mage::getModel('sales/order_' . $entityType)->getCollection()
                    ->addAttributeToFilter('increment_id', $newIncrementId);
                if ($collection->count()) {
                    $newIncrementId = false;
                    $subIncrementIdCounter++;
                } else {
                    $object->setIncrementId($newIncrementId);
                }
            }
        }
        return $this;
    }

    private function _initBlocks($block)
    {
        if (!Mage::helper('xtento_customordernumber')->getModuleEnabled()) {
            return false;
        }
        return true;
    }

    public function controllerActionPredispatchAdminhtml($event)
    {
        // Check if this module was made for the edition (CE/PE/EE) it's being run in
        $controller = $event->getControllerAction();
        if ($controller->getRequest()->getControllerName() == 'system_config' && $controller->getRequest()->getParam('section') == 'customordernumber') {
            if (!Mage::registry('edition_warning_shown')) {
                if (Xtento_CustomOrderNumber_Helper_Data::EDITION !== 'EE' && Xtento_CustomOrderNumber_Helper_Data::EDITION !== '') {
                    if (Mage::helper('xtcore/utils')->getIsPEorEE() && Mage::helper('xtento_customordernumber')->getModuleEnabled()) {
                        if (Xtento_CustomOrderNumber_Helper_Data::EDITION !== 'EE') {
                            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('xtcore')->__('Attention: The installed Order Number Customizer version is not compatible with the Enterprise Edition of Magento. The compatibility of the currently installed extension version has only been confirmed with the Community Edition of Magento. Please go to <a href="https://www.xtento.com" target="_blank">www.xtento.com</a> to purchase or download the Enterprise Edition of this extension in our store if you\'ve already purchased it.'));
                        }
                    }
                }
                Mage::register('edition_warning_shown', true);
            }
        }
    }
}