<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-17T12:56:05+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/DhShipNote.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_DhShipNote extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'DH_ShipNote Export',
            'category' => 'Order',
            'description' => 'Export ship note saved by the DH_ShipNote extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'Dh_ShipNote',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if (!$this->fieldLoadingRequired('dh_shipnote')) {
            return $returnArray;
        }

        try {
            $this->_writeArray = & $returnArray['dh_shipnote']; // Write on "dh_shipnote" level
            $note = Mage::getModel('shipnote/note')->loadByOrder($order);
            if ($note && $note->getId()) {
                foreach ($note->getData() as $key => $value) {
                    $this->writeValue($key, $value);
                }
            }
        } catch (Exception $e) {

        }

        // Done
        return $returnArray;
    }
}