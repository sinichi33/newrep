<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge extends Varien_Object
{

    /**
     * reset Surcharge to all zeroes
     */
    public function resetSurcharge()
    {
        $this->setAmount(0);
        $this->setBaseAmount(0);
        $this->setTaxAmount(0);
        $this->setBaseTaxAmount(0);
        $this->setDescription();
        $this->setCcSurchargeApplied(false);
        $this->setBasisDeductions(0);
        $this->unsExemptResult();
    }

    /**
     * helper method to combine multiple surcharge descriptions if needed
     *
     * @param $newDesc
     * @param $newAmount
     */
    public function addSurchargeDescription($newDesc, $newAmount)
    {
        if (strlen($newDesc)) {
            if (!$this->getDescription()) {
                $this->setDescription($newDesc);
            } elseif (strpos($this->getDescription(), $newDesc) === false) {
                $sign = ($newAmount > 0) ? ' + ' : ' - ';
                $this->setDescription($this->getDescription() . $sign . $newDesc);
            }
        }
    }

    /**
     * add new surcharge amount to existing
     * also increase surcharge basis
     *
     * @param $amount
     */
    public function addSurchargeAmount($amount)
    {
        $amount = $this->_round($amount);
        $this->setBaseAmount($this->getBaseAmount() + $amount);
        $surchargeOn = explode(
            ',',
            Mage::getStoreConfig('surcharge/fooman_surcharge_all/surchargeon', $this->getStoreId())
        );
        if (in_array(Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SURCHARGE, $surchargeOn)) {
            $this->setSurchargeBasis($this->getSurchargeBasis() + $amount);
        }
    }

    /**
     * round amount according to store settings
     *
     * @param $amount
     *
     * @return double
     */
    protected function _round($amount)
    {
        //return round($amount,1);
        return Mage::app()->getStore()->roundPrice($amount);
    }
}
