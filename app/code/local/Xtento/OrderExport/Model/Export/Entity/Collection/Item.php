<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-14T21:18:25+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Entity/Collection/Item.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Entity_Collection_Item extends Varien_Object
{
    private $_collectionItem;

    public function __construct($collectionItem, $entityType, $currItemNo, $collectionCount)
    {
        $this->_collectionItem = $collectionItem;
        $this->_collectionSize = $collectionCount;
        $this->_currItemNo = $currItemNo;
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_ORDER) {
            $this->setOrder($collectionItem);
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_INVOICE) {
            $this->setOrder($collectionItem->getOrder());
            $this->setInvoice($collectionItem);
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT) {
            $this->setOrder($collectionItem->getOrder());
            $this->setShipment($collectionItem);
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO) {
            $this->setOrder($collectionItem->getOrder());
            $this->setCreditmemo($collectionItem);
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_QUOTE) {
            $this->setOrder($collectionItem);
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_AWRMA) {
            // Load order associated to RMA
            $order = Mage::getModel('sales/order')->loadByIncrementId($collectionItem->getOrderId());
            $this->setOrder($order);
            // Add RMA items into "virtual" allItems array
            $orderItemArray = array();
            $orderItems = unserialize($collectionItem->getOrderItems());
            foreach ($orderItems as $orderItemId => $qty) {
                $orderItem = Mage::getModel('sales/order_item')->load($orderItemId);
                $orderItem->setData('aw_rma_qty', $qty);
                $orderItemArray[] = $orderItem;
            }
            $collectionItem->setAllItems($orderItemArray);
        }
        if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA) {
            // Load order associated to RMA
            $order = Mage::getModel('sales/order')->loadByIncrementId($collectionItem->getRmaOrderId());
            $this->setOrder($order);
            $orderItems = Mage::getModel('ProductReturn/RmaProducts')->getCollection()->addFieldToFilter('rp_rma_id', array(
                'eq' => $collectionItem->getRmaId()
            ));
            $orderItemArray = array();
            foreach ($orderItems as $item) {
                $orderItem = Mage::getModel('sales/order_item')->load($item->getRpOrderitemId());
                $orderItem->setData('boost_rma_qty', $item->getRpQty());
                $orderItemArray[] = $orderItem;
            }
            $collectionItem->setAllItems($orderItemArray);
        }
    }

    public function getObject()
    {
        return $this->_collectionItem;
    }
}