<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Model_PaypalObserver
{
    protected function _prepareOrderAttributes()
    {
        if (Mage::app()->getRequest()->getPost('amorderattr'))
        {
            $session = Mage::getSingleton('checkout/session');
            $orderAttributes = $session->getAmastyOrderAttributes();
            if (!$orderAttributes)
            {
                $orderAttributes = array();
            }
            if (!Mage::registry('attributeClear')){
                $orderAttributes = array_merge($orderAttributes, Mage::app()->getRequest()->getPost('amorderattr'));
            }
            $session->setAmastyOrderAttributes($orderAttributes);
        }
    }
     
    public function onPaypalQuoteSaveAfter($observer)
    {
       $this->_prepareOrderAttributes();
    }
    
    public function onPaypalSaveOrderAfter($observer)
    {
        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        $isMultipleCheckout = $checkoutSessionQuote -> getIsMultiShipping();
        if (!$isMultipleCheckout){
  
            $session = Mage::getSingleton('checkout/session');
            $this->_prepareOrderAttributes();
            $order = $observer->getOrder();
            $orderAttributes = $session->getAmastyOrderAttributes();
            $attributes = Mage::getModel('amorderattr/attribute');
            $attributes->load($order->getId(), 'order_id');
            if ($attributes->getId())
            {
                return false;
            }
           
            if (is_array($orderAttributes) && !empty($orderAttributes))
            {
                $collection = Mage::getModel('eav/entity_attribute')->getCollection();
                $collection->addFieldToFilter('is_visible_on_front', 1);
                $collection->addFieldToFilter('entity_type_id',Mage::getModel('eav/entity')->setType('order')->getTypeId());
                $attributesList = $collection->load();
                
                foreach ($attributesList as $attribute)
                {
                   
                    if ('checkboxes' == $attribute->getFrontend()->getInputType())
                    {
                       if (array_key_exists($attribute->getAttributeCode(), $orderAttributes)) {
                           $orderAttributes[$attribute->getAttributeCode()] = implode(',', $orderAttributes[$attribute->getAttributeCode()]);
                       }
                    }
                    if ('radios' == $attribute->getFrontend()->getInputType()){
                        $orderAttributes[$attribute->getAttributeCode()] = $orderAttributes[$attribute->getAttributeCode()][0];
                    }
                }
                $attributes->addData($orderAttributes);  
            }
            $attributes->setData('order_id', $order->getId());
            $this->_applyDefaultValues($order, $attributes);
            $attributes->save();
            $session->setAmastyOrderAttributes(array());
            Mage::register('attributeClear',true, true);
        }
      

    }
    
    // this will be used when creating/editing order in the backend
    public function onSalesOrderSaveAfter($observer)
    {
        if (false !== strpos(Mage::app()->getRequest()->getControllerName(), 'sales_order') && 'save' == Mage::app()->getRequest()->getActionName() && !Mage::registry('amorderattr_saved'))
        {
            $order = $observer->getOrder();
            $orderAttributes = Mage::app()->getRequest()->getPost('amorderattr');
            $attributes = Mage::getModel('amorderattr/attribute');
            $attributes->load($order->getId(), 'order_id');
            if ($attributes->getId())
            {
                return false;
            }
            if (is_array($orderAttributes) && !empty($orderAttributes))
            {
                $attributes->addData($orderAttributes);
            }
            $attributes->setData('order_id', $order->getId());
            $this->_applyDefaultValues($order, $attributes); // $attributes might be modified in that function
            $attributes->save();
            Mage::register('amorderattr_saved', true, true);
        }
    }
    
    protected function _applyDefaultValues($order, $attributes)
    {
        $collection = Mage::getResourceModel('eav/entity_attribute_collection')
                        ->setEntityTypeFilter( Mage::getModel('eav/entity')->setType('order')->getTypeId() );
        $collection->getSelect()
            ->where('main_table.is_user_defined = ?', 1)
            ->where('main_table.apply_default = ?', 1);
        if ($collection->getSize() > 0)
        {
            foreach ($collection as $attributeToApply)
            {
                if (!$attributes->getData($attributeToApply->getAttributeCode()) && $attributeToApply->getDefaultValue())
                {
                    $attributes->setData($attributeToApply->getAttributeCode(), $attributeToApply->getDefaultValue());
                }
            }
        }
    }
}
