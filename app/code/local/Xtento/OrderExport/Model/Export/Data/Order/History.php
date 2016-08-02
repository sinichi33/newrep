<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-05-15T11:44:36+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Order/History.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Order_History extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'Order Status History',
            'category' => 'Order',
            'description' => 'Export the order status history and any comments added.',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['order_status_history'];
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('order_status_history')) {
            return $returnArray;
        }

        if ($order) {
            foreach ($order->getAllStatusHistory() as $history) {
                $this->_writeArray = & $returnArray['order_status_history'][];
                foreach ($history->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                    if ($key == 'created_at_timestamp') {
                        $this->writeValue('created_at_timestamp', Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($value));
                    }
                }
            }
        }
        $this->_writeArray = & $returnArray;
        // Done
        return $returnArray;
    }
}