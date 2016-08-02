<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_DropdownController extends  Mage_Core_Controller_Front_Action
{
    public function getChildDataAction()
    {
        $parentId = Mage::app()->getRequest()->getParam('parentId');
        $childField = Mage::app()->getRequest()->getParam('childField');
        $parentAttributeId = Mage::app()->getRequest()->getParam('parentAttributeId');
        if (!($parentId && $childField))
        {
            $this->getResponse()->setBody('');
        } else 
        {
            $collection = Mage::getModel('eav/entity_attribute')->getCollection();
            $collection ->addFieldToFilter('is_visible_on_front', 1)
                        ->addFieldToFilter('parent_dropdown', $parentAttributeId)
                        ->getSelect()->order('sorting_order');
            $attributes = $collection->load();
            foreach ($attributes as $attribute)
            {
                if($attribute->getAttributeCode() == $childField){
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                                          ->setAttributeFilter($attribute->getId())
                                          ->addFieldToFilter('parent_option_id', $parentId  )
                                          ->load();
                        $childOptions = array();
                        if ($valuesCollection->getSize() > 0)
                        {
                            foreach ($options as $option)
                            {
                                foreach ($valuesCollection as $value){
                                    if ($option['value'] == $value->getOptionId()) 
                                    {
                                        $childOptions[$option['value'] ] = $option['label'];
                                    }
                                }
                            }
                        }else{
                            $this->getResponse()->setBody('');
                            return;
                        }
                 }
            }
            $attributeValue = Mage::helper('amorderattr')->getAttributeValue($attribute);
            $response = array($childOptions,$attributeValue);
            $result = Zend_Json::encode($response);
            $this->getResponse()->setBody(
                $result
            );
        }
    }
}