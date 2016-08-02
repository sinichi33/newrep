<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-10-27T23:49:31+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/CustomwebIsrRecord.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_CustomwebIsrRecord extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'Customweb ISR Record Export',
            'category' => 'Order',
            'description' => 'Export record data stored by the Customweb ISR extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Customweb_Isr',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray['customweb_isr_record']; // Write on "customweb_isr_record" level
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('customweb_isr_record')) {
            return $returnArray;
        }

        try {
            $isrReferenceNumber = $order->getData('isr_reference_number');
            if (!empty($isrReferenceNumber)) {
                $recordCollection = Mage::getModel('customweb_isr/record')->getCollection();
                $recordCollection->addFieldToFilter('reference_number', $isrReferenceNumber);
                $record = $recordCollection->getFirstItem();
                if ($record->getId()) {
                    foreach ($record->getData() as $key => $value) {
                        $this->writeValue($key, $value);
                    }
                }
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}