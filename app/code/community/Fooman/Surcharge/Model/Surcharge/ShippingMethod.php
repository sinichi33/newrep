<?php
/*
 * @author     Kristof Ringleff
 * @package    Fooman_Surcharge
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
*/

class Fooman_Surcharge_Model_Surcharge_ShippingMethod extends Fooman_Surcharge_Model_Surcharge_Abstract
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
        $settings = $this->retrieveExtraSettings($settings, 'surchargeon');
        $settings = $this->retrieveExtraSettings($settings, 'shippingmethods', true);

        if ($this->apply($settings, $surcharge, $quote, $address)) {
            //shipping method surcharge only applies to the shipping amounts
            if ($settings['surchargeon'] == Fooman_Surcharge_Model_System_Totals::SURCHARGE_ON_SHIPPING) {
                $surcharge->setOverrideSurchargeBasis(
                    $address->getBaseShippingAmount() + $address->getBaseShippingTaxAmount()
                );
            }
            $this->calculateAmount($settings, $surcharge);
            $surcharge->unsOverrideSurchargeBasis();
        }
        return $surcharge;
    }

    /**
     * check if the surcharge applies
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
            $data = $address->getShippingMethod();
            if (!empty($data)) {
                return (in_array($data, $settings['shippingmethods']));
            }
        }
        return false;
    }
}
