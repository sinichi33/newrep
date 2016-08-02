<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge_MinEnforced extends Fooman_Surcharge_Model_Surcharge_Abstract
{

    /**
     * calculate the surcharge
     *
     * @param                                  $type
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     * @param                                  $quote
     * @param                                  $address
     *
     * @return Fooman_Surcharge_Model_Surcharge|mixed
     */
    public function calculateSurcharge($type, Fooman_Surcharge_Model_Surcharge $surcharge, $quote, $address)
    {
        $settings = $this->retrieveCommonSettings($type, $surcharge->getStoreId());
        $settings = $this->retrieveExtraSettings($settings, 'minenforceamount');
        if ($this->apply($settings, $surcharge, $quote, $address)) {
            $diffToMinimumAmount = $settings['minenforceamount'] - $surcharge->getSurchargeBasis();
            $surcharge->addSurchargeAmount($diffToMinimumAmount);
            $surcharge->addSurchargeDescription($settings['description'], $diffToMinimumAmount);
        }
        return $surcharge;
    }

    /**
     * check if surcharge applies
     *
     * @param                                  $settings
     * @param Fooman_Surcharge_Model_Surcharge $surcharge
     * @param                                  $quote
     * @param null                             $address
     *
     * @return bool
     */
    public function apply($settings, Fooman_Surcharge_Model_Surcharge $surcharge, $quote, $address)
    {
        if (parent::apply($settings, $surcharge, $quote, $address)) {
            return (
                ($surcharge->getSurchargeBasis() - $surcharge->getBasisDeductions())
                < $settings['minenforceamount']
            );
        }
        return false;
    }

}
