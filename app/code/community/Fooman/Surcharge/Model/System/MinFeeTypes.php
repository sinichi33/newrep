<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_MinFeeTypes
{

    const VALUE = 'value';
    const QTY = 'qty';
    const PRODUCT = 'product';

    /**
     * get list of supported product surcharge settings
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = array();
        $returnArray[] = array(
            'value' => self::VALUE,
            'label' => Mage::helper('surcharge')->__('Value')
        );
        $returnArray[] = array(
            'value' => self::QTY,
            'label' => Mage::helper('surcharge')->__('Total Number of Items')
        );
        $returnArray[] = array(
            'value' => self::PRODUCT,
            'label' => Mage::helper('surcharge')->__('Number of Products')
        );
        return $returnArray;
    }
}
