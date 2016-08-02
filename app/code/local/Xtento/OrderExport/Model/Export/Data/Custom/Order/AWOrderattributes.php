<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-12-29T21:11:04+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/AWOrderattributes.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_AWOrderattributes extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'aheadWorks Order Attributes Export',
            'category' => 'Order',
            'description' => 'Export order attributes of the aheadWorks Order Attributes extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO, Xtento_OrderExport_Model_Export::ENTITY_CUSTOMER),
            'third_party' => true,
            'depends_module' => 'AW_Orderattributes',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();

        if (!$this->fieldLoadingRequired('aw_orderattributes')) {
            return $returnArray;
        }

        try {
            $this->_writeArray = & $returnArray['aw_orderattributes'];

            // Output attributes
            $attributeCollection = Mage::helper('aw_orderattributes/order')->getAttributeValueCollectionForQuote($collectionItem->getOrder()->getQuoteId());
            foreach ($attributeCollection as $attribute) {
                $attributeModel = $attribute->getAttributeModel();
                $this->writeValue($attributeModel->getCode(), $attribute->getValue());
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}