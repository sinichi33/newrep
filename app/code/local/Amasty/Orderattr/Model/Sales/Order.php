<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Model_Sales_Order extends Amasty_Orderattr_Model_Sales_Order_Pure
{
    /**
    * Gets the value of custom order attribute
    * 
    * @param string $attr attribute code
    */
     private function _getStoreValues($attribute) {
        $values = array();
        $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
             ->setAttributeFilter($attribute->getId())
             ->setStoreFilter(Mage::app()->getStore()->getId(), false)
             ->load();
        foreach ($valuesCollection as $item) {
            $values[$item->getId()] = $item->getValue();
        }
        // fix for `No default store view`
        $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
             ->setAttributeFilter($attribute->getId())
             ->setStoreFilter(0, false)
             ->load();
        foreach ($valuesCollection as $item) {
            if (isset($values[$item->getId()]) && ($values[$item->getId()] !== '')) {
                continue;
            } else {
                $values[$item->getId()] = $item->getValue();
            }
        }
        return $values;
    }
    
    public function custom($attr) {
        $orderAttributes = Mage::getModel('amorderattr/attribute')->load($this->getId(), 'order_id');
        $attribute = Mage::getModel('eav/entity_attribute')->load($attr, 'attribute_code');
        if ($attribute->getAttributeId()) {
            switch ($attribute->getFrontendInput())
            {
                case 'select':
                case 'boolean':
                case 'radios':
                        $values = $this->_getStoreValues($attribute);
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        foreach ($options as $i => $option)
                        {
                            if (isset($values[$option['value']])&&($option['value'] == $orderAttributes[$attribute->getData('attribute_code')]))
                            {
                                $value = $values[$option['value']];
                            }  elseif ($option['value'] == $orderAttributes->getData($attribute->getAttributeCode()))
                            {
                               $value = $option['label'];
                            }
                        }
                        break;
                case 'checkboxes':
                        $values = $this->_getStoreValues($attribute);
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        $checkboxValues = explode(',',$orderAttributes->getData($attribute->getAttributeCode()));
                        foreach ($options as $i => $option)
                        {
                           if (in_array($option['value'], $checkboxValues) )
                            {
                                $value[] = $values[$option['value']];
                            }
                        }
                        $value = implode(', ',$value);
                        break;
                default:
                    $value = $orderAttributes->getData($attribute->getAttributeCode());
                    break;
            }
            return $value;
        }
    }
}