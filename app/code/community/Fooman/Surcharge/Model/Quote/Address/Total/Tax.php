<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 */

class Fooman_Surcharge_Model_Quote_Address_Total_Tax extends Mage_Sales_Model_Quote_Address_Total_Tax
{
    public function _saveAppliedTaxes(Mage_Sales_Model_Quote_Address $address, $applied, $amount, $baseAmount, $rate)
    {
        parent::_saveAppliedTaxes($address, $applied, $amount, $baseAmount, $rate);
    }
}
