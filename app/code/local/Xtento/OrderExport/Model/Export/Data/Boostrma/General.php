<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-14T21:15:30+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Boostrma/General.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Boostrma_General extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    private $_origWriteArray;

    public function getConfiguration()
    {
        return array(
            'name' => 'BoostRMA information',
            'category' => 'Boostrma',
            'description' => 'Export extended information about the BoostMyShop RMA object.',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA),
            'third_party' => true,
            'depends_module' => 'MDN_ProductReturn'
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        $this->_writeArray = & $returnArray; // Write directly on object level
        $this->_origWriteArray = & $this->_writeArray;
        // Fetch fields to export
        $object = $collectionItem->getObject();

        $rmaObject = Mage::getModel('ProductReturn/rma')->load($object->getRmaId());
        if ($this->fieldLoadingRequired('entity_id')) {
            $this->writeValue('entity_id', $rmaObject->getRmaId());
        }
        if ($this->fieldLoadingRequired('created_at')) {
            $this->writeValue('created_at', $rmaObject->getRmaCreatedAt());
        }
        if ($this->fieldLoadingRequired('rma_customer_email')) {
            $this->writeValue('rma_customer_email', $rmaObject->getRmaCustomerEmail());
        }

        // Done
        return $returnArray;
    }
}