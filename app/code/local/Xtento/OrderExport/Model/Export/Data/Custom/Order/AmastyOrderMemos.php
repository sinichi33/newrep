<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-02-03T16:11:54+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/AmastyOrderMemos.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_AmastyOrderMemos extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'Amasty Order Memos Export',
            'category' => 'Order',
            'description' => 'Export custom order memos of Amasty Order Memos extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Amasty_Orderattach',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['amasty_orderattach']; // Write on "amasty_orderattach" level
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('amasty_orderattach')) {
            return $returnArray;
        }

        try {
            $orderAttachment = Mage::getModel('amorderattach/order_field');
            $orderAttachment->load($order->getId(), 'order_id');
            if ($orderAttachment->getId()) {
                foreach ($orderAttachment->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}