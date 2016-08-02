<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-11-05T20:45:46+01:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/AWPoints.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_AWPoints extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'aheadWorks Points Summary Export',
            'category' => 'Order',
            'description' => 'Export point summary of the aheadWorks Points & Rewards extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO, Xtento_OrderExport_Model_Export::ENTITY_CUSTOMER),
            'third_party' => true,
            'depends_module' => 'AW_Points',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();

        if (!$this->fieldLoadingRequired('aw_points') && !$this->fieldLoadingRequired('aw_points_orderspend')) {
            return $returnArray;
        }

        try {
            if ($this->fieldLoadingRequired('aw_points')) {
                $this->_writeArray = & $returnArray['aw_points'];
                if ($entityType == Xtento_OrderExport_Model_Export::ENTITY_CUSTOMER) {
                    $pointsSummary = Mage::getModel('points/summary')->loadByCustomerID($collectionItem->getObject()->getId());
                } else {
                    $pointsSummary = Mage::getModel('points/summary')->loadByCustomerID($collectionItem->getOrder()->getCustomerId());
                }
                if ($pointsSummary->getId()) {
                    foreach ($pointsSummary->getData() as $key => $value) {
                        $this->writeValue($key, $value);
                    }
                }
            }

            if ($this->fieldLoadingRequired('aw_points_orderspend')) {
                $this->_writeArray = & $returnArray['aw_points_orderspend'];
                $readAdapter = Mage::getSingleton('core/resource')->getConnection('core_read');
                $dataRow = $readAdapter->fetchRow("SELECT * FROM " . Mage::getSingleton('core/resource')->getTableName('points/transaction_orderspend') . " WHERE order_increment_id = " . $readAdapter->quote($collectionItem->getOrder()->getIncrementId()));

                if (is_array($dataRow)) {
                    foreach ($dataRow as $key => $value) {
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