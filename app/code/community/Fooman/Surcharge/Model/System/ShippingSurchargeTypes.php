<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_ShippingSurchargeTypes
{

    /**
     * return list of surcharge modes
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = array();
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_DEFAULT,
            'label' => Mage::helper('surcharge')->__('Use Global Setting')
        );
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SHIPPING,
            'label' => Mage::helper('surcharge')->__('On Shipping Cost Only')
        );
        return $returnArray;
    }
}
