<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-12-16T15:44:48+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/MagestoreStorePickup.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_MagestoreStorePickup extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'Magestore Store Pickup Data Export',
            'category' => 'Order',
            'description' => 'Export store pickup data stored by the Magestore Store Pickup extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Magestore_Storepickup',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('magestore_storepickup')) {
            return $returnArray;
        }

        try {
            $storeOrderCollection = Mage::getModel('storepickup/storeorder')->getCollection();
            $storeOrderCollection->addFieldToFilter('order_id', $order->getId());

            if ($storeOrderCollection->count()) {
                $this->_writeArray = & $returnArray['magestore_storepickup_order'];
                $storeOrder = $storeOrderCollection->getFirstItem();
                foreach ($storeOrder->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
            }

            $dataCollection = Mage::getModel('storepickup/store')->getCollection();
            if ($order->getData('pharmengage_nearest_store_id') != '') {
                $orderStoreId = $order->getData('pharmengage_nearest_store_id'); // Use this if populated instead of the store_id
            } else if ($storeOrderCollection->count()) {
                $orderStoreId = $storeOrder->getStoreId();
            } else {
                return $returnArray;
            }
            $dataCollection->addFieldToFilter('store_id', $orderStoreId);

            if ($dataCollection->count()) {
                $this->_writeArray = & $returnArray['magestore_storepickup_store'];
                $store = $dataCollection->getFirstItem();
                foreach ($store->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}