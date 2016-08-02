<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_AddressTypes
{

    /**
     * get address type options for surcharging
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = array();
        $returnArray[] = array('value' => 'shipping', 'label' => Mage::helper('adminhtml')->__('Shipping Address'));
        $returnArray[] = array('value' => 'billing', 'label' => Mage::helper('adminhtml')->__('Billing Address'));
        return $returnArray;
    }
}