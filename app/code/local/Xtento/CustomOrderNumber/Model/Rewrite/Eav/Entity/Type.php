<?php

/**
 * Product:       Xtento_CustomOrderNumber (1.0.6)
 * ID:            8xAf+oCns/QOEdaiZub3aLgVCGFua6nB8AAizsm8sRY=
 * Packaged:      2016-02-24T02:27:18+00:00
 * Last Modified: 2014-10-29T16:40:39+01:00
 * File:          app/code/local/Xtento/CustomOrderNumber/Model/Rewrite/Eav/Entity/Type.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_CustomOrderNumber_Model_Rewrite_Eav_Entity_Type extends Mage_Eav_Model_Entity_Type
{
    public function fetchNewIncrementId($storeId = null)
    {
        $entityTypeCode = $this->getEntityTypeCode();
        // Fetch original increment ID so eav/store tables are updated
        $originalIncrementId = parent::fetchNewIncrementId($storeId);

        try {
            if (!$originalIncrementId) {
                // There was a problem. Don't hook in either.
                return $originalIncrementId;
            }

            // Is the module enabled?
            if (!Mage::helper('xtento_customordernumber')->getModuleEnabled()) {
                return $originalIncrementId;
            }

            // Is supported entity_type?
            if (!$this->_getConfigHelper()->isSupportedEntityType($entityTypeCode)) {
                return $originalIncrementId;
            }

            // Is (order/invoice/...) number customizer enabled for this store ID?
            if (!$this->_getConfigHelper()->getConfigFlag($entityTypeCode, 'enabled', $storeId)) {
                return $originalIncrementId;
            }

            // Shall we create a new number or will the order number be used instead? Just for invoice/shipment/credit memo numbers
            if ($entityTypeCode == 'invoice' || $entityTypeCode == 'shipment' || $entityTypeCode == 'creditmemo') {
                if ($this->_getConfigHelper()->getConfigFlag($entityTypeCode, 'same_as_order', $storeId)) {
                    return $originalIncrementId;
                }
            }

            $newIncrementId = $this->_generateCustomIncrementId($storeId);
            if (!$newIncrementId || empty($newIncrementId)) {
                Mage::log(sprintf('Attention: For %s ID %s, an empty increment ID was generated: %s', $entityTypeCode, $originalIncrementId, $newIncrementId), null, 'xtento_customordernumber.log', true);
                return $originalIncrementId;
            }

            // Check if increment ID exists already, if yes return non-existing increment ID
            if ($this->_isIncrementIdExisting($newIncrementId)) {
                Mage::log(sprintf('Attention: Generated %s increment_id "%s" already exists. Using Magento increment_id "%s" instead.', $entityTypeCode, $newIncrementId, $originalIncrementId), null, 'xtento_customordernumber.log', true);
                return $originalIncrementId;
            }
        } catch (Exception $e) {
            Mage::log('Exception while generating new increment ID: ' . $e->getMessage(), null, 'xtento_customordernumber.log', true);
            #Mage::throwException($e);
            return $originalIncrementId;
        }

        return $newIncrementId;
    }

    private function _isIncrementIdExisting($incrementId)
    {
        $entityTypeCode = $this->getEntityTypeCode();
        if ($entityTypeCode == 'order') {
            $entity = 'sales/order';
        } else if ($entityTypeCode == 'invoice') {
            $entity = 'sales/order_invoice';
        } else if ($entityTypeCode == 'shipment') {
            $entity = 'sales/order_shipment';
        } else if ($entityTypeCode == 'creditmemo') {
            $entity = 'sales/order_creditmemo';
        } else {
            Mage::log(sprintf('Attention: Specified entity %s is not supported by the extension.', $entityTypeCode), null, 'xtento_customordernumber.log', true);
            return true;
        }

        // Check if increment ID exists
        /*$readAdapter = Mage::getSingleton('core/resource')->getConnection('core_read');
        $select = $readAdapter->select();
        $entityConfig = Mage::getResourceModel($entity)->getReadConnection()->getConfig();
        $select->from($entityConfig[''], 'entity_id')->where('increment_id = :increment_id');
        $entityId = $readAdapter->fetchOne($select, array(':increment_id' => $incrementId));*/
        if ($entityTypeCode == 'creditmemo') {
            $objectIds = Mage::getModel($entity)
                ->getCollection()
                ->addAttributeToFilter('increment_id', $incrementId)
                ->getAllIds();
            if (!empty($objectIds)) {
                return true;
            }
        } else {
            $object = Mage::getModel($entity)->loadByIncrementId($incrementId);
            if ($object->getId()) {
                return true;
            }
        }
        return false;
    }

    private function _generateCustomIncrementId($storeId)
    {
        $entityTypeCode = $this->getEntityTypeCode();
        $incrementIdFormat = $this->_getConfigHelper()->getConfigValue($entityTypeCode, 'id_format', $storeId); // Increment ID format
        $incrementBy = $this->_getConfigHelper()->prepareIntPositive($this->_getConfigHelper()->getConfigValue($entityTypeCode, 'increment_by', $storeId)); // Increase counter by X
        $incrementPadding = $this->_getConfigHelper()->prepareIntPositive($this->_getConfigHelper()->getConfigValue($entityTypeCode, 'padding', $storeId)); // Counter padding
        $resetCounter = $this->_getConfigHelper()->getConfigValue($entityTypeCode, 'reset_counter', $storeId); // Don't reset, daily, weekly, ...
        $lastResetDate = $this->_getConfigHelper()->getConfigValueFromDb($entityTypeCode, 'reset_date', $storeId); // Last reset date
        $countFromValue = $this->_getConfigHelper()->prepareIntPositive($this->_getConfigHelper()->getConfigValue($entityTypeCode, 'count_from', $storeId)); // Start counting from...
        $forceResetCounterNow = $this->_getConfigHelper()->getConfigValueFromDb($entityTypeCode, 'force_reset_counter', $storeId);

        $incrementCounter = $this->_getConfigHelper()->getConfigValueFromDb($entityTypeCode, 'increment_counter', $storeId);
        $currentCounterValue = $this->_getConfigHelper()->prepareIntPositive($incrementCounter->getValue());
        if ($incrementCounter && $currentCounterValue > 0) {
            if ($resetCounter !== '') {
                // Counter must be reset daily/weekly/yearly
                $lastResetDateValue = $lastResetDate->getValue();
                $dateFormat = false;
                if ($resetCounter == "daily") {
                    $dateFormat = "Y-m-d";
                } elseif ($resetCounter == "monthly") {
                    $dateFormat = "Y-m";
                } elseif ($resetCounter == "yearly") {
                    $dateFormat = "Y";
                }
                if ($dateFormat) {
                    $dateHasChanged = false;
                    if (!$lastResetDateValue) {
                        $dateHasChanged = true;
                    }
                    if (Mage::getSingleton('core/date')->date($dateFormat) != Mage::getSingleton('core/date')->date($dateFormat, $lastResetDateValue)) {
                        $dateHasChanged = true;
                    }
                    // Reset counter
                    if ($dateHasChanged) {
                        $currentCounterValue = $countFromValue;
                    }
                }
            }

            if ($incrementBy < 1) {
                $incrementBy = 1;
            }
            $newCounterValue = $currentCounterValue + $incrementBy;

            if ($forceResetCounterNow->getValue() === '1') {
                $newCounterValue = $countFromValue;
                $forceResetCounterNow->setValue('')->save();
            }
        } else {
            $newCounterValue = $countFromValue;
        }

        // Save current date
        $dateToday = Mage::getSingleton('core/date')->date("Y-m-d");
        $lastResetDate->setValue($dateToday)->save();
        // Update last ID field
        $incrementCounter->setValue($newCounterValue)->save();

        // Padding
        if ($incrementPadding > 0) {
            $newCounterValue = str_pad($newCounterValue, $incrementPadding, 0, STR_PAD_LEFT);
        }

        // Replace variables
        $replaceableVariables = array(
            '/%d%/' => Mage::getSingleton('core/date')->date('j'),
            '/%dd%/' => Mage::getSingleton('core/date')->date('d'),
            '/%m%/' => Mage::getSingleton('core/date')->date('n'),
            '/%mm%/' => Mage::getSingleton('core/date')->date('m'),
            '/%yy%/' => Mage::getSingleton('core/date')->date('y'),
            '/%yyyy%/' => Mage::getSingleton('core/date')->date('Y'),
            '/%h%/' => Mage::getSingleton('core/date')->date('G'),
            '/%hh%/' => Mage::getSingleton('core/date')->date('H'),
            '/%ii%/' => Mage::getSingleton('core/date')->date('i'),
            '/%ss%/' => Mage::getSingleton('core/date')->date('s'),
            '/%store_id%/' => $storeId,
            '/%counter%/' => $newCounterValue,
            '/%rand3%/' => rand(100, 999),
            '/%rand4%/' => rand(1000, 9999),
            '/%rand5%/' => rand(10000, 99999),
            '/%rand6%/' => rand(100000, 999999),
            '/%rand7%/' => rand(1000000, 9999999),
            '/%rand8%/' => rand(10000000, 99999999),
            '/%rand9%/' => rand(100000000, 999999999),
        );

        // Ability to add custom variables to the increment_id format using an event
        $transportObject = new Varien_Object();
        $transportObject->setCustomVariables(array());
        $transportObject->setExistingVariables($replaceableVariables);
        Mage::dispatchEvent('xtento_customordernumber_replace_variables_before', array('transport' => $transportObject));
        $replaceableVariables = array_merge($replaceableVariables, $transportObject->getCustomVariables());

        // Generate new increment_id
        $newIncrementId = preg_replace(array_keys($replaceableVariables), array_values($replaceableVariables), $incrementIdFormat);

        #var_dump($incrementIdFormat, $newIncrementId, $this->_isIncrementIdExisting($newIncrementId));
        #die();

        return $newIncrementId;
    }

    private function _getConfigHelper()
    {
        return Mage::helper('xtento_customordernumber/config');
    }
}