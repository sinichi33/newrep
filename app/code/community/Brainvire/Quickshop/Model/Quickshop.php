<?php
/** 

 * Quickshop block 

 * 

 * @category Brainvire 

 * @package Brainvire_Quickshop

* @copyright Copyright (c) 2015 Brainvire Infotech Pvt Ltd
 
 * 
 */
class Brainvire_Quickshop_Model_Quickshop extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('quickshop/quickshop');
    }
}