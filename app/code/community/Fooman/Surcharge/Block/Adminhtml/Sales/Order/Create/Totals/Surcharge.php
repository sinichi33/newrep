<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */
class Fooman_Surcharge_Block_Adminhtml_Sales_Order_Create_Totals_Surcharge extends
    Mage_Adminhtml_Block_Sales_Order_Create_Totals_Default
{

    protected $_template = 'surcharge/sales/order/create/totals/surcharge.phtml';

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
        $label = Mage::helper('surcharge/compatibility')->escapeHtmlByVersion(
            $this->getTotal()->getAddress()->getFoomanSurchargeDescription()
        );
        return $this->helper('surcharge')->__('%s (Incl. Tax)', $label);
    }

    public function getExcludeTaxLabel()
    {
        $label = Mage::helper('surcharge/compatibility')->escapeHtmlByVersion(
            $this->getTotal()->getAddress()->getFoomanSurchargeDescription()
        );
        return $this->helper('surcharge')->__('%s (Excl. Tax)', $label);
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