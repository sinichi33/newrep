<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-05-20T17:40:44+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/TiDeliverybydate.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_TiDeliverybydate extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'TI Delivery By Date Export',
            'category' => 'Order',
            'description' => 'Export delivery date of the TI Delivery By Date extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Ti_Deliverybydate',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['ti_deliverybydate']; // Write on "ti_deliverybydate" level
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('ti_deliverybydate')) {
            return $returnArray;
        }

        try {
            $deliveryByDateCollection = Mage::getModel('deliverybydate/deliverybydate')->getCollection();
            $deliveryByDateCollection->addFieldToFilter('order_id', $order->getId());
            $deliveryByDate = $deliveryByDateCollection->getFirstItem();
            if ($deliveryByDate->getId()) {
                foreach ($deliveryByDate->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}