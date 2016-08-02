<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge_Region extends Fooman_Surcharge_Model_Surcharge_Abstract
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
        $settings = $this->retrieveExtraSettings($settings, 'addresstype');
        $settings = $this->retrieveExtraSettings($settings, 'regions', true);

        if ($this->apply($settings, $surcharge, $quote, $address)) {
            $this->calculateAmount($settings, $surcharge);
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
            if ($address->getAddressType() == $settings['addresstype']) {
                return in_array($address->getRegionId(), $settings['regions']);
            }
        }
        return false;
    }
}