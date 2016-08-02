<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_ProductSurchargeTypes
{

    const PER_ORDER = 'per_order';
    const PER_PRODUCT = 'per_product';
    const PER_ITEM = 'per_item';

    /**
     * get list of supported product surcharge settings
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = array();
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_ProductSurchargeTypes::PER_ORDER,
            'label' => Mage::helper('surcharge')->__('Per Order')
        );
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_ProductSurchargeTypes::PER_PRODUCT,
            'label' => Mage::helper('surcharge')->__('Per Product')
        );
        $returnArray[] = array(
            'value' => Fooman_Surcharge_Model_System_ProductSurchargeTypes::PER_ITEM,
            'label' => Mage::helper('surcharge')->__('Per Item')
        );
        return $returnArray;
    }
}