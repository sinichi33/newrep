<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-06-27T15:42:15+02:00
 * File:          app/code/local/Xtento/OrderExport/Helper/Date.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Helper_Date extends Mage_Core_Helper_Abstract
{
    protected $_locale;

    public function getLocale()
    {
        if (!$this->_locale) {
            $this->_locale = Mage::app()->getLocale();
        }
        return $this->_locale;
    }

    /*
     * Convert date to UTC
     */
    public function convertDate($date, $useTime = false, $endOfDay = false, $locale = false)
    {
        try {
            if (!$locale) {
                $locale = $this->getLocale()->getLocaleCode();
            }
            $dateObj = $this->getLocale()->date(null, null, $locale, false);

            //set default timezone for store (admin)
            $dateObj->setTimezone(Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE));

            if (!$useTime) {
                if ($endOfDay) {
                    //set end of day
                    $dateObj->setHour(23);
                    $dateObj->setMinute(59);
                    $dateObj->setSecond(59);
                } else {
                    //set beginning of day
                    $dateObj->setHour(00);
                    $dateObj->setMinute(00);
                    $dateObj->setSecond(00);
                }
            }

            //set date with applying timezone of store
            if ($useTime) {
                $dateObj->set($date, Varien_Date::DATETIME_INTERNAL_FORMAT, $locale);
            } else {
                $dateObj->set($date, Varien_Date::DATE_INTERNAL_FORMAT, $locale);
            }

            //convert store date to default date in UTC timezone without DST
            $dateObj->setTimezone(Mage_Core_Model_Locale::DEFAULT_TIMEZONE);

            return $dateObj;
        } catch (Exception $e) {
            return null;
        }
    }

    /*
     * Convert date to local timezone timestamp. This is important so strftime() in the XSL Template returns the correct time zone.
     */
    public function convertDateToStoreTimestamp($date, $store = null)
    {
        try {
            if (Mage::getStoreConfigFlag('xtcore/compatibility_fixes/disable_timestamp_timezone_adjustment')) {
                $dateObj = new Zend_Date();
                $dateObj->set($date, Varien_Date::DATETIME_INTERNAL_FORMAT);
                return (int)$dateObj->get(null, Zend_Date::TIMESTAMP);
            }
            $dateObj = new Zend_Date();
            $dateObj->setTimezone(Mage_Core_Model_Locale::DEFAULT_TIMEZONE);
            $dateObj->set($date, Varien_Date::DATETIME_INTERNAL_FORMAT);
            $dateObj->setLocale(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE, $store));
            $dateObj->setTimezone(Mage::getStoreConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE, $store));
            $gmtOffset = $dateObj->getGmtOffset();
            if ($gmtOffset >= 0) {
                if (Mage::getStoreConfigFlag('xtcore/compatibility_fixes/zend_date_gmt_offset')) {
                    // Note: Some Zend_Date versions always return a positive $gmtOffset. Thus, we replace + with - below if affected by this.
                    return (int)$dateObj->get(null, Zend_Date::TIMESTAMP) - $gmtOffset;
                } else {
                    return (int)$dateObj->get(null, Zend_Date::TIMESTAMP) + $gmtOffset;
                }
            } else {
                return (int)$dateObj->get(null, Zend_Date::TIMESTAMP) - $gmtOffset;
            }
        } catch (Exception $e) {
            return null;
        }
    }
}