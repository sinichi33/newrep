<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2015-06-29T14:45:26+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/Dibspw.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_Dibspw extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'DibsPW Payment Method Export',
            'category' => 'Payment',
            'description' => 'Export payment information of the DibsPW gateway',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Dibspw_Dibspw',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();

        if (!$this->fieldLoadingRequired('dibspw')) {
            return $returnArray;
        }
        $payment = $collectionItem->getOrder()->getPayment();
        $this->_writeArray = & $returnArray['payment']['dibspw'];

        // Fetch fields to export
        $readAdapter = Mage::getSingleton('core/resource')->getConnection('core_read');
        $dataRow = $readAdapter->fetchRow("SELECT * FROM " . Mage::getSingleton('core/resource')->getTableName('dibs_pw_results') . " WHERE orderid = " . $readAdapter->quote($payment->getOrder()->getRealOrderId()));

        if (is_array($dataRow)) {
            foreach ($dataRow as $key => $value) {
                $this->writeValue($key, $value);
            }
        }

        return $returnArray;
    }
}