<?php

class Icube_Customisation_Model_System_Config_Source_Cms_Block extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_options;

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('cms/block_collection')
                ->load()
                ->toOptionArray();
            array_unshift($this->_options, array('value' => null, 'label' => Mage::helper('core')->__('Please select a static block ...')));
        }
        return $this->_options;
    }
}

?>
