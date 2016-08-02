<?php
class Icube_Customgiftcard_Helper_Data extends Mage_Core_Helper_Abstract
{
	const GENERAL = 'general';
	const REDEEM = 'redeem';
	const REFUND = 'refund';
	
	/**
     * Collect list of gift card account type
     *
     */
    public function typeOptions()
    {
        return array(
                    self::GENERAL => Mage::helper('customgiftcard')->__('General'),
                    self::REDEEM => Mage::helper('customgiftcard')->__('Redeem Point'),
                    self::REFUND => Mage::helper('customgiftcard')->__('Gift Card Refund'),
                );
    }

    /**
     * Collect list of gift card account type as options array.
     * If $addEmpty true - add empty option
     *
     */
    public function typeOptionArray($addEmpty = false)
    {
        $result = array();

        if ($addEmpty) {
            $result[] = array('value' => '',
                              'label' => '-- Please Select --');
        }

        foreach ($this->typeOptions() as $value=>$label) {
            $result[] = array('value' => $value,
                              'label' => $label);
        }

        return $result;
    }
}
	 