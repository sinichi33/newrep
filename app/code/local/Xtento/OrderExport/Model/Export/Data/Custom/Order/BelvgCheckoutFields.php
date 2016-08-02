<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2015-02-07T14:21:30+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/BelvgCheckoutFields.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_BelvgCheckoutFields extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'BelVG Checkout Field Data Export',
            'category' => 'Order',
            'description' => 'Export custom order data of the BelVG Checkout Fields extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Belvg_Checkoutfields',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['belvg_checkoutfields']; // Write on "belvg_checkoutfields" level
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('belvg_checkoutfields')) {
            return $returnArray;
        }

        try {
            $orderAttributes = Mage::getModel('checkoutfields/orders')->loadByOrderId($order->getId());
            foreach ($orderAttributes as $orderAttribute) {
                $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($orderAttribute->getAttributeId());
                if ($attribute->getId()) {
                    $this->writeValue($attribute->getAttributeCode(), $orderAttribute->getValue());
                } else {
                    $this->writeValue($orderAttribute->getAttributeId(), $orderAttribute->getValue());
                }
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}