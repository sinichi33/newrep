<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Model_Sales_Order_Api extends Amasty_Orderattr_Model_Sales_Order_Api_Pure
{
    public function info($orderIncrementId)
    {
        $result = parent::info($orderIncrementId);
        
        if (isset($result['order_id']))
        {
            $orderAttributes = Mage::getModel('amorderattr/attribute')->load($result['order_id'], 'order_id');
                $custom = array();
                
                $collection = Mage::getModel('eav/entity_attribute')->getCollection();
                $collection->addFieldToFilter('entity_type_id', Mage::getModel('eav/entity')->setType('order')->getTypeId());
                $collection->getSelect()->order('checkout_step');
                $attributes = $collection->load();
                
                $custom = array();
                if ($attributes->getSize())
                {
                    foreach ($attributes as $attribute)
                    {
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
                            default:
                                $value = $orderAttributes->getData($attribute->getAttributeCode());
                                break;
                        }
                        $custom[] = array('key' => $attribute->getAttributeCode(), 'value' => $value);
                    }
                }
                        
                if ($custom)
                {
                    $result['custom'] = $custom;
                }
        }
        
        return $result;
    }
}