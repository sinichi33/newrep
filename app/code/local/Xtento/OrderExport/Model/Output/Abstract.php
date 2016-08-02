<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2015-05-06T22:26:44+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Output/Abstract.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

abstract class Xtento_OrderExport_Model_Output_Abstract extends Mage_Core_Model_Abstract implements Xtento_OrderExport_Model_Output_Interface
{
    static $iteratingKeys = array(
        'items',
        'transactions',
        'entries',
        'fields',
        'custom_options',
        'product_attributes',
        'product_options',
        'downloadable_links',
        'tracks',
        'order_status_history' => 'entry',
        'addresses' => 'address',
        'invoice_comments' => 'invoice_comment',
        'skus' => 'sku',
        'salesrules' => 'salesrule'
    );

    protected function _replaceFilenameVariables($filename, $exportArray)
    {
        $filename = str_replace("|", "-", $filename); // Remove the pipe character - it's not allowed in file names anyways and we use it to separate multiple files in the DB
        // Replace variables in filename
        $replaceableVariables = array(
            '/%d%/' => Mage::getSingleton('core/date')->date('d'),
            '/%m%/' => Mage::getSingleton('core/date')->date('m'),
            '/%y%/' => Mage::getSingleton('core/date')->date('y'),
            '/%Y%/' => Mage::getSingleton('core/date')->date('Y'),
            '/%h%/' => Mage::getSingleton('core/date')->date('H'),
            '/%i%/' => Mage::getSingleton('core/date')->date('i'),
            '/%s%/' => Mage::getSingleton('core/date')->date('s'),
            '/%timestamp%/' => Mage::getSingleton('core/date')->timestamp(time()),
            '/%lastentityid%/' => $this->getVariableValue('last_entity_id', $exportArray, $filename, '%lastentityid%'),
            '/%orderid%/' => $this->getVariableValue('last_entity_id', $exportArray, $filename, '%orderid%'), // Legacy
            '/%lastincrementid%/' => $this->getVariableValue('last_increment_id', $exportArray, $filename, '%lastincrementid%'),
            '/%firstincrementid%/' => $this->getVariableValue('first_increment_id', $exportArray, $filename, '%firstincrementid%'),
            '/%lastorderincrementid%/' => $this->getVariableValue('last_order_increment_id', $exportArray, $filename, '%lastorderincrementid%'),
            '/%realorderid%/' => $this->getVariableValue('last_increment_id', $exportArray, $filename, '%realorderid%'), // Legacy
            '/%ordercount%/' => $this->getVariableValue('collection_count', $exportArray, $filename, '%ordercount%'), // Legacy
            '/%collectioncount%/' => $this->getVariableValue('collection_count', $exportArray, $filename, '%collectioncount%'),
            '/%exportCountForObject%/' => $this->getVariableValue('export_count_for_object', $exportArray, $filename, '%exportCountForObject%'), // How often was this object exported before by this profile?
            '/%dailyExportCounter%/' => $this->getVariableValue('daily_export_counter', $exportArray, $filename, '%dailyExportCounter%'), // How many objects have been exported today by this profile?
            '/%profileExportCounter%/' => $this->getVariableValue('profile_export_counter', $exportArray, $filename, '%profileExportCounter%'), // How many objects have been exported by this profile? Basically an incrementing counter for each export
            '/%uuid%/' => uniqid(),
            '/%exportid%/' => $this->getVariableValue('export_id', $exportArray, $filename, '%exportid%'),
        );

        // Ability to add custom variables to the filename using an event
        $transportObject = new Varien_Object();
        $transportObject->setCustomVariables(array());
        Mage::dispatchEvent('xtento_orderexport_replace_filename_variables_before', array('transport' => $transportObject));
        $replaceableVariables = array_merge($replaceableVariables, $transportObject->getCustomVariables());

        // Remember last exported ID
        Mage::unregister('last_exported_increment_id');
        Mage::register('last_exported_increment_id', $this->getVariableValue('last_increment_id', $exportArray, false, false));
        $filename = preg_replace(array_keys($replaceableVariables), array_values($replaceableVariables), $filename);
        return $filename;
    }

    protected function getVariableValue($variable, $exportArray, $filename = false, $attributeVariableName = false)
    {
        if (!empty($filename) && !empty($attributeVariableName) && !stristr($filename, $attributeVariableName)) {
            // Variable not required in filename
            return '';
        }
        $arrayToWorkWith = $exportArray;
        if ($variable == 'export_id') {
            if (Mage::registry('export_log')) {
                return Mage::registry('export_log')->getId();
            } else {
                return 0;
            }
        }
        if ($variable == 'collection_count') {
            return count($arrayToWorkWith);
        }
        if ($variable == 'total_item_count') {
            $totalItemCount = 0;
            foreach ($arrayToWorkWith as $collectionObject) {
                if (isset($collectionObject['items'])) {
                    foreach ($collectionObject['items'] as $item) {
                        $totalItemCount++;
                    }
                }
            }
            return $totalItemCount;
        }
        if ($variable == 'last_entity_id') {
            $lastItem = array_pop($arrayToWorkWith);
            if (isset($lastItem['entity_id'])) {
                return $lastItem['entity_id'];
            }
        }
        if ($variable == 'first_increment_id') {
            $lastItem = array_shift($arrayToWorkWith);
            if (isset($lastItem['increment_id'])) {
                return $lastItem['increment_id'];
            } else {
                return 'increment_not_set_' . $lastItem['entity_id'];
            }
        }
        if ($variable == 'last_increment_id') {
            $lastItem = array_pop($arrayToWorkWith);
            if (isset($lastItem['increment_id'])) {
                return $lastItem['increment_id'];
            } else {
                return 'increment_not_set_' . $lastItem['entity_id'];
            }
        }
        if ($variable == 'last_order_increment_id') {
            $lastItem = array_pop($arrayToWorkWith);
            if (isset($lastItem['order']) && isset($lastItem['order']['increment_id'])) {
                return $lastItem['order']['increment_id'];
            } else if (isset($lastItem['increment_id'])) {
                return $lastItem['increment_id'];
            } else {
                return '';
            }
        }
        if ($variable == 'date_from_timestamp') {
            $firstObject = array_shift($arrayToWorkWith);
            return Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($firstObject['created_at']);
        }
        if ($variable == 'date_to_timestamp') {
            $lastObject = array_pop($arrayToWorkWith);
            return Mage::helper('xtento_orderexport/date')->convertDateToStoreTimestamp($lastObject['created_at']);
        }
        if ($variable == 'max_item_count') {
            $maxItemCount = 0;
            foreach ($arrayToWorkWith as $object) {
                if (!isset($object['items'])) {
                    continue;
                }
                $itemCount = count($object['items']);
                if ($maxItemCount === 0) {
                    $maxItemCount = $itemCount;
                }
                if ($itemCount > $maxItemCount) {
                    $maxItemCount = $itemCount;
                }
            }
            return $maxItemCount;
        }
        if ($variable == 'export_count_for_object') {
            $lastItem = array_pop($arrayToWorkWith);
            if (isset($lastItem['entity_id'])) {
                $exportEntity = false;
                $profileId = false;
                if (Mage::registry('export_log')) {
                    $profileId = Mage::registry('export_log')->getProfileId();
                    $profile = Mage::getModel('xtento_orderexport/profile')->load($profileId);
                    $exportEntity = $profile->getEntity();
                }
                if (Mage::registry('order_export_profile')) {
                    $exportEntity = Mage::registry('order_export_profile')->getEntity();
                    $profileId = Mage::registry('order_export_profile')->getId();
                }
                if (!$exportEntity) {
                    return '';
                }
                $exportHistoryCollection = Mage::getModel('xtento_orderexport/history')->getCollection();
                $exportHistoryCollection->addFieldToFilter('entity', $exportEntity);
                $exportHistoryCollection->addFieldToFilter('entity_id', $lastItem['entity_id']);
                $exportHistoryCollection->addFieldToFilter('profile_id', $profileId);
                return $exportHistoryCollection->count() + 1;
            }
        }
        if ($variable == 'daily_export_counter' || $variable == 'profile_export_counter') {
            $exportEntity = false;
            $profileId = false;
            if (Mage::registry('export_log')) {
                $profileId = Mage::registry('export_log')->getProfileId();
                $profile = Mage::getModel('xtento_orderexport/profile')->load($profileId);
                $exportEntity = $profile->getEntity();
            }
            if (Mage::registry('order_export_profile')) {
                $exportEntity = Mage::registry('order_export_profile')->getEntity();
                $profileId = Mage::registry('order_export_profile')->getId();
            }
            if (!$exportEntity) {
                return '';
            }
            $exportLogCollection = Mage::getModel('xtento_orderexport/log')->getCollection();
            #$exportHistoryCollection->addFieldToFilter('entity', $exportEntity);
            if ($variable == 'daily_export_counter') {
                $exportLogCollection->getSelect()->where('DATE(created_at) = DATE(NOW())');
            }
            $exportLogCollection->addFieldToFilter('profile_id', $profileId);
            $exportLogCollection->getSelect()->where('records_exported > 0');
            return $exportLogCollection->count();
        }
        // GUID
        if ($variable == 'guid') {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }
        return '';
    }

    protected function _throwXmlException($message)
    {
        $message .= "\n";
        foreach (libxml_get_errors() as $error) {
            $message .= "\tLine " . $error->line . ": " . $error->message;
            if (strpos($error->message, "\n") === FALSE) {
                $message .= "\n";
            }
        }
        libxml_clear_errors();
        Mage::throwException($message);
    }

    protected function _changeEncoding($input, $encoding, $charsetLocale = '')
    {
        if (!empty($charsetLocale)) {
            // Set locale based on XSL Template "locale" attribute
            $oldLocale = setlocale(LC_CTYPE, "0"); // Get current locale
            @setlocale(LC_CTYPE, $charsetLocale);
        }
        $output = $input;
        if (!empty($encoding) && @function_exists('iconv')) {
            $output = @iconv("UTF-8", $encoding, $input);
            if (!$output && !empty($input)) {
                // Conversion failed, try as UTF-8 re-encoded
                $output = @iconv("UTF-8", $encoding, utf8_encode(utf8_decode($input)));
                if (!$output && !empty($input)) {
                    if (!empty($charsetLocale)) {
                        // Reset locale
                        setlocale(LC_CTYPE, $oldLocale);
                    }
                    $this->_throwXmlException(Mage::helper('xtento_orderexport')->__("While trying to convert your export data into the requested encoding '%s', the PHP iconv() function failed. You either forgot to add //IGNORE to the encoding, or you are affected by this bug: https://bugs.php.net/bug.php?id=48147", $encoding));
                }
            }
        }
        if (!empty($charsetLocale)) {
            // Reset locale
            setlocale(LC_CTYPE, $oldLocale);
        }
        return $output;
    }
}