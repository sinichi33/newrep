<?php
/**
 * @category    Local
 * @package     Icube_City
 * Override 	Mage_SalesRule_Model_Rule_Condition_Address
 */
class Icube_City_Model_Rule_Condition_Address extends Mage_SalesRule_Model_Rule_Condition_Address
{
	public function loadAttributeOptions()
    {
        $attributes = array(
            'base_subtotal' => Mage::helper('salesrule')->__('Subtotal'),
            'total_qty' => Mage::helper('salesrule')->__('Total Items Quantity'),
            'weight' => Mage::helper('salesrule')->__('Total Weight'),
            'payment_method' => Mage::helper('salesrule')->__('Payment Method'),
            'shipping_method' => Mage::helper('salesrule')->__('Shipping Method'),
            'postcode' => Mage::helper('salesrule')->__('Shipping Postcode'),
            'region' => Mage::helper('salesrule')->__('Shipping Region'),
            'region_id' => Mage::helper('salesrule')->__('Shipping State/Province'),
            'country_id' => Mage::helper('salesrule')->__('Shipping Country'),
            'city' => Mage::helper('salesrule')->__('Shipping City'),
        );

        $this->setAttributeOption($attributes);

        return $this;
    }
}