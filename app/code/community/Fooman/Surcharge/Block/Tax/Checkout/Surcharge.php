<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Block_Tax_Checkout_Surcharge extends Mage_Tax_Block_Checkout_Shipping
{
    protected $_template = 'surcharge/tax/checkout/surcharge.phtml';

    public function getSurchargeExcludeTax()
    {
        return $this->getTotal()->getAddress()->getFoomanSurchargeAmount();
    }

    public function getSurchargeIncludeTax()
    {
        return $this->getTotal()->getAddress()->getFoomanSurchargeAmount() + $this->getTotal()->getAddress()
            ->getFoomanSurchargeTaxAmount();
    }

    public function getIncludeTaxLabel()
    {
        return $this->helper('surcharge')->__(
            '%s (Incl. Tax)', $this->getTotal()->getAddress()->getFoomanSurchargeDescription()
        );
    }

    public function getExcludeTaxLabel()
    {
        return $this->helper('surcharge')->__(
            '%s (Excl. Tax)', $this->getTotal()->getAddress()->getFoomanSurchargeDescription()
        );
    }

    public function displayBoth()
    {
        return Mage::helper('surcharge')->displayBothCart();
    }

    public function displayIncludeTax()
    {
        return Mage::helper('surcharge')->displayIncludeTaxCart();
    }

}
