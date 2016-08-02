<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_HandlingTypes
{
    const HANDLING_TYPE_COMBINED = 'C';
    const HANDLING_TYPE_MIN = 'M';

    /**
     * return list of surcharge modes
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = Mage::getModel('shipping/source_handlingType')->toOptionArray();
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_HandlingTypes::HANDLING_TYPE_COMBINED,
            'label' => Mage::helper('surcharge')->__('Fixed + Percent')
        );
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_HandlingTypes::HANDLING_TYPE_MIN,
            'label' => Mage::helper('surcharge')->__('Fixed Minimum')
        );
        return $returnArray;
    }
}