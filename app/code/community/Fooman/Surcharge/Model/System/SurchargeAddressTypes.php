<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_SurchargeAddressTypes
{

    const NO_SURCHARGE = 0;
    const SURCHARGE_ON_SHIPPING = 1;
    const SURCHARGE_ON_BILLING = 2;
    const NO_SURCHARGE_ON_SHIPPING = 3;
    const NO_SURCHARGE_ON_BILLING = 4;

    /**
     * get surcharge address options
     *
     * @param bool $fieldType
     * @param bool $displayNo
     *
     * @return array
     */
    public function toOptionArray($fieldType = false, $displayNo = true)
    {
        $returnArray = array();
        if ($displayNo) {
            $returnArray[] = array(
                'value' => self::NO_SURCHARGE, 'label' => Mage::helper('adminhtml')->__('No')
            );
        }
        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_SHIPPING,
            'label' => Mage::helper('surcharge')->__('Yes - Based on Shipping Address')
        );
        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_BILLING,
            'label' => Mage::helper('surcharge')->__('Yes - Based on Billing Address')
        );
        $returnArray[] = array(
            'value' => self::NO_SURCHARGE_ON_SHIPPING,
            'label' => Mage::helper('surcharge')->__('Yes - Exclude Shipping Address')
        );
        $returnArray[] = array(
            'value' => self::NO_SURCHARGE_ON_BILLING,
            'label' => Mage::helper('surcharge')->__('Yes - Exclude Billing Address')
        );
        return $returnArray;
    }
}