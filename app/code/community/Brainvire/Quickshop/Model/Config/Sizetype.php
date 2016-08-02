<?php
/** 

 * Quickshop block 

 * 

 * @category Brainvire 

 * @package Brainvire_Quickshop

* @copyright Copyright (c) 2015 Brainvire Infotech Pvt Ltd
 
 * 
 */
class Brainvire_Quickshop_Model_Config_Sizetype
{
	protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options =  array(
            array('value'=>0,'label'=> Mage::helper('quickshop')->__('Percent')),
            array('value'=>1,'label'=> Mage::helper('quickshop')->__('Pixel')),
        );
        }
        return $this->_options;
    }
}
