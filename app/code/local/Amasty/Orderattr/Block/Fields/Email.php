<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Fields_Email extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amorderattr/list_email.phtml');
    }
    
    private function getStoreValues($attribute) {
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
    
    
    public function getList()
    {
        $list = array();
        if ('system_email_template' == Mage::app()->getRequest()->getControllerName()) {
            return $list;
        }
        $collection = Mage::getModel('eav/entity_attribute')->getCollection();
        $collection->addFieldToFilter('is_visible_on_front', 1);
        $collection->addFieldToFilter('entity_type_id', Mage::getModel('eav/entity')->setType('order')->getTypeId());
        $collection->getSelect()->order('checkout_step');
        $attributes = $collection->load();
        $order = $this->getOrder();
        $orderAttributes = Mage::getModel('amorderattr/attribute')->load($order->getId(), 'order_id');
        if ($attributes->getSize())
        {
            foreach ($attributes as $attribute)
            {
                $currentStore = $order->getStoreId();
                $storeIds = explode(',', $attribute->getData('store_ids'));
                if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds))
                {
                    continue;
                }
                
                $value = '';
                switch ($attribute->getFrontendInput())
                {
                    case 'select':
                    case 'boolean':
                    case 'radios':
                        $values = $this->getStoreValues($attribute);
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        foreach ($options as $i => $option)
                        {  
                            if ((isset($values[$option['value']]))&&($option['value'] == $orderAttributes[$attribute->getData('attribute_code')]))                            
                            {
                                $value = $values[$option['value']];
                            } elseif ($option['value'] == $orderAttributes->getData($attribute->getAttributeCode()))
                            {
                               $value = $option['label'];
                            }
                        }
                        break;
                    case 'date':
                        $value = $orderAttributes->getData($attribute->getAttributeCode());
                        if ($value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
                            $value = '';
                            break;
                        }
                        $format = Mage::app()->getLocale()->getDateTimeFormat(
                            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
                        );
                        if (!$value)
                        {
                            break;
                        }
                        if ('time' == $attribute->getNote())
                        {
                            $value = Mage::app()->getLocale()->date($value, Varien_Date::DATETIME_INTERNAL_FORMAT, null, false)->toString($format);
                        } else 
                        {
                            $format = trim(str_replace(array('m', 'a', 'H', ':', 'h', 's'), '', $format));
                            $value = Mage::app()->getLocale()->date($value, Varien_Date::DATE_INTERNAL_FORMAT, null, false)->toString($format);
                        }
                        break;
                    case 'checkboxes':
                        $values = $this->getStoreValues($attribute);
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        $checkboxValues = explode(',',$orderAttributes->getData($attribute->getAttributeCode()));
                        foreach ($options as $i => $option)
                        {
                           if (($option['value']!='')&&(in_array($option['value'], $checkboxValues) ))
                            {
                                $value[] = $values[$option['value']];
                            }
                        }
                        if (!empty($value)){
                            $value = implode(', ',$value);
                        }
                        break;
                    default:
                        $value = $orderAttributes->getData($attribute->getAttributeCode());
                        break;
                }
                
                $translations = $attribute->getStoreLabels();
                if (isset($translations[$order->getStoreId()]))
                {
                    $attributeLabel = $translations[$order->getStoreId()];
                } else 
                {
                    $attributeLabel = $attribute->getFrontendLabel();//->getLabel();
                }
                if ($attributeLabel && $value){
                    $list[$attributeLabel] = $value;                    
                }
            }
        }
        
        return $list;
    }
}