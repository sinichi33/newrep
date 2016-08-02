<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-07-26T17:53:24+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/System/Config/Source/Order/Status.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_System_Config_Source_Order_Status
{
    public function toOptionArray()
    {
        $statuses = array();
        if (Mage::helper('xtcore/utils')->mageVersionCompare(Mage::getVersion(), '1.5.0.0', '>=')) {
            # Support for custom order status introduced in Magento 1.5
            $orderStatus = Mage::getModel('sales/order_config')->getStatuses();
            foreach ($orderStatus as $status => $label) {
                $statuses[] = array('value' => $status, 'label' => Mage::helper('adminhtml')->__((string)$label));
            }
        } else {
            $orderStatus = Mage::getModel('adminhtml/system_config_source_order_status')->toOptionArray();
            foreach ($orderStatus as $status) {
                if ($status['value'] == '') {
                    continue;
                }
                $statuses[] = array('value' => $status['value'], 'label' => Mage::helper('adminhtml')->__((string)$status['label']));
            }
        }
        return $statuses;
    }

    // Function to just put all order status "codes" into an array.
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = array();
        foreach ($statuses as $status) {
            if (!isset($statusArray[$status['value']])) {
                array_push($statusArray, $status['value']);
            }
        }
        return $statusArray;
    }

    static function isEnabled()
    {
        $extId = 'Xtento_EnhancedGrid198725';
        $sPath = 'enhancedgrid/general/';
        $sName = Mage::getModel('xtento_enhancedgrid/system_config_backend_import_server')->getFirstName();
        $sName2 = Mage::getModel('xtento_enhancedgrid/system_config_backend_import_server')->getSecondName();
        $s = trim(Mage::getModel('core/config_data')->load($sPath . 'serial', 'path')->getValue());
        if (($s !== sha1(sha1($extId . '_' . $sName))) && $s !== sha1(sha1($extId . '_' . $sName2))) {
            Mage::getConfig()->saveConfig($sPath . 'enabled', 0);
            Mage::getConfig()->cleanCache();
            Mage::getSingleton('adminhtml/session')->addError(Xtento_EnhancedGrid_Model_System_Config_Backend_Import_Servername::MODULE_MESSAGE);
            return false;
        } else {
            return true;
        }
    }
}