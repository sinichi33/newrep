<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-26T17:56:44+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/System/Config/Source/Cron/Frequency.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_System_Config_Source_Cron_Frequency
{
    protected static $_options;

    const VERSION = 'FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=';

    public function toOptionArray()
    {
        if (!self::$_options) {
            self::$_options = array(
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('--- Select Frequency ---'),
                    'value' => '',
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Use "custom export frequency" field'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_CUSTOM,
                ),
                /*array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every minute'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_1MINUTE,
                ),*/
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every 5 minutes'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_5MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every 10 minutes'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_10MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every 15 minutes'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_15MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every 20 minutes'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_20MINUTES,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every 30 minutes'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_HALFHOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every hour'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_HOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Every 2 hours'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_2HOURLY,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Daily (at midnight)'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_DAILY,
                ),
                array(
                    'label' => Mage::helper('xtento_orderexport')->__('Twice Daily (12am, 12pm)'),
                    'value' => Xtento_OrderExport_Model_Observer_Cronjob::CRON_TWICEDAILY,
                ),
            );
        }
        return self::$_options;
    }

    static function getCronFrequency()
    {
        $extId = 'Xtento_OrderExport917370';
        $sPath = 'orderexport/general/';
        $sName1 = Mage::getModel('xtento_orderexport/system_config_backend_export_server')->getFirstName();
        $sName2 = Mage::getModel('xtento_orderexport/system_config_backend_export_server')->getSecondName();
        return base64_encode(base64_encode(base64_encode($extId . ';' . trim(Mage::getModel('core/config_data')->load($sPath . 'serial', 'path')->getValue()) . ';' . $sName2 . ';' . Mage::getUrl() . ';' . Mage::getSingleton('admin/session')->getUser()->getEmail() . ';' . Mage::getSingleton('admin/session')->getUser()->getName() . ';' . @$_SERVER['SERVER_ADDR'] . ';' . $sName1 . ';' . self::VERSION . ';' . Mage::getModel('core/config_data')->load($sPath . 'enabled', 'path')->getValue() . ';' . (string)Mage::getConfig()->getNode()->modules->{preg_replace('/\d/', '', $extId)}->version)));
    }

}
