<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_SurchargeTaxClass
    extends Mage_Adminhtml_Model_System_Config_Source_Shipping_Taxclass
{
    const BIGGEST_FACTOR = 'surcharge-tax-class-biggest';
    const PROPORTIONATE = 'surcharge-tax-class-proportionate'; //NOT YET IMPLEMENTED

    /**
     * get extra tax options for surcharge
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = parent::toOptionArray();
        $returnArray[] = array(
            'value' => self::BIGGEST_FACTOR,
            'label' => Mage::helper('surcharge')->__('Dominant Tax Rate')
        );
        return $returnArray;
    }
}