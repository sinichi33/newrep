<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattr
 */
class Amasty_Orderattr_Block_Adminhtml_Order_Grid_Renderer_Checkboxes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Checkbox
{
    public function render(Varien_Object $row)
    {
       
        if ($data = $this->_getValue($row)) {
            $collection = Mage::getModel('eav/entity_attribute')->getCollection();
            $collection->addFieldToFilter('entity_type_id', Mage::getModel('eav/entity')->setType('order')->getTypeId());
            $attributes = $collection->load();
            $data = explode(',',$data);
            foreach ($attributes as $attribute)
            {
                if ('checkboxes' == $attribute->getFrontendInput())
                {
                        $options = $attribute->getSource()->getAllOptions(true, true);
                        foreach ($data as $key=>$value)
                        {
                           foreach ($options as $option)
                           { 
                               if ($value==$option['value'] )
                                {
                                    $returnData[] = $option['label'];
                                    break;
                                } 
                           }
                        }
                }
            }
            $data = implode(', ',$returnData);
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}