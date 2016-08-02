<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2015-05-20T13:28:41+02:00
 * File:          app/code/local/Xtento/OrderExport/Helper/Export.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Helper_Export extends Mage_Core_Helper_Abstract
{
    public function getExportEntity($entity)
    {
        if ($entity == Xtento_OrderExport_Model_Export::ENTITY_ORDER) {
            return 'sales/order';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_INVOICE) {
            return 'sales/order_invoice';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT) {
            return 'sales/order_shipment';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO) {
            return 'sales/order_creditmemo';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_QUOTE) {
            return 'sales/quote';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_CUSTOMER) {
            return 'customer/customer';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_AWRMA) {
            return 'awrma/entity';
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA) {
            return 'ProductReturn/Rma';
        }
        Mage::throwException(Mage::helper('xtento_orderexport')->__('Could not find export entity "%s"', $entity));
    }

    public function getLastIncrementId($entity)
    {
        if ($entity == Xtento_OrderExport_Model_Export::ENTITY_QUOTE) {
            $collection = Mage::getModel($this->getExportEntity($entity))->getCollection()
                ->addFieldToSelect('entity_id');
            $collection->getSelect()->limit(1)->order('entity_id DESC');
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_AWRMA) {
            $collection = Mage::getModel($this->getExportEntity($entity))->getCollection()
                ->addFieldToSelect('id');
            $collection->getSelect()->limit(1)->order('id DESC');
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_CUSTOMER) {
            $collection = Mage::getModel($this->getExportEntity($entity))->getCollection()
                ->addAttributeToSelect('entity_id');
            $collection->getSelect()->limit(1)->order('entity_id DESC');
        } else if ($entity == Xtento_OrderExport_Model_Export::ENTITY_BOOSTRMA) {
            $collection = Mage::getModel($this->getExportEntity($entity))->getCollection()
                ->addFieldToSelect('rma_id')->getData();
            if (!function_exists('array_column')) {
                $collection = $this->_getArrayColumn($collection, 'rma_id');
            } else {
                $collection = array_column($collection, 'rma_id');
            }
            $lastId = end($collection);
            return $lastId;
        } else {
            $collection = Mage::getModel($this->getExportEntity($entity))->getCollection()
                ->addAttributeToSelect('increment_id')
                ->addAttributeToSort('entity_id', 'desc')
                ->setPage(1, 1);
        }
        $object = $collection->getFirstItem();
        return ($object->getIncrementId() ? $object->getIncrementId() : $object->getId());
    }

    public function getExportBkpDir()
    {
        return Mage::getBaseDir('var') . DS . "export_bkp" . DS;
    }

    private function _getArrayColumn(array $input, $column_key, $index_key = null)
    {
        $result = array();
        foreach ($input as $k => $v)
            $result[$index_key ? $v[$index_key] : $k] = $v[$column_key];
        return $result;
    }
}