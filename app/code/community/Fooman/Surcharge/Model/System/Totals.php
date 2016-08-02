<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_System_Totals extends Mage_Core_Model_Config_Data
{

    const SURCHARGE_ON_SUBTOTAL = 'on-subtotal';
    const SURCHARGE_ON_SUBTOTAL_INCL = 'on-subtotal-incl';
    const SURCHARGE_ON_SHIPPING = 'on-shipping';
    const SURCHARGE_ON_SHIPPING_INCL = 'on-shipping-incl';
    const SURCHARGE_ON_TAX = 'on-tax';
    const SURCHARGE_EXCLUDE_DISCOUNT = 'excl-discount';
    const SURCHARGE_ON_GRANDTOTAL = 'on-grand-total';
    const SURCHARGE_ON_DEFAULT = 'default';
    const SURCHARGE_ON_SURCHARGE = 'on-surcharge';

    //EE totals
    const SURCHARGE_ON_EE_GIFTWRAP = 'on-ee-giftwrap';
    const SURCHARGE_ON_EE_PRINTED_CARD = 'on-ee-printed-card';
    const SURCHARGE_EXCLUDE_EE_GIFTCARD = 'excl-ee-giftcard';
    const SURCHARGE_EXCLUDE_EE_POINTS = 'excl-ee-points';

    /**
     * force grand_total to be the only selected option if selected
     * @return Mage_Core_Model_Abstract|void
     */
    protected function _beforeSave()
    {
        if (strpos(implode(',', $this->getValue()), self::SURCHARGE_ON_GRANDTOTAL) !== false) {
            $this->setValue(self::SURCHARGE_ON_GRANDTOTAL);
        }
    }

    /**
     * return list of supported surcharge basis
     *
     * @return array
     */
    public function toOptionArray()
    {
        $returnArray = array();
        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_SUBTOTAL,
            'label' => Mage::helper('sales')->__('Subtotal')
        );
        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_SHIPPING,
            'label' => Mage::helper('sales')->__('Shipping')
        );

        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_SUBTOTAL_INCL,
            'label' => Mage::helper('sales')->__('Subtotal') . ' ' . Mage::helper('tax')->__('Incl. Tax')
        );
        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_SHIPPING_INCL,
            'label' => Mage::helper('sales')->__('Shipping') . ' ' . Mage::helper('tax')->__('Incl. Tax')
        );

        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_TAX,
            'label' => Mage::helper('tax')->__('Tax')
        );
        $returnArray[] = array(
            'value' => self::SURCHARGE_EXCLUDE_DISCOUNT,
            'label' => Mage::helper('surcharge')->__('Exclude Discount')
        );

        if ((string)Mage::getConfig()->getModuleConfig('Enterprise_Enterprise')->active == 'true') {
            $returnArray[] = array(
                'value' => self::SURCHARGE_ON_EE_GIFTWRAP,
                'label' => Mage::helper('surcharge')->__('Enterprise Giftwrapping')
            );
            $returnArray[] = array(
                'value' => self::SURCHARGE_ON_EE_PRINTED_CARD,
                'label' => Mage::helper('surcharge')->__('Enterprise Printed Card')
            );
            $returnArray[] = array(
                'value' => self::SURCHARGE_EXCLUDE_EE_GIFTCARD,
                'label' => Mage::helper('surcharge')->__('Enterprise Exclude Giftcard')
            );
            $returnArray[] = array(
                'value' => self::SURCHARGE_EXCLUDE_EE_POINTS,
                'label' => Mage::helper('surcharge')->__('Enterprise Exclude Reward Points')
            );
        }
        $returnArray[] = array(
            'value' => self::SURCHARGE_ON_GRANDTOTAL,
            'label' => Mage::helper('surcharge')->__('Grand Total (overrides all other settings)')
        );
        return $returnArray;
    }
}
