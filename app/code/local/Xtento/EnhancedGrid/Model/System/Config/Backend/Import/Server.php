<?php

/**
 * Product:       Xtento_EnhancedGrid (1.7.5)
 * ID:            AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=
 * Packaged:      2016-01-12T01:56:20+00:00
 * Last Modified: 2014-07-26T17:53:08+02:00
 * File:          app/code/local/Xtento/EnhancedGrid/Model/System/Config/Backend/Import/Server.php
 * Copyright:     Copyright (c) 2016 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_EnhancedGrid_Model_System_Config_Backend_Import_Server extends Mage_Core_Model_Config_Data
{
    const VERSION = 'AGFFBAazvJT3c0HVmzgIjr6dIPMduRmjRKpi1L38TZI=';

    public function afterLoad()
    {
        $extId = 'Xtento_EnhancedGrid198725';
        $sPath = 'enhancedgrid/general/';
        $sName1 = $this->getFirstName();
        $sName2 = $this->getSecondName();
        $this->setValue(base64_encode(base64_encode(base64_encode($extId . ';' . trim(Mage::getModel('core/config_data')->load($sPath . 'serial', 'path')->getValue()) . ';' . $sName2 . ';' . Mage::getUrl() . ';' . Mage::getSingleton('admin/session')->getUser()->getEmail() . ';' . Mage::getSingleton('admin/session')->getUser()->getName() . ';' . @$_SERVER['SERVER_ADDR'] . ';' . $sName1 . ';' . self::VERSION . ';' . Mage::getModel('core/config_data')->load($sPath . 'enabled', 'path')->getValue() . ';' . (string)Mage::getConfig()->getNode()->modules->Xtento_EnhancedGrid->version))));
    }

    public function getFirstName()
    {
        $table = Mage::getModel('core/config_data')->getResource()->getMainTable();
        $readConn = Mage::getSingleton('core/resource')->getConnection('core_read');
        $select = $readConn->select()->from($table, array('value'))->where('path = ?', 'web/unsecure/base_url')->where('scope_id = ?', 0)->where('scope = ?', 'default');
        $url = str_replace(array('http://', 'https://', 'www.'), '', $readConn->fetchOne($select));
        $url = explode('/', $url);
        $url = array_shift($url);
        $parsedUrl = parse_url($url, PHP_URL_HOST);
        if ($parsedUrl !== null) {
            return $parsedUrl;
        }
        return $url;
    }

    public function getSecondName()
    {
        $url = str_replace(array('http://', 'https://', 'www.'), '', @$_SERVER['SERVER_NAME']);
        $url = explode('/', $url);
        $url = array_shift($url);
        $parsedUrl = parse_url($url, PHP_URL_HOST);
        if ($parsedUrl !== null) {
            return $parsedUrl;
        }
        return $url;
    }

}
