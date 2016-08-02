<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_Groups
{

    /**
     * return list of Magento groups plus not logged in one
     *
     * @return mixed
     */
    public function toOptionArray()
    {
        $returnArray = Mage::getModel('adminhtml/system_config_source_customer_group')->toOptionArray();

        $returnArray[0] = array(
            'value' => (string)Mage_Customer_Model_Group::NOT_LOGGED_IN_ID,
            'label' => Mage::helper('surcharge')->__('Not Logged In')
        );
        //array_unshift($returnArray, array('value'=> '', 'label'=> Mage::helper('surcharge')->__('All Groups')));
        return $returnArray;
    }
}