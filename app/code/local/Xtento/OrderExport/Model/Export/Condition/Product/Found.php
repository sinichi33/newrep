<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-06-23T21:54:38+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Condition/Product/Found.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Condition_Product_Found extends Mage_SalesRule_Model_Rule_Condition_Product_Found
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('xtento_orderexport/export_condition_product_found');
    }

    public function asHtml()
    {
        $html = $this->getTypeElement()->getHtml().
            Mage::helper('salesrule')->__("If an item is %s with %s of these conditions true:",
            $this->getValueElement()->getHtml(), $this->getAggregatorElement()->getHtml());
           if ($this->getId()!='1') {
               $html.= $this->getRemoveLinkHtml();
           }
        return $html;
    }

    public function getNewChildSelectOptions()
    {
        $productCondition = Mage::getModel('xtento_orderexport/export_condition_product');
        $productAttributes = $productCondition->loadAttributeOptions()->getAttributeOption();
        $pAttributes = array();
        #$iAttributes = array();
        foreach ($productAttributes as $code => $label) {
            /*if (strpos($code, 'quote_item_') === 0) {
                $iAttributes[] = array('value' => 'xtento_orderexport/export_condition_product|' . $code, 'label' => $label);
            } else {*/
            $pAttributes[] = array('value' => 'xtento_orderexport/export_condition_product|' . $code, 'label' => $label);
            /*}*/
        }

        $itemAttributes = array();
        $customItemAttributes = Mage::getModel('xtento_orderexport/export_condition_custom')->getCustomNotMappedAttributes('_item');
        foreach ($customItemAttributes as $code => $label) {
            $itemAttributes[] = array('value' => 'xtento_orderexport/export_condition_item|' . $code, 'label' => $label);
        }

        $conditions = array(
            array('value' => 'xtento_orderexport/export_condition_combine', 'label' => Mage::helper('salesrule')->__('Conditions combination')),
            #array('label' => Mage::helper('catalog')->__('Cart Item Attribute'), 'value' => $iAttributes),
            array('label' => Mage::helper('catalog')->__('Product Attribute'), 'value' => $pAttributes),
            array('label' => Mage::helper('catalog')->__('Item Attribute'), 'value' => $itemAttributes),
        );
        return $conditions;
    }
}