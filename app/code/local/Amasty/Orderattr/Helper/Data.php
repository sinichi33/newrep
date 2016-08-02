<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function fields($step)
    {
        return Mage::app()->getLayout()->createBlock('amorderattr/fields')->setStep($step)->toHtml();
    }

    public function field($code)
    {
        return Mage::app()->getLayout()->createBlock('amorderattr/fields')->setAttributeCode($code)->toHtml();
    }
    
    //$object is a "$this" from template
    public function express($step, $object)
    {
        if ('address' == $step){
            if ($object->getShowAsShippingCheckbox())
                return Mage::app()->getLayout()->createBlock('amorderattr/fields')->setStep('billing')->toHtml();
            else
                return Mage::app()->getLayout()->createBlock('amorderattr/fields')->setStep('shipping')->toHtml();
        }
        return Mage::app()->getLayout()->createBlock('amorderattr/fields')->setStep($step)->toHtml(); 
    }
   
    public function clearCache()
    {
        $cacheDir = Mage::getBaseDir('var') . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $this->_clearDir($cacheDir);
        Mage::app()->cleanCache();
        Mage::getConfig()->reinit();

      //the client had a case when needed flush Cache Storage
      //Mage::app()->getCacheInstance()->flush();
    }
    
    protected function _clearDir($dir = '')
    {
        if($dir) 
        {
            if (is_dir($dir)) 
            {
                if ($handle = @opendir($dir)) 
                {
                    while (($file = readdir($handle)) !== false) 
                    {
                        if ($file != "." && $file != "..") 
                        {
                            $fullpath = $dir . '/' . $file;
                            if (is_dir($fullpath)) 
                            {
                                $this->_clearDir($fullpath);
                                @rmdir($fullpath);
                            }
                            else 
                            {
                                @unlink($fullpath);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
    }
    
    public function getAttributeValue($attribute)
    {
         $attributeValue = '';
         if ($attribute->getData('default_value'))
         {
            $attributeValue = $attribute->getData('default_value');
         }
         $session = Mage::getSingleton('checkout/session');
         $orderAttributes = $session->getAmastyOrderAttributes();
         
         if (is_array($orderAttributes) && array_key_exists($attribute->getAttributeCode(), $orderAttributes)){
             $attributeValue = $orderAttributes[$attribute->getAttributeCode()];
             $inputType = $attribute->getFrontend()->getInputType();
             if('checkboxes' == $inputType){
                $attributeValue = implode(',',$attributeValue);
             }
         }
         else{
            // if enabled, we will pre-fill the attribut with the last used value. works for registered customer only
            if ($attribute->getSaveSelected() && Mage::getSingleton('customer/session')->isLoggedIn())
            {
                $orderCollection = Mage::getModel('sales/order')->getCollection();
                $orderCollection->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getId());
                // 1.3 compatibility
                $alias = Mage::helper('ambase')->isVersionLessThan(1,4) ? 'e' : 'main_table';
                $orderCollection->getSelect()
                   ->join(
                        array('custom_attributes' => Mage::getModel('amorderattr/attribute')->getResource()->getTable('amorderattr/order_attribute')),
                        "$alias.entity_id = custom_attributes.order_id",
                        array($attribute->getAttributeCode())
                   );
                $orderCollection->getSelect()->order('custom_attributes.order_id DESC');
                $orderCollection->getSelect()->limit(1);
                if ($orderCollection->getSize() > 0)
                {
                    foreach ($orderCollection as $lastOrder)
                    {
                        $attributeValue = $lastOrder->getData($attribute->getAttributeCode());
                    }
                }
            }
        }
        return $attributeValue;
    }
    
    public function getShippingMethods($attributeId){
        $model = Mage::getModel('amorderattr/shipping_methods');
        $collection = $model->getCollection();
        
        $collection->addFilter('attribute_id', $attributeId);
        $collection->load();
        return $collection->getItems();
        
    }
    
}