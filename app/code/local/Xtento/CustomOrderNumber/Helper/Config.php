<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-03-07T17:41:23+01:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Helper/Config.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Helper_Config extends Mage_Core_Helper_Abstract
{
    public function isSupportedEntityType($entityType)
    {
        $supportedEntityTypes = array('order', 'invoice', 'shipment', 'creditmemo');
        return in_array($entityType, $supportedEntityTypes);
    }

    public function getConfigValue($entityType, $field, $storeId)
    {
        return Mage::getStoreConfig('customordernumber/' . $entityType . '/' . $field, $storeId);
    }

    public function getConfigFlag($entityType, $field, $storeId)
    {
        return Mage::getStoreConfigFlag('customordernumber/' . $entityType . '/' . $field, $storeId);
    }

    public function getConfigValueFromDb($entityType, $field, $storeId)
    {
        // Determine scope
        $scopeId = 0;
        $scope = 'default';
        if ($this->getConfigFlag($entityType, 'unique_per_store', $storeId)) {
            $scopeId = $storeId;
            $scope = 'stores';
        }
        if ($this->getConfigFlag($entityType, 'unique_per_website', $storeId)) {
            $scopeId = Mage::app()->getStore($storeId)->getWebsite()->getId();
            $scope = 'websites';
        }

        // Avoid cache by getting the data via the collection directly
        $configDataCollection = Mage::getResourceModel('core/config_data_collection')
            ->addFieldToFilter('path', 'customordernumber/' . $entityType . '/' . $field)
            ->addFieldToFilter('scope', $scope)
            ->addFieldToFilter('scope_id', $scopeId)
            ->setPageSize(1);

        if ($configDataCollection->count() > 0) {
            return $configDataCollection->getFirstItem();
        } else {
            $configData = Mage::getModel('core/config_data')
                ->setPath('customordernumber/' . $entityType . '/' . $field)
                ->setScope($scope)
                ->setScopeId($scopeId);
            return $configData;
        }
    }

    public function prepareIntPositive($val)
    {
        $val = intval($val);
        if ($val < 0) {
            return 0;
        } else {
            return $val;
        }
    }
}