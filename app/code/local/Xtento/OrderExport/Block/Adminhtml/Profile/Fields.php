<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2013-09-08T16:10:39+02:00
 * File:          app/code/local/Xtento/OrderExport/Block/Adminhtml/Profile/Fields.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Block_Adminhtml_Profile_Fields extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('xtento/orderexport/export_fields.phtml');
    }

    public function getFieldJson()
    {
        $export = Mage::getSingleton('xtento_orderexport/export_entity_' . Mage::registry('order_export_profile')->getEntity());
        $export->setShowEmptyFields(1);
        $export->setProfile(Mage::registry('order_export_profile'));
        $export->setCollectionFilters(
            array(array('increment_id' => array('in' => explode(",", $this->getTestId()))))
        );
        $returnArray = $export->runExport();
        if (empty($returnArray)) {
            return false;
        }
        return Zend_Json::encode($this->prepareJsonArray($returnArray));
    }

    /*
     * Convert Array into EXTJS TreePanel JSON
     */
    private function prepareJsonArray($array, $parentKey = '')
    {
        static $depth = 0;
        $newArray = array();

        $depth++;
        if ($depth >= '100') {
            return '';
        }

        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $key = Mage::getSingleton('xtento_orderexport/output_xml_writer')->handleSpecialParentKeys($key, $parentKey);
                $newArray[] = array('text' => '<strong>' . $key . '</strong>', 'leaf' => false, 'expanded' => true, 'cls' => 'x-tree-noicon', 'children' => $this->prepareJsonArray($val, $key));
            } else {
                if ($val == '') {
                    $val = Mage::helper('xtento_orderexport')->__('NULL');
                }
                $newArray[] = array('text' => $key, 'leaf' => false, 'cls' => 'x-tree-noicon', 'children' => array(array('text' => $val, 'leaf' => true, 'cls' => 'x-tree-noicon')));
            }
        }
        return $newArray;
    }

    public function getTestId()
    {
        return urldecode($this->getRequest()->getParam('test_id'));
    }
}