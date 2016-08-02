<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Adminhtml_Order_Attribute_View_List extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amasty/amorderattr/list.phtml');
    }
    
    public function getEditUrl()
    {
        if (Mage::registry('current_order')) {
            return $this->getUrl('amorderattr/adminhtml_order/edit', array('order_id' => Mage::registry('current_order')->getId()));
        } else {
            return '';
        }
    }
    
    protected function _getOrderId()
    {
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order')->getId();
        }
        if (Mage::registry('current_invoice')) {
            return Mage::registry('current_invoice')->getOrderId();
        }
        if (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment')->getOrderId();
        }
    }
    
    protected function _getStoreId()
    {
        if (Mage::registry('current_order')) {
            return Mage::registry('current_order')->getStoreId();
        }
        if (Mage::registry('current_invoice')) {
            return Mage::registry('current_invoice')->getStoreId();
        }
        if (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment')->getStoreId();
        }
    }
    
    protected function _getPlace()
    {
        if (Mage::registry('current_order')) {
            return 'order_view';
        }
        if (Mage::registry('current_invoice')) {
            return 'invoice_view';
        }
        if (Mage::registry('current_shipment')) {
            return 'shipment_view';
        }
    }
    
    public function getList()
    {
        $list = array();
        
        $collection = Mage::getModel('eav/entity_attribute')->getCollection();
        $collection->addFieldToFilter('entity_type_id', Mage::getModel('eav/entity')->setType('order')->getTypeId());
        $collection->getSelect()->order('checkout_step');
        $collection->getSelect()->order('sorting_order');
        $attributes = $collection->load();
        
        $orderAttributes = Mage::getModel('amorderattr/attribute')->load($this->_getOrderId(), 'order_id');
        $currentStore = $this->_getStoreId();
        if ($attributes->getSize())
        {
            foreach ($attributes as $attribute)
            {
                $storeIds = explode(',', $attribute->getData('store_ids'));
                if (!in_array($currentStore, $storeIds) && !in_array(0, $storeIds)) {
                    continue;
                }
                
                $value = '';
                switch ($attribute->getFrontendInput())
                {
                    case 'select':
                    case 'boolean':
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        foreach ($options as $option)
                        {
                            if ($option['value'] == $orderAttributes->getData($attribute->getAttributeCode()))
                            {
                                $value = $option['label'];
                                break;
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
                    case 'radios':
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        $attributeSelect=$orderAttributes->getData($attribute->getAttributeCode());
                        foreach ($options as $option)
                        {
                            if ($option['value'] == $attributeSelect)
                            {
                                $value = $option['label'];
                                break;
                            }
                        }
                        break;
                    case 'checkboxes':
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        $attributeList=$orderAttributes->getData($attribute->getAttributeCode());
                        $attributeList=explode(',',$attributeList);
                        $valueAttribute=array();
                        foreach($attributeList as $index=>$values) {
                           foreach($options as $option) { 
                                 if ($option['value']==$values){
                                    $valueAttribute[] = $option['label'];
                                 }
                           }
                        }
                        $value=implode(', ', $valueAttribute);
                        break;
                    default:
                        $value = $orderAttributes->getData($attribute->getAttributeCode());
                        break;
                }
                if ($attribute->getFrontendLabel() && $value) {
                    $list[$attribute->getFrontendLabel()] = str_replace('$', '\$', $value);
                }
            }
        }
        return $list;
    }
}
