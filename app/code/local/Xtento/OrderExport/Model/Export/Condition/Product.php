<?php

/**
 * Product:       Xtento_OrderExport (1.8.3)
 * ID:            FeaB451G3InQQc0KeR77IYX5M9VyiBcylAuZlrePxno=
 * Packaged:      2015-07-01T03:33:15+00:00
 * Last Modified: 2014-07-02T20:04:49+02:00
 * File:          app/code/local/Xtento/OrderExport/Model/Export/Condition/Product.php
 * Copyright:     Copyright (c) 2015 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_OrderExport_Model_Export_Condition_Product extends Mage_CatalogRule_Model_Rule_Condition_Product
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('xtento_orderexport/export_condition_product');
    }

    /**
     * Load attribute options
     *
     * @return Mage_CatalogRule_Model_Rule_Condition_Product
     */
    public function loadAttributeOptions()
    {
        $productAttributes = Mage::getResourceSingleton('catalog/product')
            ->loadAllAttributes()
            ->getAttributesByCode();

        $attributes = array();
        foreach ($productAttributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if (!$attribute->isAllowedForRuleCondition() /* || !$attribute->getDataUsingMethod($this->_isUsedForRuleProperty)*/) {
                continue;
            }
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    public function validate(Varien_Object $object)
    {
        $product = Mage::getModel('catalog/product')->setStoreId($object->getStoreId())->load($object->getProductId());
        $this->_entityAttributeValues[$product->getId()][$product->getStoreId()] = $product->getData($this->getAttribute()); // Required since 1.8.0.0 // Old: $this->getValue() //
        #var_dump($this->getAttribute(), $product->getData($this->getAttribute()), parent::validateAttribute($product));

        return parent::validate($product);
    }
}
