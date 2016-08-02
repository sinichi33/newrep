<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-02T20:09:40+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Data/Custom/Order/TIGPostNL.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Data_Custom_Order_TIGPostNL extends Xtento_OrderExport_Model_Export_Data_Abstract
{
    public function getConfiguration()
    {
        return array(
            'name' => 'TIG_PostNL Pakjegemak Address Export',
            'category' => 'Order',
            'description' => 'Export the Pakjegemak address saved by the TIG_PostNL extension',
            'enabled' => true,
            'apply_to' => array(Xtento_OrderExport_Model_Export::ENTITY_ORDER, Xtento_OrderExport_Model_Export::ENTITY_INVOICE, Xtento_OrderExport_Model_Export::ENTITY_SHIPMENT, Xtento_OrderExport_Model_Export::ENTITY_CREDITMEMO),
            'third_party' => true,
            'depends_module' => 'TIG_PostNL',
        );
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = array();
        // Fetch fields to export
        $order = $collectionItem->getOrder();

        if ($this->fieldLoadingRequired('pakjegemak_address')) {
            try {
                $this->_writeArray = & $returnArray['pakjegemak_address'];

                $pakjeGemakAddress = Mage::helper('postnl')->getPakjeGemakAddressForOrder($order);
                if ($pakjeGemakAddress && $pakjeGemakAddress->getId()) {
                    $pakjeGemakAddress->explodeStreetAddress();
                    foreach ($pakjeGemakAddress->getData() as $key => $value) {
                        $this->writeValue($key, $value);
                    }
                    // Region Code
                    if ($pakjeGemakAddress->getRegionId() !== NULL && $this->fieldLoadingRequired('region_code')) {
                        $this->writeValue('region_code', $pakjeGemakAddress->getRegionModel()->getCode());
                    }
                    // Country - ISO3, Full Name
                    if ($pakjeGemakAddress->getCountryId() !== NULL) {
                        if ($this->fieldLoadingRequired('country_name')) $this->writeValue('country_name', Zend_Locale::getTranslation($pakjeGemakAddress->getCountryId(), 'country', 'en_US'));
                        if ($this->fieldLoadingRequired('country_iso3')) $this->writeValue('country_iso3', $pakjeGemakAddress->getCountryModel()->getIso3Code());
                    }
                }
            } catch (Exception $e) {

            }
        }
        if ($this->fieldLoadingRequired('pakjegemak_order')) {
            try {
                $this->_writeArray = & $returnArray['pakjegemak_order'];
                $dataCollection = Mage::getModel('postnl_core/order')->getCollection();
                $dataCollection->addFieldToFilter('order_id', $order->getId());

                if ($dataCollection->count()) {
                    foreach ($dataCollection as $dataRow) {
                        foreach ($dataRow->getData() as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                    }
                }
            } catch (Exception $e) {

            }
        }

        // Done
        return $returnArray;
    }
}